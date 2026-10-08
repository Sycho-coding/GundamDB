<?php

require_once __DIR__ . '/auth_admin.php';
require_once __DIR__ . '/../config/database.php';

$kitId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$kitId) {
    die('Ongeldige modelkit.');
}

$sql = "
    SELECT *
    FROM gunpla_kits
    WHERE kit_id = :kit_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'kit_id' => $kitId
]);

$kit = $stmt->fetch();

if (!$kit) {
    die('Modelkit niet gevonden.');
}

$errors = [];

$allowedGrades = ['HG', 'RG', 'MG', 'PG'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = trim($_POST['name'] ?? '');
    $grade = trim($_POST['grade'] ?? '');
    $series = trim($_POST['series'] ?? '');
    $releaseYear = trim($_POST['release_year'] ?? '');
    $imagePath = trim($_POST['image_path'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if ($name === '') {
        $errors[] = 'Vul een naam in.';
    }

    if (!in_array($grade, $allowedGrades, true)) {
        $errors[] = 'Kies een geldige grade.';
    }

    if ($series === '') {
        $errors[] = 'Vul een serie in.';
    }

    if (empty($errors)) {

        $sql = "
            UPDATE gunpla_kits
            SET
                name = :name,
                grade = :grade,
                series = :series,
                release_year = :release_year,
                image_path = :image_path,
                description = :description
            WHERE kit_id = :kit_id
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'name' => $name,
            'grade' => $grade,
            'series' => $series,
            'release_year' => $releaseYear !== '' ? (int)$releaseYear : null,
            'image_path' => $imagePath !== '' ? $imagePath : null,
            'description' => $description !== '' ? $description : null,
            'kit_id' => $kitId
        ]);

        header('Location: index.php');
        exit;
    }

    $kit['name'] = $name;
    $kit['grade'] = $grade;
    $kit['series'] = $series;
    $kit['release_year'] = $releaseYear;
    $kit['image_path'] = $imagePath;
    $kit['description'] = $description;
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modelkit bewerken - GundamDB</title>
</head>

<body>

    <h1>Modelkit bewerken</h1>

    <a href="index.php">Terug</a>

    <?php if (!empty($errors)): ?>

        <ul>
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>

    <?php endif; ?>

    <form method="POST">

        <div>
            <label for="name">Naam</label>

            <input
                type="text"
                id="name"
                name="name"
                value="<?= htmlspecialchars($kit['name']) ?>"
                required
            >
        </div>

        <div>
            <label for="grade">Grade</label>

            <select id="grade" name="grade" required>

                <?php foreach ($allowedGrades as $allowedGrade): ?>

                    <option
                        value="<?= $allowedGrade ?>"
                        <?= $kit['grade'] === $allowedGrade ? 'selected' : '' ?>
                    >
                        <?= $allowedGrade ?>
                    </option>

                <?php endforeach; ?>

            </select>
        </div>

        <div>
            <label for="series">Serie</label>

            <input
                type="text"
                id="series"
                name="series"
                value="<?= htmlspecialchars($kit['series']) ?>"
                required
            >
        </div>

        <div>
            <label for="release_year">Releasejaar</label>

            <input
                type="number"
                id="release_year"
                name="release_year"
                value="<?= htmlspecialchars((string)$kit['release_year']) ?>"
            >
        </div>

        <div>
            <label for="image_path">Afbeeldingspad</label>

            <input
                type="text"
                id="image_path"
                name="image_path"
                value="<?= htmlspecialchars((string)$kit['image_path']) ?>"
            >
        </div>

        <div>
            <label for="description">Beschrijving</label>

            <textarea
                id="description"
                name="description"
            ><?= htmlspecialchars((string)$kit['description']) ?></textarea>
        </div>

        <button type="submit">
            Wijzigingen opslaan
        </button>

    </form>

</body>
</html>