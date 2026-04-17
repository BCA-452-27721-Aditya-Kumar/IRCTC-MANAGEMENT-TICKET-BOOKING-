# Railway Ticket Booking System

A comprehensive web-based train ticket booking system inspired by IRCTC, built with PHP, MySQL, and modern web technologies.

## 🚂 Features

### User Features
- **User Registration & Authentication** - Secure signup and login system
- **Train Search** - Search trains by source, destination, and travel date
- **Ticket Booking** - Book tickets with passenger details and class selection
- **Booking Management** - View, download, and cancel booked tickets
- **PDF Ticket Generation** - Download tickets as PDF documents
- **Profile Management** - Update user profile and view booking history

### Admin Features
- **Train Management** - Add new trains with schedules and routes
- **Booking Oversight** - Monitor all bookings in the system

## 🛠️ Technology Stack

### Frontend
- **HTML5 & CSS3** - Modern, responsive design
- **JavaScript** - Interactive client-side functionality
- **Font Awesome** - Icon library for UI elements

### Backend
- **PHP** - Server-side logic and API endpoints
- **MySQL** - Database for user data, trains, and bookings
- **DOMPDF** - PDF generation for ticket downloads

### Development Tools
- **Composer** - PHP dependency management

## 📋 Prerequisites

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (for dependency management)

## 🚀 Installation

### 1. Clone the Repository
```bash
git clone https://github.com/yourusername/booking-project.git
cd booking-project
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Database Setup
Create a MySQL database and import the provided schema:

```sql
-- Create database
CREATE DATABASE booking_project;

-- Import the setup.sql file
mysql -u your_username -p booking_project < Backend/setup.sql
```

Or run the setup.sql file directly through your MySQL client.

### 4. Configure Database Connection
Edit `Backend/config.php` with your database credentials:

```php
$host = 'localhost';
$dbname = 'booking_project';
$username = 'your_username';
$password = 'your_password';
```

### 5. Web Server Configuration
Place the project files in your web server's document root:
- Apache: `/var/www/html/` or `htdocs/`
- Nginx: `/usr/share/nginx/html/`

Ensure the following directories have write permissions:
- `uploads/` (if file uploads are enabled)

## 📁 Project Structure

```
BookingProject/
├── Backend/                    # PHP API endpoints
│   ├── add_train.php          # Add new trains (Admin)
│   ├── book_ticket.php        # Process ticket bookings
│   ├── cancel_ticket.php      # Cancel booked tickets
│   ├── config.php             # Database configuration
│   ├── get_bookings.php       # Retrieve user bookings
│   ├── get_trains.php         # Search and retrieve trains
│   ├── login.php              # User authentication
│   ├── setup.sql              # Database schema and sample data
│   └── signup.php             # User registration
├── Frontend/                   # User interface files
│   ├── Index.html             # Homepage and train search
│   ├── login.html             # User login page
│   ├── Singup.html            # User registration page
│   ├── add_train.html         # Admin train management
│   ├── book ticket.html       # Ticket booking interface
│   ├── confirmation.html      # Booking confirmation page
│   ├── profile.php            # User profile and booking history
│   ├── payment.php            # Payment processing
│   ├── download_pdf.php       # PDF ticket generation
│   └── style.css              # Stylesheets
├── composer.json              # PHP dependencies
├── .gitignore                 # Git ignore file
└── README.md                  # This file
```

## 🎯 Usage

### For Users
1. **Register** an account or **login** with existing credentials
2. **Search trains** by entering source, destination, and travel date
3. **Select a train** and choose your preferred class
4. **Enter passenger details** and confirm booking
5. **Make payment** and receive booking confirmation
6. **Download PDF ticket** for your records
7. **Manage bookings** through your profile

### For Administrators
1. **Access admin panel** through `add_train.html`
2. **Add new trains** with complete schedule information
3. **Monitor bookings** through the backend system

## 🎨 Sample Trains

The system comes pre-loaded with sample Indian trains:
- Rajdhani Express (Delhi-Mumbai)
- Shatabdi Express (Delhi-Chennai)
- Duronto Express (Delhi-Kolkata)
- Garib Rath (Mumbai-Kolkata)
- And many more...

## 🔧 Configuration

### Database Classes
The system supports the following train classes:
- **1A** - First Class AC
- **2A** - Second Class AC
- **3A** - Third Class AC
- **SL** - Sleeper Class
- **CC** - AC Chair Car
- **EC** - Executive Class
- **2S** - Second Seating

### Customization
- Modify `Frontend/style.css` for UI customization
- Update `Backend/config.php` for database settings
- Extend train data by modifying `Backend/setup.sql`

## 🐛 Troubleshooting

### Common Issues
1. **Database Connection Error**: Check `config.php` credentials
2. **PDF Generation Fails**: Ensure DOMPDF is properly installed
3. **File Upload Issues**: Check directory permissions
4. **Train Search Not Working**: Verify database tables are populated

### Debug Mode
Enable error reporting in PHP files for debugging:
```php
ini_set('display_errors', 1);
error_reporting(E_ALL);
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 👥 Authors

- **Your Name** - *Initial work* - [YourUsername](https://github.com/YourUsername)

## 🙏 Acknowledgments

- IRCTC for the inspiration
- Font Awesome for the icon library
- DOMPDF for PDF generation capabilities

## 📞 Support

For support and queries:
- Create an issue in the GitHub repository
- Email: your.email@example.com

---

**Note**: This is a demonstration project for educational purposes. For production use, ensure proper security measures, input validation, and error handling are implemented.
