<?php
include '../../includes/header.php';
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $date = date('Y-m-d H:i:s');

    $stmt = $pdo->prepare("INSERT INTO payments (booking_id, amount, date) VALUES (?, ?, ?)");
    $stmt->execute([$booking_id, $amount, $date]);

    header("Location: report.php");
    exit;
}

$bookings = $pdo->query("SELECT id FROM bookings")->fetchAll();
?>

<h2>Process Payment</h2>
<form method="POST">
    <label>Booking ID</label>
    <select name="booking_id">
        <?php foreach ($bookings as $booking): ?>
        <option value="<?= $booking['id'] ?>"><?= $booking['id'] ?></option>
        <?php endforeach; ?>
    </select>
    <label>Amount</label>
    <input type="number" step="0.01" name="amount" required>
    <button type="submit">Process</button>
</form>

<?php include '../../includes/footer.php'; ?>
