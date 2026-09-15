<?php
// new booking

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'New booking';

$member_id = 0;
$class_id = (int) ($_GET['class_id'] ?? 0);
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_id = (int) ($_POST['member_id'] ?? 0);
    $class_id = (int) ($_POST['class_id'] ?? 0);

    if ($member_id == 0) {
        $error = 'Please choose a member.';
    } elseif ($class_id == 0) {
        $error = 'Please choose a class.';
    } else {
        // check the booking rules (functions.php)
        $error = can_book($conn, $member_id, $class_id);
    }

    if ($error == '') {
        try {
            $stmt = $conn->prepare("INSERT INTO bookings (member_id, class_id, status) VALUES (?, ?, 'booked')");
            $stmt->bind_param("ii", $member_id, $class_id);
            $stmt->execute();

            set_flash('success', 'Booking saved.');
            header('Location: bookings.php?class_id=' . $class_id);
            exit;
        } catch (mysqli_sql_exception $e) {
            // 1062 = already booked
            if ($e->getCode() == 1062) {
                $error = 'This member is already booked in this class.';
            } else {
                throw $e;
            }
        }
    }
}

// members for the dropdown
$result = $conn->query("SELECT id, full_name, phone, status FROM members ORDER BY full_name");
$members = $result->fetch_all(MYSQLI_ASSOC);

// classes from today for the dropdown
$today = date('Y-m-d');
$stmt = $conn->prepare("SELECT classes.*,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id) AS booked
    FROM classes
    WHERE class_date >= ?
    ORDER BY class_date, start_time");
$stmt->bind_param("s", $today);
$stmt->execute();
$classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-3">New booking</h1>

<?php if ($error != '') { ?>
    <div class="alert alert-danger"><?php echo e($error); ?></div>
<?php } ?>

<form method="post" class="box">
    <div class="mb-3">
        <label for="member_id" class="form-label">Member</label>
        <select name="member_id" id="member_id" class="form-select">
            <option value="">-- choose a member --</option>
            <?php foreach ($members as $member) { ?>
                <option value="<?php echo e($member['id']); ?>" <?php if ($member['id'] == $member_id) { echo 'selected'; } ?>>
                    <?php echo e($member['full_name']); ?> (<?php echo e($member['phone']); ?>)
                    <?php if ($member['status'] == 'inactive') { echo '- inactive'; } ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <div class="mb-3">
        <label for="class_id" class="form-label">Class</label>
        <select name="class_id" id="class_id" class="form-select">
            <option value="">-- choose a class --</option>
            <?php foreach ($classes as $class) { ?>
                <option value="<?php echo e($class['id']); ?>" <?php if ($class['id'] == $class_id) { echo 'selected'; } ?>>
                    <?php echo e(show_date($class['class_date'])); ?>,
                    <?php echo e(show_time($class['start_time'])); ?>,
                    <?php echo e($class['class_name']); ?>
                    (<?php echo e($class['booked']); ?> / <?php echo e($class['capacity']); ?>)
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- app.js writes the places left here -->
    <p id="places-left"></p>

    <button type="submit" id="save-booking" class="btn btn-lume">Save booking</button>
    <a href="bookings.php" class="btn btn-outline-secondary">Cancel</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
