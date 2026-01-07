<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$cat = $stmt->fetch();

if (!$cat) {
    die("Categorie niet gevonden");
}

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $image = trim($_POST['image_url'] ?? '');
    $color = $_POST['color_hex'] ?? '#8b5cf6';

    if (empty($name) || empty($slug)) {
        $errors[] = "Naam en Slug zijn verplicht";
    } else {
        try {
            $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, image_url=?, color_hex=? WHERE id=?");
            $stmt->execute([$name, $slug, $image, $color, $id]);
            $success = "Categorie succesvol bijgewerkt!";
            $cat = array_merge($cat, [
                'name' => $name,
                'slug' => $slug,
                'image_url' => $image,
                'color_hex' => $color
            ]);
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $errors[] = "Deze slug bestaat al";
            } else {
                $errors[] = "Database fout: " . $e->getMessage();
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
<title>Categorie Bewerken</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="left">
        <a href="index.php" class="btn ghost">← Terug</a>
    </div>
    <div class="right">
        <span>Categorie Bewerken</span>
    </div>
</header>

<main class="container" style="max-width:600px;margin-top:2rem;">
    <div class="glass-card">
        <h1>✏️ Categorie Aanpassen</h1>

        <?php foreach($errors as $err): ?>
        <div class="notice err"><?= sanitize_output($err) ?></div>
        <?php endforeach; ?>

        <?php if($success): ?>
        <div class="notice success"><?= sanitize_output($success) ?></div>
        <?php endif; ?>

        <form method="POST">
            <label>
                Naam *
                <input type="text" name="name" value="<?= sanitize_output($cat['name']) ?>" required>
            </label>
            
            <label>
                Slug * <small style="color:var(--text-dim);">(alleen kleine letters, cijfers en streepjes)</small>
                <input type="text" name="slug" value="<?= sanitize_output($cat['slug']) ?>" pattern="[a-z0-9-]+" required>
            </label>
            
            <label>
                Afbeelding URL
                <input type="url" name="image_url" value="<?= sanitize_output($cat['image_url']) ?>" placeholder="https://...">
            </label>
            
            <label style="display:flex;align-items:center;gap:1rem;">
                Kleur
                <input type="color" name="color_hex" value="<?= sanitize_output($cat['color_hex']) ?>" id="colorPicker">
                <span class="color-preview" id="colorPreview" style="background-color:<?= sanitize_output($cat['color_hex']) ?>;"></span>
            </label>

            <div class="actions">
                <button type="submit" class="btn btn-purple">💾 Opslaan</button>
                <a href="index.php" class="btn btn-red">❌ Annuleren</a>
            </div>
        </form>
    </div>
</main>

<script>
const colorPicker = document.getElementById('colorPicker');
const colorPreview = document.getElementById('colorPreview');
colorPicker.addEventListener('input', (e) => {
    colorPreview.style.backgroundColor = e.target.value;
});
</script>
</body>
</html>