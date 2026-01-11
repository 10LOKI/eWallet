<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use App\Database;
use App\Depense;
use App\Category;


if (!isset($_SESSION['user_id'])) $_SESSION['user_id'] = 1;

$db = new Database();
$dbConn = $db->getConnection();
$depenseModel = new Depense($dbConn);
$categoryModel = new Category($db);


$toutesMesDepenses = $depenseModel->getAllByUser($_SESSION['user_id']);
$categories = $categoryModel->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Dépenses – Wallet App</title>
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
      <a class="active" href="depenses.php"><i class="bi bi-cash"></i> Dépenses</a>
      <a href="categories.php"><i class="bi bi-tags"></i> Catégories</a>
      <a href="Wallet.php"><i class="bi bi-wallet2"></i> Wallet</a>
      <a href="Automatiques.php"><i class="bi bi-arrow-repeat"></i> Automatiques</a>
      <a href="profil.php"><i class="bi bi-person"></i> Profil</a>
    </aside>

    <main class="col-md-10 p-4">

      <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>
      <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Mes Dépenses</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addExpense">
            <i class="bi bi-plus-lg"></i> Ajouter une dépense
        </button>
      </div>

      <div class="card p-4">
        <?php if (empty($toutesMesDepenses)): ?>
          <div class="text-center py-5">
            <i class="bi bi-receipt text-muted" style="font-size: 3rem;"></i>
            <h5 class="text-muted mt-3">Aucune dépense enregistrée</h5>
            <p class="text-muted">Commencez par ajouter votre première dépense</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle table-hover">
              <thead class="table-light">
                <tr>
                  <th>Titre</th>
                  <th>Catégorie</th>
                  <th>Date</th>
                  <th class="text-end">Montant</th>
                  <th class="text-center">Action</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($toutesMesDepenses as $depense): ?>
                  <tr>
                    <td>
                      <div class="fw-bold"><?= htmlspecialchars($depense['title']) ?></div>
                      <?php if (!empty($depense['is_automatic'])): ?>
                        <span class="badge bg-info text-dark" style="font-size:0.7em">AUTO</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <span class="badge bg-light text-primary border">
                        <?= htmlspecialchars($depense['category_name'] ?? 'Autre') ?>
                      </span>
                    </td>
                    <td><?= date('d/m/Y', strtotime($depense['expense_date'])) ?></td>
                    <td class="text-end text-danger fw-bold text-amount">
                      -<?= number_format($depense['amount'], 2) ?> MAD
                    </td>
                    <td class="text-center">
                      <form method="POST" action="supprimerDepenses.php" onsubmit="return confirm('Supprimer cette dépense ?');">
                          <input type="hidden" name="id" value="<?= $depense['id'] ?>">
                          <button type="submit" class="btn btn-sm btn-outline-danger border-0">
                              <i class="bi bi-trash"></i>
                          </button>
                      </form>
                    </td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
        <?php endif; ?>
      </div>

    </main>
  </div>
</div>

<div class="modal fade" id="addExpense">
  <div class="modal-dialog">
    <form method="POST" action="ajouter_depense.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nouvelle dépense</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
            <label>Titre</label>
            <input name="title" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Montant</label>
            <input name="amount" class="form-control" type="number" step="0.01" min="0.01" required>
        </div>
        <div class="mb-3">
            <label>Date</label>
            <input name="expense_date" class="form-control" type="date" value="<?= date('Y-m-d') ?>" required>
        </div>
        <div class="mb-3">
            <label>Catégorie</label>
            <select name="category_id" class="form-select" required>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>