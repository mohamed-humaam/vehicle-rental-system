<?php
include '../../includes/header.php';
include '../../includes/config.php';

$stmt = $pdo->query("SELECT b.*, c.name AS customer_name, v.name AS vehicle_name 
                     FROM bookings b
                     JOIN customers c ON b.customer_id = c.id
                     JOIN vehicles v ON b.vehicle_id = v.id");
?>

<h2>Bookings</h2>
<a href="create.php">Add New Booking</a>
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Customer</th>
            <th>Vehicle</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php while ($booking = $stmt->fetch()): ?>
        <tr>
            <td><?= $booking['id'] ?></td>
            <td><?= $booking['customer_name'] ?></td>
            <td><?= $booking['vehicle_name'] ?></td>
            <td><?= $booking['start_date'] ?></td>
            <td><?= $booking['end_date'] ?></td>
            <td><?= $booking['total_amount'] ?></td>
            <td><?= $booking['status'] ?></td>
            <td>
                <a href="edit.php?id=<?= $booking['id'] ?>">Edit</a>
                <a href="delete.php?id=<?= $booking['id'] ?>" class="delete">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>

<?php include '../../includes/footer.php'; ?>
