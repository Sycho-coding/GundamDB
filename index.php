<?php

session_start();

require_once __DIR__ . '/config/database.php';

$search = trim($_GET['search'] ?? '');
$grade = trim($_GET['grade'] ?? '');

$allowedGrades = ['HG', 'RG', 'MG', 'PG'];

if ($grade !== '' && !in_array($grade, $allowedGrades, true)) {
    $grade = '';
}

$sql = "
    SELECT
        kit_id,
        name,
        grade,
        series,
        release_year,
        image_path
    FROM gunpla_kits
    WHERE 1 = 1
";

$params = [];

if ($search !== '') {
    $sql .= "
        AND (
            name LIKE :search_name
            OR series LIKE :search_series
        )
    ";

    $params['search_name'] = '%' . $search . '%';
    $params['search_series'] = '%' . $search . '%';
}

if ($grade !== '') {
    $sql .= "
        AND grade = :grade
    ";

    $params['grade'] = $grade;
}

$sql .= " ORDER BY name ASC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);

$kits = $stmt->fetchAll();

$pageTitle = 'GundamDB';

require_once __DIR__ . '/includes/header.php';

?>

<section class="page-header">

    <h1>Gunpla modelkits</h1>

    <p>
        Bekijk verschillende Gunpla modelkits, zoek op naam of serie
        en filter op grade.
    </p>

</section>

<section class="search-section">

    <form
        class="search-form"
        method="GET"
        action="index.php">

        <div class="search-field">

            <label for="search">
                Zoeken
            </label>

            <input
                type="text"
                id="search"
                name="search"
                placeholder="Zoek op naam of serie"
                value="<?= htmlspecialchars($search) ?>">

        </div>

        <div class="search-field">

            <label for="grade">
                Grade
            </label>

            <select
                id="grade"
                name="grade">

                <option value="">
                    Alle grades
                </option>

                <option
                    value="HG"
                    <?= $grade === 'HG' ? 'selected' : '' ?>>
                    HG
                </option>

                <option
                    value="RG"
                    <?= $grade === 'RG' ? 'selected' : '' ?>>
                    RG
                </option>

                <option
                    value="MG"
                    <?= $grade === 'MG' ? 'selected' : '' ?>>
                    MG
                </option>

                <option
                    value="PG"
                    <?= $grade === 'PG' ? 'selected' : '' ?>>
                    PG
                </option>

            </select>

        </div>

        <div class="search-actions">

            <button type="submit">
                Zoeken
            </button>

            <a
                class="reset-link"
                href="index.php">
                Reset
            </a>

        </div>

    </form>

</section>

<section class="kits-section">

    <?php if (empty($kits)): ?>

        <div class="empty-message">

            <p>
                Geen modelkits gevonden.
            </p>

        </div>

    <?php else: ?>

        <div class="kit-grid">

            <?php foreach ($kits as $kit): ?>

                <article class="kit-card">

                    <?php if (!empty($kit['image_path'])): ?>

                        <div class="kit-card-image">

                            <img
                                src="<?= htmlspecialchars($kit['image_path']) ?>"
                                alt="<?= htmlspecialchars($kit['name']) ?>">

                        </div>

                    <?php endif; ?>

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

                        <?php if (!empty($kit['release_year'])): ?>

                            <p>
                                <strong>Releasejaar:</strong>
                                <?= htmlspecialchars($kit['release_year']) ?>
                            </p>

                        <?php endif; ?>

                        <a
                            class="detail-link"
                            href="kit.php?id=<?= $kit['kit_id'] ?>">
                            Bekijk details
                        </a>

                    </div>

                </article>

            <?php endforeach; ?>

        </div>

    <?php endif; ?>

</section>

<?php

require_once __DIR__ . '/includes/footer.php';

?>