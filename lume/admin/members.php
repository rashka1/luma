<?php
// members list

require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

$page_title = 'Members';

$result = $conn->query("SELECT * FROM members ORDER BY full_name");
$members = $result->fetch_all(MYSQLI_ASSOC);

require_once __DIR__ . '/../includes/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <h1>Members</h1>
    <a class="btn btn-lume" href="member_form.php">+ Add member</a>
</div>

<!-- search is in app.js -->
<input type="text" id="member-search" class="form-control mb-3" placeholder="Search by name or phone...">

<div class="table-responsive table-wrap">
<table class="table table-hover mb-0">
    <thead>
        <tr>
            <th>Name</th>
            <th>Phone</th>
            <th>Email</th>
            <th>Status</th>
            <th>Joined</th>
            <th></th>
        </tr>
    </thead>
    <!-- data-admin: 1 = show delete in search -->
    <tbody id="member-rows" data-admin="<?php if (is_admin()) { echo '1'; } else { echo '0'; } ?>">
        <?php if (count($members) == 0) { ?>
            <tr><td colspan="6">No members yet.</td></tr>
        <?php } ?>
        <?php foreach ($members as $member) { ?>
            <tr>
                <td><?php echo e($member['full_name']); ?></td>
                <td><?php echo e($member['phone']); ?></td>
                <td><?php echo e($member['email']); ?></td>
                <td><span class="status-badge status-<?php echo e($member['status']); ?>"><?php echo e($member['status']); ?></span></td>
                <td><?php echo e(show_date($member['join_date'])); ?></td>
                <td>
                    <a class="btn btn-sm btn-outline-lume" href="member_form.php?id=<?php echo e($member['id']); ?>">Edit</a>
                    <?php if (is_admin()) { ?>
                        <form method="post" action="member_delete.php" class="d-inline" onsubmit="return confirm('Delete this member?');">
                            <input type="hidden" name="id" value="<?php echo e($member['id']); ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                        </form>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>
</div>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>
