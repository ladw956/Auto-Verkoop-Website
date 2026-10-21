<?php
session_start();

require_once 'connect_db.php';
require_once 'functies.php';

vereistLogin();
if (empty($_SESSION['is_admin']) || $_SESSION['is_admin'] != 1) {
    header('Location: index.php');
    exit;
}

$melding = null;
$fout    = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gebruikerId = (int)($_POST['gebruiker_id'] ?? 0);
    $nieuwSaldo = (int)($_POST['nieuw_saldo'] ?? 0);

    if ($gebruikerId <= 0) {
        $fout = "Ongeldige gebruiker.";
    } else {
        $stmt = $pdo->prepare("UPDATE gebruiker SET saldo = ? WHERE id = ?");
        $stmt->execute([$nieuwSaldo, $gebruikerId]);
        $melding = "Saldo bijgewerkt.";
    }
}

$stmt = $pdo->prepare("SELECT id, naam, email, saldo, rol FROM gebruiker ORDER BY id ASC");
$stmt->execute();
$gebruikers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saldo beheer</title>

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
        .card-panel {
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
                <div class="col-lg-10">
                    <div class="card card-panel shadow-lg">
                        <div class="card-body p-4 p-md-5">
                            <h1 class="h4 text-primary fw-bold mb-4">Saldo beheer</h1>

                            <?php if ($melding): ?>
                                <div class="alert alert-success"><?= htmlspecialchars($melding) ?></div>
                            <?php endif; ?>

                            <?php if ($fout): ?>
                                <div class="alert alert-danger"><?= htmlspecialchars($fout) ?></div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-dark table-striped align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Naam</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Huidig saldo</th>
                                        <th>Nieuw saldo</th>
                                        <th>Actie</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($gebruikers as $gebruiker): ?>
                                        <tr>
                                            <td><?= (int)$gebruiker['id'] ?></td>
                                            <td><?= htmlspecialchars($gebruiker['naam']) ?></td>
                                            <td><?= htmlspecialchars($gebruiker['email']) ?></td>
                                            <td><?= htmlspecialchars($gebruiker['rol']) ?></td>
                                            <td>
                                                €<?= number_format((int)$gebruiker['saldo'], 0, ',', '.') ?>
                                            </td>
                                            <td>
                                                <form method="post" class="d-flex gap-2 align-items-center">
                                                    <input type="hidden" name="gebruiker_id"
                                                           value="<?= (int)$gebruiker['id'] ?>">
                                                    <input type="number" name="nieuw_saldo"
                                                           class="form-control form-control-sm"
                                                           style="max-width: 140px;"
                                                           value="<?= (int)$gebruiker['saldo'] ?>">
                                            </td>
                                            <td>
                                                    <button type="submit" class="btn btn-sm btn-primary">
                                                        Opslaan
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

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