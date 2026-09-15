<?php
// small test helper

$tests_passed = 0;
$tests_failed = 0;

// print a line
function print_line($text) {
    if (PHP_SAPI == 'cli') {
        echo $text . "\n";
    } else {
        echo htmlspecialchars($text) . "<br>\n";
    }
}

// one test
function check($name, $result) {
    global $tests_passed, $tests_failed;

    if ($result === true) {
        $tests_passed = $tests_passed + 1;
        print_line('PASS  ' . $name);
    } else {
        $tests_failed = $tests_failed + 1;
        print_line('FAIL  ' . $name);
    }
}
