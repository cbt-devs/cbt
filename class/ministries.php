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

    public function update($data_r = []) {
    try {
        if (empty($data_r)) {
            return ['status' => 'error', 'message' => 'No data provided for update'];
        }

        $id = $data_r['id'] ?? null;
        $name = $data_r['ministryName'] ?? null;
        $ageStart = $data_r['startAge'] ?? null;
        $ageEnd = $data_r['endAge'] ?? null;
        // Handle active status, default to 0 if not set or falsy
        $active = isset($data_r['active']) && ($data_r['active'] == 1 || $data_r['active'] === '1') ? 1 : 0;

        if (!$id || !$name) {
            return ['status' => 'error', 'message' => 'ID and Ministry Name are required'];
        }

        $sql = "UPDATE ministries SET name = :name, age_start = :age_start, age_end = :age_end, active = :active WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':name' => $name,
            ':age_start' => $ageStart,
            ':age_end' => $ageEnd,
            ':active' => $active,
            ':id' => $id
        ]);

        if ($stmt->rowCount() === 0) {
            return ['status' => 'error', 'message' => 'No ministry updated. Please check the ID.'];
        }

            return ['status' => 'success'];
        } catch (PDOException $e) {
            return ['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()];
        }
    }



    public function delete($id = 0) {
        if ($id <= 0) {
            return ['status' => 'error', 'message' => 'Invalid ID'];
        }

        try {
            $this->conn->beginTransaction();

            $sql = "UPDATE ministries SET active = 0 WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute([':id' => $id]);

            $this->conn->commit();

            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

}