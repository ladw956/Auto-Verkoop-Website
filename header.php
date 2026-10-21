<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$currentUserId = $_SESSION['gebruiker_id'] ?? null;
$currentUserName = $_SESSION['gebruiker_naam'] ?? null;
$currentSaldo = null;

if ($currentUserId) {
    if (!isset($pdo)) {
        require_once 'connect_db.php';
    }

    $stmtSaldo = $pdo->prepare("SELECT saldo FROM gebruiker WHERE id = ?");
    $stmtSaldo->execute([$currentUserId]);
    $rowSaldo = $stmtSaldo->fetch(PDO::FETCH_ASSOC);
    if ($rowSaldo) {
        $currentSaldo = (int)$rowSaldo['saldo'];
    }
}
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
    <div class="container">

        <!-- logo -->
        <a class="navbar-brand fw-bold" href="index.php">Autoverkoop</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- menu -->
        <div class="collapse navbar-collapse" id="mainNavbar">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="auto-zien.php">Auto's bekijken</a>
                </li>

                <?php if (!isset($_SESSION['gebruiker_id'])): ?>

                    <!-- gasten links -->
                    <li class="nav-item">
                        <a class="nav-link" href="registreer.php">Registreren</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Inloggen</a>
                    </li>

                <?php else: ?>

                    <!-- ingelogd -->
                    <li class="nav-item">
                        <span class="nav-link fw-semibold">
                            Ingelogd als: <?= htmlspecialchars($_SESSION['gebruiker_naam']) ?>
                        </span>
                    </li>

                    <?php if ($currentSaldo !== null): ?>
                        <li class="nav-item">
                            <span class="nav-link fw-semibold">
                                Saldo: &euro; <?= number_format($currentSaldo, 0, ',', '.') ?>
                            </span>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="auto-voegtoe.php">Auto toevoegen</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link text-warning fw-bold" href="loguit.php">Uitloggen</a>
                    </li>

                    <!-- admin panels -->
                    <?php if (!empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link text-light bg-danger rounded px-2" href="admin-pagina.php">
                                Admin Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-light bg-secondary rounded px-2" href="admin-saldo.php">
                                Saldo beheer
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php if (!empty($_SESSION['is_mainAdmin']) && $_SESSION['is_mainAdmin'] == 1): ?>
                        <li class="nav-item">
                            <a class="nav-link text-light bg-info rounded px-2 ms-2" href="admin-gebruikers.php">
                                Gebruikersbeheer
                            </a>
                        </li>
                    <?php endif; ?>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>