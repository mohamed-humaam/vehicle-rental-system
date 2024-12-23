<?php
include '../../includes/header.php';
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $booking_id = $_POST['booking_id'];
    $amount = $_POST['amount'];
    $payment_date = date('Y-m-d H:i:s');

    try {
        $pdo->beginTransaction();

        // Insert payment
        $stmt = $pdo->prepare("INSERT INTO payments (booking_id, amount, payment_date) VALUES (?, ?, ?)");
        $stmt->execute([$booking_id, $amount, $payment_date]);

        // Update booking status to 'booked' instead of 'Paid'
        $stmt = $pdo->prepare("UPDATE bookings SET status = 'booked' WHERE id = ?");
        $stmt->execute([$booking_id]);

        $pdo->commit();
        header("Location: report.php");
        exit;
    } catch (PDOException $e) {
        $pdo->rollBack();
        $error = "Error processing payment: " . $e->getMessage();
    }
}

// Only fetch pending bookings
$bookings = $pdo->query("SELECT b.id, b.total_amount, c.name as customer_name 
                         FROM bookings b 
                         JOIN customers c ON b.customer_id = c.id 
                         WHERE b.status = 'pending'")->fetchAll();
?>

<link rel="stylesheet" href="/assets/css/payments.css">
<script src="/assets/js/payments.js" defer></script>

<div class="payment-container">
    <div class="page-header">
        <h2>Process Payment</h2>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if (empty($bookings)): ?>
        <div class="empty-state">
            <p>No pending bookings available for payment</p>
        </div>
    <?php else: ?>
        <form class="payment-form" method="POST" action="" onsubmit="return validatePayment()">
            <div class="form-group">
                <label for="booking_id">Booking</label>
                <select id="booking_id" name="booking_id" class="form-control" required>
                    <option value="">Select a booking</option>
                    <?php foreach ($bookings as $booking): ?>
                        <option value="<?= $booking['id'] ?>" data-amount="<?= $booking['total_amount'] ?>">
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

<script>
function validatePayment() {
    const booking = document.getElementById('booking_id');
    const amount = document.getElementById('amount').value;
    const expectedAmount = booking.options[booking.selectedIndex].dataset.amount;
    
    if (parseFloat(amount) !== parseFloat(expectedAmount)) {
        alert('Payment amount must match the booking total amount');
        return false;
    }
    return true;
}
</script>

<?php include '../../includes/footer.php'; ?>