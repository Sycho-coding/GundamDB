<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

$userId = $_SESSION['user_id'];

$sql = "
    SELECT
        wishlist.wishlist_id,
        gunpla_kits.kit_id,
        gunpla_kits.name,
        gunpla_kits.grade,
        gunpla_kits.series,
        gunpla_kits.release_year,
        gunpla_kits.image_path,
        wishlist.added_at
    FROM wishlist
    INNER JOIN gunpla_kits
        ON wishlist.kit_id = gunpla_kits.kit_id
    WHERE wishlist.user_id = :user_id
    ORDER BY wishlist.added_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'user_id' => $userId
]);

$wishlistItems = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mijn wishlist - GundamDB</title>
</head>

<body>

    <header>
        <h1>Mijn wishlist</h1>

        <nav>
            <a href="index.php">Home</a>
            <a href="wishlist.php">Wishlist</a>
            <a href="logout.php">Uitloggen</a>
        </nav>
    </header>

    <main>

        <?php if (empty($wishlistItems)): ?>

            <p>Je wishlist is nog leeg.</p>

        <?php else: ?>

            <?php foreach ($wishlistItems as $item): ?>

                <article>

                    <?php if (!empty($item['image_path'])): ?>

                        <img
                            src="<?= htmlspecialchars($item['image_path']) ?>"
                            alt="<?= htmlspecialchars($item['name']) ?>"
                            width="200"
                        >

                    <?php endif; ?>

                    <h2>
                        <?= htmlspecialchars($item['name']) ?>
                    </h2>

                    <p>
                        Grade:
                        <?= htmlspecialchars($item['grade']) ?>
                    </p>

                    <p>
                        Serie:
                        <?= htmlspecialchars($item['series']) ?>
                    </p>

                    <a href="kit.php?id=<?= $item['kit_id'] ?>">
                        Bekijk details
                    </a>

                    <form
                        method="POST"
                        action="wishlist_remove.php"
                    >
                        <input
                            type="hidden"
                            name="kit_id"
                            value="<?= $item['kit_id'] ?>"
                        >

                        <button type="submit">
                            Verwijder uit wishlist
                        </button>
                    </form>

                </article>

            <?php endforeach; ?>

        <?php endif; ?>

    </main>

</body>
</html>