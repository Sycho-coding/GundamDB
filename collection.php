<?php

require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/config/database.php';

$userId = $_SESSION['user_id'];

$sql = "
    SELECT
        collections.collection_id,
        gunpla_kits.kit_id,
        gunpla_kits.name,
        gunpla_kits.grade,
        gunpla_kits.series,
        gunpla_kits.release_year,
        gunpla_kits.image_path,
        collections.added_at
    FROM collections
    INNER JOIN gunpla_kits
        ON collections.kit_id = gunpla_kits.kit_id
    WHERE collections.user_id = :user_id
    ORDER BY collections.added_at DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute([
    'user_id' => $userId
]);

$collectionItems = $stmt->fetchAll();

?>

<?php
$pageTitle = 'Mijn collectie - GundamDB';

require_once __DIR__ . '/includes/header.php';
?>

<section class="page-header">

    <h1>Mijn collectie</h1>

    <p>
        Bekijk de Gunpla modelkits die je bezit.
    </p>

</section>

<?php if (empty($collectionItems)): ?>

    <div class="empty-message">

        <p>
            Je collectie is nog leeg.
        </p>

    </div>

<?php else: ?>

    <div class="kit-grid">

        <?php foreach ($collectionItems as $item): ?>

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

                    <form method="POST" action="collection_remove.php">

                        <input
                            type="hidden"
                            name="kit_id"
                            value="<?= $item['kit_id'] ?>">

                        <button type="submit">
                            Verwijder uit collectie
                        </button>

                    </form>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>