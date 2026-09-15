<?php
// delete a class and its bookings

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_only.php'; // staff can not delete
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// only from the delete button (POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: classes.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM classes WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

set_flash('success', 'Class deleted.');
header('Location: classes.php');
exit;
