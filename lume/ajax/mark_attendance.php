<?php
// save present / absent (attendance page)

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    json_response(false, 'Please log in again.');
}

if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    json_response(false, 'Wrong request.');
}

$booking_id = (int) ($_POST['booking_id'] ?? 0);
$status = $_POST['status'] ?? '';

if ($status != 'attended' && $status != 'absent') {
    json_response(false, 'Please choose Present or Absent.');
}

// find the booking
$stmt = $conn->prepare("SELECT class_id FROM bookings WHERE id = ?");
$stmt->bind_param("i", $booking_id);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if ($booking == null) {
    json_response(false, 'That booking was not found.');
}

// save
$stmt = $conn->prepare("UPDATE bookings SET status = ? WHERE id = ?");
$stmt->bind_param("si", $status, $booking_id);
$stmt->execute();

// count again for the page
$class_id = $booking['class_id'];

$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE class_id = ? AND status = 'attended'");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$attended = $row['total'];

$total = booked_count($conn, $class_id);

json_response(true, 'Saved.', array('attended' => $attended, 'total' => $total));
