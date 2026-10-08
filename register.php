<?php

require_once __DIR__ . '/config/database.php';

$errors = [];
$username = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // Validatie gebruikersnaam
    if ($username === '') {
        $errors[] = 'Vul een gebruikersnaam in.';
    } elseif (strlen($username) < 3) {
        $errors[] = 'De gebruikersnaam moet minimaal 3 tekens bevatten.';
    } elseif (strlen($username) > 50) {
        $errors[] = 'De gebruikersnaam mag maximaal 50 tekens bevatten.';
    }

    // Validatie e-mail
    if ($email === '') {
        $errors[] = 'Vul een e-mailadres in.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Vul een geldig e-mailadres in.';
    }

    // Validatie wachtwoord
    if ($password === '') {
        $errors[] = 'Vul een wachtwoord in.';
    } elseif (strlen($password) < 8) {
        $errors[] = 'Het wachtwoord moet minimaal 8 tekens bevatten.';
    }

    if ($password !== $passwordConfirm) {
        $errors[] = 'De wachtwoorden komen niet overeen.';
    }

    // Controleer of e-mail al bestaat
    if (empty($errors)) {
        $sql = "SELECT user_id FROM users WHERE email = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            'email' => $email
        ]);

        if ($stmt->fetch()) {
            $errors[] = 'Er bestaat al een account met dit e-mailadres.';
        }
    }

    // Gebruiker opslaan
    if (empty($errors)) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "
            INSERT INTO users (
                username,
                email,
                password_hash,
                role
            )
            VALUES (
                :username,
                :email,
                :password_hash,
                'user'
            )
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'username' => $username,
            'email' => $email,
            'password_hash' => $passwordHash
        ]);

        header('Location: login.php?registered=1');
        exit;
    }
}

?>

<?php
$pageTitle = 'Registreren - GundamDB';

require_once __DIR__ . '/includes/header.php';
?>

<section class="form-section">

    <h1>Account aanmaken</h1>

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

            <label for="username">
                Gebruikersnaam
            </label>

            <input
                type="text"
                id="username"
                name="username"
                value="<?= htmlspecialchars($username) ?>"
                required>

        </div>

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

        <div>

            <label for="password_confirm">
                Herhaal wachtwoord
            </label>

            <input
                type="password"
                id="password_confirm"
                name="password_confirm"
                required>

        </div>

        <button type="submit">
            Registreren
        </button>

    </form>

    <p>
        Al een account?
        <a href="login.php">Inloggen</a>
    </p>

</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>