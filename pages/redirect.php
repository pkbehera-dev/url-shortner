<?php
require_once '../config/db.php';

if (isset($_GET['code'])) {
    $code = $_GET['code'];

    // Prepared statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, long_url, expires_at FROM urls WHERE short_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $long_url = $row['long_url'];
        $id = $row['id'];
        $expires_at = $row['expires_at'];

        // Check for expiration
        if ($expires_at && strtotime($expires_at) < time()) {
            // Link expired
            header("Location: index.php?error=expired");
            exit();
        }

        // Increment click count
        $update_stmt = $conn->prepare("UPDATE urls SET clicks = clicks + 1 WHERE id = ?");
        $update_stmt->bind_param("i", $id);
        $update_stmt->execute();
        $update_stmt->close();

        // Redirect to the long URL
        header("Location: " . $long_url);
        exit();
    } else {
        // Code not found, redirect to home with error
        header("Location: index.php?error=notfound");
        exit();
    }
} else {
    header("Location: index.php");
    exit();
}
?>
