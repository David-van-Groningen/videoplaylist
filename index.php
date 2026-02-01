<?php
require 'config.php';
$user = current_user($pdo);
if (!$user) redirect('login.php');

$cats = $pdo->query("SELECT * FROM categories ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Dashboard</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="left">📹 Video Dashboard</div>
  <div class="right">
    <span>👤 <?= e($user['display_name'] ?? $user['username']) ?></span>
    <a href="logout.php" class="btn btn-red">Logout</a>
  </div>
</header>

<main class="container">
<h1>Categorieën</h1>

<div class="grid cards">
<?php foreach($cats as $cat): ?>
  <div class="card-cat">
    <div class="img" style="background-image:url('<?= e($cat['image_url'] ?: 'assets/default_cat.jpg') ?>')"></div>
    <h3><?= e($cat['name']) ?></h3>
    <div class="actions">
      <a href="category_view.php?id=<?= $cat['id'] ?>" class="btn btn-accent">🎬 Videos</a>
      <?php if(is_admin()): ?>
        <a href="edit_category.php?id=<?= $cat['id'] ?>" class="btn btn-purple">✏️</a>
        <a href="delete_category.php?id=<?= $cat['id'] ?>" class="btn btn-red" onclick="return confirm('Verwijderen?')">🗑️</a>
      <?php endif; ?>
    </div>
  </div>
<?php endforeach; ?>
</div>

<?php if(is_admin()): ?>
  <div style="margin-top:2rem;text-align:center;">
    <a href="add_category.php" class="btn btn-purple">➕ Nieuwe categorie</a>
  </div>
<?php endif; ?>
</main>
</body>
</html>
