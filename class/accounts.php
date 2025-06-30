<?php
class Accounts
{
    private $conn;

    private static array $access_rights = [
        1 => 'admin',
        2 => 'leader',
        3 => 'member',
        4 => 'visitor'
    ];

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function check_access(int $accounts_id): bool
    {
        if ($accounts_id <= 0) {
            return false;
        }

        $stmt = $this->conn->prepare("SELECT role_id FROM accounts_access WHERE accounts_id = ?");
        $stmt->execute([$accounts_id]);

        $role_id = $stmt->fetchColumn();

        if (!$role_id) {
            return false;
        }

        return self::$access_rights[(int)$role_id] === 'admin';
    }

    public static function getRoleName(int $role_id): ?string
    {
        return self::$access_rights[$role_id] ?? null;
    }
}
