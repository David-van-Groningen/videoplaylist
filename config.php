<?php
declare(strict_types=1);

session_start([
    'cookie_httponly' => true,
    'cookie_samesite' => 'Lax',
]);

$dsn = 'mysql:host=localhost;dbname=video_platform;charset=utf8mb4';
$user = 'username';
$pass = 'password';

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    exit('Database verbinding mislukt: ' . $e->getMessage());
}

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function current_user(PDO $pdo): ?array {
    if (!is_logged_in()) return null;
    $stmt = $pdo->prepare('SELECT id, username, display_name, email, is_admin FROM users WHERE id=?');
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch() ?: null;
}

function is_admin(): bool {
    global $pdo;
    $user = current_user($pdo);
    return $user && $user['is_admin'] == 1;
}

function redirect(string $location): void {
    header("Location: $location");
    exit;
}

function sanitize_output(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}