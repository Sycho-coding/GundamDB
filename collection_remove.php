<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: collection.php');
    exit;
}

$kitId = filter_input(INPUT_POST, 'kit_id', FILTER_VALIDATE_INT);

if (!$kitId) {
    http_response_code(400);
    die('Ongeldige modelkit.');
}

$userId = $_SESSION['user_id'];

$sql = "
    DELETE FROM collections
    WHERE user_id = :user_id
    AND kit_id = :kit_id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'user_id' => $userId,
    'kit_id' => $kitId
]);

header('Location: collection.php');
exit;