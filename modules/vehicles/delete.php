<?php
include '../../includes/config.php';

$id = $_GET['id'];

$vehicle = $pdo->prepare("SELECT photo FROM vehicles WHERE id = ?");
$vehicle->execute([$id]);
$vehicle = $vehicle->fetch();

if ($vehicle && $vehicle['photo']) {
    $photoPath = "../../uploads/vehicles/" . $vehicle['photo'];
    if (file_exists($photoPath)) {
        unlink($photoPath);
    }
}

$stmt = $pdo->prepare("DELETE FROM vehicles WHERE id = ?");
$stmt->execute([$id]);

header("Location: list.php");
exit;
