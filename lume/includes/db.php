<?php
// database connection

require_once __DIR__ . '/../config/config.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
} catch (mysqli_sql_exception $error) {
    die('Could not connect to the database. Is MySQL running? Check config/config.php.');
}

$conn->set_charset('utf8mb4');
