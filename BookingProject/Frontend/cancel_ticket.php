<!DOCTYPE html>
<?php 
session_start();
$user_name = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : '';
$user_email = isset($_SESSION['user_email']) ? $_SESSION['user_email'] : '';
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IRCTC - Cancel Ticket</title>
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

        /* Main Container */
        .container {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 2rem;
        }

        /* Page Header */
        .page-header {
            text-align: center;
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease;
        }

        .page-header h2 {
            font-size: 2.5rem;
            color: #2c3e50;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
        }

        .page-header p {
            color: #666;
            font-size: 1.1rem;
            max-width: 600px;
            margin: 0 auto;
        }

        /* Messages Container */
        .messages-container {
            max-width: 800px;
            margin: 0 auto 2rem;
            animation: slideDown 0.5s ease;
        }

        .message-card {
            padding: 1.5rem;
            border-radius: 12px;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
            animation: messagePulse 2s infinite;
        }

        .message-card.error {
            background: linear-gradient(135deg, #ff6b6b 0%, #ee5a52 100%);
            color: white;
        }

        .message-card.success {
            background: linear-gradient(135deg, #4caf50 0%, #2e7d32 100%);
            color: white;
        }

        .message-card.info {
            background: linear-gradient(135deg, #2196f3 0%, #0d47a1 100%);
            color: white;
        }

        @keyframes messagePulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.02); }
        }

        /* Tickets Container */
        .tickets-container {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
            margin-bottom: 3rem;
            animation: fadeInUp 0.8s ease;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 4rem 2rem;
            color: #666;
        }

        .empty-state-icon {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }

        .empty-state h3 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
            color: #555;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            max-width: 500px;
            margin-left: auto;
            margin-right: auto;
        }

        /* Tickets Table */
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

        /* Ticket ID Cell */
        .ticket-id {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            color: #d32f2f;
            font-size: 1.1rem;
        }

        /* Passenger Info */
        .passenger-info {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .passenger-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .passenger-details {
            font-size: 0.9rem;
            color: #666;
            display: flex;
            gap: 0.8rem;
        }

        /* Train Info */
        .train-info {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .train-name {
            font-weight: 600;
            color: #2c3e50;
        }

        .train-number {
            font-size: 0.9rem;
            color: #666;
        }

        /* Route Info */
        .route-info {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .route-station {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .station-icon {
            color: #d32f2f;
            font-size: 0.9rem;
        }

        /* Fare */
        .fare-amount {
            font-weight: bold;
            color: #2e7d32;
            font-size: 1.2rem;
        }

        /* Date */
        .travel-date {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .date-value {
            font-weight: 600;
            color: #2c3e50;
        }

        .days-left {
            font-size: 0.85rem;
            padding: 0.2rem 0.6rem;
            border-radius: 12px;
            display: inline-block;
            width: fit-content;
        }

        .days-left.warning {
            background: #fff3cd;
            color: #856404;
        }

        .days-left.success {
            background: #d4edda;
            color: #155724;
        }

        /* Cancel Button */
        .cancel-form {
            display: inline;
        }

        .cancel-btn {
            padding: 0.7rem 1.5rem;
            background: linear-gradient(135deg, #ff6b6b 0%, #d32f2f 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            box-shadow: 0 4px 12px rgba(211, 47, 47, 0.3);
        }

        .cancel-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(211, 47, 47, 0.4);
            background: linear-gradient(135deg, #ff5252 0%, #b71c1c 100%);
        }

        .cancel-btn:active {
            transform: translateY(0);
        }

        /* Home Button Container */
        .home-btn-container {
            text-align: center;
            margin-top: 3rem;
            animation: fadeInUp 0.8s ease 0.2s both;
        }

        .home-btn {
            padding: 1rem 3rem;
            background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 20px rgba(44, 62, 80, 0.3);
        }

        .home-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(44, 62, 80, 0.4);
        }

        /* Statistics Card */
        .stats-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 15px;
            margin-bottom: 2rem;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
            animation: fadeInUp 0.8s ease 0.1s both;
        }

        .stats-content {
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .stat-item {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 800;
        }

        .stat-label {
            font-size: 1rem;
            opacity: 0.9;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }

        /* Loading Animation */
        .loading-container {
            text-align: center;
            padding: 4rem;
        }

        .loader {
            display: inline-block;
            width: 50px;
            height: 50px;
            border: 4px solid #f3f3f3;
            border-top: 4px solid #d32f2f;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-bottom: 1rem;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .container {
                padding: 0 1rem;
            }

            .header-content h1 {
                font-size: 2.2rem;
            }

            .page-header h2 {
                font-size: 2rem;
            }

            .tickets-container {
                padding: 1.5rem;
            }

            .tickets-table {
                display: block;
                overflow-x: auto;
            }

            .tickets-table th,
            .tickets-table td {
                min-width: 150px;
            }

            .nav-container {
                flex-wrap: wrap;
                padding: 0.5rem;
            }

            .nav-link {
                padding: 0.7rem;
                margin: 0.2rem;
                font-size: 0.9rem;
            }

            .stats-content {
                flex-direction: column;
                gap: 2rem;
            }
        }

        /* Confirmation Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 2.5rem;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .modal-header h3 {
            font-size: 1.8rem;
            color: #2c3e50;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .modal-body {
            text-align: center;
            margin-bottom: 2rem;
            color: #666;
            line-height: 1.6;
        }

        .modal-actions {
            display: flex;
            justify-content: center;
            gap: 1rem;
        }

        .modal-btn {
            padding: 0.8rem 2rem;
            border: none;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .modal-btn.confirm {
            background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);
            color: white;
        }

        .modal-btn.cancel {
            background: #f0f0f0;
            color: #333;
        }

        .modal-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="main-header">
        <div class="header-content">
            <h1><i class="fas fa-train"></i> IRCTC Train Booking</h1>
            <p>Indian Railway Catering and Tourism Corporation</p>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="nav-container">
        <a href="Index.html" class="nav-link"><i class="fas fa-home"></i> Home</a>
        <a href="book ticket.html" class="nav-link"><i class="fas fa-ticket-alt"></i> Book Ticket</a>
        <a href="#" class="nav-link active"><i class="fas fa-times-circle"></i> Cancel Ticket</a>
        <a href="confirmation.php" class="nav-link"><i class="fas fa-check-circle"></i> Confirmation</a>
        <a href="profile.php" class="nav-link"><i class="fas fa-user"></i> Profile</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
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
            <a href="Singup.html" class="nav-link"><i class="fas fa-user-plus"></i> Sign up</a>
            <a href="login.html" class="nav-link"><i class="fas fa-sign-in-alt"></i> Login</a>
        <?php endif; ?>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <h2><i class="fas fa-ticket-alt"></i> Manage Your Bookings</h2>
            <p>View, manage, or cancel your train tickets. All your bookings in one place.</p>
        </div>

        <!-- Messages Container -->
        <div id="messages" class="messages-container"></div>

        <!-- Statistics Card -->
        <div class="stats-card" id="statsCard" style="display: none;">
            <div class="stats-content">
                <div class="stat-item">
                    <div class="stat-number" id="totalTickets">0</div>
                    <div class="stat-label">Total Bookings</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="totalFare">₹0</div>
                    <div class="stat-label">Total Fare</div>
                </div>
                <div class="stat-item">
                    <div class="stat-number" id="upcomingTrips">0</div>
                    <div class="stat-label">Upcoming Trips</div>
                </div>
            </div>
        </div>

        <!-- Tickets Container -->
        <div class="tickets-container">
            <!-- Loading State -->
            <div class="loading-container" id="loadingState">
                <div class="loader"></div>
                <p>Loading your bookings...</p>
            </div>

            <!-- Empty State -->
            <div class="empty-state" id="emptyState" style="display: none;">
                <div class="empty-state-icon">
                    <i class="fas fa-ticket-alt"></i>
                </div>
                <h3>No Bookings Found</h3>
                <p>You don't have any active bookings at the moment. Book your next journey to see tickets here.</p>
                <button onclick="window.location.href='book ticket.html'" class="home-btn" style="background: linear-gradient(135deg, #d32f2f 0%, #b71c1c 100%);">
                    <i class="fas fa-train"></i> Book New Ticket
                </button>
            </div>

            <!-- Tickets Table -->
            <table class="tickets-table" id="ticketTable" style="display: none;">
                <thead>
                    <tr>
                        <th>Ticket ID</th>
                        <th>Passenger Details</th>
                        <th>Fare</th>
                        <th>Train</th>
                        <th>Route</th>
                        <th>Travel Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="ticketTableBody">
                    <!-- Tickets will be loaded here -->
                </tbody>
            </table>
        </div>

        <!-- Home Button -->
        <div class="home-btn-container">
            <button onclick="goToHomePage()" class="home-btn">
                <i class="fas fa-home"></i> Back to Home
            </button>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal-overlay" id="confirmationModal">
        <div class="modal-content">
            <div class="modal-header">
                <h3><i class="fas fa-exclamation-triangle"></i> Confirm Cancellation</h3>
                <p>Please review before proceeding</p>
            </div>
            <div class="modal-body">
                <p id="modalMessage">Are you sure you want to cancel this ticket?</p>
                <div id="modalDetails" style="text-align: left; background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-top: 1rem;">
                    <!-- Ticket details will be inserted here -->
                </div>
                <p style="color: #d32f2f; font-weight: 600; margin-top: 1rem;">
                    <i class="fas fa-info-circle"></i> Cancellation charges may apply
                </p>
            </div>
            <div class="modal-actions">
                <button class="modal-btn cancel" onclick="closeModal()">
                    <i class="fas fa-times"></i> Keep Ticket
                </button>
                <form id="cancelForm" action="../Backend/cancel_ticket.php" method="post" style="display: inline;">
                    <input type="hidden" name="ticket_id" id="modalTicketId">
                    <button type="submit" class="modal-btn confirm">
                        <i class="fas fa-check"></i> Yes, Cancel Ticket
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Original logic functions (unchanged)
        function loadTickets() {
            const loadingState = document.getElementById('loadingState');
            const emptyState = document.getElementById('emptyState');
            const ticketTable = document.getElementById('ticketTable');
            const tableBody = document.getElementById('ticketTableBody');
            const statsCard = document.getElementById('statsCard');

            fetch('../Backend/get_bookings.php')
                .then(response => response.json())
                .then(bookings => {
                    // Hide loading state
                    loadingState.style.display = 'none';

                    if (bookings.length === 0) {
                        emptyState.style.display = 'block';
                        ticketTable.style.display = 'none';
                        statsCard.style.display = 'none';
                        return;
                    }

                    // Show table and stats
                    emptyState.style.display = 'none';
                    ticketTable.style.display = 'table';
                    statsCard.style.display = 'block';

                    // Clear table
                    tableBody.innerHTML = '';

                    let totalFare = 0;
                    let upcomingTrips = 0;

                    bookings.forEach(booking => {
                        const row = document.createElement('tr');
                        row.style.animation = 'fadeInUp 0.5s ease';
                        
                        // Calculate days until travel
                        const travelDate = new Date(booking.travel_date);
                        const today = new Date();
                        const daysDiff = Math.ceil((travelDate - today) / (1000 * 60 * 60 * 24));
                        
                        // Update statistics
                        totalFare += parseFloat(booking.fare);
                        if (daysDiff > 0) upcomingTrips++;

                        // Format passenger details
                        const passengerDetails = booking.passenger_age || booking.passenger_gender ? 
                            `${booking.passenger_name} (${booking.passenger_age || ''}${booking.passenger_age && booking.passenger_gender ? ', ' : ''}${booking.passenger_gender || ''})` : 
                            booking.passenger_name;

                        // Create days left badge
                        let daysBadge = '';
                        if (daysDiff > 0) {
                            daysBadge = `<span class="days-left ${daysDiff <= 3 ? 'warning' : 'success'}">
                                ${daysDiff} day${daysDiff !== 1 ? 's' : ''} left
                            </span>`;
                        } else if (daysDiff === 0) {
                            daysBadge = '<span class="days-left warning">Today</span>';
                        } else {
                            daysBadge = '<span class="days-left" style="background:#f8d7da;color:#721c24;">Expired</span>';
                        }

                        row.innerHTML = `
                            <td><span class="ticket-id">${booking.ticket_id}</span></td>
                            <td>
                                <div class="passenger-info">
                                    <span class="passenger-name">${booking.passenger_name}</span>
                                    ${(booking.passenger_age || booking.passenger_gender) ? 
                                        `<div class="passenger-details">
                                            ${booking.passenger_age ? `<span><i class="fas fa-birthday-cake"></i> ${booking.passenger_age}</span>` : ''}
                                            ${booking.passenger_gender ? `<span><i class="fas fa-venus-mars"></i> ${booking.passenger_gender}</span>` : ''}
                                        </div>` : ''
                                    }
                                </div>
                            </td>
                            <td><span class="fare-amount">₹${booking.fare}</span></td>
                            <td>
                                <div class="train-info">
                                    <span class="train-name">${booking.train_name}</span>
                                    <span class="train-number">${booking.train_number}</span>
                                </div>
                            </td>
                            <td>
                                <div class="route-info">
                                    <div class="route-station">
                                        <i class="fas fa-map-marker-alt station-icon"></i>
                                        <span>${booking.from_station}</span>
                                    </div>
                                    <div style="font-size: 0.8rem; color: #999; text-align: center;">↓</div>
                                    <div class="route-station">
                                        <i class="fas fa-flag-checkered station-icon"></i>
                                        <span>${booking.to_station}</span>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="travel-date">
                                    <span class="date-value">${booking.travel_date}</span>
                                    ${daysBadge}
                                </div>
                            </td>
                            <td>
                                <button class="cancel-btn" onclick="showConfirmationModal('${booking.ticket_id}', '${booking.passenger_name}', '${booking.train_name}', '${booking.travel_date}', '${booking.fare}')">
                                    <i class="fas fa-times"></i> Cancel
                                </button>
                                <a href="confirmation.php?ticket_id=${booking.ticket_id}" style="display:inline-block; margin-left:8px; padding:0.6rem 0.9rem; background:linear-gradient(135deg,#4caf50,#2e7d32); color:white; border-radius:8px; text-decoration:none; font-weight:600;">
                                    <i class="fas fa-eye"></i> View
                                </a>
                                <a href="download_pdf.php?ticket_id=${booking.ticket_id}" target="_blank" style="display:inline-block; margin-left:8px; padding:0.6rem 0.9rem; background:linear-gradient(135deg,#2196f3,#0d47a1); color:white; border-radius:8px; text-decoration:none; font-weight:600;">
                                    <i class="fas fa-download"></i> PDF
                                </a>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });

                    // Update statistics
                    document.getElementById('totalTickets').textContent = bookings.length;
                    document.getElementById('totalFare').textContent = `₹${totalFare.toFixed(2)}`;
                    document.getElementById('upcomingTrips').textContent = upcomingTrips;

                })
                .catch(error => {
                    console.error('Error loading bookings:', error);
                    loadingState.style.display = 'none';
                    emptyState.innerHTML = `
                        <div class="empty-state-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                        <h3>Unable to Load Bookings</h3>
                        <p>There was an error loading your bookings. Please try again later.</p>
                        <button onclick="loadTickets()" class="home-btn" style="background: linear-gradient(135deg, #2196f3 0%, #0d47a1 100%);">
                            <i class="fas fa-redo"></i> Retry
                        </button>
                    `;
                    emptyState.style.display = 'block';
                });
        }

        function goToHomePage() {
            window.location.href = "Index.html";
        }

        // Modal Functions
        let currentTicketId = '';

        function showConfirmationModal(ticketId, passengerName, trainName, travelDate, fare) {
            currentTicketId = ticketId;
            
            document.getElementById('modalTicketId').value = ticketId;
            document.getElementById('modalMessage').textContent = `Cancel ticket for ${passengerName}?`;
            
            document.getElementById('modalDetails').innerHTML = `
                <p><strong>Ticket ID:</strong> ${ticketId}</p>
                <p><strong>Passenger:</strong> ${passengerName}</p>
                <p><strong>Train:</strong> ${trainName}</p>
                <p><strong>Travel Date:</strong> ${travelDate}</p>
                <p><strong>Fare:</strong> ₹${fare}</p>
            `;
            
            document.getElementById('confirmationModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('confirmationModal').style.display = 'none';
            currentTicketId = '';
        }

        // Close modal when clicking outside
        document.getElementById('confirmationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeModal();
            }
        });

        // Original messages display logic (unchanged)
        const urlParams = new URLSearchParams(window.location.search);
        const error = urlParams.get('error');
        const success = urlParams.get('success');
        const messagesDiv = document.getElementById('messages');
        
        if (error) {
            messagesDiv.innerHTML = `
                <div class="message-card error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>${error}</div>
                </div>
            `;
        } else if (success) {
            messagesDiv.innerHTML = `
                <div class="message-card success">
                    <i class="fas fa-check-circle"></i>
                    <div>${success}</div>
                </div>
            `;
            
            // Auto-hide success message after 5 seconds
            setTimeout(() => {
                messagesDiv.innerHTML = '';
            }, 5000);
        }

        // Initial load
        window.onload = loadTickets;
    </script>
</body>
</html>