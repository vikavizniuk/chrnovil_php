<?php
session_start();
require_once 'includes/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: admin/dashboard.php');
        exit;
    } else {
        $error = 'Невірне ім’я користувача або пароль.';
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Вхід в адмін-панель</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
        .login-container {
            max-width: 500px;
            margin: 100px auto;
            background-color: rgb(232, 232, 232);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            font-family: 'Poppins', sans-serif;
        }
        .login-container h2 {
            text-align: center;
            font-size: 3vw;
            margin: 0 0 30px;
        }
        .login-container input {
            width: 80%;
            padding: 12px;
            font-size: 1vw;
            border: 1px solid #ccc;
            border-radius: 6px;
            margin-bottom: 20px;
        }
        label {
            margin-bottom: 10px;
        }
        .login-container button {
            width: 85%;
            padding: 12px;
            font-size: 1.2vw;
            background-color: yellow;
            color: black;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .login-container button:hover {
            background-color: black;
            color: white;
        }
        .login-container .error {
            color: red;
            text-align: center;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            font-size: 1vw;
            color: #333;
        }
        .back-link:hover {
            color: black;
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Вхід</h2>
        <form method="post">
            <label>Ім’я користувача:</label>
            <input type="text" name="username" required>

            <label>Пароль:</label>
            <input type="password" name="password" required>

            <button type="submit">Увійти</button>

            <?php if ($error): ?>
                <p class="error"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </form>

        <a class="back-link" href="/index.php">← Повернутися на головну</a>
    </div>
</body>
</html>
