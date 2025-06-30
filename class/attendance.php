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

            // Get all active members
            $stmt = $this->conn->prepare("SELECT id FROM accounts WHERE status = 'active'");
            $stmt->execute();
            $acc_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (empty($acc_r)) {
                $this->conn->commit();
                return [];
            }

            $ids = array_column($acc_r, 'id');
            $acc_r_txt = implode(',', $ids);

            // Get names
            $stmt = $this->conn->prepare("
            SELECT accounts_id, first_name, middle_name, last_name
            FROM accounts_info
            WHERE accounts_id IN ($acc_r_txt)
        ");
            $stmt->execute();
            $info_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Get latest attendance
            $stmt = $this->conn->prepare("
            SELECT a1.accounts_id, a1.type, a1.date
            FROM attendance a1
            INNER JOIN (
                SELECT accounts_id, MAX(date) AS max_date
                FROM attendance
                GROUP BY accounts_id
            ) a2 ON a1.accounts_id = a2.accounts_id AND a1.date = a2.max_date
            WHERE a1.accounts_id IN ($acc_r_txt)
        ");
            $stmt->execute();
            $att_r = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->conn->commit();

            // Combine data
            $data_r = [];
            foreach ($att_r as $att) {
                $id = $att['accounts_id'];
                $type = self::TYPE[$att['type']] ?? 'unknown';
                $date = $att['date'];

                $name = '';
                foreach ($info_r as $info) {
                    if ($info['accounts_id'] == $id) {
                        $name = trim($info['first_name'] . ' ' . $info['middle_name'] . ' ' . $info['last_name']);
                        break;
                    }
                }

                $data_r[] = [
                    'name' => $name,
                    'date_attendance' => $_origdate ? $date : date('M d, Y h:i A', strtotime($date)),
                    'type' => $type
                ];
            }

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
