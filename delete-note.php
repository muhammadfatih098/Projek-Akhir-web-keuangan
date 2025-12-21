<?php
require_once __DIR__ . '/app/core/Session.php';
require_once __DIR__ . '/app/services/NoteServices.php';

Session::start();
if (!Session::isLoggedIn()) exit;

$id = $_POST['id'] ?? null;
if (!$id) exit;

$noteService = new NoteService();
$noteService->delete($id);