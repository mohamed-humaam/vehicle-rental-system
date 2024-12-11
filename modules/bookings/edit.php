<?php
include '../../includes/header.php';
include '../../includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : null;
$is_edit = $id !== null;

$booking = null;

if ($is_edit) {
    $stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
    $stmt->execute([$id]);
    $booking = $stmt->fetch();

    if (!$booking) {
        echo "Booking not found.";
        exit;
    }
}

$customers = $pdo->query("SELECT id, name FROM customers")->fetchAll();
$vehicles = $pdo->query("SELECT id, name, daily_price FROM vehicles")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $status = $_POST['status'];

    try {
        if ($is_edit) {
            $stmt = $pdo->prepare("
                UPDATE bookings 
                SET customer_id = ?, vehicle_id = ?, start_date = ?, 
                    end_date = ?, status = ?
                WHERE id = ?
            ");
            $stmt->execute([
                $customer_id,
                $vehicle_id,
                $start_date,
                $end_date,
                $status,
                $id
            ]);
        } else {
            $stmt = $pdo->prepare("
                INSERT INTO bookings 
                (customer_id, vehicle_id, start_date, end_date, status)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([
                $customer_id,
                $vehicle_id,
                $start_date,
                $end_date,
                $status
            ]);
        }

        header("Location: list.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error saving booking: " . $e->getMessage();
    }
}
?>

<link rel="stylesheet" href="/assets/css/booking-edit.css">
<script src="/assets/js/booking-edit.js" defer></script>

<div class="booking-edit-container">
    <div class="booking-edit-header">
        <h2><?= $is_edit ? 'Edit Booking' : 'Add Booking' ?></h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="error-message"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" class="booking-edit-form">
        <?php if ($is_edit): ?>
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <?php endif; ?>

        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select id="customer_id" name="customer_id" required>
                <option value="">Select Customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['id']) ?>"
                        <?= ($booking['customer_id'] ?? '') == $customer['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($customer['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="vehicle_id">Vehicle</label>
            <select id="vehicle_id" name="vehicle_id" required>
                <option value="">Select Vehicle</option>
                <?php foreach ($vehicles as $vehicle): ?>
                    <option value="<?= htmlspecialchars($vehicle['id']) ?>"
                        <?= ($booking['vehicle_id'] ?? '') == $vehicle['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($vehicle['name']) ?> -
                        $<?= number_format($vehicle['daily_price'] ?? 0, 2) ?>/day
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="start_date">Start Date</label>
            <input type="date" id="start_date" name="start_date"
                value="<?= htmlspecialchars($booking['start_date'] ?? '') ?>"
                required>
        </div>

        <div class="form-group">
            <label for="end_date">End Date</label>
            <input type="date" id="end_date" name="end_date"
                value="<?= htmlspecialchars($booking['end_date'] ?? '') ?>"
                required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select id="status" name="status" required>
                <?php
                $statuses = ['Pending', 'Confirmed', 'Completed', 'Cancelled'];
                foreach ($statuses as $status):
                ?>
                    <option value="<?= $status ?>"
                        <?= ($booking['status'] ?? 'Pending') == $status ? 'selected' : '' ?>>
                        <?= $status ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <button type="submit" class="submit-button">
            <i class="fas fa-save"></i>
            <?= $is_edit ? 'Update Booking' : 'Create Booking' ?>
        </button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>