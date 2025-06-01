<?php
require_once '../class/database.php'; // or wherever your DB connection is

$pdo = new Database();      // if Database is your DB class
$conn = $pdo->getConnection(); // or whatever method returns the PDO instance

require_once '../class/member.php';
$member = new Member($conn);

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';


switch ($action) {
    case 'add':
        $result = $member->addMember($_POST);
        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result
        ]);
        break;

    case 'delete':
        // $member->deleteMember($_POST['id']);
        break;

    // add more cases like 'update', 'get', etc.

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}