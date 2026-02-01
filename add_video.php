<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$category_id = intval($_GET['category_id'] ?? 0);

// Check of categorie bestaat
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id=?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();
if (!$category) redirect('index.php');

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $url   = trim($_POST['youtube_url'] ?? '');

    if ($title === '' || $url === '') {
        $msg = "Titel en YouTube URL zijn verplicht";
    } else {
        $stmt = $pdo->prepare(
            "INSERT INTO videos (category_id, title, youtube_url, created_by) VALUES (?,?,?,?)"
        );
        $stmt->execute([
            $category_id,
            $title,
            $url,
            $_SESSION['user_id'] ?? null
        ]);

        redirect("category_view.php?id=" . $category_id);
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<title>Video toevoegen</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container" style="max-width:600px;">
<h1>➕ Video toevoegen aan <?= e($category['name']) ?></h1>

<?php if($msg): ?>
  <div class="notice err"><?= e($msg) ?></div>
<?php endif; ?>

<form method="post">
  <label>
    Titel
    <input type="text" name="title" required>
  </label>

  <label>
    YouTube URL
    <input type="url" name="youtube_url" required>
  </label>

  <button class="btn btn-purple">Opslaan</button>
  <a href="category_view.php?id=<?= $category_id ?>" class="btn">Annuleren</a>
</form>
</main>
</body>
</html>
