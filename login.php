<?php
session_start();

require_once 'connect_db.php';
require_once 'functies.php';

$fouten = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $wachtwoord = $_POST['wachtwoord'] ?? '';

    $stmt = $pdo->prepare("
        SELECT id, naam, wachtwoord_hash, is_admin, is_mainAdmin
        FROM gebruiker
        WHERE email = ?
    ");
    $stmt->execute([$email]);
    $gebruiker = $stmt->fetch(PDO::FETCH_ASSOC);

    if (
        !$gebruiker ||
        !verifyPassword($wachtwoord, $gebruiker['wachtwoord_hash'])
    ) {
        $fouten[] = "Onjuiste inloggegevens.";
    } else {
        $_SESSION['gebruiker_id'] = $gebruiker['id'];
        $_SESSION['gebruiker_naam'] = $gebruiker['naam'];
        $_SESSION['is_admin'] = $gebruiker['is_admin'];
        $_SESSION['is_mainAdmin'] = $gebruiker['is_mainAdmin'];

        header('Location: index.php');
        exit();
    }
}
?>

<?php include 'header.php'; ?>

<h1>Inloggen</h1>

<?php foreach ($fouten as $f): ?>
    <p style="color: red;"><?= htmlspecialchars($f) ?></p>
<?php endforeach; ?>

<form method="post">
    <label>E-mail:
        <input type="email" name="email" required>
    </label><br>
    <label>Wachtwoord:
        <input type="password" name="wachtwoord" required>
    </label><br>
    <button type="submit">Inloggen</button>
</form>

<p>Nog geen account? <a href="registreer.php">Registreer</a></p>
<?php include 'footer.php'; ?>