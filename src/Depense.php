<?php
namespace App;
use PDO;
class Depense extends Transaction
{
    public function setUserId($userId) {
        $this->user_id = $userId;
    }
    
    public function setWalletId($walletId) {
        $this->wallet_id = $walletId;
    }
    
    public function setCategoryId($categoryId) {
        $this->category_id = $categoryId;
    }
    
    public function setTitle($title) {
        $this->title = $title;
    }
    
    public function setExpenseDate($date) {
        $this->expense_date = $date;
    }
    
    public function setIsAutomatic($isAutomatic) {
        $this->is_automatic = $isAutomatic;
    }
    
    public function setAutomaticId($automaticId) {
        $this->automatic_id = $automaticId;
    }

    public function save()
    {
        $sql = "INSERT INTO expenses (user_id,wallet_id,category_id,title,amount,expense_date,is_automatic,automatic_id) 
                VALUES (:user_id,:wallet_id,:category_id,:title,:amount,:expense_date,:is_automatic,:automatic_id)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':user_id' => $this->user_id,
            ':wallet_id' => $this->wallet_id,
            ':category_id'   => $this->category_id,
            ':title'         => $this->title,
            ':amount'        => $this->amount,
            ':expense_date'  => $this->expense_date,
            ':is_automatic'  => $this->is_automatic ?? 0,
            ':automatic_id'  => $this->automatic_id ?? null
        ]);
    }

    /**
     * ✅ Récupère toutes les dépenses d'un utilisateur avec le nom de la catégorie
     */
    public function getAllByUser($userId)
    {
        // Jointure avec la table categories pour avoir le nom (Nourriture, etc.)
        $sql = "SELECT e.*, c.name as category_name 
                FROM expenses e 
                LEFT JOIN categories c ON e.category_id = c.id 
                WHERE e.user_id = :user_id 
                ORDER BY e.expense_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * ✅ Supprime une dépense (Sécurité : on vérifie que c'est bien l'user connecté)
     */
    public function delete($id, $userId)
    {
        $sql = "DELETE FROM expenses WHERE id = :id AND user_id = :user_id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':user_id' => $userId
        ]);
    }
    public function getTotalMonth($userId,$month,$year)
    {
        $sql = "SELECT SUM(amount) as total FROM expenses WHERE user_id = :user_id AND MONTH(expense_date) = :month AND YEAR(expense_date) = :year";

        $stmt = $this -> db -> prepare($sql);
        $stmt -> execute([
            ':user_id' => $userId,
            ':month' => $month,
            ':year' => $year
        ]);
        $result = $stmt -> fetch(PDO::FETCH_ASSOC);
        return $result['total'] ?? 0;
    }
    public function getRecentByUserId($userId, $limit = 5)
    {
        $sql = "SELECT e.*, c.name as categorie_nom FROM expenses e JOIN categories c ON e.category_id = c.id WHERE e.user_id = :user_id ORDER BY e.expense_date DESC LIMIT :limit";

        $stmt = $this -> db -> prepare($sql);
        $stmt -> bindValue(':user_id' , $userId , PDO::PARAM_INT);
        $stmt -> bindValue(':limit' , $limit , PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt -> fetchAll(PDO::FETCH_ASSOC);
    }
}
?>