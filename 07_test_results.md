# Test Results

Lume Pilates Studio Management: admin panel (Members, Classes, Bookings, Attendance, Reports, Users),
2 roles (admin and staff), 3 Ajax features, and the WordPress website (Home, Classes, Schedule).

Tested on Windows 11, XAMPP (PHP 8.2.12, MariaDB 10.4.32), and Chrome.

## Summary

| Level | Result |
|---|---|
| Automated tests (`php tests/run_all.php`) | **60 passed, 0 failed** (validation, `e()`, `is_admin()`, booking rules, database keys) |
| Pages and forms | **79 passed, 0 failed** |
| Reports and sidebar | **13 passed, 0 failed** (report totals checked against the database) |
| jQuery features in the browser | **19 passed, 0 failed** |
| Admin and staff roles | **52 passed, 0 failed**: staff can add, edit, book, cancel bookings and mark attendance. Staff can not delete members or classes (also when sending the form directly) and can not open the Users pages. Admin can delete and manage users, but can not delete or demote their own account. A changed role or a deleted user counts at once |
| WordPress website | Pages load, no sideways scrolling on a phone (390 px) or a laptop (1366 px), every block is valid in the editor, nothing loads from the internet, no JavaScript errors, `wordpress.sql` imports into an empty database |
| Strict MySQL mode (like MySQL 8 on MAMP) | All the tests above pass again, and the 3 `.sql` files import into an empty database |
| On the MacBook | **Not run yet**: follow `README.md` section 4, then run the tests there |

## What was checked

**Login**
- Not logged in: admin pages go to the login page
- Wrong password and unknown email show the same message
- After log out the dashboard can not be opened again

**Members**
- Empty name, short phone, bad email: a red message under each field
- A phone that already exists is refused; what was typed stays in the form
- A status that is not in the list ("deleted") is refused
- `<script>` in a name is shown as text, never run
- Add, edit, delete work
- A member with bookings can not be deleted
- Opening the delete address in the browser (GET) deletes nothing

**Classes**
- A new class in the past is refused
- Capacity can not be lower than the people already booked

**Bookings** (every rule tried through the form)
- Full class, double booking, inactive member, class already started: each refused with its own message
- Booking the last place works, the next one is refused
- Cancelling a booking frees the place

**Ajax**
- All 3 files refuse when not logged in, and start with `<?php` (no space before)
- Search finds by name and by phone; SQL typed into the search finds nothing and breaks nothing
- Places left: "This class is full.", "8 places left", "already booked"
- Attendance: the counter comes back from the database; an unknown status is refused; GET is refused
- In Chrome: search filters without reload, Save is disabled for a full class, Present/Absent is still there after reload

**Website**
- Home, Classes, Schedule load with no errors
- No member names or phone numbers on the schedule
- A full class shows "Full"
- No forms on the website

**Security**
- `config/config.php`, `includes/*.php`, `database/*.sql` can not be opened in the browser (403)
- Passwords are saved as a `$2y$...` hash
- Nothing loads from the internet; no JavaScript errors

## Bug log

| # | Where | What happened | Fix | Retested |
|---|---|---|---|---|
| 1 | Booking form | When the page opened with a class already chosen, "places left" only appeared after changing the class | `app.js` checks straight away when a class is already selected | ☑ |
| 2 | Booking form | The `catch` treated every database error as "already booked" | Only error 1062 shows that message; other errors are shown normally | ☑ |
| 3 | Example data | Classes were on a Sunday but the footer said "Closed Sunday" (wrong time zone in the data) | The footer now says "Open every day" | ☑ |
| 4 | Admin pages on a phone | Tables were wider than the screen, Status and Cancel were off the page | Every table is inside `<div class="table-responsive">`, cells do not wrap | ☑ |
| 5 | Whole site after changes | The browser kept an old copy of `style.css` | CSS and JS links end with `?v=7`; raise the number after every change | ☑ |
| 6 | `http://localhost/lume/admin/` | No start page, so Apache showed a list of files | New `admin/index.php` opens the dashboard | ☑ |
| 7 | Staff role | Staff could not cancel a wrong booking | `booking_delete.php` is allowed for staff; member and class delete stay admin only | ☑ |
