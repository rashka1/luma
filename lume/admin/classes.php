<?php
// classes list

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Classes';

$result = $conn->query("SELECT classes.*,
        (SELECT COUNT(*) FROM bookings WHERE bookings.class_id = classes.id) AS booked
    FROM classes
    ORDER BY class_date DESC, start_time");
$classes = $result->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1>Classes</h1>
    <a class="btn btn-lume" href="class_form.php">+ Add class</a>
</div>

<div class="table-responsive table-wrap">
<table class="table table-hover mb-0">
    <tr>
        <th>Date</th>
        <th>Time</th>
        <th>Class</th>
        <th>Instructor</th>
        <th>Booked</th>
        <th></th>
    </tr>
    <?php if (count($classes) == 0) { ?>
        <tr><td colspan="6">No classes yet.</td></tr>
    <?php } ?>
    <?php foreach ($classes as $class) { ?>
        <tr>
            <td><?php echo e(show_date($class['class_date'])); ?></td>
            <td><?php echo e(show_time($class['start_time'])); ?></td>
            <td><?php echo e($class['class_name']); ?></td>
            <td><?php echo e($class['instructor']); ?></td>
            <td><?php echo e($class['booked']); ?> / <?php echo e($class['capacity']); ?></td>
            <td>
                <a class="btn btn-sm btn-outline-lume" href="bookings.php?class_id=<?php echo e($class['id']); ?>">Bookings</a>
                <a class="btn btn-sm btn-outline-lume" href="class_form.php?id=<?php echo e($class['id']); ?>">Edit</a>
                <?php if (is_admin()) { ?>
                    <form method="post" action="class_delete.php" class="d-inline" onsubmit="return confirm('Delete this class and all its bookings?');">
                        <input type="hidden" name="id" value="<?php echo e($class['id']); ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
