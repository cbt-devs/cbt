<?php
require_once '../class/database.php';
require_once '../class/member.php';

$pdo = new Database();
$conn = $pdo->getConnection();

$member = new Member($conn);

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'show':
            $result = $member->showMember();
            echo json_encode([
                'status' => $result ? 'success' : 'error',
                'data' => $result
            ]);
            break;
            
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