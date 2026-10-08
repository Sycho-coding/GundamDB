<?php

session_start();

require_once __DIR__ . '/config/database.php';

$sql = "
    SELECT
        kit_id,
        name,
        grade,
        series,
        release_year,
        image_path
    FROM gunpla_kits
    ORDER BY name ASC
";

$stmt = $pdo->query($sql);
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

                <a href="logout.php">Uitloggen</a>

            <?php else: ?>

                <a href="login.php">Inloggen</a>
                <a href="register.php">Registreren</a>

            <?php endif; ?>
        </nav>
    </header>

    <main>

        <h2>Gunpla modelkits</h2>

        <?php if (empty($kits)): ?>

            <p>Er zijn nog geen modelkits toegevoegd.</p>

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