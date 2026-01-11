<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use App\Database;
use App\Wallet;
use App\Depense;


if (!isset($_SESSION['user_id'])) $_SESSION['user_id'] = 1;

$db = new Database();
$walletModel = new Wallet($db);
$depenseModel = new Depense($db->getConnection());

$userId = $_SESSION['user_id'];
$currentMonth = date('Ym');


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['budget'])) {
    $budget = floatval($_POST['budget']);
    
    if ($budget > 0) {
        if ($walletModel->setBudget($userId, $currentMonth, $budget)) {
            $_SESSION['success'] = "Budget mis à jour avec succès !";
        } else {
            $_SESSION['error'] = "Erreur lors de la mise à jour du budget.";
        }
    } else {
        $_SESSION['error'] = "Le budget doit être positif.";
    }
    header('Location: Wallet.php');
    exit;
}


$walletInfo = $walletModel->getByUserMonth($userId, $currentMonth);
$currentBudget = $walletInfo['budget'] ?? 0;
$totalDepenses = $depenseModel->getTotalMonth($userId, date('n'), date('Y'));
$soldeRestant = $currentBudget - $totalDepenses;
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Wallet – Wallet App</title>
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
      <a href="dashboard.php"><i class="bi bi-speedometer2"></i> Dashboard</a>
      <a href="Depenses.php"><i class="bi bi-cash"></i> Dépenses</a>
      <a href="Categories.php"><i class="bi bi-tags"></i> Catégories</a>
      <a class="active" href="Wallet.php"><i class="bi bi-wallet2"></i> Wallet</a>
      <a href="Automatiques.php"><i class="bi bi-arrow-repeat"></i> Automatiques</a>
      <a href="Profil.php"><i class="bi bi-person"></i> Profil</a>
    </aside>

    <main class="col-md-10 p-4">
      
      <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <h4 class="mb-4">Mon Wallet - <?= date('F Y') ?></h4>

      <div class="row mb-4">
        <div class="col-md-4">
          <div class="card stat-card p-4 text-center">
            <i class="bi bi-piggy-bank mb-2" style="font-size: 2rem;"></i>
            <h6>Budget Mensuel</h6>
            <div class="amount"><?= number_format($currentBudget, 2) ?> MAD</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 text-center">
            <i class="bi bi-cash-stack mb-2 text-danger" style="font-size: 2rem;"></i>
            <h6 class="text-danger">Total Dépenses</h6>
            <div class="amount text-danger"><?= number_format($totalDepenses, 2) ?> MAD</div>
          </div>
        </div>
        <div class="col-md-4">
          <div class="card p-4 text-center">
            <i class="bi bi-calculator mb-2 <?= $soldeRestant >= 0 ? 'text-success' : 'text-danger' ?>" style="font-size: 2rem;"></i>
            <h6 class="<?= $soldeRestant >= 0 ? 'text-success' : 'text-danger' ?>">Solde Restant</h6>
            <div class="amount <?= $soldeRestant >= 0 ? 'text-success' : 'text-danger' ?>"><?= number_format($soldeRestant, 2) ?> MAD</div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-md-6">
          <div class="card p-4">
            <h5 class="mb-3">Définir le Budget Mensuel</h5>
            <form method="POST">
              <div class="mb-3">
                <label class="form-label">Budget pour <?= date('F Y') ?></label>
                <div class="input-group">
                  <input type="number" name="budget" class="form-control" step="0.01" min="0.01" 
                         value="<?= $currentBudget ?>" required placeholder="Ex: 5000">
                  <span class="input-group-text">MAD</span>
                </div>
                <div class="form-text">Définissez votre budget mensuel pour contrôler vos dépenses</div>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Mettre à jour le budget
              </button>
            </form>
          </div>
        </div>

        <div class="col-md-6">
          <div class="card p-4">
            <h5 class="mb-3">Résumé</h5>
            
            <?php if ($currentBudget > 0): ?>
              <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                  <span>Progression des dépenses</span>
                  <span><?= $currentBudget > 0 ? round(($totalDepenses / $currentBudget) * 100, 1) : 0 ?>%</span>
                </div>
                <div class="progress">
                  <div class="progress-bar <?= $soldeRestant >= 0 ? 'bg-success' : 'bg-danger' ?>" 
                       style="width: <?= min(100, ($totalDepenses / $currentBudget) * 100) ?>%"></div>
                </div>
              </div>
              
              <?php if ($soldeRestant < 0): ?>
                <div class="alert alert-warning">
                  <i class="bi bi-exclamation-triangle"></i>
                  Attention ! Vous avez dépassé votre budget de <?= number_format(abs($soldeRestant), 2) ?> MAD
                </div>
              <?php elseif ($soldeRestant < ($currentBudget * 0.1)): ?>
                <div class="alert alert-info">
                  <i class="bi bi-info-circle"></i>
                  Il vous reste moins de 10% de votre budget mensuel
                </div>
              <?php endif; ?>
              
            <?php else: ?>
              <div class="alert alert-info">
                <i class="bi bi-info-circle"></i>
                Définissez votre budget mensuel pour commencer à suivre vos dépenses
              </div>
            <?php endif; ?>

            <div class="d-grid gap-2 mt-3">
              <a href="Depenses.php" class="btn btn-outline-primary">
                <i class="bi bi-plus-lg"></i> Ajouter une dépense
              </a>
              <a href="dashboard.php" class="btn btn-outline-secondary">
                <i class="bi bi-speedometer2"></i> Voir le dashboard
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