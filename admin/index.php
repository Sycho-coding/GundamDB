<?php

require_once __DIR__ . '/auth_admin.php';
require_once __DIR__ . '/../config/database.php';

$sql = "
    SELECT
        kit_id,
        name,
        grade,
        series,
        release_year
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
    <title>Admin - GundamDB</title>
</head>

<body>

    <h1>Admin dashboard</h1>

    <nav>
        <a href="../index.php">Website</a>
        <a href="kit_create.php">Nieuwe modelkit</a>
        <a href="../logout.php">Uitloggen</a>
    </nav>

    <h2>Modelkits</h2>

    <?php if (empty($kits)): ?>

        <p>Er zijn nog geen modelkits.</p>

    <?php else: ?>

        <?php foreach ($kits as $kit): ?>

            <article>

                <h3>
                    <?= htmlspecialchars($kit['name']) ?>
                </h3>

                <p>
                    <?= htmlspecialchars($kit['grade']) ?>
                    -
                    <?= htmlspecialchars($kit['series']) ?>
                </p>

                <a href="kit_edit.php?id=<?= $kit['kit_id'] ?>">
                    Bewerken
                </a>

                <form
                    method="POST"
                    action="kit_delete.php"
                    style="display: inline;"
                    onsubmit="return confirm('Weet je zeker dat je deze modelkit wilt verwijderen?');"
                >

                    <input
                        type="hidden"
                        name="kit_id"
                        value="<?= $kit['kit_id'] ?>"
                    >

                    <button type="submit">
                        Verwijderen
                    </button>

                </form>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

</body>
</html>