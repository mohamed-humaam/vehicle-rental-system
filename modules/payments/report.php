<?php
// payments/report.php
include '../../includes/header.php';
include '../../includes/config.php';

// Fetch all payments with related information
$query = "
    SELECT 
        p.id AS payment_id,
        p.booking_id,
        p.amount,
        p.payment_date,
        c.name AS customer_name
    FROM payments p
    JOIN bookings b ON p.booking_id = b.id
    JOIN customers c ON b.customer_id = c.id
    ORDER BY p.payment_date DESC
";

try {
    $payments = $pdo->query($query)->fetchAll();
} catch (PDOException $e) {
    $error = "Error fetching payments: " . $e->getMessage();
}
?>

<link rel="stylesheet" href="/assets/css/payments.css">
<script src="/assets/js/payments.js" defer></script>

<div class="payment-container">
    <div class="page-header">
        <h2>Payment Report</h2>
        <a href="create.php" class="add-button">
            <i class="fas fa-plus"></i> New Payment
        </a>
    </div>

    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <?php if (empty($payments)): ?>
        <div class="empty-state">
            <p>No payments found</p>
        </div>
    <?php else: ?>
        <table class="payment-table">
            <thead>
                <tr>
                    <th data-sortable data-column="payment_id">Payment ID</th>
                    <th data-sortable data-column="booking_id">Booking ID</th>
                    <th data-sortable data-column="customer">Customer</th>
                    <th data-sortable data-column="amount">Amount</th>
                    <th data-sortable data-column="date">Payment Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($payments as $payment): ?>
                <tr>
                    <td data-payment_id="<?= $payment['payment_id'] ?>"><?= $payment['payment_id'] ?></td>
                    <td data-booking_id="<?= $payment['booking_id'] ?>"><?= $payment['booking_id'] ?></td>
                    <td data-customer="<?= htmlspecialchars($payment['customer_name']) ?>">
                        <?= htmlspecialchars($payment['customer_name']) ?>
                    </td>
                    <td data-amount="<?= $payment['amount'] ?>" class="amount-column">
                        $<?= number_format($payment['amount'], 2) ?>
                    </td>
                    <td data-date="<?= $payment['payment_date'] ?>">
                        <?= date('M d, Y H:i', strtotime($payment['payment_date'])) ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../../includes/footer.php'; ?>