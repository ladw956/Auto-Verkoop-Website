<?php
require_once 'connect_db.php';
require_once 'functies.php';

?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
        crossorigin="anonymous"
    >

    <title>Autoverkoop</title>

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

        .hero-card {
            background: rgba(15, 23, 42, 0.9);
            border: 1px solid rgba(148, 163, 184, 0.3);
        }

        .hero-card h1 {
            letter-spacing: 0.03em;
        }

        a.text-link {
            color: #0d6efd;
            text-decoration: none;
        }

        a.text-link:hover {
            text-decoration: underline;
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
    try {
        $stmt = $pdo->prepare("
            SELECT brand, model, year, prijs
            FROM auto
            ORDER BY created_at DESC
            LIMIT 3
        ");
        $stmt->execute();
        $laatsteAutos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $laatsteAutos = [];
    }

    $isIngelogd = !empty($_SESSION['gebruiker_id'] ?? null);
    $gebruikerNaam = $_SESSION['gebruiker_naam'] ?? null;
    ?>

    <!-- Hero Sectie -->
    <main class="main-content">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <div class="card hero-card shadow-lg border-0 mb-4">
                        <div class="card-body p-4 p-md-5">
                            <header class="mb-3">
                                <h1 class="text-primary fw-bold">
                                    Welkom
                                    <?php
                                    if ($isIngelogd && !empty($gebruikerNaam)) {
                                        echo ', ' . htmlspecialchars(ucwords($gebruikerNaam)) . '!';
                                    } else {
                                        echo ' bij Autoverkoop!';
                                    }
                                    ?>
                                </h1>
                            </header>

                            <p class="lead mb-3">
                                Dit is de website van Nederland voor het kopen en verkopen van auto&apos;s.
                            </p>

                            <?php if (!$isIngelogd): ?>
                                <div class="alert alert-info mb-4">
                                    Je moet inloggen of registreren om auto’s te kunnen kopen of verkopen.
                                </div>

                                <div class="d-flex flex-wrap gap-3">
                                    <a href="registreer.php" class="btn btn-primary">
                                        Registreren
                                    </a>
                                    <a href="login.php" class="btn btn-outline-light">
                                        Inloggen
                                    </a>
                                </div>
                            <?php else: ?>
                                <p class="mb-2">
                                    Je bent ingelogd. Ga direct naar
                                    <a href="auto-zien.php" class="text-link">Auto&apos;s bekijken</a>
                                    of
                                    <a href="auto-voegtoe.php" class="text-link">plaats een nieuwe auto</a>.
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-8">
                    <h2 class="h5 text-primary fw-bold mb-3">Laatste geplaatste auto&apos;s</h2>

                    <?php if (empty($laatsteAutos)): ?>
                        <p class="text-muted">Er zijn nog geen auto&apos;s geplaatst.</p>
                    <?php else: ?>
                        <div class="row g-3">
                            <?php foreach ($laatsteAutos as $auto): ?>
                                <div class="col-12 col-md-6">
                                    <div class="card auto-card shadow-sm">
                                        <div class="card-body py-3">
                                            <h5 class="card-title mb-1 text-primary">
                                                <?= htmlspecialchars($auto['brand']) . ' ' . htmlspecialchars($auto['model']) ?>
                                            </h5>
                                            <p class="mb-1 small">
                                                <strong>Bouwjaar:</strong>
                                                <?= htmlspecialchars($auto['year']) ?>
                                            </p>
                                            <p class="mb-0 small">
                                                <strong>Prijs:</strong>
                                                €<?= number_format((float)$auto['prijs'], 0, ',', '.') ?>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-3">
                            <a href="auto-zien.php" class="btn btn-outline-light btn-sm">
                                Alle auto&apos;s bekijken
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

        </div>
    </main>

    <!-- Footer -->
    <?php require_once 'footer.php'; ?>

</div>

<!-- Bootstrap JS bundle -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
    crossorigin="anonymous"
></script>
</body>
</html>