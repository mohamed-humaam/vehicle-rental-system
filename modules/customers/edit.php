<?php
include '../../includes/header.php';
include '../../includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? null;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $phone = preg_replace('/\D/', '', $phone);

    if ($id) {
        $stmt = $pdo->prepare("UPDATE customers SET name=?, email=?, phone=? WHERE id=?");
        $stmt->execute([$name, $email, $phone, $id]);
    } else {
        $stmt = $pdo->prepare("INSERT INTO customers (name, email, phone) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $phone]);
    }

    header("Location: list.php");
    exit;
}

$id = $_GET['id'] ?? null;
$customer = null;

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM customers WHERE id = ?");
    $stmt->execute([$id]);
    $customer = $stmt->fetch();
}
?>

<link rel="stylesheet" href="/assets/css/customer-edit.css">
<script src="/assets/js/customer-edit.js" defer></script>

<div class="customer-edit-container">
    <div class="customer-edit-header">
        <h2><?= $id ? 'Edit Customer' : 'Add Customer' ?></h2>
    </div>

    <form method="POST" class="customer-edit-form">
        <input type="hidden" name="id" value="<?= $customer['id'] ?? '' ?>">

        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" id="name" name="name"
                value="<?= htmlspecialchars($customer['name'] ?? '') ?>"
                required
                placeholder="Enter full name">
        </div>

        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email"
                value="<?= htmlspecialchars($customer['email'] ?? '') ?>"
                required
                placeholder="Enter email address">
        </div>

        <div class="form-group">
            <label for="phone">Phone</label>
            <input type="tel" id="phone" name="phone"
                value="<?= ($customer && $customer['phone']) ? '(' . substr($customer['phone'], 0, 3) . ') ' . substr($customer['phone'], 3, 3) . '-' . substr($customer['phone'], 6) : '' ?>" required
                placeholder="Enter phone number"
                pattern="\(\d{3}\)\s\d{3}-\d{4}">
        </div>

        <button type="submit" class="submit-button">
            <?= $id ? 'Update Customer' : 'Add Customer' ?>
        </button>
    </form>
</div>

<?php include '../../includes/footer.php'; ?>