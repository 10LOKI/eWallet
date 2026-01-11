<?php
require_once __DIR__ . '/../vendor/autoload.php';

use App\Database;

try {
    $db = new Database();
    $conn = $db->getConnection();
    
    
    $stmt = $conn->query("SELECT COUNT(*) FROM categories");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        
        $defaultCategories = [
            'Nourriture',
            'Transport', 
            'Loyer',
            'Loisirs',
            'Autre'
        ];
        
        $stmt = $conn->prepare("INSERT INTO categories (name) VALUES (?)");
        
        foreach ($defaultCategories as $category) {
            $stmt->execute([$category]);
        }
        
        echo "Catégories par défaut ajoutées avec succès!";
    } else {
        echo "Les catégories existent déjà.";
    }
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage();
}
?>