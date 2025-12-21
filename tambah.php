<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/config/timezone.php';
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/controllers/NoteController.php';
require_once __DIR__ . '/app/services/CurrencyService.php';

Session::start();
if (!Session::has('user_id')) {
    header('Location: ' . BASE_URL . '/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $currency = new CurrencyService();
    $_POST['amount_usd'] = $currency->usdFromIdr((float) $_POST['amount_idr']);
    (new NoteController())->store();
    header('Location: ' . BASE_URL . '/dashboard.php');
    exit;
}
?>