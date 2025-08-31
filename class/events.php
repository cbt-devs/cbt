<?php

require_once __DIR__ . '/ministries.php';

class Events
{
    private $conn;
    private $ministries;

    public function __construct($db)
    {
        $this->conn = $db;

        $this->ministries = new Ministries($db);
    }

    public function show($_data_r = [])
    {
        $ministry_name_r = [];
        $acc_ministries_r = [];
        $orig_date = $_data_r['orig_date'] ?? 0;

        // Step 1: Check if we have accounts_id
        if ($accounts_id = ($_data_r['accounts_id'] ?? 0)) {
            $acc_ministries_r = $this->ministries->account_ministries($accounts_id);

            // Get full ministry data (optional, if you want names later)
            $ministry_name_r = $this->ministries->show(['ministries_r' => $acc_ministries_r]);
        }

        // Step 2: Build base query
        $sql = "SELECT * FROM events";
        $params = [];

        // If account has ministries, filter events
        if (!empty($acc_ministries_r)) {
            // Add WHERE clause: either event is "all" OR it intersects with account's ministries
            $conditions = [];
            foreach ($acc_ministries_r as $mid) {
                // Use FIND_IN_SET since your column is stored as "2,4"
                $conditions[] = "FIND_IN_SET(?, events.ministries)";
                $params[] = $mid;
            }
            $sql .= " WHERE events.ministries = 'all' OR (" . implode(" OR ", $conditions) . ")";
        }

        $sql .= " ORDER BY start_date DESC";

        $stmt = $this->conn->prepare($sql);

        if ($stmt->execute($params)) {
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $result_r = [];

            foreach ($res as $data) {
                $ministry = 'all'; // Default value

                if ($data['ministries'] != 'all') {
                    $ministry_ids = explode(',', $data['ministries']);
                    $placeholders = str_repeat('?,', count($ministry_ids) - 1) . '?';

                    $stmt2 = $this->conn->prepare("SELECT name FROM ministries WHERE id IN ($placeholders)");
                    if ($stmt2->execute($ministry_ids)) {
                        $ministry_names = $stmt2->fetchAll(PDO::FETCH_COLUMN);
                        $ministry = implode(', ', $ministry_names);
                    }
                }

                $result_r[] = [
                    'id' => $data['id'],
                    'event_name' => $data['event_name'],
                    'event_location' => $data['event_location'],
                    'start_date' => !$orig_date ? date('M d, Y', strtotime($data['start_date'])) : $data['start_date'],
                    'end_date' => !$orig_date ? date('M d, Y', strtotime($data['end_date'])) : $data['end_date'],
                    'ministries' => $ministry,
                ];
            }
            return $result_r;
        } else {
            return [];
        }
    }

    public function add($data_r)
    {
        $ministry_r = $data_r['ministry'] ?? 0;
        $ministries = empty($ministry_r) ? 'all' : implode(',', $ministry_r);

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

    public function delete($data_r)
    {
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
