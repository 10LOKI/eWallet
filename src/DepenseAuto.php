<?php
namespace App;
use PDO;

class DepenseAuto extends Transaction
{
    public function save()
    {
        $sql = "INSERT INTO automatic_expenses (user_id, category_id, title, amount, is_active) 
                VALUES (:user_id, :category_id, :title, :amount, 1)";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute([
            ':user_id' => $this->user_id,
            ':category_id' => $this->category_id,
            ':title' => $this->title,
            ':amount' => $this->amount
        ]);
    }
    
    public function setUserId($userId) {
        $this->user_id = $userId;
    }
    
    public function setCategoryId($categoryId) {
        $this->category_id = $categoryId;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function getAllByUser($userId)
    {
        $sql = "SELECT ae.*, c.name as category_name 
                FROM automatic_expenses ae 
                LEFT JOIN categories c ON ae.category_id = c.id 
                WHERE ae.user_id = :user_id 
                ORDER BY ae.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function toggleActive($id, $userId)
    {
        $sql = "UPDATE automatic_expenses SET is_active = NOT is_active WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
    
    public function delete($id, $userId)
    {
        $sql = "DELETE FROM automatic_expenses WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}