<?php
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/services/NoteServices.php';

Session::start();
if (!Session::isLoggedIn()) exit;

$id        = $_POST['id'] ?? null;
$title     = $_POST['title'] ?? null;
$amountIdr = $_POST['amount_idr'] ?? null;

if (!$id || $title === null || $amountIdr === null) exit;

$noteService = new NoteService();
$noteService->update($id, [
    'title'      => $title,
    'amount_idr'=> (float)$amountIdr
]);