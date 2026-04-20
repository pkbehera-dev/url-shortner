<?php
require_once '../config/db.php';

$error = '';
$success = '';
$email = $_GET['email'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $otp = trim($_POST['otp']);
    
    if (empty($email) || empty($otp)) {
        $error = "All fields are required.";
    } else {
        $stmt = $conn->prepare("SELECT id, otp, otp_expiry FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            
            if ($user['otp'] === $otp) {
                if (strtotime($user['otp_expiry']) > time()) {
                    // Verify user
                    $update_stmt = $conn->prepare("UPDATE users SET is_verified = 1, otp = NULL, otp_expiry = NULL WHERE id = ?");
                    $update_stmt->bind_param("i", $user['id']);
                    
                    if ($update_stmt->execute()) {
                        $success = "Email verified successfully! You can now <a href='login'>Login</a>.";
                    } else {
                        $error = "Failed to verify email. Please try again.";
                    }
                } else {
                    $error = "OTP has expired. Please request a new one.";
                }
            } else {
                $error = "Invalid OTP.";
            }
        } else {
            $error = "User not found.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card">
                    <div class="auth-header">
                        <h3 class="mb-0">Verify Email</h3>
                        <p class="mb-0 opacity-75">Enter the OTP sent to your email</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>

                        <?php if (isset($_GET['msg']) && $_GET['msg'] == 'registered'): ?>
                            <div class="alert alert-info">Registration successful! Please check your email for the OTP.</div>
                        <?php endif; ?>

                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required readonly>
                            </div>
                            <div class="mb-3">
                                <label for="otp" class="form-label">OTP Code</label>
                                <input type="text" class="form-control" id="otp" name="otp" placeholder="Enter 6-digit OTP" required maxlength="6">
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-auth text-white">Verify Email</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3 border-0 bg-white rounded-bottom">
                        <p class="mb-0">Didn't receive code? <a href="#" class="text-primary text-decoration-none">Resend OTP</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
