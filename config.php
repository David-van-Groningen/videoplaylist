<?php
session_start();

$dsn = 'mysql:host=localhost;dbname=video_platform;charset=utf8mb4';
$user = 'user';
$pass = 'password';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit('Database fout');
}

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user($pdo) {
    if (!is_logged_in()) return null;
    $stmt = $pdo->prepare("SELECT id, username, display_name, is_admin FROM users WHERE id=?");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function is_admin() {
    global $pdo;
    $u = current_user($pdo);
    return $u && $u['is_admin'];
}

function redirect($url) {
    header("Location: $url");
    exit;
}

function e($s) {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}
