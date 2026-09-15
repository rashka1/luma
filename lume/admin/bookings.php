<?php
// bookings list
// bookings.php?class_id=3 shows only one class

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Bookings';
$class_id = (int) ($_GET['class_id'] ?? 0);

if ($class_id > 0) {
    $stmt = $conn->prepare("SELECT bookings.id, bookings.status, members.full_name, members.phone,
            classes.class_name, classes.class_date, classes.start_time
        FROM bookings
        JOIN members ON members.id = bookings.member_id
        JOIN classes ON classes.id = bookings.class_id
        WHERE bookings.class_id = ?
        ORDER BY members.full_name");
    $stmt->bind_param("i", $class_id);
    $stmt->execute();
    $bookings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
} else {
    $result = $conn->query("SELECT bookings.id, bookings.status, members.full_name, members.phone,
            classes.class_name, classes.class_date, classes.start_time
        FROM bookings
        JOIN members ON members.id = bookings.member_id
        JOIN classes ON classes.id = bookings.class_id
        ORDER BY classes.class_date DESC, classes.start_time, members.full_name");
    $bookings = $result->fetch_all(MYSQLI_ASSOC);
}

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1>Bookings</h1>
    <a class="btn btn-lume" href="booking_form.php?class_id=<?php echo e($class_id); ?>">+ New booking</a>
</div>

<?php if ($class_id > 0) { ?>
    <p><a href="bookings.php">Show all bookings</a></p>
<?php } ?>

<div class="table-responsive table-wrap">
<table class="table table-hover mb-0">
    <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Class</th>
        <th>Member</th>
        <th>Phone</th>
        <th>Status</th>
        <th></th>
    </tr>
    <?php if (count($bookings) == 0) { ?>
        <tr><td colspan="7">No bookings.</td></tr>
    <?php } ?>
    <?php foreach ($bookings as $booking) { ?>
        <tr>
            <td><?php echo e(show_date($booking['class_date'])); ?></td>
            <td><?php echo e(show_time($booking['start_time'])); ?></td>
            <td><?php echo e($booking['class_name']); ?></td>
            <td><?php echo e($booking['full_name']); ?></td>
            <td><?php echo e($booking['phone']); ?></td>
            <td><span class="status-badge status-<?php echo e($booking['status']); ?>"><?php echo e($booking['status']); ?></span></td>
            <td>
                <form method="post" action="booking_delete.php" class="d-inline" onsubmit="return confirm('Cancel this booking?');">
                    <input type="hidden" name="id" value="<?php echo e($booking['id']); ?>">
                    <button type="submit" class="btn btn-sm btn-outline-danger">Cancel</button>
                </form>
            </td>
        </tr>
    <?php } ?>
</table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
