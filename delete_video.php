<?php
require 'config.php';
if (!is_admin()) redirect('index.php');

$id = intval($_GET['id'] ?? 0);

// Eerst video ophalen om category_id te kennen
$stmt = $pdo->prepare("SELECT * FROM videos WHERE id=?");
$stmt->execute([$id]);
$video = $stmt->fetch();
if (!$video) redirect('index.php');

// Verwijderen
$stmt = $pdo->prepare("DELETE FROM videos WHERE id=?");
$stmt->execute([$id]);

redirect("category_view.php?id=" . $video['category_id']);
