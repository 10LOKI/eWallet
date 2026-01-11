<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use App\Database;
use App\DepenseAuto;
use App\Category;


if (!isset($_SESSION['user_id'])) $_SESSION['user_id'] = 1;

$db = new Database();
$dbConn = $db->getConnection();
$depenseAutoModel = new DepenseAuto($dbConn);
$categoryModel = new Category($db);

$userId = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'add':
            $title = trim($_POST['title']);
            $amount = floatval($_POST['amount']);
            $category_id = intval($_POST['category_id']);
            
            if (!empty($title) && $amount > 0) {
                $depenseAutoModel->setUserId($userId);
                $depenseAutoModel->setCategoryId($category_id);
                $depenseAutoModel->setTitle($title);
                $depenseAutoModel->setAmount($amount);
                
                if ($depenseAutoModel->save()) {
                    $_SESSION['success'] = "Dépense automatique ajoutée !";
                } else {
                    $_SESSION['error'] = "Erreur lors de l'ajout.";
                }
            } else {
                $_SESSION['error'] = "Veuillez remplir tous les champs correctement.";
            }
            break;
    }
    header('Location: Automatiques.php');
    exit;
}


if (isset($_GET['action'])) {
    switch ($_GET['action']) {
        case 'toggle':
            if (isset($_GET['id'])) {
                $depenseAutoModel->toggleActive($_GET['id'], $userId);
                $_SESSION['success'] = "Statut mis à jour !";
            }
            break;
        case 'delete':
            if (isset($_GET['id'])) {
                $depenseAutoModel->delete($_GET['id'], $userId);
                $_SESSION['success'] = "Dépense automatique supprimée !";
            }
            break;
    }
    header('Location: Automatiques.php');
    exit;
}


$automatiques = $depenseAutoModel->getAllByUser($userId);
$categories = $categoryModel->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Dépenses Automatiques – Wallet App</title>
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
      <a href="Wallet.php"><i class="bi bi-wallet2"></i> Wallet</a>
      <a class="active" href="Automatiques.php"><i class="bi bi-arrow-repeat"></i> Automatiques</a>
      <a href="Profil.php"><i class="bi bi-person"></i> Profil</a>
    </aside>

    <main class="col-md-10 p-4">
      
      <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Dépenses Automatiques</h4>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addAutoExpense">
            <i class="bi bi-plus-lg"></i> Ajouter une dépense automatique
        </button>
      </div>

      <div class="card p-4">
        <?php if (empty($automatiques)): ?>
          <div class="text-center py-5">
            <i class="bi bi-arrow-repeat text-muted" style="font-size: 3rem;"></i>
            <h5 class="text-muted mt-3">Aucune dépense automatique</h5>
            <p class="text-muted">Ajoutez vos dépenses récurrentes (loyer, électricité, etc.)</p>
          </div>
        <?php else: ?>
          <div class="table-responsive">
            <table class="table align-middle table-hover">
              <thead class="table-light">
                <tr>
                  <th>Titre</th>
                  <th>Catégorie</th>
                  <th class="text-end">Montant</th>
                  <th class="text-center">Statut</th>
                  <th class="text-center">Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($automatiques as $auto): ?>
                  <tr>
                    <td>
                      <div class="fw-bold"><?= htmlspecialchars($auto['title']) ?></div>
                      <small class="text-muted">Ajouté le <?= date('d/m/Y', strtotime($auto['created_at'])) ?></small>
                    </td>
                    <td>
                      <span class="badge bg-light text-primary border">
                        <?= htmlspecialchars($auto['category_name'] ?? 'Autre') ?>
                      </span>
                    </td>
                    <td class="text-end fw-bold text-amount">
                      <?= number_format($auto['amount'], 2) ?> MAD
                    </td>
                    <td class="text-center">
                      <?php if ($auto['is_active']): ?>
                        <span class="badge bg-success">Actif</span>
                      <?php else: ?>
                        <span class="badge bg-secondary">Inactif</span>
                      <?php endif; ?>
                    </td>
                    <td class="text-center">
                      <a href="?action=toggle&id=<?= $auto['id'] ?>" class="btn btn-sm btn-outline-<?= $auto['is_active'] ? 'warning' : 'success' ?> border-0" title="<?= $auto['is_active'] ? 'Désactiver' : 'Activer' ?>">
                        <i class="bi bi-<?= $auto['is_active'] ? 'pause' : 'play' ?>"></i>
                      </a>
                      <a href="?action=delete&id=<?= $auto['id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Supprimer cette dépense automatique ?')" title="Supprimer">
                        <i class="bi bi-trash"></i>
                      </a>
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


<div class="modal fade" id="addAutoExpense">
  <div class="modal-dialog">
    <form method="POST" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Nouvelle dépense automatique</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="action" value="add">
        
        <div class="mb-3">
            <label>Titre</label>
            <input name="title" class="form-control" required placeholder="Ex: Loyer, Électricité">
        </div>
        
        <div class="mb-3">
            <label>Montant mensuel</label>
            <div class="input-group">
              <input name="amount" class="form-control" type="number" step="0.01" min="0.01" required placeholder="1500">
              <span class="input-group-text">MAD</span>
            </div>
        </div>
        
        <div class="mb-3">
            <label>Catégorie</label>
            <select name="category_id" class="form-select" required>
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="alert alert-info">
          <i class="bi bi-info-circle"></i>
          Cette dépense sera automatiquement ajoutée chaque mois à votre wallet.
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
        <button type="submit" class="btn btn-primary">Créer la dépense automatique</button>
      </div>
    </form>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>