<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit;
}
require_once '../includes/db.php';

$message = '';
$content = '';
$description = '';
$is_preview = isset($_POST['preview']);
$is_submit = isset($_POST['submit']);

$categories = $pdo->query("SELECT id, name FROM category ORDER BY name")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $category_id = (int) ($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $input_method = $_POST['input_method'] ?? '';

    if ($category_id <= 0) {
        $message = 'Оберіть категорію.';
    } else {
        if ($input_method === 'text') {
            $content = trim($_POST['content']);
            if (!preg_match('/^«.*»$/u', $content) && $content !== '') {
                $content = '«' . trim($content, "«»\"") . '»';
            }
        } 
        if ($input_method === 'file') {
            if (!empty($_POST['processed_file_content'])) {
                $content = trim($_POST['processed_file_content']);
            } elseif (
                isset($_FILES['textfile']) &&
                $_FILES['textfile']['error'] === UPLOAD_ERR_OK
            ) {
                $tmpPath = $_FILES['textfile']['tmp_name'];
                $raw = file_get_contents($tmpPath);
                $lines = array_filter(array_map('trim', explode("\n", $raw)));

                if (count($lines) === 1) {
                    $line = $lines[0];
                    if (!preg_match('/^«.*»$/u', $line)) {
                        $line = '«' . trim($line, "«»\"") . '»';
                    }
                    $content = $line;
                } elseif (count($lines) > 1) {
                    $joined = implode(' ', $lines);
                    $content = '«' . trim($joined, "«»\"") . '»';
                }
            }
            if (
                isset($_FILES['textfile']) &&
                $_FILES['textfile']['error'] === UPLOAD_ERR_OK
            ) {
                $originalName = basename($_FILES['textfile']['name']);
                $newName = time() . '_' . preg_replace('/[^a-zA-Z0-9\._-]/', '_', $originalName);
                $destination = '../uploads/' . $newName;

                if (move_uploaded_file($_FILES['textfile']['tmp_name'], $destination)) {
                    $stmtUpload = $pdo->prepare("INSERT INTO uploads (title, file_path) VALUES (:title, :file_path)");
                    $stmtUpload->execute([
                        'title' => $originalName,
                        'file_path' => $destination
                    ]);
                }
            }
        } 

        if ($is_submit && !empty($content)) {
            $stmt = $pdo->prepare("INSERT INTO quotes (content, category_id, description) VALUES (:content, :category_id, :description)");
            $stmt->execute([
                'content' => $content,
                'category_id' => $category_id,
                'description' => $description
            ]);
            $message = 'Запис успішно додано!';
            $content = '';
            $description = '';
        } elseif ($is_submit && empty($content)) {
            $message = 'Текст не може бути порожнім.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <title>Додати текст</title>
    <link rel="stylesheet" href="../css/styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #e8e8e8;
            margin: 0;
            padding: 0;
        }

        .upload-container {
            max-width: 800px;
            margin: 60px auto;
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }

        .upload-container h2 {
            text-align: center;
            font-size: 2.5vw;
            margin-bottom: 30px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-top: 20px;
            margin-bottom: 8px;
            text-align: center;
            font-size: 1.1vw;
        }

        input[type="file"], textarea {
            width: 700px;
            padding: 12px;
            font-size: 1vw;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            display: block;
            margin: auto;
        }
        select {
            width: 725px;
            padding: 12px;
            font-size: 1vw;
            border: 1px solid #ccc;
            border-radius: 8px;
            resize: vertical;
            display: block;
            margin: auto;
        }

        .input-toggle {
            margin: 20px auto;
            width: 700px;
            display: flex;
            justify-content: center;
        }

        .input-toggle label {
            margin-right: 30px;
            font-weight: normal;
            font-size: 1.1vw;
            cursor: pointer;
        }
        button[name="preview"]{
            border: none;
            background-color:rgb(255, 255, 255);
            font-size: 1vw;
            width: 200px;
            margin: 20px auto 0;
            cursor: pointer;
            transition: 0.3s;
        }

        button[name="preview"]:hover {
            color:rgb(159, 159, 159);
        }

        button[name="submit"]{
            background-color: yellow;
            color: black;
            padding: 14px 0;
            width: 730px;
            border: none;
            font-size: 1.2vw;
            border-radius: 8px;
            margin: 20px auto 0;
            cursor: pointer;
            transition: 0.4s;
            display: block;
        }

        button[name="submit"]:hover {
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

        .hidden {
            display: none;
        }
    </style>
</head>
<body>
<div class="upload-container">
    <h2>Додати текст</h2>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <label for="category_id">Категорія:</label>
        <select name="category_id" id="category_id" required>
            <option value="">Оберіть категорію</option>
            <?php foreach ($categories as $cat): ?>
                <option value="<?= $cat['id'] ?>" <?= isset($_POST['category_id']) && $_POST['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($cat['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="description">Опис:</label>
        <textarea name="description" rows="4" required><?= htmlspecialchars($description) ?></textarea>

        <div class="input-toggle">
            <label><input type="radio" name="input_method" value="text" <?= (!isset($_POST['input_method']) || $_POST['input_method'] === 'text') ? 'checked' : '' ?> onclick="toggleInputs()"> Ввести вручну</label>
            <label><input type="radio" name="input_method" value="file" <?= (isset($_POST['input_method']) && $_POST['input_method'] === 'file') ? 'checked' : '' ?> onclick="toggleInputs()"> Завантажити файл (.txt)</label>
        </div>

        <div id="text-input-block" <?= (isset($_POST['input_method']) && $_POST['input_method'] === 'file') ? 'class="hidden"' : '' ?>>
            <label for="content">Текст:</label>
            <textarea name="content" id="content" rows="4"><?= htmlspecialchars($content) ?></textarea>
        </div>
        <input type="hidden" name="processed_file_content" value="<?= htmlspecialchars($content) ?>">

        <div id="file-input-block" <?= (!isset($_POST['input_method']) || $_POST['input_method'] === 'text') ? 'class="hidden"' : '' ?>>
            <label for="textfile">Файл (.txt):</label>
            <input type="file" name="textfile" id="textfile" accept=".txt">
        </div>

        <button type="submit" name="submit">Підтвердити збереження</button>
        <button type="submit" name="preview">Попередній перегляд</button>
    </form>

    <?php if ($is_preview && !empty($content)): ?>
        <div style="background:#f5f5f5; border-left:5px solid black; padding:20px; margin-top:30px;">
            <h3>Попередній перегляд:</h3>
            <p><em><?= htmlspecialchars($description) ?></em></p>
            <p><?= nl2br(htmlspecialchars($content)) ?></p>
        </div>
    <?php endif; ?>

    <a class="back-link" href="dashboard.php">← Назад до адмін-панелі</a>
</div>

<script>
    function toggleInputs() {
        const textInput = document.getElementById('text-input-block');
        const fileInput = document.getElementById('file-input-block');
        const method = document.querySelector('input[name="input_method"]:checked').value;

        if (method === 'text') {
            textInput.classList.remove('hidden');
            fileInput.classList.add('hidden');
        } else {
            fileInput.classList.remove('hidden');
            textInput.classList.add('hidden');
        }
    }
</script>
</body>
</html>
