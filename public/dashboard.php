<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use App\Database;
use App\Depense;
use App\Wallet;


if (!isset($_SESSION['user_id'])) $_SESSION['user_id'] = 1;

$db = new Database();
$dbConn = $db->getConnection();
$depenseModel = new Depense($dbConn);
$walletModel = new Wallet($db);

$userId = $_SESSION['user_id'];
$currentMonth = date('n');
$currentYear = date('Y');


$totalDepenses = $depenseModel->getTotalMonth($userId, $currentMonth, $currentYear);
$recentDepenses = $depenseModel->getRecentByUserId($userId, 5);
$walletInfo = $walletModel->getByUserMonth($userId, date('Ym'));
$budget = $walletInfo['budget'] ?? 0;
$solde = $budget - $totalDepenses;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dashboard – Wallet App</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="../test.css" rel="stylesheet">
</head>
<body>

<div class="container-fluid">
  <div class="row">
    <aside class="col-md-2 sidebar p-0">
      <h4 class="text-center py-4">WALLET</h4>
      <a class="active" href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="Depenses.php"><i class="bi bi-cash"></i> Dépenses</a>
      <a href="Categories.php"><i class="bi bi-tags"></i> Catégories</a>
      <a href="Wallet.php"><i class="bi bi-wallet2"></i> Wallet</a>
      <a href="Automatiques.php"><i class="bi bi-arrow-repeat"></i> Automatiques</a>
      <a href="Profil.php"><i class="bi bi-person"></i> Profil</a>
    </aside>

    <main class="col-md-10 p-4">
      <h4 class="mb-4">Dashboard</h4>

      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card stat-card p-4 text-center">
            <h5>Budget Mensuel</h5>
            <h2><?= number_format($budget, 2) ?> MAD</h2>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 text-center">
            <h5 class="text-danger">Total Dépenses</h5>
            <h2 class="text-danger"><?= number_format($totalDepenses, 2) ?> MAD</h2>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 text-center">
            <h5 class="<?= $solde >= 0 ? 'text-success' : 'text-danger' ?>">Solde Restant</h5>
            <h2 class="<?= $solde >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($solde, 2) ?> MAD</h2>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="card p-4">
            <h5 class="mb-3">Dépenses Récentes</h5>
            <?php if (empty($recentDepenses)): ?>
              <p class="text-muted">Aucune dépense récente</p>
            <?php else: ?>
              <div class="list-group list-group-flush">
                <?php foreach($recentDepenses as $depense): ?>
                  <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <strong><?= htmlspecialchars($depense['title']) ?></strong>
                      <small class="text-muted d-block"><?= date('d/m/Y', strtotime($depense['expense_date'])) ?></small>
                    </div>
                    <span class="text-danger fw-bold">-<?= number_format($depense['amount'], 2) ?> MAD</span>
                  </div>
                <?php endforeach; ?>
              </div>
              <div class="text-center mt-3">
                <a href="Depenses.php" class="btn btn-outline-primary">Voir toutes les dépenses</a>
              </div>
            <?php endif; ?>
          </div>
        </div>
        
        <div class="col-md-4">
          <div class="card p-4">
            <h5 class="mb-3">Actions Rapides</h5>
            <div class="d-grid gap-2">
              <a href="Depenses.php" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Ajouter une dépense
              </a>
              <a href="Categories.php" class="btn btn-outline-primary">
                <i class="bi bi-tags"></i> Gérer les catégories
              </a>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>