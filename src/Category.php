<?php
namespace App;

use PDO;

class Category {
    private $db;

    public function __construct($db) {
        // Si $db est une instance de Database, on récupère la connexion, sinon on l'utilise direct
        $this->db = $db instanceof Database ? $db->getConnection() : $db;
    }

    // Récupérer toutes les catégories
    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Ajouter une catégorie
    public function save($name) {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':name' => $name]);
    }

    // Supprimer une catégorie
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}