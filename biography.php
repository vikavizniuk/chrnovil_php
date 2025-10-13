<?php include 'includes/header.php'; ?>
<div class="main-title" style="height: 20vw;">
    <div class="yellowback"></div>
    <p style="text-align:center; width: 50vw;"><strong>Дитинство</strong> та юність</p>
</div>

<div class="biography">
    <p>
        <?php
        $textPath = 'text/дитинство.txt';
        if (file_exists($textPath)) {
            echo nl2br(htmlspecialchars(file_get_contents($textPath)));
        } else {
            echo 'Текст не знайдено.';
        }
        ?>
    </p>
</div>

<div class="main-title" style="height: 20vw;">
    <div class="grayback"></div>
    <p style="text-align:center; width: 70vw; padding: 10px;"><strong>Громадська діяльність</strong> та три ув'язнення</p>
</div>

<div class="biography">
    <p>
        <?php
        $textPath = 'text/діяльність.txt';
        if (file_exists($textPath)) {
            echo nl2br(htmlspecialchars(file_get_contents($textPath)));
        } else {
            echo 'Текст не знайдено.';
        }
        ?>
    </p>
</div>

<div class="main-title" style="height: 20vw;">
    <div class="yellowback"></div>
    <p style="text-align:center; width: 50vw;"><strong>Репресії</strong> діяча</p>
</div>

<div class="biography">
    <p>
        <?php
        $textPath = 'text/репресії.txt';
        if (file_exists($textPath)) {
            echo nl2br(htmlspecialchars(file_get_contents($textPath)));
        } else {
            echo 'Текст не знайдено.';
        }
        ?>
    </p>
</div>

<div class="main-title" style="height: 20vw;">
    <div class="grayback"></div>
    <p style="text-align:center; width: 50vw;"><strong>Загибель</strong> діяча</p>
</div>

<div class="biography">
    <p>
        <?php
        $textPath = 'text/загибель.txt';
        if (file_exists($textPath)) {
            echo nl2br(htmlspecialchars(file_get_contents($textPath)));
        } else {
            echo 'Текст не знайдено.';
        }
        ?>
    </p>
</div>

<?php include 'includes/footer.php'; ?>
