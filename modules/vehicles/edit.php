<?php
include '../../includes/header.php';
include '../../includes/config.php';

// Get vehicle ID and details
$id = $_GET['id'];
$vehicle = $pdo->prepare("SELECT * FROM vehicles WHERE id = ?");
$vehicle->execute([$id]);
$vehicle = $vehicle->fetch();

// Get all vehicle types for dropdown
$types = $pdo->query("SELECT DISTINCT type FROM vehicles")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];
    
    // Validate inputs
    $name = trim($_POST['name']);
    $type = trim($_POST['custom_type'] ?: $_POST['type']); // Use custom type if provided
    $daily_price = floatval($_POST['daily_price']);
    $availability = isset($_POST['availability']) ? 1 : 0;

    if (strlen($name) < 2) {
        $errors[] = "Name must be at least 2 characters long";
    }
    
    if (empty($type)) {
        $errors[] = "Vehicle type is required";
    }
    
    if ($daily_price <= 0) {
        $errors[] = "Daily price must be greater than 0";
    }

    // Handle file upload
    $photo = $vehicle['photo'];
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "../../uploads/vehicles/";
        $photoName = time() . "_" . basename($_FILES['photo']['name']);
        $targetFile = $targetDir . $photoName;
        
        // Validate file type
        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];
        
        if (!in_array($imageFileType, $allowedTypes)) {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES['photo']['size'] > 5000000) {
            $errors[] = "Sorry, your file is too large. Maximum size is 5MB.";
        } else {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                // Delete old photo if exists
                if ($vehicle['photo'] && file_exists($targetDir . $vehicle['photo'])) {
                    unlink($targetDir . $vehicle['photo']);
                }
                $photo = $photoName;
            } else {
                $errors[] = "Error uploading file.";
            }
        }
    }

    // If no errors, proceed with update
    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("UPDATE vehicles SET name = ?, photo = ?, type = ?, daily_price = ?, availability = ? WHERE id = ?");
            $stmt->execute([$name, $photo, $type, $daily_price, $availability, $id]);
            
            header("Location: list.php");
            exit;
        } catch (PDOException $e) {
            $errors[] = "Database error: " . $e->getMessage();
        }
    }
}
?>

<link rel="stylesheet" href="/assets/css/vehicle-create.css">
<script src="/assets/js/vehicle-create.js" defer></script>

<div class="create-container">
    <div class="page-header">
        <h2>Edit Vehicle</h2>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?= htmlspecialchars($error) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form class="create-form" method="POST" enctype="multipart/form-data">
        <div class="form-group">
            <label for="name">Vehicle Name</label>
            <input type="text" id="name" name="name" class="form-control" 
                   value="<?= htmlspecialchars($vehicle['name']) ?>" required>
        </div>

        <div class="form-group">
            <label for="type">Vehicle Type</label>
            <div class="type-input-wrapper">
                <select id="type" name="type" class="form-control">
                    <option value="">Select Type or Enter Custom</option>
                    <?php 
                    $currentTypeExists = false;
                    foreach ($types as $type): 
                        $selected = ($vehicle['type'] === $type);
                        if ($selected) $currentTypeExists = true;
                    ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= $selected ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type) ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="custom" <?= !$currentTypeExists ? 'selected' : '' ?>>+ Add Custom Type</option>
                </select>
                <input type="text" id="custom_type" name="custom_type" class="form-control" 
                       placeholder="Enter custom type" 
                       value="<?= !$currentTypeExists ? htmlspecialchars($vehicle['type']) : '' ?>"
                       style="display: <?= !$currentTypeExists ? 'block' : 'none' ?>;">
            </div>
        </div>

        <div class="form-group">
            <label for="daily_price">Daily Price ($)</label>
            <input type="number" id="daily_price" name="daily_price" class="form-control" 
                   step="0.01" min="0" value="<?= htmlspecialchars($vehicle['daily_price']) ?>" required>
        </div>

        <div class="form-group">
            <label for="photo">Vehicle Photo</label>
            <div class="file-input-wrapper">
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                <?php if ($vehicle['photo']): ?>
                    <img src="../../uploads/vehicles/<?= htmlspecialchars($vehicle['photo']) ?>" 
                         alt="Current vehicle photo" class="file-input-preview" style="display: block;">
                <?php else: ?>
                    <img class="file-input-preview" alt="Photo preview">
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox-wrapper">
                <input type="checkbox" id="availability" name="availability" 
                       <?= $vehicle['availability'] ? 'checked' : '' ?>>
                <label for="availability">Available for Rent</label>
            </div>
        </div>

        <button type="submit" class="submit-button">
            <i class="fas fa-save"></i> Update Vehicle
        </button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>