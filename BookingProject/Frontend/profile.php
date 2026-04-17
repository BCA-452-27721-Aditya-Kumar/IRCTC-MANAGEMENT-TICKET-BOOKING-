<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.html");
    exit();
}
include '../Backend/config.php';
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user_name'];
$user_email = $_SESSION['user_email'];

// Get user details from database if needed, but for now use session
// Assuming users table has more details, but for simplicity, use session

// Get bookings
$bookings = [];
$stmt = $conn->prepare("SELECT ticket_id, train_name, train_number, from_station, to_station, travel_date, passenger_name, passenger_age, passenger_gender, class, fare FROM bookings WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $map = ['1A' => 2000, '2A' => 1500, '3A' => 1000, 'SL' => 300, 'CC' => 700, 'EC' => 1200, '2S' => 200];
    $row['fare'] = isset($map[$row['class']]) ? $map[$row['class']] : 0;
    $bookings[] = $row;
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRCTC - User Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            min-height: 100vh;
            color: #333;
            line-height: 1.6;
        }

        /* Header */
        .main-header {
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            color: white;
            padding: 2rem 0;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: relative;
            overflow: hidden;
        }

        .main-header::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><path d="M0,50 Q25,40 50,50 T100,50 L100,100 L0,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
            background-size: 100px;
        }

        .header-content {
            position: relative;
            z-index: 1;
        }

        .header-content h1 {
            font-size: 2.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .header-content p {
            font-size: 1.2rem;
            opacity: 0.9;
        }

        /* Navigation */
        .nav-container {
            background-color: white;
            display: flex;
            justify-content: center;
            padding: 1rem 0;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-link {
            color: #555;
            text-decoration: none;
            padding: 0.8rem 2rem;
            margin: 0 0.5rem;
            border-radius: 6px;
            transition: all 0.3s ease;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1rem;
        }

        .nav-link:hover {
            background-color: #f0f0f0;
            color: #d32f2f;
            transform: translateY(-2px);
        }

        .nav-link.active {
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(211, 47, 47, 0.3);
        }
        /* user dropdown */
        .user-dropdown {
            position: relative;
            display: inline-block;
            margin-left: 1rem;
        }

        .dropdown-toggle {
            cursor: pointer;
            color: #555;
            display: flex;
            align-items: center;
            gap: 5px;
            font-weight: 600;
        }

        .user-dropdown .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            background-color: white;
            min-width: 200px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            border-radius: 8px;
            padding: 15px;
            z-index: 1000;
            text-align: left;
        }

        .user-dropdown:hover .dropdown-content {
            display: block;
        }

        .user-dropdown .dropdown-content p {
            margin: 0 0 8px 0;
            color: #333;
            font-size: 0.9rem;
        }

        .user-dropdown .dropdown-content a {
            display: block;
            color: #555;
            text-decoration: none;
            padding: 6px 0;
            font-size: 0.9rem;
        }

        .user-dropdown .dropdown-content a:hover {
            background: #f0f0f0;
            color: #d32f2f;
        }

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        /* Profile Section */
        .profile-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
        }

        .profile-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .profile-header h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .user-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .info-card {
            background: #f8f9fa;
            padding: 1.5rem;
            border-radius: 12px;
            border-left: 4px solid #d32f2f;
        }

        .info-card h3 {
            color: #2c3e50;
            margin-bottom: 0.5rem;
            font-size: 1.2rem;
        }

        .info-card p {
            color: #666;
            font-size: 1rem;
        }

        /* Bookings Section */
        .bookings-section {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        }

        .bookings-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .bookings-header h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
        }

        .tickets-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 2rem;
        }

        .tickets-table thead {
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        }

        .tickets-table th {
            padding: 1.2rem 1rem;
            text-align: left;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            border: none;
        }

        .tickets-table th:first-child {
            border-radius: 12px 0 0 0;
        }

        .tickets-table th:last-child {
            border-radius: 0 12px 0 0;
        }

        .tickets-table tbody tr {
            transition: all 0.3s ease;
            border-bottom: 1px solid #e0e0e0;
        }

        .tickets-table tbody tr:last-child {
            border-bottom: none;
        }

        .tickets-table tbody tr:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .tickets-table td {
            padding: 1.2rem 1rem;
            color: #444;
            border: none;
            vertical-align: middle;
        }

        .ticket-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #d32f2f;
            font-size: 1.1rem;
        }

        .fare-amount {
            font-weight: bold;
            color: #2e7d32;
            font-size: 1.2rem;
        }

        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1.5rem;
        }

        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #555;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .user-info {
                grid-template-columns: 1fr;
            }

            .tickets-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-content">
            <h1><i class="fas fa-user"></i> IRCTC Train Booking</h1>
            <p>Indian Railway Catering and Tourism Corporation</p>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="nav-container">
        <a href="Index.html" class="nav-link"><i class="fas fa-home"></i> Home</a>
        <a href="book ticket.html" class="nav-link"><i class="fas fa-ticket-alt"></i> Book Ticket</a>
        <a href="cancel_ticket.php" class="nav-link"><i class="fas fa-times-circle"></i> Cancel Ticket</a>
        <a href="confirmation.php" class="nav-link"><i class="fas fa-check-circle"></i> Confirmation</a>
        <a href="profile.php" class="nav-link active"><i class="fas fa-user"></i> Profile</a>
        <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="user-dropdown">
                <span class="dropdown-toggle"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($user_name); ?></span>
                <div class="dropdown-content">
                    <p><strong><?php echo htmlspecialchars($user_name); ?></strong></p>
                    <p><?php echo htmlspecialchars($user_email); ?></p>
                    <a href="profile.php"><i class="fas fa-user"></i> View Profile</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        <?php endif; ?>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Profile Section -->
        <div class="profile-section">
            <div class="profile-header">
                <h2><i class="fas fa-user-circle"></i> Your Profile</h2>
                <p>Manage your account details and view your booking history</p>
            </div>

            <div class="user-info">
                <div class="info-card">
                    <h3><i class="fas fa-user"></i> Full Name</h3>
                    <p><?php echo htmlspecialchars($user_name); ?></p>
                </div>
                <div class="info-card">
                    <h3><i class="fas fa-envelope"></i> Email Address</h3>
                    <p><?php echo htmlspecialchars($user_email); ?></p>
                </div>
                <div class="info-card">
                    <h3><i class="fas fa-id-card"></i> User ID</h3>
                    <p><?php echo htmlspecialchars($user_id); ?></p>
                </div>
                <div class="info-card">
                    <h3><i class="fas fa-ticket-alt"></i> Total Bookings</h3>
                    <p><?php echo count($bookings); ?> tickets</p>
                </div>
            </div>
        </div>

        <!-- Bookings Section -->
        <div class="bookings-section">
            <div class="bookings-header">
                <h2><i class="fas fa-history"></i> Your Bookings</h2>
                <p>All your train ticket bookings</p>
            </div>

            <?php if (empty($bookings)): ?>
                <div class="empty-state">
                    <div class="empty-state-icon">
                        <i class="fas fa-ticket-alt"></i>
                    </div>
                    <h3>No Bookings Found</h3>
                    <p>You haven't booked any tickets yet. Start your journey by booking a ticket.</p>
                    <a href="book ticket.html" class="nav-link" style="display: inline-block; background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%); color: white; padding: 1rem 2rem; margin-top: 1rem;">
                        <i class="fas fa-train"></i> Book Your First Ticket
                    </a>
                </div>
            <?php else: ?>
                <table class="tickets-table">
                    <thead>
                        <tr>
                            <th>Ticket ID</th>
                            <th>Passenger</th>
                            <th>Train</th>
                            <th>Route</th>
                            <th>Travel Date</th>
                            <th>Class</th>
                            <th>Fare</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bookings as $booking): ?>
                            <tr>
                                <td><span class="ticket-id"><?php echo htmlspecialchars($booking['ticket_id']); ?></span></td>
                                <td><?php echo htmlspecialchars($booking['passenger_name']); ?><br><small><?php echo htmlspecialchars($booking['passenger_age'] . ' yrs, ' . $booking['passenger_gender']); ?></small></td>
                                <td><?php echo htmlspecialchars($booking['train_name']); ?><br><small><?php echo htmlspecialchars($booking['train_number']); ?></small></td>
                                <td><?php echo htmlspecialchars($booking['from_station']); ?> → <?php echo htmlspecialchars($booking['to_station']); ?></td>
                                <td><?php echo htmlspecialchars($booking['travel_date']); ?></td>
                                <td><?php echo htmlspecialchars($booking['class']); ?></td>
                                <td><span class="fare-amount">₹<?php echo htmlspecialchars($booking['fare']); ?></span></td>
                                <td>
                                    <a href="confirmation.php?ticket_id=<?php echo htmlspecialchars($booking['ticket_id']); ?>" class="nav-link" style="padding: 0.5rem 1rem; margin: 0.2rem; background: linear-gradient(135deg,#4caf50,#2e7d32); color: white;">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="download_pdf.php?ticket_id=<?php echo htmlspecialchars($booking['ticket_id']); ?>" target="_blank" class="nav-link" style="padding: 0.5rem 1rem; margin: 0.2rem; background: linear-gradient(135deg,#2196f3,#0d47a1); color: white;">
                                        <i class="fas fa-download"></i> PDF
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>