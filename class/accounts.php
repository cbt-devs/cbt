<?php
class Accounts
{
    private $conn;

    public function __construct($db)
    {
        $this->conn = $db;
    }
}
