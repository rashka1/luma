<?php
// mark who came to a class
// the buttons save with jquery (app.js)

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Mark attendance';
$class_id = (int) ($_GET['class_id'] ?? 0);

// get the class
$stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$class = $stmt->get_result()->fetch_assoc();

if ($class == null) {
    set_flash('error', 'Class not found.');
    header('Location: attendance.php');
    exit;
}

// people booked in this class
$stmt = $conn->prepare("SELECT bookings.id, bookings.status, members.full_name, members.phone
    FROM bookings
    JOIN members ON members.id = bookings.member_id
    WHERE bookings.class_id = ?
    ORDER BY members.full_name");
$stmt->bind_param("i", $class_id);
$stmt->execute();
$bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// count who attended
$attended = 0;
foreach ($bookings as $booking) {
    if ($booking['status'] == 'attended') {
        $attended = $attended + 1;
    }
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1><?php echo e($class['class_name']); ?></h1>
<p>
    <?php echo e(show_date($class['class_date'])); ?> at <?php echo e(show_time($class['start_time'])); ?>,
    with <?php echo e($class['instructor']); ?>
</p>

<p class="h4" id="counter"><?php echo e($attended); ?> of <?php echo e(count($bookings)); ?> arrived</p>

<div class="table-responsive table-wrap">
<table class="table table-hover mb-0">
    <tr>
        <th>Member</th>
        <th>Phone</th>
        <th>Present or absent?</th>
    </tr>
    <?php if (count($bookings) == 0) { ?>
        <tr><td colspan="3">Nobody is booked into this class.</td></tr>
    <?php } ?>
    <?php foreach ($bookings as $booking) { ?>
        <?php
        // chosen button is full color
        $present_class = 'btn-outline-success';
        $absent_class = 'btn-outline-danger';
        if ($booking['status'] == 'attended') {
            $present_class = 'btn-success';
        }
        if ($booking['status'] == 'absent') {
            $absent_class = 'btn-danger';
        }
        ?>
        <tr>
            <td><?php echo e($booking['full_name']); ?></td>
            <td><?php echo e($booking['phone']); ?></td>
            <td>
                <button type="button" class="btn <?php echo $present_class; ?> mark-button present-button"
                        data-booking="<?php echo e($booking['id']); ?>" data-status="attended">Present</button>
                <button type="button" class="btn <?php echo $absent_class; ?> mark-button absent-button"
                        data-booking="<?php echo e($booking['id']); ?>" data-status="absent">Absent</button>
            </td>
        </tr>
    <?php } ?>
</table>
</div>

<a href="attendance.php?date=<?php echo e($class['class_date']); ?>">Back to attendance</a>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
