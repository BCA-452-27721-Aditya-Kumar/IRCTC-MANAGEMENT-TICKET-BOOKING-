<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - IRCTC</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* (styles copied from confirmation.html) */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background-color: #f5f7fa; color: #333; line-height: 1.6; min-height: 100vh; display: flex; flex-direction: column; }
        header { background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%); color: white; padding: 1.5rem 0; text-align: center; box-shadow: 0 4px 12px rgba(0,0,0,0.1); position: relative; overflow: hidden; }
        nav { background-color: white; display: flex; justify-content: center; padding: 0.8rem 0; box-shadow: 0 2px 10px rgba(0,0,0,0.08); position: sticky; top: 0; z-index:100; }
        nav a { color: #555; text-decoration: none; padding: 0.8rem 1.5rem; margin: 0 0.5rem; border-radius: 4px; transition: all 0.3s ease; font-weight:600; display:flex; align-items:center; gap:8px; }
        /* shared user dropdown */
        .user-dropdown { position: relative; display: inline-block; margin-left: 1rem; }
        .dropdown-toggle { cursor: pointer; color: #555; display: flex; align-items: center; gap: 5px; font-weight: 600; }
        .user-dropdown .dropdown-content { display: none; position: absolute; right: 0; background-color: white; min-width: 200px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); border-radius: 8px; padding: 15px; z-index: 1000; text-align: left; }
        .user-dropdown:hover .dropdown-content { display: block; }
        .user-dropdown .dropdown-content p { margin: 0 0 8px 0; color: #333; font-size: 0.9rem; }
        .user-dropdown .dropdown-content a { display: block; color: #555; text-decoration: none; padding: 6px 0; font-size: 0.9rem; }
        .user-dropdown .dropdown-content a:hover { background: #f0f0f0; color: #d32f2f; }
        .container { max-width: 900px; margin: 3rem auto; padding: 0 1.5rem; flex:1; }
        .success-card { background: white; border-radius:15px; padding:3rem; box-shadow:0 10px 30px rgba(0,0,0,0.1); text-align:center; position:relative; overflow:hidden; border-top:5px solid #4caf50; }
        .button-group { display: flex; justify-content: center; gap: 1rem; margin-top: 2.5rem; flex-wrap: wrap; }
        .btn { padding: 0.9rem 2rem; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; justify-content: center; gap: 10px; font-size: 1rem; }
        .btn-success { background-color: #4caf50; color: white; }
        .btn-success:hover { background-color: #388e3c; transform: translateY(-3px); box-shadow: 0 7px 15px rgba(76, 175, 80, 0.3); }
        .btn-secondary { background-color: white; color: #333; border: 2px solid #ddd; }
        .btn-secondary:hover { background-color: #f5f5f5; transform: translateY(-3px); box-shadow: 0 7px 15px rgba(0, 0, 0, 0.1); }
    </style>
</head>
<body>
    <?php
    session_start();
    $user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
    $user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
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
    ?>

    <header>
        <h1><i class="fas fa-train"></i> IRCTC Train Booking</h1>
        <p>Indian Railway Catering and Tourism Corporation</p>
    </header>
    <nav>
        <a href="Index.html"><i class="fas fa-home"></i> Home</a>
        <a href="book ticket.html"><i class="fas fa-ticket-alt"></i> Book Ticket</a>
        <a href="cancel_ticket.php"><i class="fas fa-times-circle"></i> Cancel Ticket</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="profile.php"><i class="fas fa-user"></i> Profile</a>
            <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
            <div class="user-dropdown">
                <span class="dropdown-toggle"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($user_name); ?></span>
                <div class="dropdown-content">
                    <p><strong><?php echo htmlspecialchars($user_name); ?></strong></p>
                    <p><?php echo htmlspecialchars($user_email); ?></p>
                    <a href="profile.php"><i class="fas fa-user"></i> View Profile</a>
                    <a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        <?php else: ?>
            <a href="Singup.html"><i class="fas fa-user-plus"></i> Sign up</a>
            <a href="login.html"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </nav>

    <div class="container fade-in">
        <div class="success-card">
            <h2>Booking Confirmed!</h2>
            <?php if ($hasBookingDetails): ?>
                <p>Ticket ID: <?php echo htmlspecialchars($details['ticket_id']); ?></p>
                <p><?php echo htmlspecialchars($details['train']); ?> — <?php echo htmlspecialchars($details['from']); ?> to <?php echo htmlspecialchars($details['to']); ?></p>
                <p><strong>Total Fare:</strong> ₹<?php echo htmlspecialchars($details['fare']); ?></p>
                <div class="button-group">
                    <button class="btn btn-success" onclick="window.location.href='download_pdf.php?ticket_id=<?php echo urlencode($details['ticket_id']); ?>'">
                        <i class="fas fa-download"></i> Download PDF
                    </button>
                    <button class="btn btn-secondary" onclick="window.location.href='Index.html'">
                        <i class="fas fa-home"></i> Back to Home
                    </button>
                </div>
            <?php else: ?>
                <p>No booking details found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
