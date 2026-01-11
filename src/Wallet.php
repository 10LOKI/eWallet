<?php
namespace App;

use PDO;

class Wallet implements CalculableInterface
{
    private $db;
    private $budgetInitial;
    private $walletId;

    public function __construct(Database $database = null, $walletId = null, $budgetInitial = null)
    {
        if($database) {
            $this->db = $database->getConnection();
        }
        
        if($walletId && $budgetInitial) {
            if(empty($walletId)) {
                throw new \Exception("le wallet id est obligatoire");
            }
            if($budgetInitial <= 0) {
                throw new \Exception("le budget doit etre positif");
            }
            $this->walletId = $walletId;
            $this->budgetInitial = $budgetInitial;
        }
    }

    public function CalculerTotalDepenses(): float
    {
        $sql = "SELECT SUM(amount) as total FROM expenses WHERE wallet_id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $this->walletId]);
        $resultat = $stmt->fetch();
        return (float)($resultat['total'] ?? 0);
    }

    public function calculerSoldeRestant(): float
    {
        $totalDepenses = $this->CalculerTotalDepenses();
        $solde = $this->budgetInitial - $totalDepenses;
        return $solde;
    }

    public function getExpenses(): array
    {
        $sql = "SELECT * FROM expenses WHERE wallet_id = :id ORDER BY expense_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $this->walletId]);
        return $stmt->fetchAll();
    }

    public function getBudgetByUserId($userId)
    {
        $sql = "SELECT * FROM wallets WHERE user_id = :user_id AND month = :month ORDER BY created_at DESC LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':month' => date('Ym')
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getByUserMonth($userId, $month)
    {
        $sql = "SELECT * FROM wallets WHERE user_id = :user_id AND month = :month LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':month' => $month
        ]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function setBudget($userId, $month, $budget)
    {
        // Check if wallet exists for this user and month
        $existing = $this->getByUserMonth($userId, $month);
        
        if ($existing) {
            // Update existing wallet
            $sql = "UPDATE wallets SET budget = :budget WHERE user_id = :user_id AND month = :month";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':budget' => $budget,
                ':user_id' => $userId,
                ':month' => $month
            ]);
        } else {
            // Create new wallet
            $sql = "INSERT INTO wallets (user_id, month, budget) VALUES (:user_id, :month, :budget)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':user_id' => $userId,
                ':month' => $month,
                ':budget' => $budget
            ]);
        }
    }
}
?>