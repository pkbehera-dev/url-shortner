<?php
// Database configuration
$dbHost = 'localhost';
$dbUsername = 'root';
$dbPassword = ''; // Default XAMPP password is empty
$dbName = 'url';

// Create connection
$conn = new mysqli($dbHost, $dbUsername, $dbPassword, $dbName);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL configuration
// You can hardcode your domain here if dynamic detection fails or if you are behind a proxy.
define('BASE_URL', 'https://localhost/');

// If you prefer dynamic detection, uncomment the lines below and comment out the line above.
/*
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
$host = $_SERVER['HTTP_HOST'];
$script = $_SERVER['SCRIPT_NAME'];
$path = dirname($script);

if ($path == '/' || $path == '\\') {
    $path = '';
}

define('BASE_URL', $protocol . "://" . $host . $path . '/');
*/

?>
