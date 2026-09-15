<?php
// only the admin (after auth.php)

require_once __DIR__ . '/functions.php';

if (!is_admin()) {
    set_flash('error', 'Only the admin can do that.');
    header('Location: dashboard.php');
    exit;
}
