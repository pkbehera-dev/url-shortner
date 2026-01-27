<?php
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
        exit;
    }

    $url_id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $user_id = $_SESSION['user_id'];

    if ($url_id <= 0) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid URL ID']);
        exit;
    }

    // Verify ownership and delete
    $stmt = $conn->prepare("DELETE FROM urls WHERE id = ? AND user_id = ?");
    $stmt->bind_param("ii", $url_id, $user_id);

    if ($stmt->execute()) {
        if ($stmt->affected_rows > 0) {
            echo json_encode(['status' => 'success', 'message' => 'URL deleted successfully']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'URL not found or access denied']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    $stmt->close();
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>
