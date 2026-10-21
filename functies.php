<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function hashPassword(string $plain): string
{
    return password_hash($plain, PASSWORD_DEFAULT);
}

function verifyPassword(string $plain, string $hash): bool
{
    return password_verify($plain, $hash);
}

function isIngelogd() {
    return isset($_SESSION['gebruiker_id']);
}

function huidigeGebruikerId() {
    return $_SESSION['gebruiker_id'] ?? null;
}

function huidigeGebruikerNaam() {
    return $_SESSION['gebruiker_naam'] ?? null;
}

function isAdmin() {
    return !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}

function vereistLogin() {
    if (!isIngelogd()) {
        header('Location: login.php');
        exit();
    }
}

function vereistAdmin(){
    if (!isAdmin()){
        header('HTTP/1.1 403 Forbidden');
        echo "Geen toegang tot deze pagina.";
        exit();
    }
}

?>