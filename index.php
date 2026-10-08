<?php

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