# URL Shortener

A modern, full-featured URL shortener built with PHP, MySQL, and Bootstrap 5. Create short links, track clicks, and manage your URLs through a user-friendly dashboard.

![URL Shortener](https://via.placeholder.com/800x400?text=URL+Shortener+Screenshot)

## Features

- 🚀 **Quick Shortening** - Shorten any URL in seconds
- 👤 **User Accounts** - Register and login to manage your links
- 📊 **Dashboard** - View stats: total links, total clicks, top link, and quota
- 🔗 **Link Management** - View, copy, and delete your shortened URLs
- 📈 **Click Tracking** - Track how many times your links are clicked
- ⚙️ **Settings** - Change your username and password
- 🔒 **Secure** - Password hashing with PHP's password_hash()
- ✉️ **Email Verification** - OTP-based email verification for registration
- 📱 **Responsive Design** - Works on desktop and mobile devices
- 🔗 **Clean URLs** - No .php extensions in URLs

## Tech Stack

- **Backend:** PHP 8.1+
- **Database:** MySQL
- **Frontend:** HTML5, Bootstrap 5, JavaScript
- **Server:** Apache with mod_rewrite

## Directory Structure

```
url-shortener/
├── .htaccess              # URL rewriting rules
├── index.php              # Root redirect
├── README.md              # This file
├── api/
│   ├── shorten.php        # URL shortening API
│   └── delete_url.php     # URL deletion API
├── assets/
│   ├── css/style.css      # Custom styles
│   └── js/script.js       # Frontend JavaScript
├── auth/
│   ├── login.php          # User login
│   ├── logout.php         # User logout
│   ├── register.php       # User registration
│   ├── forgot-password.php # Password recovery request
│   ├── reset-password.php  # Password reset with OTP
│   └── verify-email.php   # Email verification
├── config/
│   └── db.php             # Database connection
├── includes/
│   └── navbar.php         # Shared navigation
├── pages/
│   ├── index.php          # Home page (public shortener)
│   ├── redirect.php       # Short URL redirect handler
│   └── terms.php          # Terms & Privacy Policy
└── users/
    ├── dashboard.php      # User dashboard
    └── settings.php       # User settings
```

## Installation

### 1. Requirements

- PHP 8.1 or higher
- MySQL 5.7+ or MariaDB 10+
- Apache with mod_rewrite enabled
- Composer (optional, for dependencies)

### 2. Clone the Repository

```bash
git clone https://github.com/yourusername/url-shortener.git
cd url-shortener
```

### 3. Configure Database

Create a MySQL database and import the schema:

```bash
# Login to MySQL
mysql -u root -p

# Create database
CREATE DATABASE url;

# Use the database
USE url;

# Create tables (run the SQL from db.sql or manually)
```

### 4. Database Schema

```sql
-- Users table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    otp VARCHAR(6) DEFAULT NULL,
    otp_expire DATETIME DEFAULT NULL,
    is_verified TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- URLs table
CREATE TABLE urls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT DEFAULT NULL,
    long_url TEXT NOT NULL,
    short_code VARCHAR(10) NOT NULL UNIQUE,
    clicks INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create index for faster lookups
CREATE INDEX idx_short_code ON urls(short_code);
CREATE INDEX idx_user_id ON urls(user_id);
```

### 5. Configure Application

Edit `config/db.php` with your database credentials:

```php
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = 'your_password'; // Your MySQL password
$dbName = 'url';
```

Update `BASE_URL` in `config/db.php` to match your domain:

```php
define('BASE_URL', 'https://yourdomain.com/');
```

### 6. Configure Email (Optional)

For email verification, configure SMTP settings in your PHP configuration or use a library like PHPMailer. Currently, the system uses a basic mail() function - for production, consider using a transactional email service.

### 7. Set File Permissions

```bash
# Ensure config directory is writable for any dynamic configs
chmod 755 config/
```

### 8. Start the Server

For local development:

```bash
# Using PHP built-in server
php -S localhost:8000

# Or using XAMPP/WAMP, place in htdocs folder
```

Visit `http://localhost:8000` (or your configured URL)

## Usage

### For Visitors (Unauthenticated)

1. Visit the home page
2. Paste your long URL
3. Click "Shorten"
4. Copy your shortened URL

### For Registered Users

1. **Register** - Create an account with email verification
2. **Login** - Access your personalized dashboard
3. **Shorten Links** - Use the quick shorten form on your dashboard
4. **View Stats** - See total links, clicks, and top-performing links
5. **Manage Links** - Copy or delete your shortened URLs
6. **Settings** - Update username or change password

## Clean URLs

The application uses Apache's mod_rewrite for clean URLs:

| URL          | Maps To                  |
| ------------ | ------------------------ |
| `/`          | Home page                |
| `/login`     | Login page               |
| `/register`  | Registration page        |
| `/dashboard` | User dashboard           |
| `/settings`  | User settings            |
| `/terms`     | Terms & Privacy page     |
| `/abc123`    | Redirect to original URL |

## API Endpoints

### Shorten URL

```
POST /api/shorten.php
Content-Type: application/x-www-form-urlencoded

long_url=https://example.com/very/long/url
```

**Response:**

```json
{
  "status": "success",
  "short_url": "https://localhost/abc123",
  "code": "abc123"
}
```

### Delete URL

```
POST /api/delete_url.php
Content-Type: application/x-www-form-urlencoded

id=1
```

**Response:**

```json
{
  "status": "success",
  "message": "URL deleted successfully"
}
```

## Customization

### Adding Custom Short Code Length

In `api/shorten.php`, modify the `$length` variable:

```php
$length = 6; // Default 6 characters
```

### Changing Maximum Links Per User

In `users/dashboard.php`, update the quota check:

```php
// Currently set to 100 links
<span class="badge <?php echo $link_count >= 100 ? 'bg-danger' : 'bg-success'; ?>"><?php echo $link_count; ?>/100</span>
```

### Styling

Edit `assets/css/style.css` for custom styles. The main customizations include:

- Hero section background
- Card styling
- Shortener box styling
- Button styles

## Security Considerations

- Passwords are hashed using `password_hash()` with PASSWORD_DEFAULT
- Input sanitization with `htmlspecialchars()` to prevent XSS
- Prepared statements for all database queries to prevent SQL injection
- Session-based authentication
- Email verification required for new accounts
- OTP expiration for password reset

## Production Deployment

1. **Enable HTTPS** - Always use SSL/TLS in production
2. **Update BASE_URL** - Set to your production domain
3. **Secure Database** - Use strong database credentials
4. **Email Configuration** - Set up proper SMTP for emails
5. **Error Handling** - Disable display_errors in production
6. **File Permissions** - Restrict write permissions appropriately

Example production `php.ini` settings:

```ini
display_errors = Off
log_errors = On
error_log = /var/log/php/errors.log
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [Bootstrap 5](https://getbootstrap.com/) for the UI framework
- [Bootstrap Icons](https://icons.getbootstrap.com/) for icons
- [PHP](https://www.php.net/) for the backend
- [MySQL](https://www.mysql.com/) for the database

---

Made with ❤️ for the community
