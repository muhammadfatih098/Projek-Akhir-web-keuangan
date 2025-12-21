<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/config/timezone.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    (new AuthController())->signup();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Signup — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/css/auth.css">
</head>
<body>

<div class="container">
    <div class="auth-box">
        <h2>Buat Akun</h2>
        <p>Mulai catat keuanganmu dengan rapi</p>

        <form method="post">
            <input name="name" placeholder="Nama" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn primary">Signup</button>
        </form>

        <p class="auth-link">
            Sudah punya akun? <a href="login.php">Login</a>
        </p>
    </div>
</div>

</body>
</html>