<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$cat = $stmt->fetch();
if (!$cat) redirect('index.php');

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $image = trim($_POST['image_url'] ?? '');
    $color = $_POST['color_hex'] ?? '#FFD700';

    if ($name === '') {
        $msg = "Naam is verplicht";
    } else {
        $stmt = $pdo->prepare("UPDATE categories SET name=?, image_url=?, color_hex=? WHERE id=?");
        $stmt->execute([$name, $image, $color, $id]);
        redirect('index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Categorie bewerken</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container" style="max-width:600px;">
<h1>Categorie bewerken</h1>

<?php if($msg): ?><div class="notice err"><?= e($msg) ?></div><?php endif; ?>

<form method="post">
  <label>Naam <input type="text" name="name" value="<?= e($cat['name']) ?>" required></label>
  <label>Afbeelding URL <input type="url" name="image_url" value="<?= e($cat['image_url']) ?>"></label>
  <label>Kleur <input type="color" name="color_hex" value="<?= e($cat['color_hex']) ?>"></label>
  
  <div style="margin-top:1rem; display:flex; gap:0.5rem;">
    <button class="btn btn-yellow">Opslaan</button>
    <a href="index.php" class="btn">Annuleren</a>
  </div>
</form>
</main>
</body>
</html>
