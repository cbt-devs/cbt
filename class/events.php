<?php
class Events {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }
}