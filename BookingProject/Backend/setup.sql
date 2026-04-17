-- Create database
CREATE DATABASE IF NOT EXISTS booking_project;

-- Use the database
USE booking_project;

-- Create users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create bookings table
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    train_number VARCHAR(10) NOT NULL,
    train_name VARCHAR(255) NOT NULL,
    from_station VARCHAR(255) NOT NULL,
    to_station VARCHAR(255) NOT NULL,
    travel_date DATE NOT NULL,
    class VARCHAR(50) NOT NULL,
    passenger_name VARCHAR(255) NOT NULL,
    passenger_age INT NOT NULL,
    passenger_gender VARCHAR(10) NOT NULL,
    passenger_email VARCHAR(255) NOT NULL,
    passenger_phone VARCHAR(15) NOT NULL,
    ticket_id VARCHAR(10) UNIQUE NOT NULL,
    fare DECIMAL(10,2) NOT NULL,
    booked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Create trains table
CREATE TABLE IF NOT EXISTS trains (
    id INT AUTO_INCREMENT PRIMARY KEY,
    train_number VARCHAR(10) UNIQUE NOT NULL,
    train_name VARCHAR(255) NOT NULL,
    from_station VARCHAR(255) NOT NULL,
    to_station VARCHAR(255) NOT NULL,
    departure_time TIME NOT NULL,
    arrival_time TIME NOT NULL,
    duration VARCHAR(10) NOT NULL,
    classes_available TEXT NOT NULL -- JSON or comma-separated classes
);

-- Insert some sample trains
INSERT INTO trains (train_number, train_name, from_station, to_station, departure_time, arrival_time, duration, classes_available) VALUES
('12345', 'Rajdhani Express', 'Delhi', 'Mumbai', '10:00:00', '18:00:00', '8h', '1A,2A,3A,SL'),
('67890', 'Shatabdi Express', 'Delhi', 'Chennai', '08:00:00', '14:00:00', '6h', 'CC,EC'),
('54321', 'Garib Rath', 'Mumbai', 'Kolkata', '21:00:00', '05:00:00', '8h', '3A,SL'),
('11111', 'Duronto Express', 'Delhi', 'Kolkata', '22:00:00', '10:00:00', '12h', '1A,2A,3A'),
('22222', 'Jan Shatabdi', 'Delhi', 'Pune', '06:00:00', '18:00:00', '12h', 'CC,2S'),
('33333', 'Intercity Express', 'Mumbai', 'Delhi', '07:00:00', '19:00:00', '12h', 'CC,2S,SL'),
('44444', 'Superfast Express', 'Chennai', 'Delhi', '20:00:00', '08:00:00', '12h', '1A,2A,3A,SL'),
('55555', 'Garib Rath', 'Kolkata', 'Mumbai', '23:00:00', '11:00:00', '12h', '3A,SL'),
('66666', 'Rajdhani Express', 'Chennai', 'Mumbai', '09:00:00', '21:00:00', '12h', '1A,2A,3A'),
('77777', 'Shatabdi Express', 'Pune', 'Delhi', '05:00:00', '17:00:00', '12h', 'CC,EC'),
('88888', 'Duronto Express', 'Bangalore', 'Delhi', '21:00:00', '09:00:00', '12h', '1A,2A,3A'),
('99999', 'Jan Shatabdi', 'Hyderabad', 'Delhi', '04:00:00', '16:00:00', '12h', 'CC,2S'),
('00001', 'Intercity Express', 'Ahmedabad', 'Delhi', '08:00:00', '20:00:00', '12h', 'CC,SL'),
('00002', 'Superfast Express', 'Jaipur', 'Delhi', '10:00:00', '22:00:00', '12h', '2A,3A,SL');