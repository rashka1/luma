<?php
// form checks
// returns '' if ok, or the error

function v_required($value, $label) {
    if (trim($value) == '') {
        return $label . ' is required.';
    }
    return '';
}

// name: letters and spaces, 3 to 100 characters
function v_name($value, $label) {
    $value = trim($value);

    if ($value == '') {
        return $label . ' is required.';
    }
    if (strlen($value) < 3 || strlen($value) > 100) {
        return $label . ' must be between 3 and 100 characters.';
    }
    if (!preg_match("/^[a-zA-Z .'-]+$/", $value)) {
        return $label . ' may only contain letters and spaces.';
    }
    return '';
}

// phone: 7 to 15 numbers, can start with +
function v_phone($value) {
    $value = trim($value);

    if ($value == '') {
        return 'Phone is required.';
    }
    if (!preg_match('/^\+?[0-9]{7,15}$/', $value)) {
        return 'Phone must be 7 to 15 digits (it may start with +).';
    }
    return '';
}

// email is not required
function v_email($value) {
    $value = trim($value);

    if ($value == '') {
        return '';
    }
    if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
        return 'Please enter a valid email address.';
    }
    return '';
}

// password: at least 8 characters, with letters and numbers
function v_password($value) {
    if (strlen($value) < 8) {
        return 'Password must be at least 8 characters.';
    }
    if (!preg_match('/[a-zA-Z]/', $value) || !preg_match('/[0-9]/', $value)) {
        return 'Password must have letters and numbers.';
    }
    return '';
}

// date like 2026-09-15
function v_date($value, $label) {
    $value = trim($value);

    if ($value == '') {
        return $label . ' is required.';
    }
    if (!preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $value)) {
        return 'Please enter a valid ' . strtolower($label) . '.';
    }

    // no 30 February
    $parts = explode('-', $value);
    if (!checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0])) {
        return 'Please enter a valid ' . strtolower($label) . '.';
    }
    return '';
}

// date must be today or later
function v_not_past_date($value, $today) {
    if ($value < $today) {
        return 'The date cannot be in the past.';
    }
    return '';
}

// time like 09:30
function v_time($value) {
    $value = trim($value);

    if ($value == '') {
        return 'Start time is required.';
    }
    if (!preg_match('/^([01][0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$/', $value)) {
        return 'Please enter a valid time.';
    }
    return '';
}

// whole number between min and max
function v_number_between($value, $min, $max, $label) {
    $value = trim($value);

    if ($value == '') {
        return $label . ' is required.';
    }
    if (!ctype_digit($value)) {
        return $label . ' must be a whole number.';
    }
    if ((int) $value < $min || (int) $value > $max) {
        return $label . ' must be between ' . $min . ' and ' . $max . '.';
    }
    return '';
}
