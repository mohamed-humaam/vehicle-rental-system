<?php
include '../../includes/header.php';
include '../../includes/config.php';

$stmt = $pdo->query("SELECT b.*, c.name AS customer_name, v.name AS vehicle_name 
                     FROM bookings b
                     JOIN customers c ON b.customer_id = c.id
                     JOIN vehicles v ON b.vehicle_id = v.id");
?>

<link rel="stylesheet" href="/assets/css/booking-list.css">
<script src="/assets/js/booking-list.js" defer></script>

<div class="booking-container">
    <div class="page-header">
        <h2>Bookings</h2>
        <a href="create.php" class="add-button">
            <i class="fas fa-plus"></i> Add Booking
        </a>
    </div>

    <table class="bookings-table">
        <thead>
            <tr>
                <th data-sortable data-column="id">ID</th>
                <th data-sortable data-column="customer">Customer</th>
                <th data-sortable data-column="vehicle">Vehicle</th>
                <th data-sortable data-column="start">Start Date</th>
                <th data-sortable data-column="end">End Date</th>
                <th data-sortable data-column="total">Total</th>
                <th data-sortable data-column="status">Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($booking = $stmt->fetch()): ?>
            <tr>
                <td data-id="<?= $booking['id'] ?>"><?= $booking['id'] ?></td>
                <td data-customer="<?= htmlspecialchars($booking['customer_name']) ?>">
                    <?= htmlspecialchars($booking['customer_name']) ?>
                </td>
                <td data-vehicle="<?= htmlspecialchars($booking['vehicle_name']) ?>">
                    <?= htmlspecialchars($booking['vehicle_name']) ?>
                </td>
                <td data-start="<?= $booking['start_date'] ?>"><?= $booking['start_date'] ?></td>
                <td data-end="<?= $booking['end_date'] ?>"><?= $booking['end_date'] ?></td>
                <td data-total="<?= $booking['total_amount'] ?>">
                    $<?= number_format($booking['total_amount'], 2) ?>
                </td>
                <td data-status="<?= $booking['status'] ?>">
                    <span class="status-badge status-<?= strtolower($booking['status']) ?>">
                        <?= $booking['status'] ?>
                    </span>
                </td>
                <td>
                    <div class="action-buttons">
                        <a href="edit.php?id=<?= $booking['id'] ?>" 
                           class="action-button edit-button">
                            <i class="fas fa-edit"></i> Edit
                        </a>
                        <a href="delete.php?id=<?= $booking['id'] ?>" 
                           class="action-button delete-button"
                           data-id="<?= $booking['id'] ?>">
                            <i class="fas fa-trash"></i> Delete
                        </a>
                    </div>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>