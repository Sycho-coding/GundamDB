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

<?php
$pageTitle = 'Admin - GundamDB';

require_once __DIR__ . '/../includes/header.php';
?>

<section class="page-header">

    <h1>Admin dashboard</h1>

    <p>
        Beheer de Gunpla modelkits in GundamDB.
    </p>

</section>

<p>
    <a href="kit_create.php">
        Nieuwe modelkit toevoegen
    </a>
</p>

<?php if (empty($kits)): ?>

    <p>
        Er zijn nog geen modelkits.
    </p>

<?php else: ?>

    <div class="kit-grid">

        <?php foreach ($kits as $kit): ?>

            <article class="kit-card">

                <div class="kit-card-content">

                    <h2>
                        <?= htmlspecialchars($kit['name']) ?>
                    </h2>

                    <p>
                        <strong>Grade:</strong>
                        <?= htmlspecialchars($kit['grade']) ?>
                    </p>

                    <p>
                        <strong>Serie:</strong>
                        <?= htmlspecialchars($kit['series']) ?>
                    </p>

                    <a href="kit_edit.php?id=<?= $kit['kit_id'] ?>">
                        Bewerken
                    </a>

                    <form
                        method="POST"
                        action="kit_delete.php"
                        onsubmit="return confirm('Weet je zeker dat je deze modelkit wilt verwijderen?');">

                        <input
                            type="hidden"
                            name="kit_id"
                            value="<?= $kit['kit_id'] ?>">

                        <button type="submit">
                            Verwijderen
                        </button>

                    </form>

                </div>

            </article>

        <?php endforeach; ?>

    </div>

<?php endif; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>