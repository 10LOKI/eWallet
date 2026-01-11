<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use App\Database;
use App\Category;

$db = new Database();
$categoryModel = new Category($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    $name = trim($_POST['name']);

    if (!empty($name)) {
        if ($categoryModel->save($name)) {
            $_SESSION['success'] = "Catégorie ajoutée !";
        } else {
            $_SESSION['error'] = "Erreur lors de l'ajout.";
        }
    }
    header('Location: Categories.php');
    exit;
}


if (isset($_GET['delete'])) {
    $categoryModel->delete($_GET['delete']);
    header('Location: Categories.php');
    exit;
}


$categories = $categoryModel->getAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Catégories – Wallet App</title>
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
      <a class="active" href="Categories.php"><i class="bi bi-tags"></i> Catégories</a>
      <a href="Wallet.php"><i class="bi bi-wallet2"></i> Wallet</a>
      <a href="Automatiques.php"><i class="bi bi-arrow-repeat"></i> Automatiques</a>
      <a href="Profil.php"><i class="bi bi-person"></i> Profil</a>
    </aside>

    <main class="col-md-10 p-4">
      
      <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>

      <div class="row">
        <div class="col-md-8">
            <h4 class="mb-4">Gestion des Catégories</h4>
            <div class="card p-4">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($categories as $cat): ?>
                        <tr>
                            <td class="fw-bold"><?= htmlspecialchars($cat['name']) ?></td>
                            <td class="text-end">
                                <a href="?delete=<?= $cat['id'] ?>" class="btn btn-sm btn-outline-danger border-0" onclick="return confirm('Supprimer cette catégorie ?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-4">
            <h4 class="mb-4">Ajouter</h4>
            <div class="card p-4">
                <form method="POST">
                    <input type="hidden" name="action" value="add">
                    
                    <div class="mb-3">
                        <label>Nom de la catégorie</label>
                        <input type="text" name="name" class="form-control" required placeholder="Ex: Santé">
                    </div>

                    <button class="btn btn-primary w-100">Ajouter la catégorie</button>
                </form>
            </div>
        </div>

      </div>
    </main>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>