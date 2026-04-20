<?php
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terms of Service & Privacy Policy - URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <div class="container py-5">
        <div class="content-section">
            <h1 class="mb-4">Terms of Service & Privacy Policy</h1>
            
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body p-4">
                    <h4 class="text-primary">Terms of Service</h4>
                    <p class="text-muted">Last updated: January 2025</p>
                    
                    <h5 class="mt-4">1. Acceptance of Terms</h5>
                    <p>By accessing and using this URL Shortener service, you accept and agree to be bound by the terms and provision of this agreement.</p>
                    
                    <h5 class="mt-4">2. Use of Service</h5>
                    <p>You agree to use this service only for lawful purposes. You may not:</p>
                    <ul>
                        <li>Use the service to shorten URLs that contain illegal content</li>
                        <li>Attempt to gain unauthorized access to any systems or networks</li>
                        <li>Use the service for any malicious activities</li>
                        <li>Spam or create bulk links for spamming purposes</li>
                    </ul>
                    
                    <h5 class="mt-4">3. Limitation of Liability</h5>
                    <p>This service is provided "as is" without any warranties, expressed or implied. We are not responsible for any damages arising from the use of this service.</p>
                    
                    <h5 class="mt-4">4. Service Availability</h5>
                    <p>We reserve the right to modify, suspend, or discontinue the service at any time without notice.</p>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="text-primary">Privacy Policy</h4>
                    <p class="text-muted">Last updated: January 2025</p>
                    
                    <h5 class="mt-4">1. Information Collection</h5>
                    <p>We collect the following information:</p>
                    <ul>
                        <li><strong>Account Information:</strong> Username and email address when you register</li>
                        <li><strong>Usage Data:</strong> URLs you shorten and click statistics</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, and access times</li>
                    </ul>
                    
                    <h5 class="mt-4">2. How We Use Your Information</h5>
                    <ul>
                        <li>To provide and improve our URL shortening service</li>
                        <li>To track link analytics and click counts</li>
                        <li>To communicate important service updates</li>
                        <li>To maintain account security</li>
                    </ul>
                    
                    <h5 class="mt-4">3. Data Protection</h5>
                    <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, or disclosure.</p>
                    
                    <h5 class="mt-4">4. Third-Party Disclosure</h5>
                    <p>We do not sell, trade, or otherwise transfer your personally identifiable information to outside parties except trusted third parties who assist in operating our service.</p>
                    
                    <h5 class="mt-4">5. Cookie Usage</h5>
                    <p>We use cookies to enhance your experience, analyze website traffic, and personalize content. You can disable cookies in your browser settings.</p>
                    
                    <h5 class="mt-4">6. Your Consent</h5>
                    <p>By using our service, you consent to our privacy policy and terms of service.</p>
                    
                    <h5 class="mt-4">7. Contact Information</h5>
                    <p>If you have any questions about these terms or our privacy policy, please contact us.</p>
                </div>
            </div>

            <div class="text-center mt-4">
                <a href="index" class="btn btn-primary"><i class="bi bi-arrow-left"></i> Back to Home</a>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white py-4 text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> URL Shortener. All rights reserved. | <a href="terms" class="text-white">Terms & Privacy</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
