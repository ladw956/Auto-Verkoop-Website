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
$isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

if ($auto['gebruiker_id'] != $currentUserId && !$isAdmin) {
    header('Location: auto-zien.php');
    exit;
}

$stmt = $pdo->prepare("DELETE FROM auto WHERE id = ?");
$stmt->execute([$autoId]);

header('Location: auto-zien.php');
exit;