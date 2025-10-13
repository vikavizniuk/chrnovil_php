<?php
require_once 'includes/db.php';
include 'includes/header.php';

$categories = $pdo->query("SELECT id, name FROM category ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

$selectedCategoryId = isset($_GET['category_id']) ? (int)$_GET['category_id'] : 0;

if ($selectedCategoryId > 0) {
    $stmt = $pdo->prepare("
        SELECT q.content, q.description, q.created_at, c.name AS category
        FROM quotes q
        LEFT JOIN category c ON q.category_id = c.id
        WHERE q.category_id = :id
        ORDER BY q.created_at DESC
    ");
    $stmt->execute(['id' => $selectedCategoryId]);
} else {
    $stmt = $pdo->query("
        SELECT q.content, q.description, q.created_at, c.name AS category
        FROM quotes q
        LEFT JOIN category c ON q.category_id = c.id
        ORDER BY q.created_at DESC
    ");
}
$quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<style>
    .filter-container {
        display: flex;
        justify-content: center;
        margin: 30px;
    }

    .filter-form {
        background-color: #e8e8e8;
        padding: 20px 30px;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
        font-family: 'Poppins', sans-serif;
        font-size: 1.2vw;
    }

    .filter-form label {
        font-weight: bold;
        margin-bottom: 10px;
    }

    .filter-form select {
        padding: 10px;
        font-size: 1.1vw;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .text-block {
        margin-bottom: 40px;
        background: #f5f5f5;
        padding: 20px;
        border-left: 10px solid black;
    }
    .text-block p{
        font-size: x-large;
    }

    .text-block em {
        font-style: italic;
        color: #333;
    }

    .text-block small {
        display: block;
        margin-top: 10px;
        color: #666;
    }
</style>

<div class="main-title" style="height: 20vw;">
<div class="yellowback"></div>
    <p style="text-align:center; width: 50vw;">Архів текстів <strong>Чорновола</strong></p>
</div>
<div class="vstup">
    <div class="filter-container">
        <form method="get" class="filter-form">
            <label for="category_id">Фільтрувати за категорією:</label>
            <select name="category_id" id="category_id" onchange="this.form.submit()">
                <option value="0">Усі категорії</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $cat['id'] == $selectedCategoryId ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </form>
    </div>

    <?php if (count($quotes) > 0): ?>
        <?php foreach ($quotes as $q): ?>
            <div class="text-block">
                <p><strong><?= htmlspecialchars(ucfirst($q['category'])) ?>:</strong></p>
                <p><em><?= htmlspecialchars($q['description']) ?></em></p>
                <p style="white-space: pre-line; margin-top: 15px;"><?= htmlspecialchars($q['content']) ?></p>
                <small>Дата: <?= date('d.m.Y H:i', strtotime($q['created_at'])) ?></small>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p style="text-align:center;">Записів не знайдено для обраної категорії.</p>
    <?php endif; ?>
</div>

<?php include 'includes/footer.php'; ?>
