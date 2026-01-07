<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM videos WHERE id=?");
$stmt->execute([$id]);
$video = $stmt->fetch();

if (!$video) redirect('index.php');

$cats = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();

$notice = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $url = trim($_POST['youtube_url'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);

    if (empty($title) || empty($url) || $category_id === 0) {
        $error = "Vul alle velden in";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE videos SET title=?, youtube_url=?, category_id=? WHERE id=?");
            $stmt->execute([$title, $url, $category_id, $id]);
            $notice = "Video succesvol bijgewerkt!";
            $video['title'] = $title;
            $video['youtube_url'] = $url;
            $video['category_id'] = $category_id;
        } catch (PDOException $e) {
            $error = "Database fout: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Video Bewerken</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="left">
        <a href="index.php" class="btn ghost">← Terug</a>
    </div>
    <div class="right">
        <span>Video Bewerken</span>
    </div>
</header>

<main class="container" style="max-width:600px;margin-top:2rem;">
    <div class="glass-card">
        <h1>✏️ Video Aanpassen</h1>
        
        <?php if($notice): ?>
        <div class="notice success"><?= sanitize_output($notice) ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
        <div class="notice err"><?= sanitize_output($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label>
                Titel *
                <input type="text" name="title" value="<?= sanitize_output($video['title']) ?>" required>
            </label>
            
            <label>
                YouTube URL *
                <input type="url" name="youtube_url" value="<?= sanitize_output($video['youtube_url']) ?>" required>
            </label>
            
            <label>
                Categorie *
                <select name="category_id" required>
                    <?php foreach($cats as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $video['category_id'] == $cat['id'] ? 'selected' : '' ?>>
                        <?= sanitize_output($cat['name']) ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </label>
            
            <div class="actions">
                <button type="submit" class="btn btn-purple">💾 Opslaan</button>
                <a href="index.php" class="btn btn-red">❌ Annuleren</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>