<?php
// delete a member

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_only.php'; // staff can not delete
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// only from the delete button (POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: members.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

// a member with bookings cannot be deleted
if (member_has_bookings($conn, $id)) {
    set_flash('error', 'This member has bookings and cannot be deleted. Set the status to Inactive instead.');
} else {
    $stmt = $conn->prepare("DELETE FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    set_flash('success', 'Member deleted.');
}

header('Location: members.php');
exit;
