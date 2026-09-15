<?php
// users list (only the admin)

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/admin_only.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Users';

$result = $conn->query("SELECT id, full_name, email, role FROM users ORDER BY role, full_name");
$users = $result->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1>Users</h1>
    <a class="btn btn-lume" href="user_form.php">+ Add user</a>
</div>

<p class="text-muted-lume">
    <strong>Staff</strong> can add and change members, classes, bookings and attendance, but can not delete.
    <strong>Admin</strong> can do everything and manage the users.
</p>

<div class="table-responsive table-wrap">
<table class="table table-hover mb-0">
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Role</th>
        <th></th>
    </tr>
    <?php foreach ($users as $user) { ?>
        <tr>
            <td>
                <?php echo e($user['full_name']); ?>
                <?php if ($user['id'] == $_SESSION['user_id']) { echo '(you)'; } ?>
            </td>
            <td><?php echo e($user['email']); ?></td>
            <td><span class="status-badge role-<?php echo e($user['role']); ?>"><?php echo e($user['role']); ?></span></td>
            <td>
                <a class="btn btn-sm btn-outline-lume" href="user_form.php?id=<?php echo e($user['id']); ?>">Edit</a>
                <?php if ($user['id'] != $_SESSION['user_id']) { ?>
                    <form method="post" action="user_delete.php" class="d-inline" onsubmit="return confirm('Delete this user?');">
                        <input type="hidden" name="id" value="<?php echo e($user['id']); ?>">
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                <?php } ?>
            </td>
        </tr>
    <?php } ?>
</table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
