<?php
// init.php
require_once __DIR__ . '/class/database.php';
require_once __DIR__ . '/class/member.php';
require_once __DIR__ . '/class/ministries.php';

$pdo = new Database();
$conn = $pdo->getConnection();

$ministry = new Ministries($conn);