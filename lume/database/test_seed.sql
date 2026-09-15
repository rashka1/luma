-- data for the tests (lume_test database)
-- members 1-4 active, member 5 inactive
-- class 1: member 1 booked
-- class 2: full (capacity 3)
-- class 3: empty
-- class 4: in the past

-- password is lume2026
INSERT INTO users (id, full_name, email, password, role) VALUES
(1, 'Test Admin', 'admin@lume.test', '$2y$10$Oe3rsnEXSFPHwf8msJYIde1931VD3NqUFpFWo4vobhOhfz3fd5z5G', 'admin'),
(2, 'Test Staff', 'staff@lume.test', '$2y$10$Oe3rsnEXSFPHwf8msJYIde1931VD3NqUFpFWo4vobhOhfz3fd5z5G', 'staff');

INSERT INTO members (id, full_name, phone, email, status, join_date) VALUES
(1, 'Amina Yusuf',   '0611000001', 'amina@mail.test', 'active',   CURDATE() - INTERVAL 60 DAY),
(2, 'Hodan Ali',     '0611000002', NULL,              'active',   CURDATE() - INTERVAL 60 DAY),
(3, 'Sarah Mohamed', '0611000003', NULL,              'active',   CURDATE() - INTERVAL 60 DAY),
(4, 'Leila Hassan',  '0611000004', NULL,              'active',   CURDATE() - INTERVAL 60 DAY),
(5, 'Nasra Omar',    '0611000005', NULL,              'inactive', CURDATE() - INTERVAL 60 DAY);

INSERT INTO classes (id, class_name, instructor, class_date, start_time, capacity) VALUES
(1, 'Mat Pilates',      'Sofia Hassan', CURDATE() + INTERVAL 7 DAY, '09:00:00', 10),
(2, 'Reformer Pilates', 'Layla Ahmed',  CURDATE() + INTERVAL 7 DAY, '11:00:00', 3),
(3, 'Barre',            'Sofia Hassan', CURDATE() + INTERVAL 7 DAY, '17:00:00', 10),
(4, 'Mat Pilates',      'Sofia Hassan', CURDATE() - INTERVAL 7 DAY, '09:00:00', 10);

INSERT INTO bookings (id, member_id, class_id, status) VALUES
(1, 1, 1, 'booked'),
(2, 2, 2, 'booked'),
(3, 3, 2, 'booked'),
(4, 4, 2, 'booked'),
(5, 1, 4, 'attended'),
(6, 2, 4, 'absent');
