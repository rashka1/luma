<?php
// only logged in users can open admin pages

session_start();

// no cache (back button after logout)
header('Cache-Control: no-store');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// get the user again, the role can change
require_once __DIR__ . '/db.php';

$stmt = $conn->prepare("SELECT full_name, role FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$logged_in_user = $stmt->get_result()->fetch_assoc();

if ($logged_in_user == null) {
    session_destroy();
    header('Location: login.php');
    exit;
}

$_SESSION['user_name'] = $logged_in_user['full_name'];
$_SESSION['role'] = $logged_in_user['role'];
