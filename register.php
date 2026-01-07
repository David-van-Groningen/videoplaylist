<?php
require 'config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $password_confirm = $_POST['password_confirm'] ?? '';

    if (empty($username) || empty($email) || empty($password)) {
        $error = "Vul alle velden in";
    } elseif ($password !== $password_confirm) {
        $error = "Wachtwoorden komen niet overeen";
    } elseif (strlen($password) < 6) {
        $error = "Wachtwoord moet minimaal 6 tekens zijn";
    } else {
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $error = "Gebruikersnaam of email bestaat al";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash, display_name, is_admin) VALUES (?,?,?,?,0)");
            $stmt->execute([$username, $email, $hash, $username]);
            redirect('login.php');
        }
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registreren - Video Platform</title>
<link rel="stylesheet" href="assets/style.css">
</head>
<body class="bg">
<main class="container" style="max-width:450px;margin-top:5rem;">
    <div class="glass-card">
        <h1 style="text-align:center;">📝 Registreren</h1>
        <?php if($error): ?>
        <div class="notice err"><?= sanitize_output($error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>
                Gebruikersnaam
                <input type="text" name="username" required autofocus>
            </label>
            <label>
                Email
                <input type="email" name="email" required>
            </label>
            <label>
                Wachtwoord
                <input type="password" name="password" minlength="6" required>
            </label>
            <label>
                Bevestig Wachtwoord
                <input type="password" name="password_confirm" minlength="6" required>
            </label>
            <div class="actions">
                <button type="submit" class="btn btn-purple">Registreren</button>
                <a href="login.php" class="btn ghost">Terug naar Login</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>