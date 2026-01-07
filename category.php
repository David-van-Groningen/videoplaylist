<?php
require 'config.php';
$user = current_user($pdo);
if (!$user || !$user['is_admin']) {
    die("Toegang geweigerd. Alleen admins kunnen categorieën bewerken.");
}

$id = $_GET['id'] ?? null;
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$id]);
$cat = $stmt->fetch();

if (!$cat) die("Categorie niet gevonden.");

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $slug = trim($_POST['slug']);
    $image = trim($_POST['image_url']);
    $color = $_POST['color_hex'];

    if (empty($name) || empty($slug)) {
        $errors[] = "Naam en Slug zijn verplicht.";
    } else {
        $stmt = $pdo->prepare("UPDATE categories SET name=?, slug=?, image_url=?, color_hex=? WHERE id=?");
        try {
            $stmt->execute([$name, $slug, $image, $color, $id]);
            $success = true;
            $cat['name'] = $name;
            $cat['slug'] = $slug;
            $cat['image_url'] = $image;
            $cat['color_hex'] = $color;
        } catch (PDOException $e) {
            $errors[] = "Fout bij bijwerken: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>✏️ Categorie Aanpassen</title>
<link rel="stylesheet" href="assets/style.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lucide-static@0.244.0/dist/lucide.min.css">
</head>
<body>

<header class="topbar">
  <div class="left"><a class="btn btn-purple" href="index.php">🏠 Home</a></div>
  <div class="right"><span>Edit Category</span><a class="btn ghost" href="logout.php">Logout</a></div>
</header>

<main class="container">
  <div class="glass-card" style="max-width:600px; margin:3rem auto;">
    <h1 style="text-align:center; margin-bottom:1rem;">✏️ Categorie Aanpassen</h1>

    <?php foreach ($errors as $err): ?>
      <div class="notice err"><?= htmlspecialchars($err) ?></div>
    <?php endforeach; ?>
    <?php if ($success): ?>
      <div class="notice" style="border-left-color:var(--success)">✅ Categorie succesvol bijgewerkt!</div>
    <?php endif; ?>

    <form method="POST" class="form-fancy">
      <label>
        Naam *
        <input type="text" name="name" value="<?= htmlspecialchars($cat['name']) ?>" required>
      </label>
      <label>
        Slug *
        <input type="text" name="slug" value="<?= htmlspecialchars($cat['slug']) ?>" pattern="[a-z0-9-]+" title="Alleen kleine letters, cijfers en streepjes" required>
      </label>
      <label>
        Afbeelding URL
        <input type="url" name="image_url" value="<?= htmlspecialchars($cat['image_url']) ?>" placeholder="https://example.com/image.jpg">
      </label>
      <label style="display:flex; align-items:center; gap:10px;">
        Kleur
        <input type="color" name="color_hex" value="<?= htmlspecialchars($cat['color_hex']) ?>">
        <span class="color-preview" style="background-color:<?= htmlspecialchars($cat['color_hex']) ?>"></span>
      </label>

      <div class="actions" style="justify-content:flex-end; margin-top:1.5rem;">
        <a href="index.php" class="btn btn-red">
          <span class="icon">✖️</span> Annuleren
        </a>
        <button type="submit" class="btn btn-purple">
          <span class="icon">💾</span> Opslaan
        </button>
      </div>
    </form>
  </div>
</main>

<script>
const colorInput = document.querySelector('input[type=color]');
const preview = document.querySelector('.color-preview');
colorInput.addEventListener('input', e => {
  preview.style.backgroundColor = e.target.value;
});
</script>

</body>
</html>
