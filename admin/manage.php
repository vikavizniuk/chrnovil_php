<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/db.php';

$quotes = $pdo->query("
    SELECT q.id, q.description, LEFT(q.content, 200) AS preview, q.created_at, c.name AS category
    FROM quotes q
    LEFT JOIN category c ON q.category_id = c.id
    ORDER BY q.created_at DESC
")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Керування записами</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        .record {
            width: 90%;
            border: 1px solid #ccc;
            margin-top: 30px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f5f5f5;
        }
        .record p {
            margin: 5px 0;
        }
        .record-actions {
            margin-top: 10px;
        }
        .record-actions a {
            margin-right: 20px;
            text-decoration: none;
            color: rgb(143, 143, 143);
            transition: 0.3s;
        }
        .record-actions a:hover{
            color: rgb(58, 58, 58);
        }
    </style>
</head>
<body>
    <div class="main-title" style="height: 20vw;">
    <div class="yellowback"></div>
        <p style="text-align:center; width: 50vw;">Керування <strong>текстами</strong></p>
    </div>
    <main style="padding-left:90px;">


        <?php if (count($quotes) > 0): ?>
            <?php foreach ($quotes as $q): ?>
                <div class="record">
                    <p><strong>Категорія:</strong> <?= htmlspecialchars($q['category']) ?></p>
                    <p><strong>Опис:</strong> <?= htmlspecialchars($q['description']) ?></p>
                    <p><strong>Фрагмент:</strong> <?= nl2br(htmlspecialchars($q['preview'])) ?><?= strlen($q['preview']) === 200 ? '…' : '' ?></p>
                    <p><strong>Дата:</strong> <?= date('d.m.Y H:i', strtotime($q['created_at'])) ?></p>
                    <div class="record-actions">
                        <a href="edit.php?id=<?= $q['id'] ?>">Редагувати</a>
                        <a href="delete.php?id=<?= $q['id'] ?>" onclick="return confirm('Ви впевнені, що хочете видалити цей запис?')">Видалити</a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Немає жодного запису.</p>
        <?php endif; ?>

        <p><a href="dashboard.php">← Назад до адмін-панелі</a></p>
    </main>
</body>
</html>
