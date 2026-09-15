<?php
// functions

require_once __DIR__ . '/../config/config.php';

// safe text for html
function e($text) {
    return htmlspecialchars((string) $text, ENT_QUOTES, 'UTF-8');
}

// 2026-09-15 -> 15 Sep 2026
function show_date($date) {
    return date(DATE_FORMAT, strtotime($date));
}

// 18:30:00 -> 06:30 PM
function show_time($time) {
    return date(TIME_FORMAT, strtotime($time));
}

// message for the next page
function set_flash($type, $message) {
    $_SESSION['flash_type'] = $type;
    $_SESSION['flash_message'] = $message;
}

// is the user admin?
function is_admin() {
    if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin') {
        return true;
    }
    return false;
}

// json answer for jquery
function json_response($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode(array(
        'success' => $success,
        'message' => $message,
        'data' => $data
    ));
    exit;
}

// how many bookings a class has
function booked_count($conn, $class_id) {
    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM bookings WHERE class_id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();

    return (int) $row['total'];
}

// how many free places a class has
function places_left($conn, $class_id) {
    $stmt = $conn->prepare("SELECT capacity FROM classes WHERE id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $class = $stmt->get_result()->fetch_assoc();

    if ($class == null) {
        return 0;
    }

    return $class['capacity'] - booked_count($conn, $class_id);
}

// can this member book this class?
// returns '' if yes, or the reason if no
function can_book($conn, $member_id, $class_id) {
    // 1. member must exist
    $stmt = $conn->prepare("SELECT status FROM members WHERE id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();
    $member = $stmt->get_result()->fetch_assoc();

    if ($member == null) {
        return 'That member was not found.';
    }

    // 2. member must be active
    if ($member['status'] != 'active') {
        return 'This member is inactive and cannot be booked.';
    }

    // 3. class must exist
    $stmt = $conn->prepare("SELECT class_date, start_time FROM classes WHERE id = ?");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $class = $stmt->get_result()->fetch_assoc();

    if ($class == null) {
        return 'That class was not found.';
    }

    // 4. class must not have started
    $class_start = strtotime($class['class_date'] . ' ' . $class['start_time']);
    if ($class_start <= time()) {
        return 'That class has already started.';
    }

    // 5. member must not be booked already
    $stmt = $conn->prepare("SELECT id FROM bookings WHERE member_id = ? AND class_id = ?");
    $stmt->bind_param("ii", $member_id, $class_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        return 'This member is already booked in this class.';
    }

    // 6. class must not be full
    if (places_left($conn, $class_id) <= 0) {
        return 'This class is full.';
    }

    return '';
}

// does the member have bookings?
function member_has_bookings($conn, $member_id) {
    $stmt = $conn->prepare("SELECT id FROM bookings WHERE member_id = ?");
    $stmt->bind_param("i", $member_id);
    $stmt->execute();

    return $stmt->get_result()->num_rows > 0;
}
