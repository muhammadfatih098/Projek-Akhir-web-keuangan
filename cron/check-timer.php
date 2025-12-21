<?php
$TOKEN = 'fikan_9cA7E2dF4b8KQmR3H6ZxP1W0N5TJsY';

if (!isset($_GET['token']) || $_GET['token'] !== $TOKEN) {
    http_response_code(403);
    exit('Forbidden');
}

file_put_contents(
    __DIR__ . '/run.log',
    date('Y-m-d H:i:s') . " CRON START\n",
    FILE_APPEND
);

require_once __DIR__ . '/../vendor/phpmailer/PHPMailer.php';
require_once __DIR__ . '/../vendor/phpmailer/SMTP.php';
require_once __DIR__ . '/../vendor/phpmailer/Exception.php';

require_once __DIR__ . '/../app/services/TimerService.php';

$mailConfig = require __DIR__ . '/../app/config/mail.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$timerService = new TimerService();
$timers = $timerService->dueTimers();

foreach ($timers as $timer) {

    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = $mailConfig['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailConfig['username'];
        $mail->Password   = $mailConfig['password'];
        $mail->SMTPSecure = $mailConfig['encryption'];
        $mail->Port       = $mailConfig['port'];
        
        $mail->setFrom($mailConfig['from_email'], $mailConfig['from_name']);
        $mail->addAddress($timer['email']);
        
        $mail->isHTML(true);
        $mail->Subject = '⏰ Pengingat Pembayaran';

        $mail->Body = "
            <h2>⏰ Pengingat Pembayaran</h2>

            <p>Halo,</p>

            <p>
                Ini adalah pengingat bahwa Anda memiliki kewajiban yang perlu segera dilakukan:
            </p>

            <blockquote style='padding:10px;border-left:4px solid #ccc;'>
                <strong>{$timer['note']}</strong>
            </blockquote>

            <p>
                Waktu pengingat:<br>
                <strong>{$timer['datetime']}</strong>
            </p>

            <p>
                Mohon segera ditindaklanjuti agar tidak terlambat.
            </p>

            <hr>
            <small>
                Email ini dikirim otomatis oleh sistem Reminder App.<br>
                Mohon tidak membalas email ini.
            </small>
        ";
        
        $mail->send();
        $timerService->markSent($timer['id']);

        file_put_contents(
            __DIR__ . '/run.log',
            date('Y-m-d H:i:s') . " SENT: {$timer['email']}\n",
            FILE_APPEND
        );

    } catch (Exception $e) {
        file_put_contents(
            __DIR__ . '/run.log',
            date('Y-m-d H:i:s') . " ERROR: {$mail->ErrorInfo}\n",
            FILE_APPEND
        );
    }
}

file_put_contents(
    __DIR__ . '/run.log',
    date('Y-m-d H:i:s') . " CRON END\n",
    FILE_APPEND
);