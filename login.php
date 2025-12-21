<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

Session::start();

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new AuthController();
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if ($controller->login($email, $password)) {
    header('Location: ' . BASE_URL . '/dashboard.php');
    exit;
    } else {
        $error = "Email atau password salah";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="container">
    <div class="auth-box">
        <h2>Login</h2>
        <p>Masuk ke dashboard keuanganmu</p>

        <?php if ($error): ?>
            <p style="color:red; margin-bottom:12px"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn primary">Login</button>
        </form>

        <p class="auth-link">
            Belum punya akun? <a href="signup.php">Signup</a>
        </p>
    </div>
</div>

</body>
</html>