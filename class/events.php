<?php
class Events {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function show() {
        $stmt = $this->conn->prepare("SELECT * FROM events ORDER BY start_date DESC");

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function add($data_r) {
    // Convert ministries array to comma-separated string
    $ministries = isset($data_r['ministries']) && is_array($data_r['ministries']) && count($data_r['ministries']) > 0
    ? implode(',', $data_r['ministries'])
    : 'all';

    // Combine date + time for start and end
    $startDateTime = $data_r['eventDate'] . ' ' . $data_r['eventTime'] . ':00';
    $endDateTime = $data_r['eventEndDate'] . ' ' . $data_r['eventEndTime'] . ':00';

    $sql = "INSERT INTO events (event_name, event_location, start_date, end_date, ministries)
            VALUES (:event_name, :event_location, :start_date, :end_date, :ministries)";
    
    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(':event_name', $data_r['eventName']);
    $stmt->bindValue(':event_location', $data_r['place']);
    $stmt->bindValue(':start_date', $startDateTime);
    $stmt->bindValue(':end_date', $endDateTime);
    $stmt->bindValue(':ministries', $ministries);

    $this->conn->beginTransaction();

        try {
             if (!$stmt->execute()) {
                $this->conn->rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Failed to execute SQL statement.',
                    'errorInfo' => $stmt->errorInfo()
                ];
            }

            $this->conn->commit();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    public function delete($data_r) {
        try {
            $this->conn->beginTransaction();

            $query = "DELETE FROM events WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $data_r['id']]);

            $this->conn->commit();
            return ['status' => 'success'];
        } catch (Exception $e) {
            $this->conn->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

}