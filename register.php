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
<body>
<main class="container" style="max-width:450px;margin-top:5rem;">
    <div style="background:#fff; padding:2rem; border-radius:4px; border:1px solid #ddd;">
        <h1 style="text-align:center;">Registreren</h1>
        <?php if($error): ?>
        <div class="notice err"><?= e($error) ?></div>
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
            <div style="margin-top:1rem; display:flex; gap:0.5rem;">
                <button type="submit" class="btn btn-yellow">Registreren</button>
                <a href="login.php" class="btn">Terug naar Login</a>
            </div>
        </form>
    </div>
</main>
</body>
</html>
