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
<main class="container">
<h1><?= e($category['name']) ?></h1>

<?php foreach($videos as $v): ?>
  <div class="glass-card">
    <h3><?= e($v['title']) ?></h3>
    <a href="<?= e($v['youtube_url']) ?>" target="_blank">▶ Afspelen</a>
  </div>
<?php endforeach; ?>

<?php if(is_admin()): ?>
  <a href="add_video.php?category_id=<?= $category['id'] ?>" class="btn btn-purple">➕ Video</a>
<?php endif; ?>
</main>
</body>
</html>
