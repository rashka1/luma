<?php
// top of every admin page (sidebar menu)

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo e($page_title); ?> - Lume Pilates Studio Management</title>
    <link rel="icon" href="../assets/img/logo.svg">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <!-- change v=7 after editing css or js -->
    <link rel="stylesheet" href="../assets/css/style.css?v=7">
</head>
<body>

<div class="layout">

    <div class="sidebar">
        <a href="dashboard.php" class="sidebar-brand">
            <img src="../assets/img/logo.svg" alt="" width="36" height="36">
            <div>Lume <span>Pilates</span></div>
        </a>
        <div class="sidebar-subtitle">Studio Management</div>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'dashboard.php') { echo 'active'; } ?>" href="dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'members.php') { echo 'active'; } ?>" href="members.php">Members</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'classes.php') { echo 'active'; } ?>" href="classes.php">Classes</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'bookings.php') { echo 'active'; } ?>" href="bookings.php">Bookings</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'attendance.php') { echo 'active'; } ?>" href="attendance.php">Attendance</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php if ($current_page == 'reports.php') { echo 'active'; } ?>" href="reports.php">Reports</a>
            </li>
            <?php if (is_admin()) { ?>
                <li class="nav-item">
                    <a class="nav-link <?php if ($current_page == 'users.php') { echo 'active'; } ?>" href="users.php">Users</a>
                </li>
            <?php } ?>
        </ul>

        <hr>
        <p class="small mb-2">
            Hello, <?php echo e($_SESSION['user_name']); ?><br>
            <span class="status-badge role-<?php echo e($_SESSION['role']); ?>"><?php echo e($_SESSION['role']); ?></span>
        </p>
        <a class="btn btn-outline-light btn-sm" href="logout.php">Log out</a>
    </div>

    <div class="main">

<?php
// flash message
if (isset($_SESSION['flash_message'])) {
    if ($_SESSION['flash_type'] == 'success') {
        echo '<div class="alert alert-success">' . e($_SESSION['flash_message']) . '</div>';
    } else {
        echo '<div class="alert alert-danger">' . e($_SESSION['flash_message']) . '</div>';
    }
    unset($_SESSION['flash_message']);
    unset($_SESSION['flash_type']);
}
?>
