<?php
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $long_url = trim($_POST['url']);

    if (empty($long_url)) {
        echo json_encode(['status' => 'error', 'message' => 'URL is required']);
        exit;
    }

    if (!filter_var($long_url, FILTER_VALIDATE_URL)) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid URL format']);
        exit;
    }

    $user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : NULL;
    $expires_at = NULL;

    if ($user_id) {
        // Registered user: Check limit (100 links)
        $count_stmt = $conn->prepare("SELECT COUNT(*) as count FROM urls WHERE user_id = ?");
        $count_stmt->bind_param("i", $user_id);
        $count_stmt->execute();
        $result = $count_stmt->get_result();
        $row = $result->fetch_assoc();
        
        if ($row['count'] >= 100) {
            echo json_encode(['status' => 'error', 'message' => 'You have reached the limit of 100 links.']);
            exit;
        }
        $count_stmt->close();
    } else {
        // Unregistered user: Set expiration to 7 days from now
        $expires_at = date('Y-m-d H:i:s', strtotime('+7 days'));
    }

    // Generate unique short code
    $short_code = generateShortCode();
    
    // Check if code exists (collision check)
    while (codeExists($conn, $short_code)) {
        $short_code = generateShortCode();
    }

    $stmt = $conn->prepare("INSERT INTO urls (user_id, long_url, short_code, expires_at) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $long_url, $short_code, $expires_at);

    if ($stmt->execute()) {
        $short_url = BASE_URL . $short_code;
        $response = ['status' => 'success', 'short_url' => $short_url];
        if ($expires_at) {
            $response['message'] = 'This link will expire in 7 days.';
        }
        echo json_encode($response);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}

function generateShortCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

function codeExists($conn, $code) {
    $stmt = $conn->prepare("SELECT id FROM urls WHERE short_code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $result = $stmt->get_result();
    $exists = $result->num_rows > 0;
    $stmt->close();
    return $exists;
}
?>
