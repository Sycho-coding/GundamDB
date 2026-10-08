<?php

session_start();

require_once __DIR__ . '/config/database.php';

$sql = "SELECT * FROM gunpla_kits";
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

<?php if (isset($_SESSION['user_id'])): ?>

    <p>
        Welkom, <?= htmlspecialchars($_SESSION['username']) ?>
    </p>

    <p>
        <a href="logout.php">Uitloggen</a>
    </p>

<?php else: ?>

    <p>
        <a href="login.php">Inloggen</a>
        |
        <a href="register.php">Registreren</a>
    </p>

<?php endif; ?>

    <h1>GundamDB</h1>

    <h2>Gunpla kits</h2>

    <?php foreach ($kits as $kit): ?>

        <div>
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
        </div>

    <?php endforeach; ?>

</body>
</html>