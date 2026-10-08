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

<?php
$pageTitle = 'Mijn wishlist - GundamDB';

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-header">

    <h1>Mijn wishlist</h1>

    <p>
        Gunpla modelkits die je nog wilt toevoegen aan je collectie.
    </p>

</section>

<?php if (empty($wishlistItems)): ?>

    <div class="empty-message">

        <p>
            Je wishlist is nog leeg.
        </p>

    </div>

<?php else: ?>

    <div class="kit-grid">

        <?php foreach ($wishlistItems as $item): ?>

            <article class="kit-card">

                <?php if (!empty($item['image_path'])): ?>

                    <div class="kit-card-image">

                        <img
                            src="<?= htmlspecialchars($item['image_path']) ?>"
                            alt="<?= htmlspecialchars($item['name']) ?>">

                    </div>

                <?php endif; ?>

                <div class="kit-card-content">

                    <h2>
                        <?= htmlspecialchars($item['name']) ?>
                    </h2>

                    <p>
                        <strong>Grade:</strong>
                        <?= htmlspecialchars($item['grade']) ?>
                    </p>

                    <p>
                        <strong>Serie:</strong>
                        <?= htmlspecialchars($item['series']) ?>
                    </p>

                    <a href="kit.php?id=<?= $item['kit_id'] ?>">
                        Bekijk details
                    </a>

                    <form method="POST" action="wishlist_remove.php">

                        <input
                            type="hidden"
                            name="kit_id"
                            value="<?= $item['kit_id'] ?>">

                        <button type="submit">
                            Verwijder uit wishlist
                        </button>

                    </form>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>