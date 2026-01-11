<?php
namespace App;

use PDO;

class Category {
    private $db;

    public function __construct($db) {
        $this->db = $db instanceof Database ? $db->getConnection() : $db;
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function save($name, $icon, $color) {
        $sql = "INSERT INTO categories (name, icon, color) VALUES (:name, :icon, :color)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':name' => $name,
            ':icon' => $icon,
            ':color' => $color
        ]);
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM categories WHERE id = ?");
        return $stmt->execute([$id]);
    }
}