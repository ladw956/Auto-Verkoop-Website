<?php
$dbPath = getenv('DB_PATH') ?: '/tmp/autoverkoop.sqlite';
$directory = dirname($dbPath);
if (!is_dir($directory)) {
    mkdir($directory, 0775, true);
}

$pdo = new PDO('sqlite:' . $dbPath);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->exec('PRAGMA foreign_keys = ON');

$pdo->exec(<<<SQL
CREATE TABLE IF NOT EXISTS gebruiker (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    naam TEXT NOT NULL,
    email TEXT NOT NULL UNIQUE,
    wachtwoord_hash TEXT NOT NULL,
    is_admin INTEGER NOT NULL DEFAULT 0,
    is_mainAdmin INTEGER NOT NULL DEFAULT 0,
    saldo INTEGER NOT NULL DEFAULT 10000,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    rol TEXT NOT NULL DEFAULT 'User' CHECK (rol IN ('Admin', 'User'))
);

CREATE TABLE IF NOT EXISTS auto (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    brand TEXT NOT NULL,
    model TEXT NOT NULL,
    year INTEGER NOT NULL,
    prijs INTEGER NOT NULL,
    omschrijving TEXT,
    image TEXT,
    gebruiker_id INTEGER NOT NULL,
    naam TEXT NOT NULL,
    created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TEXT,
    FOREIGN KEY (gebruiker_id) REFERENCES gebruiker(id) ON DELETE CASCADE
);
SQL);

$count = (int)$pdo->query('SELECT COUNT(*) FROM gebruiker')->fetchColumn();
if ($count === 0) {
    $stmt = $pdo->prepare('INSERT INTO gebruiker (naam, email, wachtwoord_hash, is_admin, is_mainAdmin, saldo, rol) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute(['admin', 'admin@demo.local', password_hash('admin-demo', PASSWORD_DEFAULT), 1, 1, 50000, 'Admin']);
    $stmt->execute(['demo user', 'user@demo.local', password_hash('user-demo', PASSWORD_DEFAULT), 0, 0, 20000, 'User']);

    $stmtAuto = $pdo->prepare('INSERT INTO auto (brand, model, year, prijs, omschrijving, gebruiker_id, naam) VALUES (?, ?, ?, ?, ?, ?, ?)');
    $stmtAuto->execute(['Volvo', 'V60', 2020, 18500, 'Voorbeeldauto voor de portfolio-demo.', 1, 'admin']);
    $stmtAuto->execute(['Toyota', 'Yaris', 2019, 12000, 'Tweede voorbeeldauto voor de portfolio-demo.', 1, 'admin']);
}

fwrite(STDOUT, "Demo database ready at {$dbPath}\n");
