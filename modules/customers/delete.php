<?php
include '../../includes/config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("DELETE FROM customers WHERE id = ?");
$stmt->execute([$id]);

header("Location: list.php");
exit;
?>
