<?php
$host = 'localhost';
$port = '5432';
$dbname = 'chornovil_db';
$user = 'postgres';
$password = 'q1w2e3r4t5';

try {
    $pdo = new PDO("pgsql:host=$host;port=$port;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Помилка підключення до бази даних: " . $e->getMessage());
}
?>
