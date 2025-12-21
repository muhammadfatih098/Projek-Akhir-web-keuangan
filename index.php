<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/config/timezone.php';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title><?= APP_NAME; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="container">
    <header class="hero">
        <h1>Catatan Keuangan</h1>
        <p>
            Aplikasi pencatatan pemasukan dan pengeluaran berbasis desktop.
            Semua data rapi, saldo otomatis, dan kurs Dollar realtime.
        </p>

        <div class="hero-actions">
            <a href="login.php" class="btn primary">Login</a>
            <a href="signup.php" class="btn secondary">Signup</a>
        </div>
    </header>
</div>
<section>
    <div class="container">

        <div class="section-title">
            <h2>Kenapa Catatan Keuangan?</h2>
            <p>Satu tempat untuk kontrol keuangan pribadi tanpa ribet</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <h3>Data Terpisah per Akun</h3>
                <p>
                    Setiap user memiliki catatan keuangan sendiri.
                    Aman, tidak tercampur dengan akun lain.
                </p>
            </div>

            <div class="feature-card">
                <h3>Perhitungan Otomatis</h3>
                <p>
                    Total saldo, pemasukan, dan pengeluaran
                    dihitung otomatis oleh sistem.
                </p>
            </div>

            <div class="feature-card">
                <h3>Kurs Dollar Realtime</h3>
                <p>
                    Nominal Rupiah langsung dikonversi ke USD
                    menggunakan kurs terbaru.
                </p>
            </div>
        </div>

    </div>
</section>
<section>
    <div class="container">

        <div class="section-title">
            <h2>Cara Kerja</h2>
            <p>Empat langkah sederhana untuk mulai mencatat keuangan</p>
        </div>

        <div class="steps">
            <div class="step">
                <div class="number">01</div>
                <h4>Buat Akun</h4>
                <p>Daftar satu kali untuk menyimpan seluruh data keuanganmu.</p>
            </div>

            <div class="step">
                <div class="number">02</div>
                <h4>Login</h4>
                <p>Masuk ke dashboard pribadi yang hanya bisa diakses olehmu.</p>
            </div>

            <div class="step">
                <div class="number">03</div>
                <h4>Catat Transaksi</h4>
                <p>Masukkan pemasukan dan pengeluaran dengan keterangan jelas.</p>
            </div>

            <div class="step">
                <div class="number">04</div>
                <h4>Pantau Saldo</h4>
                <p>Saldo dan laporan keuangan langsung terlihat realtime.</p>
            </div>
        </div>

    </div>
</section>
<section class="preview">
    <div class="preview-box">

        <div class="preview-text">
            <h2>Dashboard yang Jelas & Fokus</h2>
            <p>
                Setelah login, kamu langsung melihat total saldo,
                pemasukan, dan pengeluaran tanpa perlu buka satu-satu catatan.
            </p>

            <ul class="preview-list">
                <li>Total saldo langsung terlihat</li>
                <li>Pemisahan pemasukan dan pengeluaran</li>
                <li>Daftar catatan rapi dan bisa di-scroll</li>
                <li>Konversi Rupiah ke Dollar realtime</li>
            </ul>

            <a href="signup.php" class="btn primary">Buat Akun & Coba</a>
        </div>

        <div class="mockup-box">
            <img src="assets/images/mockup.jpeg">
        </div>

    </div>
</section>
<footer class="footer">
    <div class="footer-inner">

        <div class="footer-brand">
            <h2>Catatan Keuangan</h2>
            <p>
                Aplikasi pencatatan pemasukan dan pengeluaran
                berbasis desktop yang rapi, fokus, dan realtime.
            </p>
        </div>

        <div class="footer-links">
            <div class="footer-col">
                <h4>Produk</h4>
                <a href="#">Dashboard</a>
                <a href="#">Catatan</a>
                <a href="#">Kurs Realtime</a>
            </div>

            <div class="footer-col">
                <h4>Akun</h4>
                <a href="login.php">Login</a>
                <a href="signup.php">Signup</a>
            </div>

            <div class="footer-col">
                <h4>Informasi</h4>
                <a href="#">Tentang</a>
                <a href="#">Keamanan Data</a>
                <a href="#">Bantuan</a>
            </div>
        </div>

    </div>

    <div class="footer-bottom">
        © <?= date('Y'); ?> Catatan Keuangan. All rights reserved.
    </div>
</footer>
</body>
</html>