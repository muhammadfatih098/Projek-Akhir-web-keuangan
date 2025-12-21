<?php
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/services/TimerService.php';

Session::start();

if (!Session::isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$email    = Session::user()['email'];
$note     = $_POST['note'] ?? '';
$datetime = $_POST['datetime'] ?? '';

if (!$note || !$datetime) {
    die('Data tidak lengkap');
}

$timerService = new TimerService();
$timerService->add($email, $note, $datetime);

header('Location: dashboard.php');
exit;