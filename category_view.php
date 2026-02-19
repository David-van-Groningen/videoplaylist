<?php
require 'config.php';
$user = current_user($pdo);
if (!$user) redirect('login.php');

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$id]);
$category = $stmt->fetch();
if (!$category) redirect('index.php');

$stmt = $pdo->prepare("SELECT * FROM videos WHERE category_id=? ORDER BY created_at DESC");
$stmt->execute([$id]);
$videos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title><?= e($category['name']) ?></title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
  <div class="left">
    <a href="index.php" class="btn btn-yellow">Terug</a>
  </div>
  <div class="right">
    <span><?= e($user['display_name'] ?? $user['username']) ?></span>
    <a href="logout.php" class="btn btn-red">Logout</a>
  </div>
</header>

<main class="container">
<h1><?= e($category['name']) ?></h1>

<?php if(empty($videos)): ?>
  <p style="color:#666; margin:2rem 0;">Nog geen video's in deze categorie.</p>
<?php else: ?>
  <?php foreach($videos as $v): ?>
    <div class="glass-card">
      <h3><?= e($v['title']) ?></h3>
      <div style="display:flex; gap:0.5rem; align-items:center; margin-top:0.5rem;">
        <a href="<?= e($v['youtube_url']) ?>" target="_blank" class="btn btn-yellow">Afspelen</a>
        <?php if(is_admin()): ?>
          <a href="edit_video.php?id=<?= $v['id'] ?>" class="btn btn-black">Bewerken</a>
          <a href="delete.php?type=video&id=<?= $v['id'] ?>" class="btn btn-red" onclick="return confirm('Video verwijderen?')">Verwijderen</a>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
<?php endif; ?>

<?php if(is_admin()): ?>
  <div style="margin-top:2rem;">
    <a href="add_video.php?category_id=<?= $category['id'] ?>" class="btn btn-yellow">Video toevoegen</a>
  </div>
<?php endif; ?>
</main>
</body>
</html>
