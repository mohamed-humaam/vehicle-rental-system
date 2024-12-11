<?php
include '../../includes/header.php';
include '../../includes/config.php';

$types = $pdo->query("SELECT DISTINCT type FROM vehicles")->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Validate inputs
    $name = trim($_POST['name']);
    $type = trim($_POST['custom_type'] ?: $_POST['type']);
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

    $photo = null;
    if (!empty($_FILES['photo']['name'])) {
        $targetDir = "../../uploads/vehicles/";
        $photoName = time() . "_" . basename($_FILES['photo']['name']);
        $targetFile = $targetDir . $photoName;

        $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (!in_array($imageFileType, $allowedTypes)) {
            $errors[] = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        } elseif ($_FILES['photo']['size'] > 5000000) {
            $errors[] = "Sorry, your file is too large. Maximum size is 5MB.";
        } else {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $targetFile)) {
                $photo = $photoName;
            } else {
                $errors[] = "Error uploading file.";
            }
        }
    }

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("INSERT INTO vehicles (name, photo, type, daily_price, availability) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$name, $photo, $type, $daily_price, $availability]);

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
        <h2>Add New Vehicle</h2>
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
                value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="type">Vehicle Type</label>
            <div class="type-input-wrapper">
                <select id="type" name="type" class="form-control">
                    <option value="">Select Type or Enter Custom</option>
                    <?php foreach ($types as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>"
                            <?= (isset($_POST['type']) && $_POST['type'] === $type) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type) ?>
                        </option>
                    <?php endforeach; ?>
                    <option value="custom">+ Add Custom Type</option>
                </select>
                <input type="text" id="custom_type" name="custom_type" class="form-control"
                    placeholder="Enter custom type" style="display: none;">
            </div>
        </div>

        <div class="form-group">
            <label for="daily_price">Daily Price ($)</label>
            <input type="number" id="daily_price" name="daily_price" class="form-control"
                step="0.01" min="0" value="<?= isset($_POST['daily_price']) ? htmlspecialchars($_POST['daily_price']) : '' ?>" required>
        </div>

        <div class="form-group">
            <label for="photo">Vehicle Photo</label>
            <div class="file-input-wrapper">
                <input type="file" id="photo" name="photo" class="form-control" accept="image/*">
                <img class="file-input-preview" alt="Photo preview">
            </div>
        </div>

        <div class="form-group">
            <div class="checkbox-wrapper">
                <input type="checkbox" id="availability" name="availability"
                    <?= !isset($_POST['availability']) || $_POST['availability'] ? 'checked' : '' ?>>
                <label for="availability">Available for Rent</label>
            </div>
        </div>

        <button type="submit" class="submit-button">
            <i class="fas fa-plus"></i> Add Vehicle
        </button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>