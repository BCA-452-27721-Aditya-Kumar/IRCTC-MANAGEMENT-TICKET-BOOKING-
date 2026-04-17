<?php
session_start();
include 'config.php';

// Simple fare mapping by class. Adjust values as needed.
function calculateFare($class) {
    $map = [
        '1A' => 2000,
        '2A' => 1500,
        '3A' => 1000,
        'SL' => 300,
        'CC' => 700,
        'EC' => 1200,
        '2S' => 200
    ];
    return isset($map[$class]) ? $map[$class] : 0;
}

$bookings = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $stmt = $conn->prepare("SELECT ticket_id, train_name, train_number, from_station, to_station, travel_date, passenger_name, passenger_age, passenger_gender, class, fare FROM bookings WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
    $stmt->close();
} else {
    // Allow guest view: return all bookings so tickets are visible even when not logged in
    $result = $conn->query("SELECT ticket_id, train_name, train_number, from_station, to_station, travel_date, passenger_name, passenger_age, passenger_gender, class, fare FROM bookings");
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($bookings);
?>