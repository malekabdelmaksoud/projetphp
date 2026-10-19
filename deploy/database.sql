-- =============================================
-- CovoiturageApp - Database Setup
-- Import this file in phpMyAdmin on your host
-- =============================================

-- Table: users
CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(20) NOT NULL,
    email VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table: trips
CREATE TABLE IF NOT EXISTS trips (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    departure VARCHAR(100) NOT NULL,
    destination VARCHAR(100) NOT NULL,
    trip_date DATE NOT NULL,
    seats INT NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Table: bookings
CREATE TABLE IF NOT EXISTS bookings (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    trip_id INT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (trip_id) REFERENCES trips(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
