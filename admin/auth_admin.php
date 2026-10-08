<?php

require_once __DIR__ . '/../includes/auth.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    http_response_code(403);
    die('Geen toegang.');
}