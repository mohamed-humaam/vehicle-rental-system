<?php
include '../../includes/header.php';
include '../../includes/config.php';

$stmt = $pdo->query("SELECT * FROM customers");
$customers = $stmt->fetchAll();
?>

<link rel="stylesheet" href="/assets/css/customer-list.css">
<script src="/assets/js/customer-list.js" defer></script>

<div class="customer-container">
    <div class="page-header">
        <h2>Customers</h2>
        <a href="edit.php" class="add-button">
            <i class="fas fa-plus"></i> Add Customer
        </a>
    </div>

    <div class="controls">
        <input type="text" class="search-box" placeholder="Search customers...">
    </div>

    <table class="customers-table">
        <thead>
            <tr>
                <th data-sortable data-column="id">ID</th>
                <th data-sortable data-column="name">Name</th>
                <th data-sortable data-column="email">Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($customers)): ?>
            <tr>
                <td colspan="5" class="empty-state">
                    <p>No customers found</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($customers as $customer): ?>
                <tr>
                    <td data-id="<?= $customer['id'] ?>"><?= $customer['id'] ?></td>
                    <td data-name="<?= htmlspecialchars($customer['name']) ?>">
                        <?= htmlspecialchars($customer['name']) ?>
                    </td>
                    <td data-email="<?= htmlspecialchars($customer['email']) ?>">
                        <?= htmlspecialchars($customer['email']) ?>
                    </td>
                    <td><?= htmlspecialchars($customer['phone']) ?></td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= $customer['id'] ?>" 
                               class="action-button edit-button">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete.php?id=<?= $customer['id'] ?>" 
                               class="action-button delete-button"
                               data-name="<?= htmlspecialchars($customer['name']) ?>">
                                <i class="fas fa-trash"></i> Delete
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include '../../includes/footer.php'; ?>