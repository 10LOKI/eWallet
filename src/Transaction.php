<?php
namespace App;
abstract class Transaction
{
    protected $db;
    protected $user_id;
    protected $wallet_id;
    protected $category_id;
    protected $title;
    protected $amount;
    protected $expense_date;
    protected $is_automatic;
    protected $automatic_id;

    public function __construct($db)
    {
        $this -> db = $db;
    }
    public function setAmount($value)
    {
        if($value <= 0)
        {
            throw new \Exception("il faut avoir un montant valide");
        }
        $this -> amount = $value;
    }
    abstract public function save();
}

?>