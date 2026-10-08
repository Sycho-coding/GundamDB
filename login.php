<?php

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inloggen - GundamDB</title>
</head>

<body>

    <h1>Inloggen</h1>

    <?php if (isset($_GET['registered'])): ?>
        <p>Account succesvol aangemaakt. Je kunt nu inloggen.</p>
    <?php endif; ?>

</body>
</html>