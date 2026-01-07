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
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Video Platform</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="left">📹 Video Dashboard</div>
    <div class="right">
        <span>👤 <?= sanitize_output($user['display_name'] ?? $user['username']) ?></span>
        <a href="logout.php" class="btn btn-red">Logout</a>
    </div>
</header>

<main class="container">
    <h1>Video categorieën</h1>
    
    <?php if(empty($cats)): ?>
        <div class="glass-card" style="text-align:center;padding:3rem;">
            <p style="font-size:1.1rem;color:var(--text-dim);margin-bottom:1.5rem;">
                Nog geen categorieën aangemaakt
            </p>
            <?php if(is_admin()): ?>
                <a href="add_category.php" class="btn btn-purple">➕ Eerste Categorie Toevoegen</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid cards">
            <?php foreach($cats as $cat): ?>
            <div class="card-cat" style="border-color:<?= sanitize_output($cat['color_hex']) ?>;">
                <div class="img" style="background-image:url('<?= sanitize_output($cat['image_url'] ?: 'assets/default_cat.jpg') ?>')"></div>
                <div class="card-content">
                    <h3><?= sanitize_output($cat['name']) ?></h3>
                    <div class="actions">
                        <a href="category_view.php?slug=<?= urlencode($cat['slug']) ?>" class="btn btn-accent">
                            🎬 Videos
                        </a>
                        <?php if(is_admin()): ?>
                        <a href="edit_category.php?id=<?= $cat['id'] ?>" class="btn btn-purple">
                            ✏️ Bewerken
                        </a>
                        <a href="delete_category.php?id=<?= $cat['id'] ?>"
                           onclick="return confirm('Weet je zeker dat je deze categorie + alle videos wilt verwijderen?')"
                           class="btn btn-red">
                            🗑️ Verwijderen
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if(is_admin()): ?>
        <div style="margin-top:2rem;text-align:center;">
            <a href="add_category.php" class="btn btn-purple">➕ Nieuwe Categorie</a>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</main>

<script src="assets/app.js"></script>
</body>
</html>