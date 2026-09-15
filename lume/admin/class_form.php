<?php
// add or edit a class
// class_form.php = add, class_form.php?id=3 = edit

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';

$id = (int) ($_GET['id'] ?? 0);

$class_name = '';
$instructor = '';
$class_date = date('Y-m-d');
$start_time = '09:00';
$capacity = '10';
$errors = array();

// edit: get the class from the database
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM classes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $class = $stmt->get_result()->fetch_assoc();

    if ($class == null) {
        set_flash('error', 'Class not found.');
        header('Location: classes.php');
        exit;
    }

    $class_name = $class['class_name'];
    $instructor = $class['instructor'];
    $class_date = $class['class_date'];
    $start_time = substr($class['start_time'], 0, 5); // 09:00:00 -> 09:00
    $capacity = $class['capacity'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $class_name = trim($_POST['class_name'] ?? '');
    $instructor = trim($_POST['instructor'] ?? '');
    $class_date = $_POST['class_date'] ?? '';
    $start_time = $_POST['start_time'] ?? '';
    $capacity = trim($_POST['capacity'] ?? '');

    // check the form
    $errors['class_name'] = v_required($class_name, 'Class name');
    $errors['instructor'] = v_name($instructor, 'Instructor');
    $errors['class_date'] = v_date($class_date, 'Date');
    $errors['start_time'] = v_time($start_time);
    $errors['capacity'] = v_number_between($capacity, 1, 50, 'Capacity');

    // a new class can't be in the past
    if ($id == 0 && $errors['class_date'] == '') {
        $errors['class_date'] = v_not_past_date($class_date, date('Y-m-d'));
    }

    // capacity can't be less than the people already booked
    if ($id > 0 && $errors['capacity'] == '') {
        $booked = booked_count($conn, $id);
        if ((int) $capacity < $booked) {
            $errors['capacity'] = 'Capacity cannot be less than the ' . $booked . ' people already booked.';
        }
    }

    $errors = array_filter($errors);

    if (count($errors) == 0) {
        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE classes SET class_name = ?, instructor = ?, class_date = ?, start_time = ?, capacity = ? WHERE id = ?");
            $stmt->bind_param("ssssii", $class_name, $instructor, $class_date, $start_time, $capacity, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO classes (class_name, instructor, class_date, start_time, capacity) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssi", $class_name, $instructor, $class_date, $start_time, $capacity);
        }
        $stmt->execute();

        set_flash('success', 'Class saved.');
        header('Location: classes.php');
        exit;
    }
}

if ($id > 0) {
    $page_title = 'Edit class';
} else {
    $page_title = 'Add class';
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-3"><?php echo e($page_title); ?></h1>

<form method="post" class="box">
    <div class="mb-3">
        <label class="form-label">Class name (for example Mat Pilates)</label>
        <input type="text" name="class_name" class="form-control" maxlength="80" value="<?php echo e($class_name); ?>">
        <?php if (isset($errors['class_name'])) { ?>
            <div class="text-danger"><?php echo e($errors['class_name']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Instructor</label>
        <input type="text" name="instructor" class="form-control" value="<?php echo e($instructor); ?>">
        <?php if (isset($errors['instructor'])) { ?>
            <div class="text-danger"><?php echo e($errors['instructor']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Date</label>
        <input type="date" name="class_date" class="form-control" value="<?php echo e($class_date); ?>">
        <?php if (isset($errors['class_date'])) { ?>
            <div class="text-danger"><?php echo e($errors['class_date']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Start time</label>
        <input type="time" name="start_time" class="form-control" value="<?php echo e($start_time); ?>">
        <?php if (isset($errors['start_time'])) { ?>
            <div class="text-danger"><?php echo e($errors['start_time']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Capacity (how many people can join)</label>
        <input type="number" name="capacity" class="form-control" value="<?php echo e($capacity); ?>">
        <?php if (isset($errors['capacity'])) { ?>
            <div class="text-danger"><?php echo e($errors['capacity']); ?></div>
        <?php } ?>
    </div>

    <button type="submit" class="btn btn-lume">Save</button>
    <a href="classes.php" class="btn btn-outline-secondary">Cancel</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
