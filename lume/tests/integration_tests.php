<?php
// booking rules tests (lume_test)
// data: database/test_seed.sql

// load the test data
function reset_test_database($conn) {
    $sql = file_get_contents(__DIR__ . '/../database/schema.sql');
    $sql = $sql . file_get_contents(__DIR__ . '/../database/test_seed.sql');

    $conn->multi_query($sql);

    // wait for the queries
    while ($conn->more_results()) {
        $conn->next_result();
    }
}

// get the error number
function error_number($conn, $sql) {
    try {
        $conn->query($sql);
    } catch (mysqli_sql_exception $e) {
        return $e->getCode();
    }
    return 0;
}


reset_test_database($conn);

// can_book rules
check('an active member can book an empty class',  can_book($conn, 1, 3) == '');
check('a full class is refused',                   can_book($conn, 1, 2) == 'This class is full.');
check('booking the same class twice is refused',   can_book($conn, 1, 1) == 'This member is already booked in this class.');
check('a class in the past is refused',            can_book($conn, 1, 4) == 'That class has already started.');
check('an inactive member is refused',             can_book($conn, 5, 3) == 'This member is inactive and cannot be booked.');
check('a member that does not exist is refused',   can_book($conn, 99, 3) == 'That member was not found.');
check('a class that does not exist is refused',    can_book($conn, 1, 99) == 'That class was not found.');

// places
check('class 1 has 1 booking',                     booked_count($conn, 1) == 1);
check('class 2 has 0 places left',                 places_left($conn, 2) == 0);
check('class 3 has 10 places left',                places_left($conn, 3) == 10);

// cancel frees a place
$conn->query("DELETE FROM bookings WHERE id = 2");
check('after a cancellation the full class can be booked', can_book($conn, 1, 2) == '');

// the database rules
reset_test_database($conn);
check('the database refuses a double booking (error 1062)',
    error_number($conn, "INSERT INTO bookings (member_id, class_id) VALUES (1, 1)") == 1062);
check('the database refuses the same phone twice (error 1062)',
    error_number($conn, "INSERT INTO members (full_name, phone, join_date) VALUES ('Copy', '0611000001', CURDATE())") == 1062);

// deleting
check('member 1 has bookings',                     member_has_bookings($conn, 1) == true);
check('member 5 has no bookings',                  member_has_bookings($conn, 5) == false);
check('the database refuses to delete a member with bookings (error 1451)',
    error_number($conn, "DELETE FROM members WHERE id = 1") == 1451);

$conn->query("DELETE FROM classes WHERE id = 2");
$result = $conn->query("SELECT COUNT(*) AS total FROM bookings WHERE class_id = 2");
$row = $result->fetch_assoc();
check('deleting a class also deletes its bookings', $row['total'] == 0);
