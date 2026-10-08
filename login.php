<?php

session_start();

require_once __DIR__ . '/config/database.php';

$errors = [];
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '') {
        $errors[] = 'Vul je e-mailadres in.';
    }

    if ($password === '') {
        $errors[] = 'Vul je wachtwoord in.';
    }

    if (empty($errors)) {
        $sql = "
            SELECT user_id, username, email, password_hash, role
            FROM users
            WHERE email = :email
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'email' => $email
        ]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password_hash'])) {
            session_regenerate_id(true);

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            header('Location: index.php');
            exit;
        } else {
            $errors[] = 'E-mailadres of wachtwoord is onjuist.';
        }
    }
}

?>

<?php
$pageTitle = 'Inloggen - GundamDB';

require_once __DIR__ . '/includes/header.php';
?>

<section class="form-section">

    <h1>Inloggen</h1>

    <?php if (isset($_GET['registered'])): ?>

        <div class="success-message">
            Account succesvol aangemaakt. Je kunt nu inloggen.
        </div>

    <?php endif; ?>

    <?php if (!empty($errors)): ?>

        <div class="error-message">

            <ul>

                <?php foreach ($errors as $error): ?>

                    <li>
                        <?= htmlspecialchars($error) ?>
                    </li>

                <?php endforeach; ?>

            </ul>

        </div>

    <?php endif; ?>

    <form method="POST">

        <div>

            <label for="email">
                E-mailadres
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="<?= htmlspecialchars($email) ?>"
                required>

        </div>

        <div>

            <label for="password">
                Wachtwoord
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required>

        </div>

        <button type="submit">
            Inloggen
        </button>

    </form>

    <p>
        Nog geen account?
        <a href="register.php">Registreren</a>
    </p>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>