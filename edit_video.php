<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM videos WHERE id=?");
$stmt->execute([$id]);
$video = $stmt->fetch();
if (!$video) redirect('index.php');

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $url   = trim($_POST['youtube_url'] ?? '');

    if ($title === '' || $url === '') {
        $msg = "Titel en YouTube URL zijn verplicht";
    } else {
        $stmt = $pdo->prepare("UPDATE videos SET title=?, youtube_url=? WHERE id=?");
        $stmt->execute([$title, $url, $id]);

        redirect("category_view.php?id=" . $video['category_id']);
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Video bewerken</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container" style="max-width:600px;">
<h1>Video bewerken</h1>

<?php if($msg): ?>
  <div class="notice err"><?= e($msg) ?></div>
<?php endif; ?>

<form method="post">
  <label>
    Titel
    <input type="text" name="title" value="<?= e($video['title']) ?>" required>
  </label>

  <label>
    YouTube URL
    <input type="url" name="youtube_url" value="<?= e($video['youtube_url']) ?>" required>
  </label>

  <button class="btn btn-yellow">Opslaan</button>
  <a href="category_view.php?id=<?= $video['category_id'] ?>" class="btn">Annuleren</a>
</form>
</main>
</body>
</html>
