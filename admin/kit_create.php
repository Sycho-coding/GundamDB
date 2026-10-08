<?php

require_once __DIR__ . '/auth_admin.php';
require_once __DIR__ . '/../config/database.php';

$errors = [];

$name = '';
$grade = '';
$series = '';
$releaseYear = '';
$imagePath = '';
$description = '';

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

    if (
        $releaseYear !== ''
        && (
            !ctype_digit($releaseYear)
            || (int)$releaseYear < 1980
            || (int)$releaseYear > (int)date('Y') + 1
        )
    ) {
        $errors[] = 'Vul een geldig releasejaar in.';
    }

    if (empty($errors)) {

        $sql = "
            INSERT INTO gunpla_kits (
                name,
                grade,
                series,
                release_year,
                image_path,
                description
            )
            VALUES (
                :name,
                :grade,
                :series,
                :release_year,
                :image_path,
                :description
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'name' => $name,
            'grade' => $grade,
            'series' => $series,
            'release_year' => $releaseYear !== '' ? (int)$releaseYear : null,
            'image_path' => $imagePath !== '' ? $imagePath : null,
            'description' => $description !== '' ? $description : null
        ]);

        header('Location: index.php');
        exit;
    }
}

?>

<?php
$pageTitle = 'Modelkit toevoegen - GundamDB';

require_once __DIR__ . '/../includes/header.php';
?>



<h1>Modelkit toevoegen</h1>

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
            value="<?= htmlspecialchars($name) ?>"
            required>
    </div>

    <div>
        <label for="grade">Grade</label>

        <select id="grade" name="grade" required>
            <option value="">Kies grade</option>

            <?php foreach ($allowedGrades as $allowedGrade): ?>

                <option
                    value="<?= $allowedGrade ?>"
                    <?= $grade === $allowedGrade ? 'selected' : '' ?>>
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
            value="<?= htmlspecialchars($series) ?>"
            required>
    </div>

    <div>
        <label for="release_year">Releasejaar</label>
        <input
            type="number"
            id="release_year"
            name="release_year"
            value="<?= htmlspecialchars($releaseYear) ?>">
    </div>

    <div>
        <label for="image_path">Afbeeldingspad</label>
        <input
            type="text"
            id="image_path"
            name="image_path"
            value="<?= htmlspecialchars($imagePath) ?>"
            placeholder="assets/images/barbatos.jpg">
    </div>

    <div>
        <label for="description">Beschrijving</label>

        <textarea
            id="description"
            name="description"><?= htmlspecialchars($description) ?></textarea>
    </div>

    <button type="submit">
        Modelkit toevoegen
    </button>

</form>


<?php require_once __DIR__ . '/../includes/footer.php'; ?>