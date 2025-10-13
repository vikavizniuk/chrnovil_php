<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/db.php';

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    die('Невірний ID');
}

$stmt = $pdo->prepare("DELETE FROM quotes WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: manage.php");
exit;
