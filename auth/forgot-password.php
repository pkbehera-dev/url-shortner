<?php
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    
    if (empty($email)) {
        $error = "Please enter your email address.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $otp = sprintf("%06d", mt_rand(100000, 999999));
            $otp_expiry = date("Y-m-d H:i:s", strtotime("+15 minutes"));
            
            $update_stmt = $conn->prepare("UPDATE users SET otp = ?, otp_expiry = ? WHERE email = ?");
            $update_stmt->bind_param("sss", $otp, $otp_expiry, $email);
            
            if ($update_stmt->execute()) {
                // Send OTP
                $subject = "Reset Your Password";
                $message = "Your OTP for password reset is: $otp. It expires in 15 minutes.";
                $headers = "From: noreply@pkbehera.in";
                
                @mail($email, $subject, $message, $headers);
                
                header("Location: reset-password?email=" . urlencode($email));
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        } else {
            // For security, don't reveal if email exists or not, but for this project we might just say "User not found" or fake success
            $error = "No account found with that email address.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .auth-card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        .auth-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 30px;
            text-align: center;
        }
        .form-control {
            border-radius: 8px;
            padding: 12px;
        }
        .btn-auth {
            border-radius: 8px;
            padding: 12px;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
        }
        .btn-auth:hover {
            background: linear-gradient(135deg, #764ba2 0%, #667eea 100%);
        }
    </style>
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card auth-card">
                    <div class="auth-header">
                        <h3 class="mb-0">Forgot Password</h3>
                        <p class="mb-0 opacity-75">Enter your email to reset password</p>
                    </div>
                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <form method="POST" action="">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                            </div>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-auth text-white">Send Reset Link</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3 border-0 bg-white rounded-bottom">
                        <p class="mb-0"><a href="login" class="text-primary text-decoration-none">Back to Login</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
