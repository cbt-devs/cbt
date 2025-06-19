<?php
class Logs {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function show($_limit = 0) {
        try {
            $sql = "SELECT * FROM logs ORDER BY id DESC";
            if ($_limit > 0) {
                $sql .= " LIMIT :limit";
            }

            $stmt = $this->conn->prepare($sql);

            if ($_limit > 0) {
                $stmt->bindValue(':limit', (int)$_limit, PDO::PARAM_INT);
            }

            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            return [
                'status' => 'error',
                'message' => 'Failed to fetch logs: ' . $e->getMessage()
            ];
        }
    }

    public function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
        
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
        
        $string = [
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hr',
            'i' => 'min',
            's' => 'sec',
        ];
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}