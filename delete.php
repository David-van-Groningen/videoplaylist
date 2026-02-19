<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$type = $_GET['type'] ?? '';
$id = intval($_GET['id'] ?? 0);

if ($id > 0) {
    if ($type === 'category') {
        $pdo->prepare("DELETE FROM categories WHERE id=?")->execute([$id]);
        redirect('index.php');
    } elseif ($type === 'video') {
        $stmt = $pdo->prepare("SELECT category_id FROM videos WHERE id=?");
        $stmt->execute([$id]);
        $video = $stmt->fetch();
        
        $pdo->prepare("DELETE FROM videos WHERE id=?")->execute([$id]);
        
        if ($video) {
            redirect("category_view.php?id=" . $video['category_id']);
        } else {
            redirect('index.php');
        }
    }
}

redirect('index.php');
