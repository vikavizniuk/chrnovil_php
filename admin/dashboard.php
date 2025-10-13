<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Адмін-панель</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <main>
        <div class="main-title" style="height: 20vw;">
            <div class="yellowback"></div>
            <p style="text-align:center"><strong>Адмін</strong>-панель</p>
        </div>
        <div style="text-align:center" class="panel-admin">
            <div class="links-admin">
                <a href="upload.php">Завантажити файл</a> |
                <a href="manage.php">Керувати записами</a> |
                <a href="files.php">Завантажені файли</a> |
                <a href="logout.php">Вийти</a>
            </div>
        </div>
    </main>
</body>
</html>
