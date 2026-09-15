<?php
// add or edit a user (only the admin)
// user_form.php = add, user_form.php?id=3 = edit

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_only.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/validation.php';

$id = (int) ($_GET['id'] ?? 0);

$full_name = '';
$email = '';
$role = 'staff';
$errors = array();

// can not change your own role
$is_me = false;
if ($id == $_SESSION['user_id']) {
    $is_me = true;
}

// edit: get the user
if ($id > 0) {
    $stmt = $conn->prepare("SELECT id, full_name, email, role FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $user = $stmt->get_result()->fetch_assoc();

    if ($user == null) {
        set_flash('error', 'User not found.');
        header('Location: users.php');
        exit;
    }

    $full_name = $user['full_name'];
    $email = $user['email'];
    $role = $user['role'];
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$is_me) {
        $role = $_POST['role'] ?? '';
    }

    // check the form
    $errors['full_name'] = v_name($full_name, 'Full name');
    $errors['email'] = v_required($email, 'Email');
    if ($errors['email'] == '') {
        $errors['email'] = v_email($email);
    }

    if ($role != 'admin' && $role != 'staff') {
        $errors['role'] = 'Please choose a role.';
    }

    // password is needed for a new user
    if ($id == 0 || $password != '') {
        $errors['password'] = v_password($password);
    }

    // email must be unique
    if ($errors['email'] == '') {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
        $stmt->bind_param("si", $email, $id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors['email'] = 'This email is already used by another user.';
        }
    }

    $errors = array_filter($errors);

    if (count($errors) == 0) {
        if ($id == 0) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (full_name, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("ssss", $full_name, $email, $hash, $role);
            $stmt->execute();
        } else {
            $stmt = $conn->prepare("UPDATE users SET full_name = ?, email = ?, role = ? WHERE id = ?");
            $stmt->bind_param("sssi", $full_name, $email, $role, $id);
            $stmt->execute();

            // new password typed
            if ($password != '') {
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $stmt->bind_param("si", $hash, $id);
                $stmt->execute();
            }
        }

        // my name changed, update the menu
        if ($is_me) {
            $_SESSION['user_name'] = $full_name;
        }

        set_flash('success', 'User saved.');
        header('Location: users.php');
        exit;
    }
}

if ($id > 0) {
    $page_title = 'Edit user';
} else {
    $page_title = 'Add user';
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
        <label class="form-label">Email (used to log in)</label>
        <input type="text" name="email" class="form-control" value="<?php echo e($email); ?>">
        <?php if (isset($errors['email'])) { ?>
            <div class="text-danger"><?php echo e($errors['email']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">Role</label>
        <?php if ($is_me) { ?>
            <p class="mb-0">admin (you can not change your own role)</p>
        <?php } else { ?>
            <select name="role" class="form-select">
                <option value="staff" <?php if ($role == 'staff') { echo 'selected'; } ?>>Staff - can add and change, can not delete</option>
                <option value="admin" <?php if ($role == 'admin') { echo 'selected'; } ?>>Admin - can do everything</option>
            </select>
        <?php } ?>
        <?php if (isset($errors['role'])) { ?>
            <div class="text-danger"><?php echo e($errors['role']); ?></div>
        <?php } ?>
    </div>

    <div class="mb-3">
        <label class="form-label">
            Password (at least 8 characters, letters and numbers)
            <?php if ($id > 0) { echo '- leave empty to keep the old password'; } ?>
        </label>
        <input type="password" name="password" class="form-control">
        <?php if (isset($errors['password'])) { ?>
            <div class="text-danger"><?php echo e($errors['password']); ?></div>
        <?php } ?>
    </div>

    <button type="submit" class="btn btn-lume">Save</button>
    <a href="users.php" class="btn btn-outline-secondary">Cancel</a>
</form>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
