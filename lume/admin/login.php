<?php
// login page

session_start();
require_once __DIR__ . '/../includes/db.php';
require_once __DIR__ . '/../includes/functions.php';

// already logged in
if (isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit;
}

$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email == '' || $password == '') {
        $error = 'Please enter your email and password.';
    } else {
        $stmt = $conn->prepare("SELECT id, full_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();

        // check the password
        if ($user != null && password_verify($password, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['role'] = $user['role'];

            header('Location: dashboard.php');
            exit;
        }

        // same message for wrong email or password
        $error = 'Email or password is not correct.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Log in - Lume Pilates Studio Management</title>
    <link rel="icon" href="../assets/img/logo.svg">
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/style.css?v=7">
</head>
<body>

<div class="login-page">
    <div class="login-card">
        <div class="text-center mb-4">
            <img src="../assets/img/logo.svg" alt="" width="56" height="56" class="mb-2">
            <div class="login-brand">Lume <span>Pilates</span></div>
            <div class="text-muted-lume">Studio Management</div>
        </div>

        <?php if ($error != '') { ?>
            <div class="alert alert-danger"><?php echo e($error); ?></div>
        <?php } ?>

        <form method="post" action="login.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="<?php echo e($email); ?>">
            </div>
            <div class="mb-4">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" id="password" class="form-control">
            </div>
            <button type="submit" class="btn btn-lume w-100 py-2">Log in</button>
        </form>

        <p class="text-center mt-4 mb-0 small"><a href="../website/">&larr; Back to the website</a></p>
    </div>
</div>

</body>
</html>
