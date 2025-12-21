<?php
require_once __DIR__ . '/app/config/app.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

(new AuthController())->logout();