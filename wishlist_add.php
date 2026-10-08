<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$kitId = filter_input(INPUT_POST, 'kit_id', FILTER_VALIDATE_INT);

if (!$kitId) {
    http_response_code(400);
    die('Ongeldige modelkit.');
}

$userId = $_SESSION['user_id'];

$sql = "
    SELECT kit_id
    FROM gunpla_kits
    WHERE kit_id = :kit_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'kit_id' => $kitId
]);

if (!$stmt->fetch()) {
    http_response_code(404);
    die('Modelkit niet gevonden.');
}

$sql = "
    INSERT INTO wishlist (
        user_id,
        kit_id
    )
    VALUES (
        :user_id,
        :kit_id
    )
";

$stmt = $pdo->prepare($sql);

try {
    $stmt->execute([
        'user_id' => $userId,
        'kit_id' => $kitId
    ]);
} catch (PDOException $e) {
    if ($e->getCode() !== '23000') {
        throw $e;
    }
}

header('Location: wishlist.php');
exit;