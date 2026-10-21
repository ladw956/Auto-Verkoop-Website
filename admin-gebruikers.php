<?php
session_start();

require_once 'connect_db.php';
require_once 'functies.php';

vereistLogin();

// Alleen main admin mag hier komen
if (empty($_SESSION['is_mainAdmin']) || $_SESSION['is_mainAdmin'] != 1) {
    header('Location: index.php');
    exit;
}

$melding = null;
$fout = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $targetId = (int)($_POST['gebruiker_id'] ?? 0);
    $nieuweAdmin = isset($_POST['is_admin']) ? 1 : 0;
    if ($targetId <= 0) {
        $fout = "Ongeldige gebruiker.";
    } else {
        $stmt = $pdo->prepare("
            SELECT id, naam, is_admin, is_mainAdmin
            FROM gebruiker
            WHERE id = ?
        ");
        $stmt->execute([$targetId]);
        $target = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$target) {
            $fout = "Gebruiker niet gevonden.";
        } else {
            if ((int)$target['is_mainAdmin'] === 1) {
                $fout = "Je kunt de hoofd admin niet aanpassen.";
            } else {
                $stmtUpdate = $pdo->prepare("UPDATE gebruiker SET is_admin = ? WHERE id = ?");
                $stmtUpdate->execute([$nieuweAdmin, $targetId]);
                $melding = "Rol van gebruiker '" . htmlspecialchars($target['naam']) . "' is bijgewerkt.";
            }
        }
    }
}

$stmt = $pdo->prepare("
    SELECT id, naam, email, rol, is_admin, is_mainAdmin, saldo
    FROM gebruiker
    ORDER BY id ASC
");
$stmt->execute();
$gebruikers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gebruikersbeheer</title>

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
        .badge-super {
            background: #ffc107;
            color: #000;
        }
        .badge-admin {
            background: #0d6efd;
        }
        .badge-user {
            background: #6c757d;
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
                            <h1 class="h4 text-primary fw-bold mb-4">Gebruikersbeheer (alleen hoofd admin)</h1>

                            <?php if ($melding): ?>
                                <div class="alert alert-success"><?= $melding ?></div>
                            <?php endif; ?>

                            <?php if ($fout): ?>
                                <div class="alert alert-danger"><?= $fout ?></div>
                            <?php endif; ?>

                            <div class="table-responsive">
                                <table class="table table-dark table-striped align-middle mb-0">
                                    <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Naam</th>
                                        <th>Email</th>
                                        <th>Rol</th>
                                        <th>Saldo</th>
                                        <th>Status</th>
                                        <th>Admin rechten</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php foreach ($gebruikers as $gebruiker): ?>
                                        <?php
                                        $isSuper = (int)$gebruiker['is_mainAdmin'] === 1;
                                        $isAdmin = (int)$gebruiker['is_admin'] === 1;
                                        ?>
                                        <tr>
                                            <td><?= (int)$gebruiker['id'] ?></td>
                                            <td><?= htmlspecialchars($gebruiker['naam']) ?></td>
                                            <td><?= htmlspecialchars($gebruiker['email']) ?></td>
                                            <td><?= htmlspecialchars($gebruiker['rol']) ?></td>
                                            <td>€<?= number_format((int)$gebruiker['saldo'], 0, ',', '.') ?></td>
                                            <td>
                                                <?php if ($isSuper): ?>
                                                    <span class="badge badge-super">MainAdmin</span>
                                                <?php elseif ($isAdmin): ?>
                                                    <span class="badge badge-admin">Admin</span>
                                                <?php else: ?>
                                                    <span class="badge badge-user">User</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if ($isSuper): ?>
                                                    <span class="text-warning small">
                                                        Kan niet aangepast worden
                                                    </span>
                                                <?php else: ?>
                                                    <form method="post" class="d-inline-block">
                                                        <input type="hidden" name="gebruiker_id"
                                                               value="<?= (int)$gebruiker['id'] ?>">
                                                        <div class="form-check form-switch d-inline-flex align-items-center gap-2">
                                                            <input class="form-check-input" type="checkbox"
                                                                   name="is_admin"
                                                                   id="adminSwitch<?= (int)$gebruiker['id'] ?>"
                                                                <?= $isAdmin ? 'checked' : '' ?>>
                                                            <label class="form-check-label small"
                                                                   for="adminSwitch<?= (int)$gebruiker['id'] ?>">
                                                                Admin
                                                            </label>
                                                        </div>
                                                        <button type="submit"
                                                                class="btn btn-sm btn-primary ms-2">
                                                            Opslaan
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
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