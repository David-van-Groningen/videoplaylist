<?php
require 'config.php';

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($username) || empty($password)) {
        $err = "Vul alle velden in";
    } else {
        $stmt = $pdo->prepare("SELECT id, password_hash FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            redirect('index.php');
        } else {
            $err = "Onjuiste inloggegevens";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login - Video Platform</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<main class="container" style="max-width:450px;margin-top:5rem;">
    <div style="background:#fff; padding:2rem; border-radius:4px; border:1px solid #ddd;">
        <h1 style="text-align:center;">Inloggen</h1>
        <?php if($err): ?>
        <div class="notice err"><?= e($err) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>
                Gebruikersnaam
                <input type="text" name="username" required autofocus>
            </label>
            <label>
                Wachtwoord
                <input type="password" name="password" required>
            </label>
            <div style="margin-top:1rem; display:flex; gap:0.5rem;">
                <button type="submit" class="btn btn-yellow">Login</button>
                <a href="register.php" class="btn">Registreren</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
