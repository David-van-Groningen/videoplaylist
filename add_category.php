<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $image = trim($_POST['image_url'] ?? '');
    $color = $_POST['color_hex'] ?? '#FFD700';

    if ($name === '') {
        $msg = "Naam is verplicht";
    } else {
        $stmt = $pdo->prepare("INSERT INTO categories (name, image_url, color_hex) VALUES (?,?,?)");
        $stmt->execute([$name, $image, $color]);
        redirect('index.php');
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Nieuwe categorie</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container" style="max-width:600px;">
<h1>Nieuwe categorie</h1>

<?php if($msg): ?><div class="notice err"><?= e($msg) ?></div><?php endif; ?>

<form method="post">
  <label>Naam <input type="text" name="name" required></label>
  <label>Afbeelding URL <input type="url" name="image_url"></label>
  <label>Kleur <input type="color" name="color_hex" value="#FFD700"></label>
  <button class="btn btn-yellow">Opslaan</button>
  <a href="index.php" class="btn">Annuleren</a>
</form>
</main>
</body>
</html>
