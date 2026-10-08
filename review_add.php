<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];

$kitId = filter_input(INPUT_POST, 'kit_id', FILTER_VALIDATE_INT);
$rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
$reviewText = trim($_POST['review_text'] ?? '');

$errors = [];

if (!$kitId) {
    $errors[] = 'Ongeldige modelkit.';
}

if (!$rating || $rating < 1 || $rating > 5) {
    $errors[] = 'De beoordeling moet tussen 1 en 5 liggen.';
}

if ($reviewText === '') {
    $errors[] = 'Vul een review in.';
} elseif (strlen($reviewText) > 1000) {
    $errors[] = 'De review mag maximaal 1000 tekens bevatten.';
}

if (empty($errors)) {
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
        $errors[] = 'Modelkit niet gevonden.';
    }
}

if (empty($errors)) {
    $sql = "
        SELECT review_id
        FROM reviews
        WHERE user_id = :user_id
        AND kit_id = :kit_id
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'user_id' => $userId,
        'kit_id' => $kitId
    ]);

    if ($stmt->fetch()) {
        $errors[] = 'Je hebt deze modelkit al beoordeeld.';
    }
}

if (!empty($errors)) {
    $_SESSION['review_errors'] = $errors;
    $_SESSION['review_text'] = $reviewText;

    header('Location: kit.php?id=' . $kitId);
    exit;
}

$sql = "
    INSERT INTO reviews (
        user_id,
        kit_id,
        rating,
        review_text
    )
    VALUES (
        :user_id,
        :kit_id,
        :rating,
        :review_text
    )
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'user_id' => $userId,
    'kit_id' => $kitId,
    'rating' => $rating,
    'review_text' => $reviewText
]);

header('Location: kit.php?id=' . $kitId);
exit;