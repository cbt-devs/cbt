<?php
class Booklet {
    private $conn;

    private static array $type = [
        1 => 'weekly',
        2 => 'funeral',
        3 => 'event',
    ];

    public function __construct($db) {
        $this->conn = $db;
    }

    /*
    * Fetches all booklet records from the database, ordered by date in descending order.
    * Each row is returned as an associative array.
    */
    public function show() {
        $stmt = $this->conn->prepare("SELECT * FROM booklet ORDER BY date DESC");
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    /* 
    * Fetches the latest booklet entry (one row).
    */
    public function get_latest() {
        $stmt = $this->conn->prepare("SELECT * FROM booklet ORDER BY date DESC LIMIT 1");
        if ($stmt->execute()) {
            return $stmt->fetch(PDO::FETCH_NUM);
        }

        return [];
    }
}