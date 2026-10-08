<?php

session_start();

$reviewErrors = $_SESSION['review_errors'] ?? [];
$oldReviewText = $_SESSION['review_text'] ?? '';

unset($_SESSION['review_errors']);
unset($_SESSION['review_text']);

require_once __DIR__ . '/config/database.php';

$kitId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$kitId) {
    http_response_code(400);
    die('Ongeldige modelkit.');
}

$sql = "
    SELECT
        kit_id,
        name,
        grade,
        series,
        release_year,
        image_path,
        description
    FROM gunpla_kits
    WHERE kit_id = :kit_id
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'kit_id' => $kitId
]);

$kit = $stmt->fetch();

if (!$kit) {
    http_response_code(404);
    die('Modelkit niet gevonden.');
}

$sql = "
    SELECT
        reviews.review_id,
        reviews.rating,
        reviews.review_text,
        reviews.created_at,
        reviews.user_id,
        users.username
    FROM reviews
    INNER JOIN users
        ON reviews.user_id = users.user_id
    WHERE reviews.kit_id = :kit_id
    ORDER BY reviews.created_at DESC
";

$stmt = $pdo->prepare($sql);

$stmt->execute([
    'kit_id' => $kitId
]);

$reviews = $stmt->fetchAll();

$pageTitle = $kit['name'] . ' - GundamDB';

require_once __DIR__ . '/includes/header.php';

?>

<section class="kit-detail">

    <div class="kit-detail-content">

        <?php if (!empty($kit['image_path'])): ?>

            <div class="kit-image">

                <img
                    src="<?= htmlspecialchars($kit['image_path']) ?>"
                    alt="<?= htmlspecialchars($kit['name']) ?>">

            </div>

        <?php endif; ?>

        <div class="kit-info">

            <h1>
                <?= htmlspecialchars($kit['name']) ?>
            </h1>

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

            <?php if (!empty($kit['description'])): ?>

                <div class="kit-description">

                    <h2>Beschrijving</h2>

                    <p>
                        <?= nl2br(htmlspecialchars($kit['description'])) ?>
                    </p>

                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<?php if (isset($_SESSION['user_id'])): ?>

    <section class="kit-actions">

        <h2>Mijn lijsten</h2>

        <div class="action-buttons">

            <form method="POST" action="wishlist_add.php">

                <input
                    type="hidden"
                    name="kit_id"
                    value="<?= $kit['kit_id'] ?>">

                <button type="submit">
                    Toevoegen aan wishlist
                </button>

            </form>

            <form method="POST" action="collection_add.php">

                <input
                    type="hidden"
                    name="kit_id"
                    value="<?= $kit['kit_id'] ?>">

                <button type="submit">
                    Toevoegen aan collectie
                </button>

            </form>

        </div>

    </section>

<?php else: ?>

    <section class="kit-actions">

        <p>
            <a href="login.php">
                Log in om deze kit aan je collectie of wishlist toe te voegen.
            </a>
        </p>

    </section>

<?php endif; ?>

<section class="review-section">

    <h2>Review plaatsen</h2>

    <?php if (isset($_SESSION['user_id'])): ?>

        <?php if (!empty($reviewErrors)): ?>

            <div class="error-message">

                <ul>

                    <?php foreach ($reviewErrors as $error): ?>

                        <li>
                            <?= htmlspecialchars($error) ?>
                        </li>

                    <?php endforeach; ?>

                </ul>

            </div>

        <?php endif; ?>

        <form
            class="review-form"
            method="POST"
            action="review_add.php">

            <input
                type="hidden"
                name="kit_id"
                value="<?= $kit['kit_id'] ?>">

            <div>

                <label for="rating">
                    Beoordeling
                </label>

                <select
                    id="rating"
                    name="rating"
                    required>

                    <option value="">
                        Kies een beoordeling
                    </option>

                    <option value="1">1 ster</option>
                    <option value="2">2 sterren</option>
                    <option value="3">3 sterren</option>
                    <option value="4">4 sterren</option>
                    <option value="5">5 sterren</option>

                </select>

            </div>

            <div>

                <label for="review_text">
                    Review
                </label>

                <textarea
                    id="review_text"
                    name="review_text"
                    maxlength="1000"
                    required><?= htmlspecialchars($oldReviewText) ?></textarea>

            </div>

            <button type="submit">
                Review plaatsen
            </button>

        </form>

    <?php else: ?>

        <p>
            <a href="login.php">
                Log in om een review te plaatsen.
            </a>
        </p>

    <?php endif; ?>

</section>

<section class="reviews">

    <h2>Reviews</h2>

    <?php if (empty($reviews)): ?>

        <p>
            Er zijn nog geen reviews geplaatst.
        </p>

    <?php else: ?>

        <?php foreach ($reviews as $review): ?>

            <article class="review">

                <div class="review-header">

                    <strong>
                        <?= htmlspecialchars($review['username']) ?>
                    </strong>

                    <span class="review-rating">
                        <?= htmlspecialchars($review['rating']) ?>/5
                    </span>

                </div>

                <p>
                    <?= nl2br(htmlspecialchars($review['review_text'])) ?>
                </p>

                <small>
                    <?= htmlspecialchars($review['created_at']) ?>
                </small>

            </article>

        <?php endforeach; ?>

    <?php endif; ?>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>