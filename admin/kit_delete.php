<?php

require_once __DIR__ . '/auth_admin.php';
require_once __DIR__ . '/../config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$kitId = filter_input(INPUT_POST, 'kit_id', FILTER_VALIDATE_INT);

if (!$kitId) {
    die('Ongeldige modelkit.');
}

$sql = "
    DELETE FROM gunpla_kits
    WHERE kit_id = :kit_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'kit_id' => $kitId
]);

header('Location: index.php');
exit;