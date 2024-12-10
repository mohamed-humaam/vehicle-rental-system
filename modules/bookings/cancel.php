<?php
include '../../includes/header.php';
include '../../includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id > 0) {
    $stmt = $pdo->prepare("UPDATE bookings SET status = 'Cancelled' WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: list.php");
exit;
?>
