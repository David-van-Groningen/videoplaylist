<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$notice = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $color_hex = $_POST['color_hex'] ?? '#8b5cf6';

    if (empty($name) || empty($slug)) {
        $error = "Naam en slug zijn verplicht";
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO categories (name, slug, image_url, color_hex) VALUES (?,?,?,?)");
            $stmt->execute([$name, $slug, $image_url, $color_hex]);
            $notice = "Categorie succesvol toegevoegd!";
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "Deze slug bestaat al";
            } else {
                $error = "Database fout: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Nieuwe Categorie</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="left">
        <a href="index.php" class="btn ghost">← Terug</a>
    </div>
    <div class="right">
        <span>Nieuwe Categorie</span>
    </div>
</header>

<main class="container" style="max-width:600px;margin-top:2rem;">
    <div class="glass-card">
        <h1>➕ Nieuwe Categorie</h1>
        
        <?php if($notice): ?>
        <div class="notice success"><?= sanitize_output($notice) ?></div>
        <?php endif; ?>
        
        <?php if($error): ?>
        <div class="notice err"><?= sanitize_output($error) ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <label>
                Naam *
                <input type="text" name="name" required>
            </label>
            
            <label>
                Slug * <small style="color:var(--text-dim);">(alleen kleine letters, cijfers en streepjes)</small>
                <input type="text" name="slug" pattern="[a-z0-9-]+" required>
            </label>
            
            <label>
                Afbeelding URL
                <input type="url" name="image_url" placeholder="https://...">
            </label>
            
            <label style="display:flex;align-items:center;gap:1rem;">
                Kleur
                <input type="color" name="color_hex" value="#8b5cf6" id="catColor">
                <span class="color-preview" id="colorPreview" style="background-color:#8b5cf6;"></span>
            </label>
            
            <div class="actions">
                <button type="submit" class="btn btn-purple">💾 Opslaan</button>
                <a href="index.php" class="btn btn-red">❌ Annuleren</a>
            </div>
        </form>
    </div>
</main>

<script>
const colorInput = document.getElementById('catColor');
const colorPreview = document.getElementById('colorPreview');
colorInput.addEventListener('input', (e) => {
    colorPreview.style.backgroundColor = e.target.value;
});
</script>
</body>
</html>