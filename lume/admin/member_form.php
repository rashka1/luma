<?php
// add or edit a member
// member_form.php = add, member_form.php?id=3 = edit

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';

$id = (int) ($_GET['id'] ?? 0);

$full_name = '';
$phone = '';
$email = '';
$status = 'active';
$join_date = date('Y-m-d');
$errors = array();

// edit: get the member from the database
if ($id > 0) {
    $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $member = $stmt->get_result()->fetch_assoc();

    if ($member == null) {
        set_flash('error', 'Member not found.');
        header('Location: members.php');
        exit;
    }

    $full_name = $member['full_name'];
    $phone = $member['phone'];
    $email = $member['email'];
    $status = $member['status'];
    $join_date = $member['join_date'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $status = $_POST['status'] ?? '';
    $join_date = $_POST['join_date'] ?? '';

    // check the form
    $errors['full_name'] = v_name($full_name, 'Full name');
    $errors['phone'] = v_phone($phone);
    $errors['email'] = v_email($email);
    $errors['join_date'] = v_date($join_date, 'Join date');

    if ($status != 'active' && $status != 'inactive') {
        $errors['status'] = 'Please choose a status.';
    }

    // phone must be unique
    if ($errors['phone'] == '') {
        $stmt = $conn->prepare("SELECT id FROM members WHERE phone = ? AND id != ?");
        $stmt->bind_param("si", $phone, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['phone'] = 'This phone number is already used by another member.';
        }
    }

    // remove empty errors
    $errors = array_filter($errors);

    if (count($errors) == 0) {
        if ($email == '') {
            $email = null;
        }

        if ($id > 0) {
            $stmt = $conn->prepare("UPDATE members SET full_name = ?, phone = ?, email = ?, status = ?, join_date = ? WHERE id = ?");
            $stmt->bind_param("sssssi", $full_name, $phone, $email, $status, $join_date, $id);
        } else {
            $stmt = $conn->prepare("INSERT INTO members (full_name, phone, email, status, join_date) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sssss", $full_name, $phone, $email, $status, $join_date);
        }
        $stmt->execute();

        set_flash('success', 'Member saved.');
        header('Location: members.php');
        exit;
    }
}

if ($id > 0) {
    $page_title = 'Edit member';
} else {
    $page_title = 'Add member';
}

require_once __DIR__ . '/../includes/header.php';
?>

<h1 class="mb-3"><?php echo e($page_title); ?></h1>

<form method="post" class="box">
    <div class="mb-3">
        <label class="form-label">Full name</label>
        <input type="text" name="full_name" class="form-control" value="<?php echo e($full_name); ?>">
        <?php if (isset($errors['full_name'])) { ?>
            <div class="text-danger"><?php echo e($errors['full_name']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Phone (numbers only, for example 0612345678)</label>
        <input type="text" name="phone" class="form-control" value="<?php echo e($phone); ?>">
        <?php if (isset($errors['phone'])) { ?>
            <div class="text-danger"><?php echo e($errors['phone']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Email (optional)</label>
        <input type="text" name="email" class="form-control" value="<?php echo e($email); ?>">
        <?php if (isset($errors['email'])) { ?>
            <div class="text-danger"><?php echo e($errors['email']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="active" <?php if ($status == 'active') { echo 'selected'; } ?>>Active</option>
            <option value="inactive" <?php if ($status == 'inactive') { echo 'selected'; } ?>>Inactive</option>
        </select>
        <?php if (isset($errors['status'])) { ?>
            <div class="text-danger"><?php echo e($errors['status']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Join date</label>
        <input type="date" name="join_date" class="form-control" value="<?php echo e($join_date); ?>">
        <?php if (isset($errors['join_date'])) { ?>
            <div class="text-danger"><?php echo e($errors['join_date']); ?></div>
        <?php } ?>
    </div>

    <button type="submit" class="btn btn-lume">Save</button>
    <a href="members.php" class="btn btn-outline-secondary">Cancel</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
