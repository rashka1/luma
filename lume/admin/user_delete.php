<?php
// delete a user (only the admin)

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_only.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// only from the delete button (POST)
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    header('Location: users.php');
    exit;
}

$id = (int) ($_POST['id'] ?? 0);

// can not delete yourself
if ($id == $_SESSION['user_id']) {
    set_flash('error', 'You can not delete your own account.');
} else {
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    set_flash('success', 'User deleted.');
}

header('Location: users.php');
exit;
