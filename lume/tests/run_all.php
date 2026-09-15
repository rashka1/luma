<?php
// run all tests
// terminal: php tests/run_all.php
// browser:  http://localhost/lume/tests/run_all.php

// use the test database (lume_test)
define('TESTING', true);

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/validation.php';
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/test_runner.php';

// not on the real database
if (DB_NAME != 'lume_test') {
    print_line('STOP: the tests may only use the lume_test database.');
    exit(1);
}

// create lume_test
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$server = new mysqli(DB_HOST, DB_USER, DB_PASS, '', DB_PORT);
$server->query("CREATE DATABASE IF NOT EXISTS lume_test");

require_once __DIR__ . '/../includes/db.php';

print_line('--- Unit tests (validation) ---');
require __DIR__ . '/unit_tests.php';

print_line('');
print_line('--- Integration tests (booking rules, database lume_test) ---');
require __DIR__ . '/integration_tests.php';

print_line('');
print_line('Passed: ' . $tests_passed);
print_line('Failed: ' . $tests_failed);

if ($tests_failed == 0) {
    print_line('RESULT: ALL TESTS PASSED');
} else {
    print_line('RESULT: SOME TESTS FAILED');
}

if (PHP_SAPI == 'cli') {
    if ($tests_failed == 0) {
        exit(0);
    }
    exit(1);
}
