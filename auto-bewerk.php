<?php
session_start();

require_once 'connect_db.php';
require_once 'functies.php';

vereistLogin();

$autoId = (int)($_GET['id'] ?? 0);
if ($autoId <= 0) {
    header('Location: auto-zien.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT * FROM auto WHERE id = ?
");
$stmt->execute([$autoId]);
$auto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$auto) {
    header('Location: auto-zien.php');
    exit;
}

$currentUserId = huidigeGebruikerId();
$isAdmin = !empty($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;

// Alleen eigenaar of admin mag bewerken
if ($auto['gebruiker_id'] != $currentUserId && !$isAdmin) {
    header('Location: auto-zien.php');
    exit;
}

$fouten = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $brand = trim($_POST['brand'] ?? '');
    $model = trim($_POST['model'] ?? '');
    $year = (int)($_POST['year'] ?? 0);
    $prijs = (int)($_POST['prijs'] ?? 0);
    $omschrijving = trim($_POST['omschrijving'] ?? '');

    if ($brand === '' || $model === '' || !$year || !$prijs) {
        $fouten[] = "Merk, model, bouwjaar en prijs zijn verplicht.";
    }

    if (empty($fouten)) {
        $stmt = $pdo->prepare("
            UPDATE auto
            SET brand = ?, model = ?, year = ?, prijs = ?, omschrijving = ?, updated_at = CURRENT_TIMESTAMP
            WHERE id = ?
        ");
        $stmt->execute([
            $brand,
            $model,
            $year,
            $prijs,
            $omschrijving,
            $autoId
        ]);

        header('Location: auto-zien.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Auto bewerken</title>

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
        .card-form {
            background: rgba(15, 23, 42, 0.95);
            border: 1px solid rgba(148, 163, 184, 0.35);
        }
    </style>
</head>
<body>
<div class="page-wrapper">

    <?php include 'header.php'; ?>

    <main class="main-content">
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="card card-form shadow-lg">
                        <div class="card-body p-4 p-md-5">
                            <h1 class="h3 mb-4 text-primary fw-bold">
                                Auto bewerken
                            </h1>

                            <?php if (!empty($fouten)): ?>
                                <div class="alert alert-danger">
                                    <ul class="mb-0">
                                        <?php foreach ($fouten as $f): ?>
                                            <li><?= htmlspecialchars($f) ?></li>
                                        <?php endforeach; ?>
                                    </ul>
                                </div>
                            <?php endif; ?>

                            <form method="post" novalidate>
                                <div class="mb-3">
                                    <label class="form-label">Merk</label>
                                    <input type="text" name="brand" class="form-control"
                                           value="<?= htmlspecialchars($_POST['brand'] ?? $auto['brand']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Model</label>
                                    <input type="text" name="model" class="form-control"
                                           value="<?= htmlspecialchars($_POST['model'] ?? $auto['model']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Bouwjaar</label>
                                    <input type="number" name="year" class="form-control"
                                           value="<?= htmlspecialchars($_POST['year'] ?? $auto['year']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Prijs (€)</label>
                                    <input type="number" name="prijs" class="form-control"
                                           value="<?= htmlspecialchars($_POST['prijs'] ?? $auto['prijs']) ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Omschrijving (optioneel)</label>
                                    <textarea name="omschrijving" rows="4"
                                              class="form-control"><?= htmlspecialchars($_POST['omschrijving'] ?? $auto['omschrijving']) ?></textarea>
                                </div>

                                <div class="d-flex justify-content-between">
                                    <a href="auto-zien.php" class="btn btn-outline-light">
                                        Annuleren
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        Opslaan
                                    </button>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>