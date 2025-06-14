<?php
// init.php
require_once __DIR__ . '/class/database.php';
require_once __DIR__ . '/class/member.php';
require_once __DIR__ . '/class/ministries.php';
require_once __DIR__ . '/class/commitments.php';

$pdo = new Database();
$conn = $pdo->getConnection();

$member = new Member($conn);
$ministry = new Ministries($conn);
$commitments = new Commitments($conn);

function generateTimeOptions() {
    $options = '<option value="">Select Time</option>';
    for ($hour = 0; $hour < 24; $hour++) {
        for ($minute = 0; $minute < 60; $minute += 30) {
            $time24 = sprintf('%02d:%02d', $hour, $minute);
            $time12 = date('g:i A', strtotime($time24));

            // Auto-select 08:00
            $selected = ($time24 === '08:00') ? ' selected' : '';

            $options .= "<option value=\"$time24\"$selected>$time12</option>";
        }
    }
    return $options;
}