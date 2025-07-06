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

    public function login($data_r = [])
    {
        $email = $data_r['email'] ?? '';
        $pass = $data_r['pass'] ?? '';

        if (!$email || !$pass) {
            return ['status' => 'error', 'message' => 'Missing email or password'];
        }

        $stmt = $this->conn->prepare("
        SELECT a.id, a.email, a.pass, ai.first_name, ai.last_name
        FROM accounts a
        LEFT JOIN accounts_info ai ON a.id = ai.accounts_id
        WHERE a.email = ?
    ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return ['status' => 'error', 'message' => 'Account not found'];
        }

        if (!password_verify($pass, $user['pass'])) {
            return ['status' => 'error', 'message' => 'Invalid password'];
        }

        // Get role_id from accounts_access
        $roleStmt = $this->conn->prepare("SELECT role_id FROM accounts_access WHERE accounts_id = ?");
        $roleStmt->execute([$user['id']]);
        $role_id = (int) $roleStmt->fetchColumn();

        if (!in_array($role_id, [1, 2])) { // Not admin or leader
            return ['status' => 'error', 'message' => 'Access Denied'];
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['accounts_id'] = $user['id'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['name'] = trim(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? ''));
        $_SESSION['role_id'] = $role_id;
        $_SESSION['role_name'] = self::getRoleName($role_id);

        return ['status' => 'success', 'message' => 'Login successful'];
    }

    public function logout(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();

        return ['status' => 'success', 'message' => 'Logged out successfully'];
    }
}
