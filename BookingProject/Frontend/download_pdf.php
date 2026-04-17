<?php
require_once '../vendor/autoload.php'; // Load Composer autoloader

use Dompdf\Dompdf;

session_start();
$hasBookingDetails = false;
$details = [];
$detailsFromSession = false;

if (isset($_SESSION['booking_details'])) {
    $details = $_SESSION['booking_details'];
    $hasBookingDetails = true;
    $detailsFromSession = true;
}

if (!$hasBookingDetails && isset($_GET['ticket_id']) && !empty($_GET['ticket_id'])) {
    include __DIR__ . '/../Backend/config.php';
    $ticket_id = $_GET['ticket_id'];
    $stmt = $conn->prepare("SELECT ticket_id, train_number, train_name, from_station, to_station, travel_date, class, passenger_name, passenger_age, passenger_gender, passenger_email, passenger_phone, fare FROM bookings WHERE ticket_id = ? LIMIT 1");
    if ($stmt) {
        $stmt->bind_param('s', $ticket_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($row = $result->fetch_assoc()) {
            $details = [
                'ticket_id' => $row['ticket_id'],
                'train' => $row['train_name'] . ' (' . $row['train_number'] . ')',
                'from' => $row['from_station'],
                'to' => $row['to_station'],
                'date' => $row['travel_date'],
                'class' => $row['class'],
                'passenger' => $row['passenger_name'] . ', Age: ' . $row['passenger_age'] . ', Gender: ' . $row['passenger_gender'],
                'email' => $row['passenger_email'],
                'phone' => $row['passenger_phone'],
                'fare' => $row['fare']
            ];
            $hasBookingDetails = true;
        }
        $stmt->close();
    }
    $conn->close();
}

if (!$hasBookingDetails) {
    die('No booking details found.');
}

// Generate HTML for PDF
$html = '
<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .ticket { border: 1px solid #000; padding: 20px; max-width: 600px; margin: auto; }
        h1 { text-align: center; }
        .detail { margin: 10px 0; }
    </style>
</head>
<body>
    <div class="ticket">
        <h1>Train Ticket</h1>
        <div class="detail"><strong>Ticket ID:</strong> ' . htmlspecialchars($details['ticket_id']) . '</div>
        <div class="detail"><strong>Train:</strong> ' . htmlspecialchars($details['train']) . '</div>
        <div class="detail"><strong>From:</strong> ' . htmlspecialchars($details['from']) . ' <strong>To:</strong> ' . htmlspecialchars($details['to']) . '</div>
        <div class="detail"><strong>Date:</strong> ' . htmlspecialchars($details['date']) . '</div>
        <div class="detail"><strong>Class:</strong> ' . htmlspecialchars($details['class']) . '</div>
        <div class="detail"><strong>Passenger:</strong> ' . htmlspecialchars($details['passenger']) . '</div>
        <div class="detail"><strong>Email:</strong> ' . htmlspecialchars($details['email']) . '</div>
        <div class="detail"><strong>Phone:</strong> ' . htmlspecialchars($details['phone']) . '</div>
        <div class="detail"><strong>Fare:</strong> ₹' . htmlspecialchars($details['fare']) . '</div>
    </div>
</body>
</html>
';

// Create PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output PDF
$dompdf->stream('ticket_' . $details['ticket_id'] . '.pdf', array('Attachment' => 1));
?></content>
<parameter name="filePath">d:\xampp\htdocs\BookingProject\Frontend\download_pdf.php