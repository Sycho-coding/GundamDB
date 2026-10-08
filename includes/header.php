<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$pageTitle = $pageTitle ?? 'GundamDB';

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?= htmlspecialchars($pageTitle) ?></title>

    <link
        rel="stylesheet"
        href="/GundamDB/assets/css/style.css">
</head>

<body>

    <header class="site-header">

        <div class="container header-content">

            <a class="logo" href="/GundamDB/index.php">
                GundamDB
            </a>

            <nav class="main-nav">

                <a href="/GundamDB/index.php">
                    Home
                </a>

                <?php if (isset($_SESSION['user_id'])): ?>

                    <a href="/GundamDB/collection.php">
                        Collectie
                    </a>

                    <a href="/GundamDB/wishlist.php">
                        Wishlist
                    </a>

                    <?php if (($_SESSION['role'] ?? '') === 'admin'): ?>

                        <a href="/GundamDB/admin/index.php">
                            Admin
                        </a>

                    <?php endif; ?>

                    <span class="username">
                        <?= htmlspecialchars($_SESSION['username']) ?>
                    </span>

                    <a href="/GundamDB/logout.php">
                        Uitloggen
                    </a>

                <?php else: ?>

                    <a href="/GundamDB/login.php">
                        Inloggen
                    </a>

                    <a href="/GundamDB/register.php">
                        Registreren
                    </a>

                <?php endif; ?>

            </nav>

        </div>

    </header>

    <main class="container"></main>