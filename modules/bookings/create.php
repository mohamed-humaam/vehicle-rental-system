<?php
include '../../includes/header.php';
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customer_id = $_POST['customer_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $total_amount = $_POST['total_amount'];

    $stmt = $pdo->prepare("INSERT INTO bookings (customer_id, vehicle_id, start_date, end_date, total_amount, status) VALUES (?, ?, ?, ?, ?, 'Pending')");
    $stmt->execute([$customer_id, $vehicle_id, $start_date, $end_date, $total_amount]);

    header("Location: list.php");
    exit;
}

$customers = $pdo->query("SELECT id, name FROM customers")->fetchAll();
$vehicles = $pdo->query("SELECT id, name, daily_price FROM vehicles WHERE availability = 1")->fetchAll();
?>

<link rel="stylesheet" href="/assets/css/booking-create.css">

<div class="booking-container">
    <div class="booking-header">
        <h2>Create Booking</h2>
    </div>

    <div class="error-message"></div>

    <form method="POST" class="booking-form">
        <div class="form-group">
            <label for="customer_id">Customer</label>
            <select id="customer_id" name="customer_id" required>
                <option value="">Select a customer</option>
                <?php foreach ($customers as $customer): ?>
                    <option value="<?= htmlspecialchars($customer['id']) ?>">
                        <?= htmlspecialchars($customer['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="vehicle_id">Vehicle</label>
            <select id="vehicle_id" name="vehicle_id" required>
                <option value="">Select a vehicle</option>
                <?php foreach ($vehicles as $vehicle): ?>
                    <option value="<?= htmlspecialchars($vehicle['id']) ?>" 
                            data-price="<?= htmlspecialchars($vehicle['daily_price']) ?>">
                        <?= htmlspecialchars($vehicle['name']) ?> - 
                        $<?= number_format($vehicle['daily_price'], 2) ?>/day
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="dates-container">
            <div class="form-group">
                <label for="start_date">Start Date</label>
                <input type="date" id="start_date" name="start_date" required>
            </div>

            <div class="form-group">
                <label for="end_date">End Date</label>
                <input type="date" id="end_date" name="end_date" required>
            </div>
        </div>

        <div class="form-group amount-container">
            <label for="total_amount">Total Amount</label>
            <input type="number" id="total_amount" name="total_amount" 
                   readonly required step="0.01">
        </div>

        <button type="submit" class="submit-button">
            Create Booking
        </button>
    </form>
</div>

<script src="/assets/js/booking-create.js"></script>

<?php include '../../includes/footer.php'; ?>