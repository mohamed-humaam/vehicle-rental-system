<?php
// payments/create.php
include '../../includes/header.php';
include '../../includes/config.php';

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $payment_date = date('Y-m-d H:i:s');

    try {
        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, amount, payment_date) VALUES (?, ?, ?)");
        $stmt->execute([$booking_id, $amount, $payment_date]);

        $stmt = $pdo->prepare("UPDATE bookings SET status = 'Paid' WHERE id = ?");
        $stmt->execute([$booking_id]);

        header("Location: report.php");
        exit;
    } catch (PDOException $e) {
        $error = "Error processing payment: " . $e->getMessage();
    }
}

// Fetch pending bookings
$bookings = $pdo->query("SELECT b.id, b.total_amount, c.name as customer_name 
                         FROM bookings b 
                         JOIN customers c ON b.customer_id = c.id 
                         WHERE b.status = 'Pending'")->fetchAll();
?>

<link rel="stylesheet" href="/assets/css/payments.css">
<script src="/assets/js/payments.js" defer></script>

<div class="payment-container">
    <div class="page-header">
        <h2>Process Payment</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div class="empty-state">
            <p>No pending bookings available for payment</p>
        </div>
    <?php else: ?>
        <form class="payment-form" method="POST" action="">
            <div class="form-group">
                <label for="booking_id">Booking</label>
                <select id="booking_id" name="booking_id" class="form-control" required>
                    <option value="">Select a booking</option>
                    <?php foreach ($bookings as $booking): ?>
                    <option value="<?= $booking['id'] ?>">
                        Booking #<?= $booking['id'] ?> - <?= htmlspecialchars($booking['customer_name']) ?> 
                        - Amount: $<?= number_format($booking['total_amount'], 2) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label for="amount">Amount ($)</label>
                <input type="number" id="amount" name="amount" step="0.01" class="form-control" required>
            </div>

            <button type="submit" class="submit-button">Process Payment</button>
        </form>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>