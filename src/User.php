<?php
namespace App;
use PDO;
class User 
{
    private $db;
    public $username;
    public $email;
    public $password;

    public function __construct($db)
    {
        $this -> db = ($db instanceof Database) ? $db -> getConnection() : $db;
    }
    public function register()
    {
        $hashedPassword = password_hash($this->password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO users(name, email, password) VALUES (:name, :email, :pass)";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':name'  => $this->username,
            ':email' => $this->email,
            ':pass'  => $hashedPassword
        ]);
    }
    public function login($inputPassword)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        $stmt = $this -> db -> prepare($sql);
        $stmt -> execute([':email' => $this->username]);
        $user = $stmt -> fetch(PDO::FETCH_ASSOC);

        if($user && password_verify($inputPassword,$user['password']))
        {
            if(session_status() === PHP_SESSION_NONE) session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            return true;
        }
        return false;
    }
    
    public function getById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function updateProfile($id, $name, $email)
    {
        $sql = "UPDATE users SET name = :name, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':name' => $name,
            ':email' => $email
        ]);
    }
    
    public function changePassword($id, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':id' => $id,
            ':password' => $hashedPassword
        ]);
    }
}
?>