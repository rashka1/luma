-- Lume Pilates tables
-- import this first, then seed.sql

-- delete old tables
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS bookings;
DROP TABLE IF EXISTS classes;
DROP TABLE IF EXISTS members;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;


-- people who can log in (admin or staff)
CREATE TABLE users (
    id         INT          NOT NULL AUTO_INCREMENT,
    full_name  VARCHAR(100) NOT NULL,
    email      VARCHAR(150) NOT NULL,
    password   VARCHAR(255) NOT NULL,   -- password_hash()
    role       VARCHAR(10)  NOT NULL DEFAULT 'staff',   -- 'admin' or 'staff'
    PRIMARY KEY (id),
    UNIQUE KEY unique_user_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- members
CREATE TABLE members (
    id         INT          NOT NULL AUTO_INCREMENT,
    full_name  VARCHAR(100) NOT NULL,
    phone      VARCHAR(20)  NOT NULL,
    email      VARCHAR(150) NULL,
    status     VARCHAR(10)  NOT NULL DEFAULT 'active',   -- 'active' or 'inactive'
    join_date  DATE         NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY unique_member_phone (phone)   -- phone must be unique
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- classes
CREATE TABLE classes (
    id          INT          NOT NULL AUTO_INCREMENT,
    class_name  VARCHAR(80)  NOT NULL,
    instructor  VARCHAR(100) NOT NULL,
    class_date  DATE         NOT NULL,
    start_time  TIME         NOT NULL,
    capacity    INT          NOT NULL,   -- how many people can join
    PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- bookings
CREATE TABLE bookings (
    id         INT         NOT NULL AUTO_INCREMENT,
    member_id  INT         NOT NULL,
    class_id   INT         NOT NULL,
    status     VARCHAR(10) NOT NULL DEFAULT 'booked',   -- 'booked', 'attended' or 'absent'
    booked_at  DATETIME    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),

    -- same member can't book the same class twice
    UNIQUE KEY one_booking_per_member (member_id, class_id),

    -- can't delete a member who has bookings
    FOREIGN KEY (member_id) REFERENCES members (id),

    -- delete class = delete its bookings
    FOREIGN KEY (class_id) REFERENCES classes (id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
