<?php
session_start();

require_once 'connect_db.php';
require_once 'functies.php';

vereistLogin();

$autoId = (int)($_POST['auto_id'] ?? 0);
if ($autoId <= 0) {
    header('Location: auto-zien.php');
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM auto WHERE id = ?");
$stmt->execute([$autoId]);
$auto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auto) {
    header('Location: auto-zien.php');
    exit;
}

$currentUserId = huidigeGebruikerId();
$currentUserName = huidigeGebruikerNaam();

if ($auto['gebruiker_id'] == $currentUserId) {
    header('Location: auto-zien.php');
    exit;
}

$verkoperId = (int)$auto['gebruiker_id'];
$prijs = (int)$auto['prijs'];

$stmtSaldo = $pdo->prepare("SELECT saldo FROM gebruiker WHERE id = ?");
$stmtSaldo->execute([$currentUserId]);
$gebruiker = $stmtSaldo->fetch(PDO::FETCH_ASSOC);

if (!$gebruiker) {
    header('Location: auto-zien.php?error=nogebruiker');
    exit;
}

$saldoKoper = (int)$gebruiker['saldo'];

if ($saldoKoper < $prijs) {
    header('Location: auto-zien.php?error=teweinig_saldo');
    exit;
}

$stmtUpdateKoper = $pdo->prepare("
    UPDATE gebruiker
    SET saldo = saldo - ?
    WHERE id = ?
");
$stmtUpdateKoper->execute([$prijs, $currentUserId]);

if ($verkoperId > 0 && $verkoperId !== $currentUserId) {
    $stmtUpdateVerkoper = $pdo->prepare("
        UPDATE gebruiker
        SET saldo = saldo + ?
        WHERE id = ?
    ");
    $stmtUpdateVerkoper->execute([$prijs, $verkoperId]);
}

$stmtUpdateAuto = $pdo->prepare("
    UPDATE auto
    SET gebruiker_id = ?, naam = ?, updated_at = CURRENT_TIMESTAMP
    WHERE id = ?
");
$stmtUpdateAuto->execute([
    $currentUserId,
    $currentUserName,
    $autoId
]);

header('Location: auto-zien.php?success=gekocht');
exit;