<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/core/Session.php';

Session::start();

?>
<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Timer Pembayaran — <?= APP_NAME ?></title>

<link rel="stylesheet" href="assets/css/dashboard.css">
<link rel="stylesheet" href="assets/css/timer.css">
</head>
<body>

<header class="dash-top">
    <div class="dash-brand"><?= APP_NAME ?></div>
    <a href="<?= BASE_URL ?>/dashboard.php" class="btn secondary">← Dashboard</a>
</header>

<main class="timer-container">
    <h2>⏱ Tambah Timer Pembayaran</h2>

    <form class="timer-form" method="post" action="add-timer.php">
        <label>Keterangan Pembayaran</label>
        <textarea
            name="note"
            placeholder="Contoh: Bayar listrik bulan ini"
            required
        ></textarea>

        <label>Tanggal & Waktu</label>
        <input
            type="datetime-local"
            name="datetime"
            required
        >

        <div class="timer-actions">
            <button type="submit" class="btn-primary">
                Simpan Timer
            </button>
            <a href="<?= BASE_URL ?>/dashboard.php" class="btn-secondary">
                Batal
            </a>
        </div>
    </form>
</main>

</body>
</html>