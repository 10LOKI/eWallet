<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Depense;
use App\Wallet;

// Vérification de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: ../public/auth.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $userId = $_SESSION['user_id'];
        $title = trim($_POST['title'] ?? '');
        $amount = floatval($_POST['amount'] ?? 0);
        $expense_date = $_POST['expense_date'] ?? date('Y-m-d');
        $category_id = intval($_POST['category_id'] ?? 1);

        // ✅ Règle métier : Bloquer les montants négatifs ou nuls
        if (empty($title) || $amount <= 0) {
            throw new Exception('Le montant doit être positif et le titre renseigné.');
        }

        $db = new Database();
        $dbConn = $db->getConnection(); // Assure-toi que ta classe Database a cette méthode

        // Récupération du wallet (simplifié pour l'exemple)
        $wallet = new Wallet($db); 
        $month = date('Ym', strtotime($expense_date));
        $walletInfo = $wallet->getByUserMonth($userId, $month);
        
        // Si pas de wallet, on en crée un ou on utilise une valeur par défaut
        $wallet_id = $walletInfo['id'] ?? 1; 

        $depense = new Depense($dbConn); // On passe la connexion PDO directement
        
        // Hydratation de l'objet (Mapping)
        $depense->user_id = $userId;
        $depense->wallet_id = $wallet_id;
        $depense->category_id = $category_id;
        $depense->title = $title;
        $depense->amount = $amount;
        $depense->expense_date = $expense_date;

        if ($depense->save()) {
            $_SESSION['success'] = "Dépense ajoutée avec succès !";
        } else {
            $_SESSION['error'] = "Erreur SQL lors de l'ajout.";
        }

    } catch (Exception $e) {
        $_SESSION['error'] = $e->getMessage();
    }
}

// ✅ Redirection vers la page principale
header('Location: ../public/Depenses.php');
exit;
?>