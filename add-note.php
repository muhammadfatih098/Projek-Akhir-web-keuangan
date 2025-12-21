<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/controllers/NoteController.php';
require_once __DIR__ . '/app/services/CurrencyService.php';

Session::start();
if (!Session::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'] ?? '';
    $type = $_POST['type'] ?? 'income';
    $amountIdr = str_replace('.', '', $_POST['amount_idr'] ?? '0');
    $amountIdr = (float)$amountIdr;

    if ($amountIdr <= 0) {
        $error = "Jumlah harus lebih dari 0";
    } else {
        $currency = new CurrencyService();
        $amountUsd = $currency->fromIdr($amountIdr, 'USD');

        $noteController = new NoteController();
        $noteController->store([
            'id' => uniqid(),
            'user_id' => Session::user()['id'],
            'title' => $title,
            'type' => $type,
            'amount_idr' => $amountIdr,
            'amount_usd' => $amountUsd,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        header('Location: dashboard.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Catatan — <?= APP_NAME ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .input-wrapper { position: relative; margin-bottom: 16px; }
        .input-wrapper span {
            position: absolute;
            left: 12px;
            top: 36%;
            transform: translateY(-50%);
            color: #111827;
            font-weight: 500;
        }
        .input-wrapper input {
            width: 100%;
            padding: 12px 12px 12px 36px;
            border-radius: 10px;
            border: 1px solid #cbd5f5;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="auth-box">
        <h2>Tambah Catatan</h2>

        <?php if($error): ?>
            <p style="color:red; margin-bottom:12px"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <select name="type" required>
                <option value="income">Pemasukan</option>
                <option value="expense">Pengeluaran</option>
            </select>

            <input name="title" placeholder="Keterangan" required>

            <div class="input-wrapper">
                <span>Rp</span>
                <input type="tel" inputmode="numeric" name="amount_idr" id="amount_idr" placeholder="Jumlah (IDR)" required>
            </div>

            <button class="btn primary">Simpan</button>
        </form>
    </div>
</div>

<script>
const amountInput = document.getElementById('amount_idr');
amountInput.addEventListener('input', function() {
    let value = this.value.replace(/\D/g,'');
    if(value) value = parseInt(value).toLocaleString('id-ID');
    this.value = value;
});
</script>

</body>
</html>