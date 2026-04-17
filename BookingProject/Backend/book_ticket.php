<?php
session_start();
include 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../Frontend/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $passenger_name = trim($_POST['name']);
    $passenger_age = (int)$_POST['age'];
    $passenger_gender = $_POST['gender'];
    $passenger_email = trim($_POST['email']);
    $passenger_phone = trim($_POST['phone']);

    $train_number = $_POST['train_number'];
    $train_name = $_POST['train_name'];
    $from_station = $_POST['from'];
    $to_station = $_POST['to'];
    $travel_date = $_POST['date'];
    $class = $_POST['class'];

    // Validation
    $errors = [];

    if (empty($passenger_name)) {
        $errors[] = "Passenger name is required.";
    } elseif (!preg_match('/^[a-zA-Z\s]+$/', $passenger_name)) {
        $errors[] = "Passenger name must contain only letters and spaces.";
    }

    if ($passenger_age <= 5 || $passenger_age > 120) {
        $errors[] = "Passenger must be older than 5 years to book tickets.";
    }

    if (!in_array($passenger_gender, ['Male', 'Female', 'Other'])) {
        $errors[] = "Valid gender is required.";
    }

    if (empty($passenger_email) || !filter_var($passenger_email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required.";
    }

    if (empty($passenger_phone)) {
        $errors[] = "Phone number is required.";
    } elseif (!preg_match('/^\d{10}$/', $passenger_phone)) {
        $errors[] = "Phone number must be exactly 10 digits and should not contain alphabets or special characters.";
    }

    if (empty($train_number) || empty($train_name) || empty($from_station) || empty($to_station) || empty($travel_date) || empty($class)) {
        $errors[] = "Train and travel details are required.";
    } else {
        // Check if train exists and class is available
        $stmt = $conn->prepare("SELECT classes_available FROM trains WHERE train_number = ? AND from_station = ? AND to_station = ?");
        $stmt->bind_param("sss", $train_number, $from_station, $to_station);
        $stmt->execute();
        $stmt->bind_result($classes_available);
        if ($stmt->fetch()) {
            $classes = explode(',', $classes_available);
            if (!in_array($class, $classes)) {
                $errors[] = "Selected class is not available for this train.";
            }
        } else {
            $errors[] = "Invalid train selection.";
        }
        $stmt->close();
    }

    // Generate ticket ID
    $ticket_id = 'TKT' . strtoupper(substr(md5(uniqid()), 0, 6));

    // Compute fare
    $fare_map = [
        '1A' => 2000,
        '2A' => 1500,
        '3A' => 1000,
        'SL' => 300,
        'CC' => 700,
        'EC' => 1200,
        '2S' => 200
    ];
    $fare = isset($fare_map[$class]) ? $fare_map[$class] : 0;

    // Insert booking
    if (empty($errors)) {
        $user_id = $_SESSION['user_id'];

        $stmt = $conn->prepare("INSERT INTO bookings (user_id, train_number, train_name, from_station, to_station, travel_date, class, passenger_name, passenger_age, passenger_gender, passenger_email, passenger_phone, ticket_id, fare) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssisssssd", $user_id, $train_number, $train_name, $from_station, $to_station, $travel_date, $class, $passenger_name, $passenger_age, $passenger_gender, $passenger_email, $passenger_phone, $ticket_id, $fare);

        if ($stmt->execute()) {

            $booking_details = [
                'ticket_id' => $ticket_id,
                'train' => $train_name . ' (' . $train_number . ')',
                'from' => $from_station,
                'to' => $to_station,
                'date' => $travel_date,
                'class' => $class,
                'passenger' => $passenger_name . ', Age: ' . $passenger_age . ', Gender: ' . $passenger_gender,
                'email' => $passenger_email,
                'phone' => $passenger_phone,
                'fare' => $fare
            ];
            $_SESSION['booking_details'] = $booking_details;
            // Redirect to payment page
            header("Location: ../Frontend/payment.php");
            exit();
        } else {
            $errors[] = "Error booking ticket. Please try again.";
        }
        $stmt->close();
    }

    // If errors, redirect back
    if (!empty($errors)) {
        $error_string = implode("&", array_map(function($e) { return "error[]=" . urlencode($e); }, $errors));
        header("Location: ../Frontend/book ticket.html?$error_string");
        exit();
    }
} else {
    header("Location: ../Frontend/book ticket.html");
    exit();
}

$conn->close();
?>