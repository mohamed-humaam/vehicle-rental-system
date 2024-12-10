<?php
include '../../includes/header.php';
include '../../includes/config.php';

$vehicles = $pdo->query("SELECT * FROM vehicles")->fetchAll();
$types = $pdo->query("SELECT DISTINCT type FROM vehicles")->fetchAll(PDO::FETCH_COLUMN);
?>

<link rel="stylesheet" href="/assets/css/vehicle-list.css">
<script src="/assets/js/vehicle-list.js" defer></script>

<div class="vehicle-container">
    <div class="page-header">
        <h2>Vehicles</h2>
        <a href="create.php" class="add-button">
            <i class="fas fa-plus"></i> Add Vehicle
        </a>
    </div>

    <div class="controls">
        <input type="text" class="search-box" placeholder="Search vehicles...">
        <select class="filter-select">
            <option value="">All Types</option>
            <?php foreach ($types as $type): ?>
                <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <table class="vehicles-table">
        <thead>
            <tr>
                <th data-sortable data-column="id">ID</th>
                <th data-sortable data-column="name">Name</th>
                <th>Photo</th>
                <th data-sortable data-column="type">Type</th>
                <th data-sortable data-column="price">Daily Price</th>
                <th data-sortable data-column="availability">Available</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($vehicles)): ?>
            <tr>
                <td colspan="7" class="empty-state">
                    <p>No vehicles found</p>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($vehicles as $vehicle): ?>
                <tr>
                    <td data-id="<?= $vehicle['id'] ?>"><?= $vehicle['id'] ?></td>
                    <td data-name="<?= htmlspecialchars($vehicle['name']) ?>"><?= htmlspecialchars($vehicle['name']) ?></td>
                    <td>
                        <?php if ($vehicle['photo']): ?>
                            <img src="../../uploads/vehicles/<?= htmlspecialchars($vehicle['photo']) ?>" 
                                 alt="<?= htmlspecialchars($vehicle['name']) ?>" 
                                 class="vehicle-image">
                        <?php else: ?>
                            <div class="no-image">No Photo</div>
                        <?php endif; ?>
                    </td>
                    <td data-type="<?= htmlspecialchars($vehicle['type']) ?>"><?= htmlspecialchars($vehicle['type']) ?></td>
                    <td data-price="<?= $vehicle['daily_price'] ?>" class="price-column">
                        $<?= number_format($vehicle['daily_price'], 2) ?>
                    </td>
                    <td data-availability="<?= $vehicle['availability'] ?>">
                        <span class="availability-badge <?= $vehicle['availability'] ? 'available' : 'unavailable' ?>">
                            <?= $vehicle['availability'] ? 'Available' : 'Unavailable' ?>
                        </span>
                    </td>
                    <td>
                        <div class="action-buttons">
                            <a href="edit.php?id=<?= $vehicle['id'] ?>" class="action-button edit-button">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            <a href="delete.php?id=<?= $vehicle['id'] ?>" 
                               class="action-button delete-button"
                               data-id="<?= $vehicle['id'] ?>"
                               data-name="<?= htmlspecialchars($vehicle['name']) ?>">
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