<?php
require_once '../class/database.php';
require_once '../class/member.php';
require_once '../class/ministries.php';

$pdo = new Database();
$conn = $pdo->getConnection();

$member = new Member($conn);
$ministry = new Ministries($conn);

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';
$type = $_POST['type'] ?? '';

$handlers = [
    'members' => $member,
    'ministries' => $ministry,
];

if (!isset($handlers[$type])) {
    echo json_encode(['status' => 'error', 'message' => 'Invalid type']);
    exit;
}

$handler = $handlers[$type];

switch ($action) {
    case 'show':
        $result = $handler->show();
        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'data' => $result
        ]);
        break;

    case 'add':
        $result = $handler->add($_POST);
        echo json_encode([
            'status' => $result ? 'success' : 'error',
            'message' => $result
        ]);
        break;

    case 'update':
        if (isset($_POST['id'])) {
            $handler->update($_POST);
            echo json_encode(['status' => 'success']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID missing']);
        }
        break;

    case 'delete':
        if (isset($_POST['id'])) {
            $result = $handler->delete($_POST['id']);
            echo json_encode($result);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'ID missing']);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
        break;
}

exit;