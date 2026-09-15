-- example data, import after schema.sql
-- dates use CURDATE() so they are always around today
-- logins: admin@lume.test (admin) and staff@lume.test (staff), password lume2026
-- class 15 (tomorrow) is full

-- password is lume2026
INSERT INTO users (id, full_name, email, password, role) VALUES
(1, 'Studio Admin', 'admin@lume.test', '$2y$10$Oe3rsnEXSFPHwf8msJYIde1931VD3NqUFpFWo4vobhOhfz3fd5z5G', 'admin'),
(2, 'Front Desk Staff', 'staff@lume.test', '$2y$10$Oe3rsnEXSFPHwf8msJYIde1931VD3NqUFpFWo4vobhOhfz3fd5z5G', 'staff');


INSERT INTO members (id, full_name, phone, email, status, join_date) VALUES
(1,  'Amina Yusuf',    '0612000001', 'amina@mail.test',  'active',   CURDATE() - INTERVAL 300 DAY),
(2,  'Hodan Ali',      '0612000002', NULL,               'active',   CURDATE() - INTERVAL 280 DAY),
(3,  'Sarah Mohamed',  '0612000003', 'sarah@mail.test',  'active',   CURDATE() - INTERVAL 250 DAY),
(4,  'Leila Hassan',   '0612000004', NULL,               'active',   CURDATE() - INTERVAL 220 DAY),
(5,  'Nasra Omar',     '0612000005', NULL,               'inactive', CURDATE() - INTERVAL 200 DAY),
(6,  'Fartun Abdi',    '0612000006', 'fartun@mail.test', 'active',   CURDATE() - INTERVAL 180 DAY),
(7,  'Khadija Farah',  '0612000007', NULL,               'active',   CURDATE() - INTERVAL 160 DAY),
(8,  'Ifrah Jama',     '0612000008', 'ifrah@mail.test',  'active',   CURDATE() - INTERVAL 140 DAY),
(9,  'Deeqa Nur',      '0612000009', NULL,               'active',   CURDATE() - INTERVAL 120 DAY),
(10, 'Sagal Osman',    '0612000010', 'sagal@mail.test',  'active',   CURDATE() - INTERVAL 100 DAY),
(11, 'Maria Rossi',    '0612000011', 'maria@mail.test',  'active',   CURDATE() - INTERVAL 90 DAY),
(12, 'Grace Mwangi',   '0612000012', NULL,               'inactive', CURDATE() - INTERVAL 80 DAY),
(13, 'Yasmin Qalib',   '0612000013', NULL,               'active',   CURDATE() - INTERVAL 60 DAY),
(14, 'Salma Ahmed',    '0612000014', 'salma@mail.test',  'active',   CURDATE() - INTERVAL 10 DAY),
(15, 'Nadia Karim',    '0612000015', NULL,               'active',   CURDATE() - INTERVAL 3 DAY);


INSERT INTO classes (id, class_name, instructor, class_date, start_time, capacity) VALUES
-- past
(1,  'Mat Pilates',      'Sofia Hassan',   CURDATE() - INTERVAL 14 DAY, '07:00:00', 10),
(2,  'Reformer Pilates', 'Layla Ahmed',    CURDATE() - INTERVAL 13 DAY, '12:30:00', 6),
(3,  'Barre',            'Hana Ibrahim',   CURDATE() - INTERVAL 12 DAY, '18:00:00', 10),
(4,  'Prenatal Pilates', 'Maryam Warsame', CURDATE() - INTERVAL 11 DAY, '10:00:00', 8),
(5,  'Mat Pilates',      'Sofia Hassan',   CURDATE() - INTERVAL 9 DAY,  '07:00:00', 10),
(6,  'Reformer Pilates', 'Layla Ahmed',    CURDATE() - INTERVAL 7 DAY,  '12:30:00', 6),
(7,  'Barre',            'Hana Ibrahim',   CURDATE() - INTERVAL 5 DAY,  '18:00:00', 10),
(8,  'Mat Pilates',      'Sofia Hassan',   CURDATE() - INTERVAL 4 DAY,  '07:00:00', 10),
(9,  'Reformer Pilates', 'Layla Ahmed',    CURDATE() - INTERVAL 2 DAY,  '12:30:00', 6),
(10, 'Prenatal Pilates', 'Maryam Warsame', CURDATE() - INTERVAL 1 DAY,  '10:00:00', 8),
-- today
(11, 'Mat Pilates',      'Sofia Hassan',   CURDATE(),                   '07:00:00', 10),
(12, 'Reformer Pilates', 'Layla Ahmed',    CURDATE(),                   '12:30:00', 6),
(13, 'Barre',            'Hana Ibrahim',   CURDATE(),                   '18:00:00', 10),
-- next days
(14, 'Mat Pilates',      'Sofia Hassan',   CURDATE() + INTERVAL 1 DAY,  '07:00:00', 10),
(15, 'Reformer Pilates', 'Layla Ahmed',    CURDATE() + INTERVAL 1 DAY,  '12:30:00', 4),
(16, 'Prenatal Pilates', 'Maryam Warsame', CURDATE() + INTERVAL 2 DAY,  '10:00:00', 8),
(17, 'Barre',            'Hana Ibrahim',   CURDATE() + INTERVAL 3 DAY,  '18:00:00', 10),
(18, 'Mat Pilates',      'Sofia Hassan',   CURDATE() + INTERVAL 4 DAY,  '07:00:00', 10),
(19, 'Reformer Pilates', 'Layla Ahmed',    CURDATE() + INTERVAL 5 DAY,  '12:30:00', 6),
(20, 'Barre',            'Hana Ibrahim',   CURDATE() + INTERVAL 6 DAY,  '18:00:00', 10);


-- bookings for past classes
INSERT INTO bookings (member_id, class_id, status) VALUES
(1, 1, 'attended'), (2, 1, 'attended'), (3, 1, 'attended'), (5, 1, 'attended'), (6, 1, 'absent'),
(2, 2, 'attended'), (4, 2, 'attended'), (7, 2, 'attended'), (8, 2, 'absent'),
(1, 3, 'attended'), (3, 3, 'attended'), (9, 3, 'attended'), (10, 3, 'absent'), (12, 3, 'attended'),
(6, 4, 'attended'), (11, 4, 'attended'), (13, 4, 'absent'),
(1, 5, 'attended'), (2, 5, 'attended'), (4, 5, 'attended'), (5, 5, 'absent'), (7, 5, 'attended'),
(3, 6, 'attended'), (8, 6, 'attended'), (9, 6, 'attended'), (10, 6, 'attended'), (11, 6, 'attended'), (13, 6, 'attended'),
(1, 7, 'attended'), (2, 7, 'absent'), (6, 7, 'attended'), (12, 7, 'attended'),
(3, 8, 'attended'), (4, 8, 'attended'), (7, 8, 'attended'), (9, 8, 'attended'), (11, 8, 'absent'),
(2, 9, 'attended'), (8, 9, 'attended'), (10, 9, 'attended'), (13, 9, 'absent'),
(1, 10, 'attended'), (6, 10, 'attended'), (11, 10, 'attended');

-- bookings for today
INSERT INTO bookings (member_id, class_id, status) VALUES
(1, 11, 'booked'), (3, 11, 'booked'), (4, 11, 'booked'), (7, 11, 'booked'), (9, 11, 'booked'),
(2, 12, 'booked'), (8, 12, 'booked'),
(6, 13, 'booked'), (10, 13, 'booked'), (11, 13, 'booked'), (13, 13, 'booked');

-- bookings for next days (class 15 is full)
INSERT INTO bookings (member_id, class_id, status) VALUES
(1, 14, 'booked'), (2, 14, 'booked'), (9, 14, 'booked'),
(3, 15, 'booked'), (4, 15, 'booked'), (7, 15, 'booked'), (8, 15, 'booked'),
(6, 16, 'booked'), (11, 16, 'booked'),
(1, 17, 'booked'), (10, 17, 'booked'),
(2, 18, 'booked'), (3, 18, 'booked'), (4, 18, 'booked'), (13, 18, 'booked'),
(8, 19, 'booked');
