<?php
// delete_category.php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
    } catch (PDOException $e) {
        // Stille error handling
    }
}
redirect('index.php');
?>

<?php
// delete_video.php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
    try {
        $pdo->prepare("DELETE FROM videos WHERE id=?")->execute([$id]);
    } catch (PDOException $e) {
        // Stille error handling
    }
}
redirect('index.php');
?>