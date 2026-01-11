<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Depense;
use App\Wallet;


if (!isset($_SESSION['user_id'])) {
    
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié']);
        exit;
    }
    header('Location: auth.php');
    exit;
}

try {
    $userId = $_SESSION['user_id'];
    $title = trim($_POST['title'] ?? '');
    $amount = floatval($_POST['amount'] ?? 0);
    $expense_date = $_POST['expense_date'] ?? date('Y-m-d');
    $category_id = intval($_POST['category_id'] ?? 1);

    if (empty($title) || $amount <= 0) {
        throw new Exception('Le montant doit être positif et le titre renseigné.');
    }

    $db = new Database();
    $dbConn = $db->getConnection();

    $wallet = new Wallet($db);
    $month = date('Ym', strtotime($expense_date));
    $walletInfo = $wallet->getByUserMonth($userId, $month);
    $wallet_id = $walletInfo['id'] ?? 1;

    $depense = new Depense($dbConn);
    $depense->setUserId($userId);
    $depense->setWalletId($wallet_id);
    $depense->setCategoryId($category_id);
    $depense->setTitle($title);
    $depense->setAmount($amount);
    $depense->setExpenseDate($expense_date);

    if ($depense->save()) {
        
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }

        $_SESSION['success'] = "Dépense ajoutée avec succès !";
    } else {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Erreur SQL lors de l\'ajout.']);
            exit;
        }
        $_SESSION['error'] = "Erreur SQL lors de l'ajout.";
    }

} catch (Exception $e) {
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
    $_SESSION['error'] = $e->getMessage();
}


header('Location: Depenses.php');
exit;

?>
