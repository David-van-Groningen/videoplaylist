<?php
require 'config.php';
$user = current_user($pdo);
if (!$user) redirect('login.php');

$slug = $_GET['slug'] ?? '';
if (empty($slug)) redirect('index.php');

// Haal categorie op
$stmt = $pdo->prepare("SELECT * FROM categories WHERE slug = ?");
$stmt->execute([$slug]);
$category = $stmt->fetch();

if (!$category) redirect('index.php');

// Haal videos op voor deze categorie
$stmt = $pdo->prepare("
    SELECT v.*, u.username as creator_username 
    FROM videos v
    LEFT JOIN users u ON v.created_by = u.id
    WHERE v.category_id = ?
    ORDER BY v.created_at DESC
");
$stmt->execute([$category['id']]);
$videos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= sanitize_output($category['name']) ?> - Video Platform</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="topbar">
    <div class="left">
        <a href="index.php" class="btn ghost">← Terug</a>
        <span style="margin-left:1rem;font-size:1.25rem;"><?= sanitize_output($category['name']) ?></span>
    </div>
    <div class="right">
        <span>👤 <?= sanitize_output($user['display_name'] ?? $user['username']) ?></span>
        <?php if(is_admin()): ?>
            <a href="add_video.php?category_id=<?= $category['id'] ?>" class="btn btn-purple">➕ Video Toevoegen</a>
        <?php endif; ?>
        <a href="logout.php" class="btn btn-red">Logout</a>
    </div>
</header>

<main class="container">
    <?php if(!empty($category['image_url'])): ?>
    <div class="category-header" style="
        background: linear-gradient(135deg, rgba(0,0,0,0.7), rgba(0,0,0,0.5)), 
                    url('<?= sanitize_output($category['image_url']) ?>');
        background-size: cover;
        background-position: center;
        padding: 3rem 2rem;
        border-radius: 12px;
        margin-bottom: 2rem;
        border: 2px solid <?= sanitize_output($category['color_hex']) ?>;
    ">
        <h1 style="font-size:2.5rem;margin:0;"><?= sanitize_output($category['name']) ?></h1>
        <p style="color: var(--text-dim);margin-top:0.5rem;"><?= count($videos) ?> video(s)</p>
    </div>
    <?php else: ?>
    <h1 style="color: <?= sanitize_output($category['color_hex']) ?>;"><?= sanitize_output($category['name']) ?></h1>
    <?php endif; ?>

    <?php if(empty($videos)): ?>
        <div class="glass-card" style="text-align:center;padding:3rem;">
            <p style="font-size:1.1rem;color:var(--text-dim);margin-bottom:1.5rem;">
                📹 Nog geen video's in deze categorie
            </p>
            <?php if(is_admin()): ?>
                <a href="add_video.php?category_id=<?= $category['id'] ?>" class="btn btn-purple">➕ Eerste Video Toevoegen</a>
            <?php endif; ?>
        </div>
    <?php else: ?>
        <div class="grid videos">
            <?php foreach($videos as $video): 
                // Extract YouTube video ID
                preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/i', $video['youtube_url'], $matches);
                $youtube_id = $matches[1] ?? null;
                $thumbnail = $youtube_id ? "https://img.youtube.com/vi/{$youtube_id}/mqdefault.jpg" : 'assets/default_video.jpg';
            ?>
            <div class="video-card">
                <div class="video-thumbnail" style="background-image:url('<?= $thumbnail ?>');">
                    <a href="<?= sanitize_output($video['youtube_url']) ?>" target="_blank" rel="noopener noreferrer" class="play-overlay">
                        <span class="play-icon">▶</span>
                    </a>
                </div>
                <div class="video-info">
                    <h3><?= sanitize_output($video['title']) ?></h3>
                    <?php if($video['creator_username']): ?>
                    <p class="video-meta">Toegevoegd door: <?= sanitize_output($video['creator_username']) ?></p>
                    <?php endif; ?>
                    <p class="video-meta">📅 <?= date('d-m-Y', strtotime($video['created_at'])) ?></p>
                    
                    <div class="video-actions">
                        <a href="<?= sanitize_output($video['youtube_url']) ?>" target="_blank" rel="noopener noreferrer" class="btn btn-purple">
                            ▶ Afspelen
                        </a>
                        <button class="btn ghost copy-btn" data-url="<?= sanitize_output($video['youtube_url']) ?>">
                            📋 Kopieer Link
                        </button>
                        <?php if(is_admin()): ?>
                        <a href="edit_video.php?id=<?= $video['id'] ?>" class="btn ghost">
                            ✏️ Bewerken
                        </a>
                        <a href="delete_video.php?id=<?= $video['id'] ?>" 
                           onclick="return confirm('Weet je zeker dat je deze video wilt verwijderen?')"
                           class="btn btn-red">
                            🗑️ Verwijderen
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</main>

<script src="assets/app.js"></script>
</body>
</html>