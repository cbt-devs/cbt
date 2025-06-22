<?php
class Commitments {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function show( $table_name = '' ) {
        try {
            if( $table_name ) {
                return Commitments::type_show();
            }

            $this->conn->beginTransaction();

            // Fetch all commitments
            $stmt = $this->conn->prepare("SELECT * FROM commitments ORDER BY start_date DESC");
            $stmt->execute();
            $commitments = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if( empty( $commitments ))
                return false;

            // Extract unique account and type IDs
            $account_ids = array_unique(array_column($commitments, 'accounts_id'));
            $type_ids = array_unique(array_column($commitments, 'type_id'));

            $acc_ids_str = implode(',', array_map('intval', $account_ids));
            $type_ids_str = implode(',', array_map('intval', $type_ids));

            // Fetch account info
            $stmt = $this->conn->prepare("SELECT accounts_id, first_name, middle_name, last_name FROM accounts_info WHERE accounts_id IN ($acc_ids_str)");
            $stmt->execute();
            $acc_info = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Fetch commitment types
            $stmt = $this->conn->prepare("SELECT id, name FROM commitments_type WHERE id IN ($type_ids_str)");
            $stmt->execute();
            $types = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->conn->commit();

            // Index data for quick lookup
            $acc_info_map = [];
            foreach ($acc_info as $info) {
                $acc_info_map[$info['accounts_id']] = "{$info['first_name']} {$info['middle_name']} {$info['last_name']}";
            }

            $types_map = array_column($types, 'name', 'id');

            $result = [];
            foreach ($commitments as $c) {
                $start = trim(($c['start_date'] ?? '') . ' ' . ($c['start_time'] ?? ''));
                $end = trim(($c['end_date'] ?? '') . ' ' . ($c['end_time'] ?? ''));

                $result[] = [
                    'id' => $c['id'],
                    'member' => $acc_info_map[$c['accounts_id']] ?? 'Unknown',
                    'type' => $types_map[$c['type_id']] ?? 'Unknown',
                    'amount' => $c['amount'],
                    'start' => date('M d, Y', strtotime($start)),
                    'end' => date('M d, Y', strtotime($end))
                ];
            }

            return $result;

        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    public function type_show() {
        $stmt = $this->conn->prepare("SELECT * FROM commitments_type ORDER BY name ASC");
        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function add($data_r) {
        $startDateTime = $data_r['eventDate'] . ' ' . $data_r['eventTime'] . ':00';
        $endDateTime = $data_r['eventEndDate'] . ' ' . $data_r['eventEndTime'] . ':00';

        // Extract data
        $account_id = $data_r['member'] ?? null;
        $type_id = $data_r['commitment'][0] ?? null; // First selected commitment type
        $amount = $data_r['amount'] ?? 0;

        $sql = "INSERT INTO commitments (accounts_id, type_id, amount, start_date, end_date)
                VALUES (:accounts_id, :type_id, :amount, :start_date, :end_date)";

        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':accounts_id', $account_id, PDO::PARAM_INT);
        $stmt->bindValue(':type_id', $type_id, PDO::PARAM_INT);
        $stmt->bindValue(':amount', $amount, PDO::PARAM_STR); // use string to support decimal in the future
        $stmt->bindValue(':start_date', $startDateTime);
        $stmt->bindValue(':end_date', $endDateTime);

        $this->conn->beginTransaction();

        try {
            if (!$stmt->execute()) {
                $this->conn->rollBack();
                return [
                    'status' => 'error',
                    'message' => 'Failed to insert commitment.',
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

            $stmt = $this->conn->prepare("DELETE FROM commitments WHERE id = :id");
            $stmt->execute([':id' => $data_r['id']]);

            $this->conn->commit();
            return ['status' => 'success'];
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }
}