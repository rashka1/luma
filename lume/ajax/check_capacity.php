<?php
// places left in a class (new booking page)

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    json_response(false, 'Please log in again.');
}

$class_id = (int) ($_POST['class_id'] ?? 0);
$member_id = (int) ($_POST['member_id'] ?? 0);

$places = places_left($conn, $class_id);

if ($member_id > 0) {
    // member chosen, check the booking rules
    $problem = can_book($conn, $member_id, $class_id);
} else {
    $problem = '';
    if ($places <= 0) {
        $problem = 'This class is full.';
    }
}

if ($problem != '') {
    json_response(true, $problem, array('can_book' => false, 'places_left' => $places));
}

json_response(true, $places . ' places left', array('can_book' => true, 'places_left' => $places));
