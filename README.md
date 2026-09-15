# Lume Pilates Studio Management

A web system for a small Pilates studio. It has two parts:

1. **The website** (for visitors, no login). It shows the studio, the classes and the schedule.
2. **The admin panel** (for the studio staff, with login). Here the staff manage members, classes,
   bookings and attendance, and see reports.

This file explains:

- [What the project does](#1-what-the-project-does)
- [The technology we used](#2-the-technology-we-used)
- [The tools we used](#3-the-tools-we-used)
- [How to run it on a MacBook Air](#4-how-to-run-it-on-a-macbook-air)
- [Logins](#5-logins)
- [A short tour for the presentation](#6-a-short-tour-for-the-presentation)
- [How the project works](#7-how-the-project-works)
- [Tests](#8-tests)
- [Problems and fixes](#9-problems-and-fixes)
- [Photos](#10-photos)

---

## 1. What the project does

### Website (WordPress)

| Page | What it shows |
|---|---|
| Home | Big photo, the 4 kinds of classes with photos, the studio, the next 3 classes |
| Classes | Each class with a photo and a short text |
| Schedule | All classes of the next 14 days, with how many places are left ("Full" when no place) |
| Staff login | A link in the menu to the admin panel |

The schedule is **real data**: when the staff add a class in the admin panel, it appears on the website.

### Admin panel (PHP)

| Part | What you can do |
|---|---|
| Dashboard | 4 numbers (active members, classes today, bookings today, classes this week), today's classes, the last 7 days |
| Members | List, live search, add, edit, delete |
| Classes | List, add, edit, delete (name, instructor, date, time, capacity) |
| Bookings | Book a member into a class, cancel a booking |
| Attendance | Choose a day, open a class, click **Present** or **Absent** |
| Reports | Choose two dates: a table of classes (booked, attended, absent, attendance %) and a table of members. Can be printed |
| Users (admin only) | Add, edit and delete the people who can log in, and choose their role |

### Two roles

| Role | Can do |
|---|---|
| **Staff** | Add and edit members, classes, bookings. Cancel bookings. Mark attendance. See reports. **Can not delete** members, classes or users |
| **Admin** | Everything, including deleting and managing the users |

---

## 2. The technology we used

| Technology | Version | What we used it for |
|---|---|---|
| **HTML** | 5 | The structure of the pages |
| **CSS** | 3 | The Lume design (colors, fonts, sidebar) in `assets/css/style.css` |
| **Bootstrap** | 5.3.3 | Ready-made layout, tables, buttons and forms, so the pages also work on a phone |
| **PHP** | 8.2 | The admin panel. Plain PHP, no framework. Reads the forms, checks them, talks to the database |
| **MySQL** (MariaDB on Windows) | 8 / 10.4 | The database `lume_db` that keeps users, members, classes and bookings |
| **SQL** | | Creating the tables (`schema.sql`), example data (`seed.sql`), queries in PHP |
| **JavaScript + jQuery** | 3.7.1 | 3 small features that work without reloading the page (Ajax) |
| **Ajax + JSON** | | jQuery sends a request to a PHP file, PHP answers in JSON, jQuery updates the page |
| **WordPress** | 7.1 | The public website, made in the block editor |
| **Twenty Twenty-Five** | 1.5 | The free WordPress theme we used |
| **WordPress plugin** (our own, small) | 1.0 | Shows the classes from our database on the website with shortcodes |
| **Apache** | 2.4 | The web server that runs PHP and WordPress |
| **Git + GitHub** | | Saving the project and sharing it |

Everything (Bootstrap, jQuery, fonts, photos, WordPress) is **inside the project folder**.
Nothing is loaded from the internet, so the project works even without Wi-Fi at the presentation.

---

## 3. The tools we used

| Tool | What for |
|---|---|
| **XAMPP** (Windows) | Apache + MariaDB + PHP + phpMyAdmin in one program. We built the project with it |
| **MAMP** (Mac) | The same thing for Mac. Used to run the project on the MacBook |
| **phpMyAdmin** | Creating the database and importing the `.sql` files (comes with XAMPP and MAMP) |
| **A code editor** (for example VS Code) | Writing the code |
| **Google Chrome** + DevTools (F12) | Testing the pages, looking at Ajax requests and errors, testing the phone size |
| **WordPress block editor** | Making the Home, Classes and Schedule pages |
| **Unsplash** | Free photos for the website (saved in the project, see section 10) |
| **Google Fonts** | The fonts Cormorant Garamond and Jost (downloaded and saved in `assets/fonts/`) |
| **Git + GitHub** | Version control, and to download the project on another computer |

---

## 4. How to run it on a MacBook Air

It takes about 20 minutes. You do it only once.

### Step 1: Install MAMP

1. Go to **https://www.mamp.info/en/downloads/** and download **MAMP for macOS**
   (choose the **Apple chip** version if the MacBook has an M1, M2, M3 or M4 chip,
   see Apple menu > About This Mac).
2. Open the downloaded file and install it like any other program.
3. It installs two apps: **MAMP** and **MAMP PRO**. We only use **MAMP** (the free one).

### Step 2: Set MAMP the same as XAMPP

This is important. The WordPress pages have the address `http://localhost/lume/...` saved inside,
so MAMP must use the normal ports.

1. Open **MAMP** (Applications > MAMP > MAMP).
2. Open the **Settings** (menu MAMP > Settings, in older versions **Preferences**).
3. Go to **Ports** and click **Set Web & MySQL ports to 80 & 3306**.
   (If there is no button, type `80` for Apache / Web and `3306` for MySQL.)
4. In **Server** (or **Web Server**) check that the **Document root** is `/Applications/MAMP/htdocs`.
5. Choose PHP version **8.2** or newer if you can choose.
6. Click **OK**, then click **Start** (the Mac may ask for your Mac password because of port 80).
7. When both lights are green, MAMP is running.

### Step 3: Download the project

1. Open **https://github.com/rashka1/luma**
2. Click the green **Code** button, then **Download ZIP**.
3. Open the ZIP in Downloads. You get a folder `luma-main`. Inside it is the folder **`lume`**.

(Or with Terminal: `git clone https://github.com/rashka1/luma.git`)

### Step 4: Copy the project into MAMP

1. Copy the folder **`lume`** (not `luma-main`) into **`/Applications/MAMP/htdocs/`**.
   In Finder: Go > Go to Folder... > type `/Applications/MAMP/htdocs` > paste.
2. The name must stay **`lume`**, all small letters.
3. The folder has hidden files called `.htaccess`. Finder copies them too, but do not delete them.
   (To see hidden files in Finder press **Cmd + Shift + .**)

### Step 5: Change the database password in 2 files

MAMP's database user is `root` with the password **`root`** (XAMPP has no password).
Open these two files with a code editor (VS Code, or TextEdit in plain text mode):

**File 1:** `/Applications/MAMP/htdocs/lume/config/config.php`

```php
define('DB_HOST', '127.0.0.1');   // was 'localhost'
define('DB_PORT', 3306);
define('DB_USER', 'root');
define('DB_PASS', 'root');        // was ''
```

**File 2:** `/Applications/MAMP/htdocs/lume/website/wp-config.php`

```php
define( 'DB_USER', 'root' );
define( 'DB_PASSWORD', 'root' );  // was ''
define( 'DB_HOST', '127.0.0.1' ); // was 'localhost'
```

Save both files.

### Step 6: Create the database

1. Open **http://localhost/phpMyAdmin5/**
   (or in MAMP click **Open WebStart page**, then **Tools > phpMyAdmin**).
2. On the left click **New**. Type `lume_db` and click **Create**.
3. Click on `lume_db` on the left, then the **Import** tab at the top.
4. Import these 3 files **in this order** (choose the file, then **Import** at the bottom of the page):
   1. `/Applications/MAMP/htdocs/lume/database/schema.sql` (the tables)
   2. `/Applications/MAMP/htdocs/lume/database/seed.sql` (the example data)
   3. `/Applications/MAMP/htdocs/lume/database/wordpress.sql` (the website)

   Tip: in the file window press **Cmd + Shift + G** and type the folder path.
5. After that, `lume_db` has 4 tables for the admin panel (`users`, `members`, `classes`, `bookings`)
   and the WordPress tables that start with `wp_`.

### Step 7: Open the project

| What | Address |
|---|---|
| Website | **http://localhost/lume/** |
| Admin panel | **http://localhost/lume/admin/** |
| WordPress (to edit the website) | http://localhost/lume/website/wp-admin/ |
| Tests | http://localhost/lume/tests/run_all.php |

If the website opens with photos and the admin login works, everything is ready.

### Every time after that

1. Open MAMP and click **Start**.
2. Open http://localhost/lume/
3. When you finish, click **Stop** in MAMP.

**The day before the presentation:** import `schema.sql` and `seed.sql` again (Step 6, points 3 and 4, only the first two files).
The example classes are made from "today", so the dashboard has classes for that day.
This does not change the website.

### Other way: XAMPP on Mac

If XAMPP is used instead of MAMP, **Step 5 is not needed** (no password, same as Windows).
Copy `lume` to `/Applications/XAMPP/htdocs/`, start Apache and MySQL in **manager-osx**
(Applications > XAMPP), and open phpMyAdmin at http://localhost/phpmyadmin/.

---

## 5. Logins

| Where | Email / username | Password |
|---|---|---|
| Admin panel as **admin** | `admin@lume.test` | `lume2026` |
| Admin panel as **staff** | `staff@lume.test` | `lume2026` |
| WordPress (wp-admin) | `admin` | `lume2026` |

---

## 6. A short tour for the presentation

1. **Website:** open http://localhost/lume/, show Home, Classes, Schedule. Point at a class that is "Full".
2. Click **Staff login** in the menu. Log in as **admin**.
3. **Dashboard:** the 4 numbers and today's classes.
4. **Members:** type a name in the search box, the list changes without reloading (Ajax).
   Add a member with a wrong phone to show the error messages.
5. **Classes:** add a class for tomorrow. Open the website Schedule, the new class is there.
6. **Bookings:** New booking, choose a class. It shows "places left" (Ajax). Choose the full class: "This class is full."
7. **Attendance:** open today's class, click Present / Absent (Ajax, saved at once).
8. **Reports:** choose dates, show the totals, click Print.
9. **Users:** show that the admin can add a staff user.
10. Log out, log in as **staff**: there is no Users menu and no Delete buttons, but staff can still cancel a booking.

---

## 7. How the project works

### The folders

```
lume/
├── index.php            sends visitors to the website
├── website/             WordPress (the public website)
├── admin/               the admin pages
├── ajax/                3 small PHP files that answer jQuery with JSON
├── includes/            parts used by many pages (database, login check, functions, header, footer)
├── config/config.php    the settings (database, time zone, date format)
├── assets/              css, js, fonts, photos, logo
├── database/            the .sql files
└── tests/               the tests
```

### The admin files

| File | What it does |
|---|---|
| `admin/login.php`, `logout.php` | Log in and log out |
| `admin/dashboard.php` | The first page after login |
| `admin/members.php`, `member_form.php`, `member_delete.php` | Members list, add/edit form, delete |
| `admin/classes.php`, `class_form.php`, `class_delete.php` | Classes list, add/edit form, delete |
| `admin/bookings.php`, `booking_form.php`, `booking_delete.php` | Bookings list, new booking, cancel |
| `admin/attendance.php`, `check_in.php` | Choose a class, then mark Present / Absent |
| `admin/reports.php` | The reports |
| `admin/users.php`, `user_form.php`, `user_delete.php` | Users (admin only) |
| `includes/db.php` | Connects to the database and makes `$conn` |
| `includes/auth.php` | If you are not logged in, it sends you to the login page. It also reads your role |
| `includes/admin_only.php` | If you are not the admin, it sends you back to the dashboard |
| `includes/functions.php` | Small helper functions and the booking rules |
| `includes/validation.php` | Functions that check the forms (name, phone, email, date, time, number, password) |
| `includes/header.php`, `footer.php` | The sidebar menu and the bottom of every admin page |

### How one admin page works (example: add a member)

1. `member_form.php` starts with `auth.php` (must be logged in).
2. The first time, the page shows an empty form.
3. When you click **Save**, the form is sent to the same page with **POST**.
4. PHP checks every field with the functions from `validation.php`.
   If something is wrong, the page shows the form again with a red message under the field, and keeps what you typed.
5. If everything is ok, PHP saves the member with a **prepared statement** (`INSERT INTO members ...`).
6. PHP saves a message ("Member saved.") in the session and goes to `members.php`, which shows the message once.

### The database (4 tables)

| Table | Columns |
|---|---|
| `users` | id, full_name, email (unique), password (hashed), role (`admin` or `staff`) |
| `members` | id, full_name, phone (unique), email, status (`active` or `inactive`), join_date |
| `classes` | id, class_name, instructor, class_date, start_time, capacity |
| `bookings` | id, member_id, class_id, status (`booked`, `attended`, `absent`), booked_at |

How the tables are connected:

- `bookings.member_id` points to `members.id`. A member who has bookings **can not be deleted** (MySQL error 1451).
- `bookings.class_id` points to `classes.id` with `ON DELETE CASCADE`. When a class is deleted, its bookings are deleted too.
- `UNIQUE (member_id, class_id)`: the same member can not be booked twice in the same class (MySQL error 1062).
- `seed.sql` uses `CURDATE()` for the dates, so the example classes are always around today.

### The booking rules

The function `can_book()` in `includes/functions.php` checks, in this order:

1. The member exists
2. The member is active
3. The class exists
4. The class has not started yet
5. The member is not already booked in this class
6. The class is not full

It gives back `''` (empty) if the booking is allowed, or the reason, for example "This class is full.".
The booking form and the Ajax "places left" both use this same function.

### Other form rules

- Name: letters and spaces, 3 to 100 characters
- Phone: 7 to 15 numbers, can start with `+`, must be unique
- Email: not required for members, must be a real email format
- A new class can not be in the past. Capacity is 1 to 50, and can not be less than the people already booked
- User password: at least 8 characters, with letters and numbers

### Login and roles

1. `login.php` finds the user by email and checks the password with `password_verify()`.
   Passwords are saved with `password_hash()`, never as plain text.
2. The user's id is saved in the **session** (`$_SESSION['user_id']`).
3. Every admin page starts with `auth.php`. It checks the session and reads the user's role from the database.
4. `is_admin()` gives `true` for the admin. Pages use it to hide the Delete buttons and the Users menu from staff.
5. Hiding a button is not enough, so `member_delete.php`, `class_delete.php` and the Users pages also include
   `admin_only.php`, which stops staff on the server.

### The 3 Ajax features

| Page | What happens | PHP file |
|---|---|---|
| Members | Typing in the search box filters the list | `ajax/search_member.php` |
| New booking | Choosing a class shows "3 places left" or "This class is full." | `ajax/check_capacity.php` |
| Attendance | Clicking Present or Absent saves at once | `ajax/mark_attendance.php` |

How it works: `assets/js/app.js` (jQuery) sends the data with `$.post`. The PHP file checks
the login, runs the query and answers with JSON, for example:

```json
{ "success": true, "message": "3 places left", "data": { "can_book": true, "places_left": 3 } }
```

jQuery reads the answer and changes the page, without reloading.

### The website (WordPress)

- WordPress is in `lume/website/` and uses the **same database** `lume_db`. Its tables start with `wp_`.
- The pages were made in the **block editor** with the theme **Twenty Twenty-Five**.
  To change a page: wp-admin > Pages > click the page.
- The photos are in the Media Library (`website/wp-content/uploads/2026/09/`).
- Our only code for WordPress is a small plugin: `website/wp-content/plugins/lume_schedule/lume_schedule.php`.
  It adds 2 **shortcodes** that read the `classes` and `bookings` tables:
  - `[lume_schedule]`: the table of the next 14 days (on the Schedule page)
  - `[lume_next_classes]`: the next 3 classes (on the Home page)
- If you change the website in WordPress, export the `wp_` tables again into `database/wordpress.sql`
  (phpMyAdmin > `lume_db` > Export > Custom > select only the `wp_` tables).

### Security

| Danger | What we did |
|---|---|
| SQL injection | Every query with a value from the user uses **prepared statements** (`?` and `bind_param`) |
| XSS (someone types `<script>`) | Every value printed in HTML goes through `e()`, which uses `htmlspecialchars()` |
| Stolen passwords | Passwords are saved with `password_hash()` |
| Opening admin pages without login | `auth.php` on every admin page |
| Staff deleting by typing the address | Delete pages only work with POST and check the role with `admin_only.php` |
| Opening `config.php` or the `.sql` files in the browser | `.htaccess` in `config/`, `includes/` and `database/` blocks it |

---

## 8. Tests

The tests use their own database `lume_test` (they create it themselves), so they never change the real data.

- `tests/unit_tests.php`: the form checks in `validation.php`, `e()` and `is_admin()`
- `tests/integration_tests.php`: the 6 booking rules, places left, the unique keys, the foreign key and the cascade delete

**Run in the browser:** http://localhost/lume/tests/run_all.php

**Run in Terminal on the Mac:**

```
ls /Applications/MAMP/bin/php/
/Applications/MAMP/bin/php/php8.2.XX/bin/php /Applications/MAMP/htdocs/lume/tests/run_all.php
```

(Replace `php8.2.XX` with the folder name that `ls` shows.)

The last line must say **`RESULT: ALL TESTS PASSED`** (60 tests).

On Windows: `C:\xampp\php\php.exe tests\run_all.php`

---

## 9. Problems and fixes

| Problem | Fix |
|---|---|
| MAMP: Apache does not start | Another program uses port 80. Close it, or restart the Mac and start MAMP first |
| "Could not connect to the database" (admin panel) | MAMP is not started, or Step 5 was not done in `config/config.php` |
| "Error establishing a database connection" (website) | Same, but in `website/wp-config.php` |
| The website opens but has no pages or no photos | Import `database/wordpress.sql`. Check that MAMP uses port **80** (Step 2) |
| Classes or Schedule page says "Not Found" | The folder must be `htdocs/lume` and `lume/website/.htaccess` must be there |
| `http://localhost/lume/` does not open | Check the folder name is `lume` and the document root is `/Applications/MAMP/htdocs` |
| phpMyAdmin says "Access denied" | Log in with user `root`, password `root` |
| Blank or broken admin page | Read the PHP error on the page (errors are shown, see `config/config.php`) |
| Search, places left or Present/Absent do nothing | Chrome: right click > Inspect > **Network**, click the request and read the answer. Log in again if it says so |
| No classes today on the dashboard | Import `schema.sql` and `seed.sql` again |
| Page looks old after changing CSS | Change `?v=7` to `?v=8` in `includes/header.php`, `includes/footer.php` and `admin/login.php` |

---

## 10. Photos

The photos are from [Unsplash](https://unsplash.com) (free to use, Unsplash License).
They are in `lume/assets/img/` and in the WordPress Media Library.

| File | Photographer |
|---|---|
| `hero.jpg` | Jaspinder Singh |
| `studio.jpg`, `reformer_pilates.jpg` | Roxana Popovici |
| `mat_pilates.jpg` | Margaret Young |
| `barre.jpg` | Performance Medicine |
| `prenatal_pilates.jpg` | Jeferson Santu |
