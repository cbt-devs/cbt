<?php
class Accounts
{
    private $conn;

    private static array $access_rights = [
        1 => 'admin',
        2 => 'leader',
        3 => 'member',
        4 => 'visitor'
    ];

    public function __construct($db)
    {
        $this->conn = $db;
    }
}
