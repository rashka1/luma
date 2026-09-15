<?php
// settings

// database
// mac (MAMP): host 127.0.0.1, password root
define('DB_HOST', 'localhost');
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', '');

// tests use lume_test
if (defined('TESTING')) {
    define('DB_NAME', 'lume_test');
} else {
    define('DB_NAME', 'lume_db');
}

// show errors
error_reporting(E_ALL);
ini_set('display_errors', '1');

date_default_timezone_set('Africa/Mogadishu');

// date and time format
define('DATE_FORMAT', 'd M Y');
define('TIME_FORMAT', 'h:i A');
