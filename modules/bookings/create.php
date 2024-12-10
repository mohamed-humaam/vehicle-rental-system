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

<h2>Create Booking</h2>
<form method="POST">
    <label>Customer</label>
    <select name="customer_id" required>
        <?php foreach ($customers as $customer): ?>
        <option value="<?= $customer['id'] ?>"><?= $customer['name'] ?></option>
        <?php endforeach; ?>
    </select>
    <label>Vehicle</label>
    <select name="vehicle_id" required>
        <?php foreach ($vehicles as $vehicle): ?>
        <option value="<?= $vehicle['id'] ?>" data-price="<?= $vehicle['daily_price'] ?>">
            <?= $vehicle['name'] ?> - $<?= $vehicle['daily_price'] ?>/day
        </option>
        <?php endforeach; ?>
    </select>
    <label>Start Date</label>
    <input type="date" name="start_date" required>
    <label>End Date</label>
    <input type="date" name="end_date" required>
    <label>Total Amount</label>
    <input type="number" name="total_amount" readonly required>
    <button type="submit">Create Booking</button>
</form>

<script>
    const vehicleSelect = document.querySelector('[name="vehicle_id"]');
    const startDateInput = document.querySelector('[name="start_date"]');
    const endDateInput = document.querySelector('[name="end_date"]');
    const totalAmountInput = document.querySelector('[name="total_amount"]');

    function calculateTotalAmount() {
        const vehicleOption = vehicleSelect.options[vehicleSelect.selectedIndex];
        const pricePerDay = parseFloat(vehicleOption.getAttribute('data-price'));
        const startDate = new Date(startDateInput.value);
        const endDate = new Date(endDateInput.value);

        if (pricePerDay && startDate && endDate && startDate <= endDate) {
            const days = (endDate - startDate) / (1000 * 60 * 60 * 24) + 1;
            totalAmountInput.value = (pricePerDay * days).toFixed(2);
        } else {
            totalAmountInput.value = '';
        }
    }

    vehicleSelect.addEventListener('change', calculateTotalAmount);
    startDateInput.addEventListener('change', calculateTotalAmount);
    endDateInput.addEventListener('change', calculateTotalAmount);
</script>

<?php include '../../includes/footer.php'; ?>
