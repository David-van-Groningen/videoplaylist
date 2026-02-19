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
  <div class="left">Video Dashboard</div>
  <div class="right">
    <span><?= e($user['display_name'] ?? $user['username']) ?></span>
    <a href="logout.php" class="btn btn-red">Logout</a>
  </div>
</header>

<main class="container">
<h1>Categorieën</h1>

<?php if(empty($cats)): ?>
  <p style="color:#666; margin:2rem 0;">Nog geen categorieën aangemaakt.</p>
<?php else: ?>
  <div class="grid cards">
  <?php foreach($cats as $cat): ?>
    <div class="card-cat">
      <div class="img" style="background-image:url('<?= e($cat['image_url'] ?: 'assets/default_cat.jpg') ?>')"></div>
      <h3><?= e($cat['name']) ?></h3>
      <div class="actions">
        <a href="category_view.php?id=<?= $cat['id'] ?>" class="btn btn-yellow">Videos</a>
        <?php if(is_admin()): ?>
          <a href="edit_category.php?id=<?= $cat['id'] ?>" class="btn btn-black">Bewerken</a>
          <a href="delete.php?type=category&id=<?= $cat['id'] ?>" class="btn btn-red" onclick="return confirm('Categorie verwijderen?')">Verwijderen</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
<?php endif; ?>

<?php if(is_admin()): ?>
  <div style="margin-top:2rem;text-align:center;">
    <a href="add_category.php" class="btn btn-yellow">Nieuwe categorie</a>
  </div>
<?php endif; ?>
</main>
</body>
</html>
