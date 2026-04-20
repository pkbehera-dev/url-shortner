<?php
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener - Minimal & Fast</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <?php include '../includes/navbar.php'; ?>

    <header class="hero-section">
        <div class="container">
            <h1 class="display-4 fw-bold">Shorten Your Links</h1>
            <p class="lead">The best free tool to shorten your URLs and track clicks.</p>
        </div>
    </header>

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <?php if (isset($_GET['error'])): ?>
                    <div class="alert alert-danger text-center mt-3">
                        <?php 
                        if ($_GET['error'] === 'expired') echo "This link has expired.";
                        elseif ($_GET['error'] === 'notfound') echo "Link not found.";
                        else echo "An error occurred.";
                        ?>
                    </div>
                <?php endif; ?>
                <div class="shortener-box">
                    <form id="shortenForm">
                        <div class="input-group input-group-lg">
                            <input type="url" class="form-control" id="longUrl" placeholder="Paste your long URL here..." required>
                            <button class="btn btn-primary" type="submit">Shorten</button>
                        </div>
                    </form>

                    <div id="resultBox" class="result-box text-center">
                        <div id="successMessage" class="alert alert-warning mb-3 d-none"></div>
                        <p class="mb-2">Your shortened URL:</p>
                        <div class="input-group">
                            <input type="text" class="form-control" id="shortUrl" readonly>
                            <button class="btn btn-outline-secondary" type="button" id="copyBtn">
                                <i class="bi bi-clipboard"></i> Copy
                            </button>
                        </div>
                    </div>
                    <div id="errorBox" class="alert alert-danger mt-3 d-none"></div>
                </div>
            </div>
        </div>
    </div>

    <section class="py-5 mt-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-lightning-charge fs-3"></i>
                        </div>
                        <h3>Fast & Secure</h3>
                        <p class="text-muted mt-3">Our service is fast, secure, and reliable. Your links are safe with us.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-bar-chart fs-3"></i>
                        </div>
                        <h3>Analytics</h3>
                        <p class="text-muted mt-3">Track the performance of your links with our detailed analytics dashboard.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="feature-card">
                        <div class="feature-icon-wrapper">
                            <i class="bi bi-phone fs-3"></i>
                        </div>
                        <h3>Mobile Friendly</h3>
                        <p class="text-muted mt-3">Manage your links on the go with our fully responsive and modern design.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="benefits-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 mb-5 mb-lg-0">
                    <h2 class="fw-bold mb-4">Unlock more when you sign up</h2>
                    <p class="text-muted mb-4">Create a free account to get access to advanced features designed to give you more control over your links.</p>
                    <a href="register" class="btn btn-outline-dark px-4 py-2 fw-medium">Create an Account</a>
                    <a href="login" class="btn btn-link text-decoration-none ms-2">Sign In</a>
                </div>
                <div class="col-lg-6 offset-lg-1">
                    <div class="benefit-item">
                        <div class="icon"><i class="bi bi-link-45deg"></i></div>
                        <div>
                            <h4>Custom Aliases</h4>
                            <p>Create memorable, branded links instead of random characters.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="icon"><i class="bi bi-geo-alt"></i></div>
                        <div>
                            <h4>Advanced Tracking</h4>
                            <p>See exactly where your clicks are coming from geographically and by device type.</p>
                        </div>
                    </div>
                    <div class="benefit-item">
                        <div class="icon"><i class="bi bi-collection"></i></div>
                        <div>
                            <h4>Link Management</h4>
                            <p>Organize, edit, or delete your previously shortened URLs easily.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="py-4 text-center">
        <div class="container">
            <p class="mb-0 text-muted">&copy; <?php echo date('Y'); ?> URL Shortener. All rights reserved. | <a href="terms">Terms & Privacy</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/script.js"></script>
</body>
</html>
