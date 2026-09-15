<?php
// validation tests

// v_required
check('v_required accepts a value',          v_required('Amina', 'Name') == '');
check('v_required refuses empty text',       v_required('', 'Name') != '');
check('v_required refuses only spaces',      v_required('   ', 'Name') != '');

// v_name
check('v_name accepts a normal name',        v_name('Amina Yusuf', 'Full name') == '');
check("v_name accepts O'Brien",              v_name("O'Brien", 'Full name') == '');
check('v_name refuses 2 letters',            v_name('Am', 'Full name') != '');
check('v_name refuses numbers',              v_name('Amina123', 'Full name') != '');
check('v_name refuses <script>',             v_name('<script>', 'Full name') != '');

// v_phone
check('v_phone accepts 10 digits',           v_phone('0612345678') == '');
check('v_phone accepts a + at the start',    v_phone('+252612345678') == '');
check('v_phone accepts exactly 7 digits',    v_phone('1234567') == '');
check('v_phone refuses 6 digits',            v_phone('123456') != '');
check('v_phone refuses 16 digits',           v_phone('1234567890123456') != '');
check('v_phone refuses letters',             v_phone('06123abc') != '');
check('v_phone refuses empty',               v_phone('') != '');

// v_email
check('v_email accepts a normal email',      v_email('amina@mail.test') == '');
check('v_email accepts empty (optional)',    v_email('') == '');
check('v_email refuses text without @',      v_email('not-an-email') != '');

// v_date
check('v_date accepts 2026-09-15',           v_date('2026-09-15', 'Date') == '');
check('v_date accepts 29 Feb in a leap year', v_date('2024-02-29', 'Date') == '');
check('v_date refuses 30 February',          v_date('2026-02-30', 'Date') != '');
check('v_date refuses 15-09-2026',           v_date('15-09-2026', 'Date') != '');
check('v_date refuses empty',                v_date('', 'Date') != '');

// v_not_past_date (today = 2026-09-15 here)
check('v_not_past_date accepts today',       v_not_past_date('2026-09-15', '2026-09-15') == '');
check('v_not_past_date accepts tomorrow',    v_not_past_date('2026-09-16', '2026-09-15') == '');
check('v_not_past_date refuses yesterday',   v_not_past_date('2026-09-14', '2026-09-15') != '');

// v_time
check('v_time accepts 09:30',                v_time('09:30') == '');
check('v_time accepts 09:30:00',             v_time('09:30:00') == '');
check('v_time refuses 24:00',                v_time('24:00') != '');
check('v_time refuses text',                 v_time('morning') != '');

// v_number_between
check('v_number_between accepts 10',         v_number_between('10', 1, 50, 'Capacity') == '');
check('v_number_between accepts 1 and 50',   v_number_between('1', 1, 50, 'Capacity') == '' && v_number_between('50', 1, 50, 'Capacity') == '');
check('v_number_between refuses 0',          v_number_between('0', 1, 50, 'Capacity') != '');
check('v_number_between refuses 51',         v_number_between('51', 1, 50, 'Capacity') != '');
check('v_number_between refuses 2.5',        v_number_between('2.5', 1, 50, 'Capacity') != '');

// v_password
check('v_password accepts lume2026',         v_password('lume2026') == '');
check('v_password refuses 7 characters',     v_password('lume202') != '');
check('v_password refuses only letters',     v_password('pilatesstudio') != '');
check('v_password refuses only numbers',     v_password('12345678') != '');

// is_admin
$_SESSION['role'] = 'admin';
check('is_admin is true for the admin',      is_admin() == true);
$_SESSION['role'] = 'staff';
check('is_admin is false for staff',         is_admin() == false);
unset($_SESSION['role']);
check('is_admin is false when not logged in', is_admin() == false);

// e()
check('e() escapes a script tag',            e('<script>') == '&lt;script&gt;');
