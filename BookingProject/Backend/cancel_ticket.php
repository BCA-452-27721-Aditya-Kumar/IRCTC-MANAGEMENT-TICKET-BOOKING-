<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../Frontend/login.html");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ticket_id = trim($_POST['ticket_id']);

    if (empty($ticket_id)) {
        header("Location: ../Frontend/cancel_ticket.php?error=Invalid ticket ID");
        exit();
    }

    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM bookings WHERE ticket_id = ? AND user_id = ?");
    $stmt->bind_param("si", $ticket_id, $user_id);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        header("Location: ../Frontend/cancel_ticket.php?success=Ticket cancelled successfully");
        exit();
    } else {
        header("Location: ../Frontend/cancel_ticket.php?error=Ticket not found or already cancelled");
        exit();
    }

    $stmt->close();
} else {
    header("Location: ../Frontend/cancel_ticket.php");
    exit();
}

$conn->close();
?>