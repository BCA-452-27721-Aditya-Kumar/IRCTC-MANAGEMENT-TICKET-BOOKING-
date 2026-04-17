<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $train_number = trim($_POST['train_number']);
    $train_name = trim($_POST['train_name']);
    $from_station = trim($_POST['from_station']);
    $to_station = trim($_POST['to_station']);
    $departure_time = $_POST['departure_time'];
    $arrival_time = $_POST['arrival_time'];
    $duration = trim($_POST['duration']);
    $classes_available = trim($_POST['classes_available']);

    // Validation
    $errors = [];

    if (empty($train_number) || empty($train_name) || empty($from_station) || empty($to_station) || empty($departure_time) || empty($arrival_time) || empty($duration) || empty($classes_available)) {
        $errors[] = "All fields are required.";
    }

    // Check if train_number already exists
    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM trains WHERE train_number = ?");
        $stmt->bind_param("s", $train_number);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $errors[] = "Train number already exists.";
        }
        $stmt->close();
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO trains (train_number, train_name, from_station, to_station, departure_time, arrival_time, duration, classes_available) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $train_number, $train_name, $from_station, $to_station, $departure_time, $arrival_time, $duration, $classes_available);

        if ($stmt->execute()) {
            header("Location: ../Frontend/add_train.html?success=Train added successfully");
            exit();
        } else {
            $errors[] = "Error adding train.";
        }
        $stmt->close();
    }

    if (!empty($errors)) {
        $error_string = implode("&", array_map(function($e) { return "error[]=" . urlencode($e); }, $errors));
        header("Location: ../Frontend/add_train.html?$error_string");
        exit();
    }
} else {
    header("Location: ../Frontend/add_train.html");
    exit();
}

$conn->close();
?>