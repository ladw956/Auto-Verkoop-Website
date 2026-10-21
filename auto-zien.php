<?php
session_start();

require_once 'config.php';
require_once 'connect_db.php';
require_once 'functies.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Auto's bekijken</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at top, #0d6efd 0, #020617 55%, #000 100%);
            color: #f8f9fa;
        }
        .page-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .main-content {
            flex: 1;
        }
        .auto-card {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.35);
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    <?php include 'header.php'; ?>

    <?php
    $stmt = $pdo->prepare("
        SELECT a.*, g.naam AS eigenaar_naam
        FROM auto a
        LEFT JOIN gebruiker g ON a.gebruiker_id = g.id
        ORDER BY a.id DESC
    ");
    $stmt->execute();
    $autos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $currentUserId = $_SESSION['gebruiker_id'] ?? null;
    $currentUserName = $_SESSION['gebruiker_naam'] ?? null;
    $isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
    ?>

    <?php
    $error = $_GET['error'] ?? null;
    $success = $_GET['success'] ?? null;
    ?>

    <main class="main-content">
        <div class="container py-5">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 text-primary fw-bold mb-0">Bekijk alle auto&apos;s</h1>
                <?php if ($currentUserId): ?>
                    <a href="auto-voegtoe.php" class="btn btn-outline-light btn-sm">
                        + Nieuwe auto toevoegen
                    </a>
                <?php endif; ?>
            </div>

            <?php if ($error === 'teweinig_saldo'): ?>
                <div class="alert alert-danger">
                    Je hebt niet genoeg saldo om deze auto te kopen.
                </div>
            <?php elseif ($error === 'nogebruiker'): ?>
                <div class="alert alert-danger">
                    Er ging iets mis met je accountgegevens.
                </div>
            <?php elseif ($success === 'gekocht'): ?>
                <div class="alert alert-success">
                    Je hebt de auto succesvol gekocht!
                </div>
            <?php endif; ?>

            <?php if (empty($autos)): ?>
                <div class="alert alert-info">
                    Er zijn nog geen auto&apos;s geplaatst.
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($autos as $auto): ?>
                        <?php
                        $isOwner = $currentUserId && $auto['gebruiker_id'] == $currentUserId;
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card auto-card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title text-primary">
                                        <?= htmlspecialchars($auto['brand']) . ' ' . htmlspecialchars($auto['model']) ?>
                                    </h5>

                                    <p class="mb-1">
                                        <strong>Bouwjaar:</strong>
                                        <?= htmlspecialchars($auto['year']) ?>
                                    </p>

                                    <p class="mb-1">
                                        <strong>Prijs:</strong>
                                        €<?= number_format((float)$auto['prijs'], 0, ',', '.') ?>
                                    </p>

                                    <?php if (!empty($auto['omschrijving'])): ?>
                                        <p class="small text-muted mb-2">
                                            <?= nl2br(htmlspecialchars($auto['omschrijving'])) ?>
                                        </p>
                                    <?php endif; ?>

                                    <p class="small text-secondary mb-3">
                                        <strong>Eigenaar:</strong>
                                        <?= htmlspecialchars($auto['eigenaar_naam'] ?? $auto['naam'] ?? 'Onbekend') ?>
                                    </p>

                                    <div class="mt-auto">
                                        <?php if ($currentUserId): ?>
                                            <div class="d-flex flex-wrap gap-2">

                                                <?php if ($isOwner || $isAdmin): ?>
                                                    <a href="auto-bewerk.php?id=<?= (int)$auto['id'] ?>"
                                                       class="btn btn-sm btn-warning">
                                                        Bewerken
                                                    </a>

                                                    <form method="post"
                                                          action="verwijder-auto.php"
                                                          onsubmit="return confirm('Weet je zeker dat je deze auto wilt verwijderen?');">
                                                        <input type="hidden" name="auto_id" value="<?= (int)$auto['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            Verwijderen
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                                <?php if (!$isOwner): ?>
                                                    <form method="post" action="auto-kopen.php">
                                                        <input type="hidden" name="auto_id" value="<?= (int)$auto['id'] ?>">
                                                        <button type="submit" class="btn btn-sm btn-success">
                                                            Kopen
                                                        </button>
                                                    </form>
                                                <?php endif; ?>

                                            </div>
                                        <?php else: ?>
                                            <p class="small text-muted mb-0">
                                                <a href="login.php" class="link-light">Log in</a> om te kunnen kopen.
                                            </p>
                                        <?php endif; ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </main>

    <?php include 'footer.php'; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>