<?php

define('APP_NAME', 'Catatan Keuangan');

if (php_sapi_name() !== 'cli' && isset($_SERVER['HTTP_HOST'])) {
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    define('BASE_URL', $scheme . '://' . $_SERVER['HTTP_HOST']);
} else {
    define('BASE_URL', '');
}

define('STORAGE_DRIVER', 'json');