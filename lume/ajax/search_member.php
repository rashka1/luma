<?php
// search members by name or phone (members page)

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    json_response(false, 'Please log in again.');
}

$search = trim($_POST['search'] ?? '');
$like = '%' . $search . '%';

$stmt = $conn->prepare("SELECT id, full_name, phone, email, status, join_date
    FROM members
    WHERE full_name LIKE ? OR phone LIKE ?
    ORDER BY full_name");
$stmt->bind_param("ss", $like, $like);
$stmt->execute();
$members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// change the date format
foreach ($members as $index => $member) {
    $members[$index]['join_date'] = show_date($member['join_date']);
}

json_response(true, count($members) . ' members found', $members);
