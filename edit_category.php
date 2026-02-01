<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$cat = $stmt->fetch();
if (!$cat) redirect('index.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $image = trim($_POST['image_url']);
    $color = $_POST['color_hex'];

    $stmt = $pdo->prepare("UPDATE categories SET name=?, image_url=?, color_hex=? WHERE id=?");
    $stmt->execute([$name, $image, $color, $id]);
    redirect('index.php');
}
?>
<!DOCTYPE html>
<html>
<head><link rel="stylesheet" href="assets/style.css"></head>
<body>
<form method="post">
  <input name="name" value="<?= e($cat['name']) ?>">
  <input name="image_url" value="<?= e($cat['image_url']) ?>">
  <input type="color" name="color_hex" value="<?= e($cat['color_hex']) ?>">
  <button>Opslaan</button>
</form>
</body>
</html>
