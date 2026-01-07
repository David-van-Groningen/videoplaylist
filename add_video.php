<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$cats = $pdo->query("SELECT * FROM categories ORDER BY name ASC")->fetchAll();
$notice = '';
$error = '';

// Pre-selecteer categorie als meegegeven via URL
$preselect_category = intval($_GET['category_id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $url = trim($_POST['youtube_url'] ?? '');
    $category_id = intval($_POST['category_id'] ?? 0);

    if (empty($title) || empty($url) || $category_id === 0) {
        $error = "Vul alle velden in";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO videos (title, youtube_url, category_id, created_by) VALUES (?,?,?,?)");
            $stmt->execute([$title, $url, $category_id, $_SESSION['user_id']]);
            $notice = "Video succesvol toegevoegd!";
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
<title>Nieuwe Video</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="left">
        <a href="index.php" class="btn ghost">← Terug</a>
    </div>
    <div class="right">
        <span>Nieuwe Video</span>
    </div>
</header>

<main class="container" style="max-width:600px;margin-top:2rem;">
    <div class="glass-card">
        <h1>🎬 Nieuwe Video</h1>
        
        <?php if($notice): ?>
        <div class="notice success"><?= sanitize_output($notice) ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
        <div class="notice err"><?= sanitize_output($error) ?></div>
        <?php endif; ?>
        
        <?php if(empty($cats)): ?>
        <div class="notice err">
            Eerst een categorie aanmaken voordat je videos kunt toevoegen.
        </div>
        <div class="actions">
            <a href="add_category.php" class="btn btn-purple">➕ Categorie Toevoegen</a>
        </div>
        <?php else: ?>
        <form method="POST">
            <label>
                Titel *
                <input type="text" name="title" required>
            </label>
            
            <label>
                YouTube URL *
                <input type="url" name="youtube_url" placeholder="https://www.youtube.com/watch?v=..." required>
            </label>
            
            <label>
                Categorie *
                <select name="category_id" required>
                    <option value="">-- Kies een categorie --</option>
                    <?php foreach($cats as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $preselect_category == $cat['id'] ? 'selected' : '' ?>>
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
        <?php endif; ?>
    </div>
</main>
</body>
</html>