<?php
class Attendance
{
    private $conn;

    const TYPE = [
        1 => 'present',
        2 => 'absent',
        3 => 'excused'
    ];

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function show($_origdate = false)
    {
        try {
            $this->conn->beginTransaction();

            $startDate = $_POST['start_date'] ?? null;
            $endDate = $_POST['end_date'] ?? null;

            $query = "
            SELECT 
                att.id,
                att.accounts_id,
                att.type,
                att.date,
                ai.first_name,
                ai.middle_name,
                ai.last_name
            FROM attendance att
            INNER JOIN accounts a ON a.id = att.accounts_id
            LEFT JOIN accounts_info ai ON ai.accounts_id = att.accounts_id
            WHERE a.status = 'active'
        ";

            $params = [];

            if ($startDate && $endDate) {
                $query .= " AND att.date BETWEEN :start_date AND :end_date";
                $params[':start_date'] = $startDate . ' 00:00:00';
                $params[':end_date'] = $endDate . ' 23:59:59';
            }

            $query .= " ORDER BY att.date DESC";

            $stmt = $this->conn->prepare($query);
            $stmt->execute($params);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->conn->commit();

            $data_r = [];
            foreach ($rows as $row) {
                $id = $row['id'];
                $name = trim($row['first_name'] . ' ' . $row['middle_name'] . ' ' . $row['last_name']);
                $type = self::TYPE[(int)$row['type']] ?? 'unknown';
                $date = $_origdate ? $row['date'] : date('M d, Y h:i A', strtotime($row['date']));

                $data_r[] = [
                    'id' => $id,
                    'name' => $name,
                    'date' => $date,
                    'raw_date' => $row['date'],
                    'type' => $type
                ];
            }

            usort($data_r, function ($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });

            return $data_r;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'status' => 'error',
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }

    public function add($data_r = [])
    {
        if (empty($data_r)) {
            return false;
        }

        $type = (int) $data_r['attendance_type'];
        $members = $data_r['members'];
        $description = $data_r['description'] ?? '';
        $date = $data_r['date'] ?? date('Y-m-d H:i:s');

        try {
            $this->conn->beginTransaction();

            $stmt = $this->conn->prepare("
                INSERT INTO attendance (accounts_id, type, description, date)
                VALUES (:accounts_id, :type, :description, :date)
            ");

            foreach ($members as $member_id) {
                $stmt->execute([
                    ':accounts_id' => $member_id,
                    ':type' => $type,
                    ':description' => $description,
                    ':date' => $date
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (PDOException $e) {
            $this->conn->rollBack();
            return [
                'success' => false,
                'message' => 'Database error: ' . $e->getMessage()
            ];
        }
    }
}
