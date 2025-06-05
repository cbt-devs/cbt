<?php
class Ministries {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function show() {
    try {
        $stmt = $this->conn->prepare("SELECT * FROM ministries ORDER BY name ASC");
        $stmt->execute();
        $ministries = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $ministries;
    } catch (PDOException $e) {
        return [
            'status' => 'error',
            'message' => 'Failed to fetch ministries: ' . $e->getMessage()
        ];
    }
}


    public function add($data) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO ministries (name, age_start, age_end)
                VALUES (:name, :age_start, :age_end)
            ");
            $stmt->execute([
                ':name' => $data['ministryName'],
                ':age_start' => $data['startAge'],
                ':age_end' => $data['endAge']
            ]);

            $this->conn->commit();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            error_log('Ministry insert failed: ' . $e->getMessage());
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}