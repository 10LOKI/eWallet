<?php
require_once __DIR__ . '/../vendor/autoload.php';
session_start();

use App\Database;
use App\User;


if (!isset($_SESSION['user_id'])) $_SESSION['user_id'] = 1;

$db = new Database();
$userModel = new User($db);
$userId = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_profile':
                $name = trim($_POST['name']);
                $email = trim($_POST['email']);
                
                if (!empty($name) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    if ($userModel->updateProfile($userId, $name, $email)) {
                        $_SESSION['success'] = "Profil mis à jour avec succès !";
                        $_SESSION['user_name'] = $name;
                    } else {
                        $_SESSION['error'] = "Erreur lors de la mise à jour du profil.";
                    }
                } else {
                    $_SESSION['error'] = "Veuillez remplir tous les champs correctement.";
                }
                break;
                
            case 'change_password':
                $currentPassword = $_POST['current_password'];
                $newPassword = $_POST['new_password'];
                $confirmPassword = $_POST['confirm_password'];
                
                if ($newPassword === $confirmPassword && strlen($newPassword) >= 6) {
                    
                    if ($userModel->changePassword($userId, $newPassword)) {
                        $_SESSION['success'] = "Mot de passe modifié avec succès !";
                    } else {
                        $_SESSION['error'] = "Erreur lors du changement de mot de passe.";
                    }
                } else {
                    $_SESSION['error'] = "Les mots de passe ne correspondent pas ou sont trop courts (min 6 caractères).";
                }
                break;
        }
    }
    header('Location: Profil.php');
    exit;
}


$user = $userModel->getById($userId);
if (!$user) {
    
    $user = [
        'name' => $_SESSION['user_name'] ?? 'Utilisateur Test',
        'email' => 'user@example.com',
        'created_at' => date('Y-m-d H:i:s')
    ];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mon Profil – Wallet App</title>
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
      <a href="Automatiques.php"><i class="bi bi-arrow-repeat"></i> Automatiques</a>
      <a class="active" href="Profil.php"><i class="bi bi-person"></i> Profil</a>
    </aside>

    <main class="col-md-10 p-4">
      
      <?php if (isset($_SESSION['success'])): ?>
          <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
      <?php endif; ?>
      <?php if (isset($_SESSION['error'])): ?>
          <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
      <?php endif; ?>
      
      <h4 class="mb-4">Mon Profil</h4>

      <div class="row">
        <div class="col-md-8">
          <div class="card p-4 mb-4">
            <h5 class="mb-3">Informations du compte</h5>
            <form method="POST">
              <input type="hidden" name="action" value="update_profile">
              <div class="mb-3">
                <label class="form-label">Nom d'utilisateur</label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user['email']) ?>" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Date d'inscription</label>
                <input type="text" class="form-control" value="<?= date('d/m/Y', strtotime($user['created_at'])) ?>" readonly>
              </div>
              <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Mettre à jour le profil
              </button>
            </form>
          </div>
          
          <div class="card p-4">
            <h5 class="mb-3">Changer le mot de passe</h5>
            <form method="POST">
              <input type="hidden" name="action" value="change_password">
              <div class="mb-3">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-control" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" minlength="6" required>
              </div>
              <div class="mb-3">
                <label class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" class="form-control" minlength="6" required>
              </div>
              <button type="submit" class="btn btn-warning">
                <i class="bi bi-key"></i> Changer le mot de passe
              </button>
            </form>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card p-4">
            <h5 class="mb-3">Actions</h5>
            <div class="d-grid gap-2">
              <a href="dashboard.php" class="btn btn-outline-primary">
                <i class="bi bi-speedometer2"></i> Aller au dashboard
              </a>
              <a href="Wallet.php" class="btn btn-outline-secondary">
                <i class="bi bi-wallet2"></i> Gérer mon wallet
              </a>
              <hr>
              <a href="logout.php" class="btn btn-outline-danger" onclick="return confirm('Voulez-vous vraiment vous déconnecter ?')">
                <i class="bi bi-box-arrow-right"></i> Se déconnecter
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