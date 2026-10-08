<?php

session_start();

require_once __DIR__ . '/config/database.php';

$kitId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$kitId) {
    http_response_code(400);
    die('Ongeldige modelkit.');
}

$sql = "
    SELECT
        kit_id,
        name,
        grade,
        series,
        release_year,
        image_path,
        description
    FROM gunpla_kits
    WHERE kit_id = :kit_id
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'kit_id' => $kitId
]);

$kit = $stmt->fetch();

if (!$kit) {
    http_response_code(404);
    die('Modelkit niet gevonden.');
}

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>
        <?= htmlspecialchars($kit['name']) ?> - GundamDB
    </title>
</head>

<body>

    <header>

        <h1>GundamDB</h1>

        <nav>
            <a href="index.php">Terug naar overzicht</a>

            <?php if (isset($_SESSION['user_id'])): ?>

                <span>
                    Welkom, <?= htmlspecialchars($_SESSION['username']) ?>
                </span>

                <a href="logout.php">Uitloggen</a>

            <?php else: ?>

                <a href="login.php">Inloggen</a>

            <?php endif; ?>

        </nav>

    </header>

    <main>

        <h2>
            <?= htmlspecialchars($kit['name']) ?>
        </h2>

        <?php if (!empty($kit['image_path'])): ?>

            <img
                src="<?= htmlspecialchars($kit['image_path']) ?>"
                alt="<?= htmlspecialchars($kit['name']) ?>"
                width="300"
            >

        <?php endif; ?>

        <p>
            <strong>Grade:</strong>
            <?= htmlspecialchars($kit['grade']) ?>
        </p>

        <p>
            <strong>Serie:</strong>
            <?= htmlspecialchars($kit['series']) ?>
        </p>

        <?php if (!empty($kit['release_year'])): ?>

            <p>
                <strong>Releasejaar:</strong>
                <?= htmlspecialchars($kit['release_year']) ?>
            </p>

        <?php endif; ?>

        <?php if (!empty($kit['description'])): ?>

            <h3>Beschrijving</h3>

            <p>
                <?= nl2br(htmlspecialchars($kit['description'])) ?>
            </p>

        <?php endif; ?>

        <?php if (isset($_SESSION['user_id'])): ?>

    <form method="POST" action="wishlist_add.php">

        <input
            type="hidden"
            name="kit_id"
            value="<?= $kit['kit_id'] ?>"
        >

        <button type="submit">
            Toevoegen aan wishlist
        </button>

    </form>

<?php else: ?>

    <p>
        <a href="login.php">
            Log in om deze kit aan je wishlist toe te voegen.
        </a>
    </p>

<?php endif; ?>

<?php if (isset($_SESSION['user_id'])): ?>

    <form method="POST" action="collection_add.php">

        <input
            type="hidden"
            name="kit_id"
            value="<?= $kit['kit_id'] ?>"
        >

        <button type="submit">
            Toevoegen aan collectie
        </button>

    </form>

<?php endif; ?>

    </main>

</body>
</html>