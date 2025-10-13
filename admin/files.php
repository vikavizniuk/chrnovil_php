<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/db.php';

$files = $pdo->query("SELECT * FROM uploads ORDER BY uploaded_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Завантажені файли</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e8e8e8;
        }

        .container {
            max-width: 900px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        h2 {
            text-align: center;
            font-size: 2.2vw;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 1vw;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 12px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        a.download {
            color: darkblue;
            text-decoration: underline;
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
<div class="container">
    <h2>Завантажені файли</h2>

    <?php if (count($files) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Назва файлу</th>
                    <th>Дата завантаження</th>
                    <th>Посилання</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($files as $index => $file): ?>
                    <tr>
                        <td><?= htmlspecialchars($file['title']) ?></td>
                        <td><?= date('d.m.Y H:i', strtotime($file['uploaded_at'])) ?></td>
                        <td>
                            <a class="download" href="<?= htmlspecialchars(str_replace('../', '/', $file['file_path'])) ?>" target="_blank">Відкрити</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p style="text-align:center;">Файлів ще не було завантажено.</p>
    <?php endif; ?>

    <a class="back-link" href="dashboard.php">← Назад до адмін-панелі</a>
</div>
</body>
</html>
