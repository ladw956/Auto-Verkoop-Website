<?php
require_once 'connect_db.php';
require_once 'functies.php';

$fouten = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = trim($_POST['naam'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    if ($naam === '' || $email === '' || $wachtwoord === '') {
        $fouten[] = "Alle velden zijn verplicht.";
    }

    if (empty($fouten)) {
        // check of email al bestaat
        $stmt = $pdo->prepare("SELECT id FROM gebruiker WHERE email = ?");
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $fouten[] = "Dit e-mailadres is al geregistreerd.";
        } else {
            // Wachtwoord hashen
            $wachtwoordHash = hashPassword($wachtwoord);

            $stmt = $pdo->prepare("
                INSERT INTO gebruiker (naam, email, wachtwoord_hash, is_admin, rol)
                VALUES (?, ?, ?, 0, 'User')
            ");
            $stmt->execute([
                $naam,
                $email,
                $wachtwoordHash
            ]);

            header('Location: login.php');
            exit;
        }
    }
}
?>
<?php include 'header.php'; ?>

<h1>Registreren</h1>

<?php foreach ($fouten as $f): ?>
    <p style="color:red;"><?= htmlspecialchars($f) ?></p>
<?php endforeach; ?>

<form method="post">
    <label>Naam:
        <input type="text" name="naam" required>
    </label><br>
    <label>E-mail:
        <input type="email" name="email" required>
    </label><br>
    <label>Wachtwoord:
        <input type="password" name="wachtwoord" required>
    </label><br>
    <button type="submit">Account aanmaken</button>
</form>

<p>Al een account? <a href="login.php">Inloggen</a></p>

<?php include 'footer.php'; ?>