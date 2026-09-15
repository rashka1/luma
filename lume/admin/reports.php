<?php
// reports page

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';

$page_title = 'Reports';

// dates from the form, or this month
$from = $_GET['from'] ?? '';
$to = $_GET['to'] ?? '';

if (v_date($from, 'From') != '') {
    $from = date('Y-m-01');
}
if (v_date($to, 'To') != '') {
    $to = date('Y-m-d');
}

// report 1: classes
$stmt = $conn->prepare("SELECT classes.*,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id) AS booked,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id AND bookings.status = 'attended') AS attended,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id AND bookings.status = 'absent') AS absent
    FROM classes
    WHERE class_date >= ? AND class_date <= ?
    ORDER BY class_date, start_time");
$stmt->bind_param("ss", $from, $to);
$stmt->execute();
$classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// totals for report 1
$total_booked = 0;
$total_attended = 0;
$total_absent = 0;

foreach ($classes as $class) {
    $total_booked = $total_booked + $class['booked'];
    $total_attended = $total_attended + $class['attended'];
    $total_absent = $total_absent + $class['absent'];
}

$attendance_percent = 0;
if ($total_booked > 0) {
    $attendance_percent = round($total_attended / $total_booked * 100);
}

// report 2: members
$stmt = $conn->prepare("SELECT members.full_name, members.phone, members.status,
        (SELECT COUNT(*) FROM bookings JOIN classes ON classes.id = bookings.class_id
         WHERE bookings.member_id = members.id AND classes.class_date >= ? AND classes.class_date <= ?) AS booked,
        (SELECT COUNT(*) FROM bookings JOIN classes ON classes.id = bookings.class_id
         WHERE bookings.member_id = members.id AND bookings.status = 'attended'
         AND classes.class_date >= ? AND classes.class_date <= ?) AS attended
    FROM members
    ORDER BY booked DESC, full_name");
$stmt->bind_param("ssss", $from, $to, $from, $to);
$stmt->execute();
$members = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1>Reports</h1>
    <button type="button" class="btn btn-outline-lume no-print" onclick="window.print()">Print</button>
</div>

<form method="get" class="box mb-4 no-print">
    <div class="row g-2 align-items-end">
        <div class="col-sm-4">
            <label class="form-label">From</label>
            <input type="date" name="from" class="form-control" value="<?php echo e($from); ?>">
        </div>
        <div class="col-sm-4">
            <label class="form-label">To</label>
            <input type="date" name="to" class="form-control" value="<?php echo e($to); ?>">
        </div>
        <div class="col-sm-4">
            <button type="submit" class="btn btn-lume w-100">Show</button>
        </div>
    </div>
</form>

<p>From <?php echo e(show_date($from)); ?> to <?php echo e(show_date($to)); ?></p>

<h2 class="h4">Classes</h2>

<div class="table-responsive table-wrap mb-4">
<table class="table table-hover mb-0">
    <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Class</th>
        <th>Instructor</th>
        <th>Booked</th>
        <th>Attended</th>
        <th>Absent</th>
    </tr>
    <?php if (count($classes) == 0) { ?>
        <tr><td colspan="7">No classes between these dates.</td></tr>
    <?php } ?>
    <?php foreach ($classes as $class) { ?>
        <tr>
            <td><?php echo e(show_date($class['class_date'])); ?></td>
            <td><?php echo e(show_time($class['start_time'])); ?></td>
            <td><?php echo e($class['class_name']); ?></td>
            <td><?php echo e($class['instructor']); ?></td>
            <td><?php echo e($class['booked']); ?></td>
            <td><?php echo e($class['attended']); ?></td>
            <td><?php echo e($class['absent']); ?></td>
        </tr>
    <?php } ?>
    <tr>
        <th colspan="4">Total (<?php echo count($classes); ?> classes)</th>
        <th><?php echo e($total_booked); ?></th>
        <th><?php echo e($total_attended); ?></th>
        <th><?php echo e($total_absent); ?></th>
    </tr>
</table>
</div>

<p>Attendance: <strong><?php echo e($attendance_percent); ?>%</strong> of the bookings came to class.</p>

<h2 class="h4 mt-4">Members</h2>

<div class="table-responsive table-wrap">
<table class="table table-hover mb-0">
    <tr>
        <th>Name</th>
        <th>Phone</th>
        <th>Status</th>
        <th>Bookings</th>
        <th>Attended</th>
    </tr>
    <?php foreach ($members as $member) { ?>
        <tr>
            <td><?php echo e($member['full_name']); ?></td>
            <td><?php echo e($member['phone']); ?></td>
            <td><?php echo e($member['status']); ?></td>
            <td><?php echo e($member['booked']); ?></td>
            <td><?php echo e($member['attended']); ?></td>
        </tr>
    <?php } ?>
</table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
