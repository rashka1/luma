<?php
// attendance: classes of one day

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';

$page_title = 'Attendance';

// the chosen day, or today
$date = $_GET['date'] ?? '';
if (v_date($date, 'Date') != '') {
    $date = date('Y-m-d');
}

$stmt = $conn->prepare("SELECT classes.*,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id) AS booked,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id AND bookings.status = 'attended') AS attended
    FROM classes
    WHERE class_date = ?
    ORDER BY start_time");
$stmt->bind_param("s", $date);
$stmt->execute();
$classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-3">Attendance</h1>

<form method="get" class="box mb-3">
    <label class="form-label">Choose a day</label>
    <div class="d-flex gap-2">
        <input type="date" name="date" class="form-control" value="<?php echo e($date); ?>">
        <button type="submit" class="btn btn-lume">Show</button>
    </div>
</form>

<h2 class="h4"><?php echo e(show_date($date)); ?></h2>

<div class="table-responsive table-wrap">
<table class="table table-hover mb-0">
    <tr>
        <th>Time</th>
        <th>Class</th>
        <th>Instructor</th>
        <th>Booked</th>
        <th>Attended</th>
        <th></th>
    </tr>
    <?php if (count($classes) == 0) { ?>
        <tr><td colspan="6">No classes on this day.</td></tr>
    <?php } ?>
    <?php foreach ($classes as $class) { ?>
        <tr>
            <td><?php echo e(show_time($class['start_time'])); ?></td>
            <td><?php echo e($class['class_name']); ?></td>
            <td><?php echo e($class['instructor']); ?></td>
            <td><?php echo e($class['booked']); ?></td>
            <td><?php echo e($class['attended']); ?></td>
            <td><a class="btn btn-sm btn-lume" href="check_in.php?class_id=<?php echo e($class['id']); ?>">Mark attendance</a></td>
        </tr>
    <?php } ?>
</table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
