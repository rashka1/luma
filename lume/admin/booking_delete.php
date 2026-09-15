<?php
// cancel a booking (delete it)
// staff and admin can both cancel bookings

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// only from the cancel button (POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: bookings.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

$stmt = $conn->prepare("DELETE FROM bookings WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

set_flash('success', 'Booking cancelled.');
header('Location: bookings.php');
exit;
