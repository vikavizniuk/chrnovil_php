<?php
require_once 'includes/db.php';
include 'includes/header.php';

$stmt = $pdo->query("SELECT section, content FROM biography_blocks ORDER BY id");
$blocks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="main-title" style="height: 20vw;">
<div class="yellowback"></div>
    <p style="text-align:center; width: 50vw;"><strong>Політична</strong> діяльність</p>
</div>

<div class="biography">

    <?php foreach ($blocks as $block): ?>
        <h2><?= htmlspecialchars($block['section']) ?></h2>
        <p><?= nl2br(htmlspecialchars($block['content'])) ?></p>
    <?php endforeach; ?>
</div>

<?php include 'includes/footer.php'; ?>
