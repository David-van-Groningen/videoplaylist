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
<body class="bg">
<main class="container" style="max-width:450px;margin-top:5rem;">
    <div class="glass-card">
        <h1 style="text-align:center;">🔐 Inloggen</h1>
        <?php if($err): ?>
        <div class="notice err"><?= sanitize_output($err) ?></div>
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
            <div class="actions">
                <button type="submit" class="btn btn-purple">Login</button>
                <a href="register.php" class="btn ghost">Registreren</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>