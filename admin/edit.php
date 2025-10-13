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

// Отримання категорій
$categories = $pdo->query("SELECT id, name FROM category ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

// Отримання запису
$stmt = $pdo->prepare("SELECT * FROM quotes WHERE id = :id");
$stmt->execute(['id' => $id]);
$quote = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$quote) {
    die('Запис не знайдено.');
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $description = trim($_POST['description']);
    $content = trim($_POST['content']);
    $category_id = (int)$_POST['category_id'];

    if (!empty($content) && !empty($description) && $category_id > 0) {
        $stmt = $pdo->prepare("UPDATE quotes SET content = :content, description = :description, category_id = :category_id WHERE id = :id");
        $stmt->execute([
            'content' => $content,
            'description' => $description,
            'category_id' => $category_id,
            'id' => $id
        ]);
        $message = 'Запис оновлено успішно!';
        // оновити поточні значення
        $quote['content'] = $content;
        $quote['description'] = $description;
        $quote['category_id'] = $category_id;
    } else {
        $message = 'Усі поля обов’язкові.';
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Редагувати запис</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e8e8e8;
            margin: 0;
            padding: 0;
        }

        .edit-container {
            max-width: 800px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .edit-container h2 {
            text-align: center;
            font-size: 2.5vw;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            font-size: 1.1vw;
        }

        select {
            width: 100%;
            padding: 12px;
            font-size: 1vw;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
        }
        textarea {
            width: 775px;
            padding: 12px;
            font-size: 1vw;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
        }

        button {
            background-color: yellow;
            color: black;
            padding: 14px 0;
            width: 100%;
            border: none;
            font-size: 1.2vw;
            border-radius: 8px;
            margin-top: 30px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background-color: black;
            color: white;
        }

        .message {
            text-align: center;
            color: green;
            font-weight: bold;
            font-size: 1.1vw;
            margin-bottom: 20px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 30px;
            font-size: 1vw;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="edit-container">
    <h2>Редагувати запис</h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post">
        <label for="category_id">Категорія:</label>
        <select name="category_id" id="category_id" required>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $quote['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="description">Опис:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($quote['description']) ?></textarea>

        <label for="content">Текст:</label>
        <textarea name="content" rows="10" required><?= htmlspecialchars($quote['content']) ?></textarea>

        <button type="submit">Зберегти зміни</button>
    </form>

    <a class="back-link" href="manage.php">← Назад до списку</a>
</div>
</body>
</html>

