<?php
// dashboard

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Dashboard';
$today = date('Y-m-d');
$week_end = date('Y-m-d', strtotime('+6 days'));

// active members
$result = $conn->query("SELECT COUNT(*) AS total FROM members WHERE status = 'active'");
$row = $result->fetch_assoc();
$active_members = $row['total'];

// classes today
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM classes WHERE class_date = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$classes_today = $row['total'];

// bookings today
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM bookings
    JOIN classes ON classes.id = bookings.class_id
    WHERE classes.class_date = ?");
$stmt->bind_param("s", $today);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$bookings_today = $row['total'];

// classes this week
$stmt = $conn->prepare("SELECT COUNT(*) AS total FROM classes WHERE class_date >= ? AND class_date <= ?");
$stmt->bind_param("ss", $today, $week_end);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
$classes_week = $row['total'];

// today's classes
$stmt = $conn->prepare("SELECT classes.*,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id) AS booked
    FROM classes
    WHERE class_date = ?
    ORDER BY start_time");
$stmt->bind_param("s", $today);
$stmt->execute();
$todays_classes = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// last 7 days: bookings and attended for each day
$last_7_days = array();

for ($i = 0; $i < 7; $i++) {
    $day = date('Y-m-d', strtotime('-' . $i . ' days'));

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM bookings
        JOIN classes ON classes.id = bookings.class_id
        WHERE classes.class_date = ?");
    $stmt->bind_param("s", $day);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $booked = $row['total'];

    $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM bookings
        JOIN classes ON classes.id = bookings.class_id
        WHERE classes.class_date = ? AND bookings.status = 'attended'");
    $stmt->bind_param("s", $day);
    $stmt->execute();
    $row = $stmt->get_result()->fetch_assoc();
    $attended = $row['total'];

    // attendance %
    $percent = 0;
    if ($booked > 0) {
        $percent = round($attended / $booked * 100);
    }

    $last_7_days[] = array('day' => $day, 'booked' => $booked, 'attended' => $attended, 'percent' => $percent);
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="mb-0">Dashboard</h1>
        <div class="text-muted-lume">Welcome back, <?php echo e($_SESSION['user_name']); ?>. Today is <?php echo e(date('l')); ?>, <?php echo e(show_date($today)); ?>.</div>
    </div>
    <div class="d-flex gap-2">
        <a href="booking_form.php" class="btn btn-accent">+ New booking</a>
        <a href="member_form.php" class="btn btn-lume">+ Add member</a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Active members</div>
            <div class="big-number"><?php echo e($active_members); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Classes today</div>
            <div class="big-number"><?php echo e($classes_today); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card">
            <div class="stat-label">Bookings today</div>
            <div class="big-number"><?php echo e($bookings_today); ?></div>
        </div>
    </div>
    <div class="col-6 col-lg-3">
        <div class="stat-card stat-accent">
            <div class="stat-label">Classes this week</div>
            <div class="big-number"><?php echo e($classes_week); ?></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xxl-7">
        <h2 class="h3">Today's classes</h2>
        <div class="table-responsive table-wrap">
        <table class="table table-hover mb-0">
            <tr>
                <th>Time</th>
                <th>Class</th>
                <th>Instructor</th>
                <th>Booked</th>
                <th></th>
            </tr>
            <?php if (count($todays_classes) == 0) { ?>
                <tr><td colspan="5">No classes today.</td></tr>
            <?php } ?>
            <?php foreach ($todays_classes as $class) { ?>
                <tr>
                    <td><?php echo e(show_time($class['start_time'])); ?></td>
                    <td><?php echo e($class['class_name']); ?></td>
                    <td><?php echo e($class['instructor']); ?></td>
                    <td><?php echo e($class['booked']); ?> / <?php echo e($class['capacity']); ?></td>
                    <td><a class="btn btn-sm btn-lume" href="check_in.php?class_id=<?php echo e($class['id']); ?>">Attendance</a></td>
                </tr>
            <?php } ?>
        </table>
        </div>
    </div>

    <div class="col-xxl-5">
        <h2 class="h3">Last 7 days</h2>
        <div class="table-responsive table-wrap">
        <table class="table table-hover mb-0">
            <tr>
                <th>Date</th>
                <th>Bookings</th>
                <th>Attended</th>
                <th>Attendance</th>
            </tr>
            <?php foreach ($last_7_days as $row) { ?>
                <tr>
                    <td><?php echo e(date('D', strtotime($row['day']))); ?> <?php echo e(show_date($row['day'])); ?></td>
                    <td><?php echo e($row['booked']); ?></td>
                    <td><?php echo e($row['attended']); ?></td>
                    <td><?php echo e($row['percent']); ?>%</td>
                </tr>
            <?php } ?>
        </table>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
