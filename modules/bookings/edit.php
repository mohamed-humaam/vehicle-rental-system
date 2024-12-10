<?php
include '../../includes/header.php';
include '../../includes/config.php';

// Check if ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid Booking ID.";
    exit;
}

$booking_id = $_GET['id'];

// Fetch booking details
$stmt = $pdo->prepare("SELECT * FROM bookings WHERE id = ?");
$stmt->execute([$booking_id]);
$booking = $stmt->fetch();

if (!$booking) {
    echo "Booking not found.";
    exit;
}

// Fetch customers and vehicles for dropdowns
$customers = $pdo->query("SELECT id, name FROM customers")->fetchAll();
$vehicles = $pdo->query("SELECT id, name FROM vehicles")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $total_amount = $_POST['total_amount'];

    // Update booking details
    $stmt = $pdo->prepare("UPDATE bookings SET customer_id = ?, vehicle_id = ?, start_date = ?, end_date = ?, total_amount = ? WHERE id = ?");
    $stmt->execute([$customer_id, $vehicle_id, $start_date, $end_date, $total_amount, $booking_id]);

    header("Location: list.php");
    exit;
}
?>

<h2>Edit Booking</h2>
<form method="POST">
    <label>Customer</label>
    <select name="customer_id" required>
        <?php foreach ($customers as $customer): ?>
        <option value="<?= $customer['id'] ?>" <?= $customer['id'] == $booking['customer_id'] ? 'selected' : '' ?>>
            <?= $customer['name'] ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label>Vehicle</label>
    <select name="vehicle_id" required>
        <?php foreach ($vehicles as $vehicle): ?>
        <option value="<?= $vehicle['id'] ?>" <?= $vehicle['id'] == $booking['vehicle_id'] ? 'selected' : '' ?>>
            <?= $vehicle['name'] ?>
        </option>
        <?php endforeach; ?>
    </select>
    <label>Start Date</label>
    <input type="date" name="start_date" value="<?= $booking['start_date'] ?>" required>
    <label>End Date</label>
    <input type="date" name="end_date" value="<?= $booking['end_date'] ?>" required>
    <label>Total Amount</label>
    <input type="number" name="total_amount" value="<?= $booking['total_amount'] ?>" required>
    <button type="submit">Update Booking</button>
</form>

<?php include '../../includes/footer.php'; ?>
