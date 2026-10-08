<?php

session_start();

require_once __DIR__ . '/config/database.php';

$search = trim($_GET['search'] ?? '');
$grade = trim($_GET['grade'] ?? '');

$allowedGrades = ['HG', 'RG', 'MG', 'PG'];

if ($grade !== '' && !in_array($grade, $allowedGrades, true)) {
    $grade = '';
}

$sql = "
    SELECT
        kit_id,
        name,
        grade,
        series,
        release_year,
        image_path
    FROM gunpla_kits
    WHERE 1 = 1
";

$params = [];

if ($search !== '') {
    $sql .= "
        AND (
            name LIKE :search_name
            OR series LIKE :search_series
        )
    ";

    $params['search_name'] = '%' . $search . '%';
    $params['search_series'] = '%' . $search . '%';
}

if ($grade !== '') {
    $sql .= "
        AND grade = :grade
    ";

    $params['grade'] = $grade;
}

$sql .= " ORDER BY name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$kits = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GundamDB</title>
</head>

<body>

    <header>
        <h1>GundamDB</h1>

        <nav>
            <a href="index.php">Home</a>
<?php if (isset($_SESSION['user_id'])): ?>

    <span>
        Welkom, <?= htmlspecialchars($_SESSION['username']) ?>
    </span>

    <a href="wishlist.php">Mijn wishlist</a>
    <a href="logout.php">Uitloggen</a>

<?php else: ?>

    <a href="login.php">Inloggen</a>
    <a href="register.php">Registreren</a>

<?php endif; ?>
        </nav>
    </header>

    <main>

        <h2>Gunpla modelkits</h2>

        <form method="GET" action="index.php">

    <div>
        <label for="search">Zoeken</label>

        <input
            type="text"
            id="search"
            name="search"
            placeholder="Zoek op naam of serie"
            value="<?= htmlspecialchars($search) ?>"
        >
    </div>

    <div>
        <label for="grade">Grade</label>

        <select id="grade" name="grade">

            <option value="">Alle grades</option>

            <option
                value="HG"
                <?= $grade === 'HG' ? 'selected' : '' ?>
            >
                HG
            </option>

            <option
                value="RG"
                <?= $grade === 'RG' ? 'selected' : '' ?>
            >
                RG
            </option>

            <option
                value="MG"
                <?= $grade === 'MG' ? 'selected' : '' ?>
            >
                MG
            </option>

            <option
                value="PG"
                <?= $grade === 'PG' ? 'selected' : '' ?>
            >
                PG
            </option>

        </select>
    </div>

    <button type="submit">
        Zoeken
    </button>

    <a href="index.php">
        Reset
    </a>

</form>

        <?php if (empty($kits)): ?>

            <p>Geen modelkits gevonden.</p>

        <?php else: ?>

            <?php foreach ($kits as $kit): ?>

                <article>

                    <?php if (!empty($kit['image_path'])): ?>

                        <img
                            src="<?= htmlspecialchars($kit['image_path']) ?>"
                            alt="<?= htmlspecialchars($kit['name']) ?>"
                            width="200"
                        >

                    <?php endif; ?>

                    <h3>
                        <?= htmlspecialchars($kit['name']) ?>
                    </h3>

                    <p>
                        Grade:
                        <?= htmlspecialchars($kit['grade']) ?>
                    </p>

                    <p>
                        Serie:
                        <?= htmlspecialchars($kit['series']) ?>
                    </p>

                    <?php if (!empty($kit['release_year'])): ?>

                        <p>
                            Uitgebracht:
                            <?= htmlspecialchars($kit['release_year']) ?>
                        </p>

                    <?php endif; ?>

                    <a href="kit.php?id=<?= $kit['kit_id'] ?>">
                        Bekijk details
                    </a>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>

</body>
</html>