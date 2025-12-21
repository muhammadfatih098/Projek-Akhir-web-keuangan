<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/core/Session.php';

Session::start();

$allowed = ['USD','MYR','JPY','SGD','EUR'];
$currency = $_GET['currency'] ?? 'USD';

if (!in_array($currency, $allowed)) {
    $currency = 'USD';
}

$_SESSION['currency'] = $currency;

header('Location: ' . BASE_URL . '/dashboard.php');
exit;