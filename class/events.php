<?php
class Events {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function show() {
        $stmt = $this->conn->prepare("SELECT * FROM events ORDER BY start_date DESC");

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result_r = [];
            
            foreach($res as $data) {
                $ministry = 'all'; // Default value
                
                if($data['ministries'] != 'all') {
                    // Fixed: removed extra $ sign
                    $ministry_ids = explode(',', $data['ministries']);
                    
                    // Create placeholders for IN clause
                    $placeholders = str_repeat('?,', count($ministry_ids) - 1) . '?';
                    
                    // Fixed: proper SQL query with WHERE clause and placeholders
                    $stmt2 = $this->conn->prepare("SELECT name FROM ministries WHERE id IN ($placeholders)");
                    
                    if($stmt2->execute($ministry_ids)) {
                        // Fixed: use fetchAll and extract names properly
                        $ministry_names = $stmt2->fetchAll(PDO::FETCH_COLUMN);
                        $ministry = implode(', ', $ministry_names);
                    }
                }

                // Fixed: use [] to add to array, not reassign
                $result_r[] = [
                    'id' => $data['id'],
                    'event_name' => $data['event_name'],
                    'event_location' => $data['event_location'],
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'ministries' => $ministry,
                ];
            }
            return $result_r;
        } else {
            return [];
        }
    }

    public function add($data_r) {
        $ministry_r = $data_r['ministry'] ?? 0;
        $ministries = empty( $ministry_r ) ? 'all' : implode( ',', $ministry_r );

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