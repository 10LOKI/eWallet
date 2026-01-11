<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;
use App\Depense;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $db = new Database();
    $dbConn = $db->getConnection();
    $depenseModel = new Depense($dbConn);
    
    $id = intval($_POST['id']);
    $userId = $_SESSION['user_id'] ?? 0;

    
    if ($depenseModel->delete($id, $userId)) {
        $_SESSION['success'] = "Dépense supprimée.";
    } else {
        $_SESSION['error'] = "Impossible de supprimer cette dépense.";
    }
}

header('Location: Depenses.php');
exit;
?>