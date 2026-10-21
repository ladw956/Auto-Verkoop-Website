<?php
require_once 'connect_db.php';
require_once 'functies.php';

vereistAdmin();

$stmt = $pdo->query("SELECT * FROM auto ORDER BY created_at DESC");
$autos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include 'header.php'; ?>

<h1>Admin – Auto beheer</h1>

<?php foreach ($autos as $auto): ?>
    <div style="border:1px solid #ccc; margin:10px; padding:10px;">
        <h2><?= htmlspecialchars($auto['brand'] . ' ' . $auto['model']) ?></h2>
        <p>Bouwjaar: <?= (int)$auto['year'] ?></p>
        <p>Prijs: € <?= (int)$auto['prijs'] ?></p>
        <p>Geplaatst door: <?= htmlspecialchars($auto['naam']) ?> op <?= $auto['created_at'] ?></p>
        <a href="auto-bewerk.php?id=<?= $auto['id'] ?>">Bewerken</a> |
        <a href="verwijder-auto.php?id=<?= $auto['id'] ?>" onclick="return confirm('Weet je het zeker?');">Verwijderen</a>
    </div>
<?php endforeach; ?>

<?php include 'footer.php'; ?>