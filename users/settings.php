<?php
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];
$message = '';
$error = '';

// Get current user data
$stmt = $conn->prepare("SELECT id, username, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'update_username') {
        $new_username = trim($_POST['username']);
        
        if (empty($new_username)) {
            $error = "Username cannot be empty.";
        } elseif (strlen($new_username) < 3) {
            $error = "Username must be at least 3 characters.";
        } elseif ($new_username !== $username) {
            // Check if username already exists
            $check_stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
            $check_stmt->bind_param("si", $new_username, $user_id);
            $check_stmt->execute();
            $check_result = $check_stmt->get_result();
            
            if ($check_result->num_rows > 0) {
                $error = "Username already taken.";
            } else {
                // Update username
                $update_stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
                $update_stmt->bind_param("si", $new_username, $user_id);
                
                if ($update_stmt->execute()) {
                    $_SESSION['username'] = $new_username;
                    $username = $new_username;
                    $message = "Username updated successfully!";
                } else {
                    $error = "Failed to update username.";
                }
                $update_stmt->close();
            }
            $check_stmt->close();
        } else {
            $message = "Username is already set to this.";
        }
    } elseif ($action === 'update_password') {
        $current_password = $_POST['current_password'];
        $new_password = $_POST['new_password'];
        $confirm_password = $_POST['confirm_password'];
        
        if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
            $error = "All password fields are required.";
        } elseif ($new_password !== $confirm_password) {
            $error = "New passwords do not match.";
        } elseif (strlen($new_password) < 6) {
            $error = "Password must be at least 6 characters.";
        } else {
            // Verify current password
            $pwd_stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
            $pwd_stmt->bind_param("i", $user_id);
            $pwd_stmt->execute();
            $pwd_result = $pwd_stmt->get_result();
            $user_data = $pwd_result->fetch_assoc();
            $pwd_stmt->close();
            
            if (!password_verify($current_password, $user_data['password'])) {
                $error = "Current password is incorrect.";
            } else {
                // Update password
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                $update_stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
                $update_stmt->bind_param("si", $hashed_password, $user_id);
                
                if ($update_stmt->execute()) {
                    $message = "Password updated successfully!";
                } else {
                    $error = "Failed to update password.";
                }
                $update_stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <h3 class="mb-4"><i class="bi bi-gear"></i> Settings</h3>
                
                <?php if ($message): ?>
                    <div class="alert alert-success"><?php echo $message; ?></div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <!-- Username Section -->
                <div class="card settings-card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-person"></i> Change Username</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="update_username">
                            <div class="mb-3">
                                <label class="form-label">Current Username</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($username); ?>" disabled>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Username</label>
                                <input type="text" name="username" class="form-control" placeholder="Enter new username" required minlength="3">
                            </div>
                            <button type="submit" class="btn btn-primary">Update Username</button>
                        </form>
                    </div>
                </div>
                
                <!-- Password Section -->
                <div class="card settings-card">
                    <div class="card-header bg-white">
                        <h5 class="mb-0"><i class="bi bi-lock"></i> Change Password</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="">
                            <input type="hidden" name="action" value="update_password">
                            <div class="mb-3">
                                <label class="form-label">Current Password</label>
                                <div class="password-wrapper">
                                    <input type="password" name="current_password" id="current_password" class="form-control" placeholder="Enter current password" required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('current_password')">
                                        <i class="bi bi-eye" id="current_password-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">New Password</label>
                                <div class="password-wrapper">
                                    <input type="password" name="new_password" id="new_password" class="form-control" placeholder="Enter new password" required minlength="6">
                                    <button type="button" class="password-toggle" onclick="togglePassword('new_password')">
                                        <i class="bi bi-eye" id="new_password-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Confirm New Password</label>
                                <div class="password-wrapper">
                                    <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirm new password" required>
                                    <button type="button" class="password-toggle" onclick="togglePassword('confirm_password')">
                                        <i class="bi bi-eye" id="confirm_password-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById(fieldId + '-icon');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }
    </script>
</body>
</html>
