<?php
require_once 'config.php';

try {
    if (DB_DRIVER === 'sqlite') {
        $directory = dirname(DB_PATH);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }
        $pdo = new PDO('sqlite:' . DB_PATH);
        $pdo->exec('PRAGMA foreign_keys = ON');
        $pdo->exec('PRAGMA busy_timeout = 5000');
    } else {
        $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
        $pdo = new PDO($dsn, DB_USER, DB_Pass);
    }
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    die('Databaseverbinding mislukt. Controleer de configuratie.');
}
?>
