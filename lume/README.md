# Lume Pilates Studio Management

- **Website** (WordPress): `http://localhost/lume/`
- **Admin panel** (PHP): `http://localhost/lume/admin/`

The full guide (technology, how to run it on a Mac, how it works) is in the `README.md`
one folder up, and on GitHub: https://github.com/rashka1/luma

## Quick install

1. Copy this `lume` folder into `htdocs` (XAMPP: `C:\xampp\htdocs\`, MAMP: `/Applications/MAMP/htdocs/`)
2. Start Apache and MySQL (on MAMP set the ports to **80 & 3306**)
3. **Only on MAMP:** in `config/config.php` and `website/wp-config.php` set the host to `127.0.0.1` and the password to `root`
4. In phpMyAdmin create the database `lume_db`, then import in this order:
   `database/schema.sql`, `database/seed.sql`, `database/wordpress.sql`
5. Open `http://localhost/lume/`

## Logins

| Where | Login | Password |
|---|---|---|
| Admin panel (admin) | admin@lume.test | lume2026 |
| Admin panel (staff) | staff@lume.test | lume2026 |
| WordPress (`/lume/website/wp-admin/`) | admin | lume2026 |

## Tests

`http://localhost/lume/tests/run_all.php` must end with `RESULT: ALL TESTS PASSED`.
