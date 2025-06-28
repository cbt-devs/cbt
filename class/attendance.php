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

    public function show()
    {
        try {


            return ['working'];
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
