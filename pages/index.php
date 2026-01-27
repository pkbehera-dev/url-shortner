<?php
require_once '../config/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>URL Shortener - Shorten Your Links</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
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

    <section class="py-5 bg-light mt-5">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-4">
                    <div class="p-4">
                        <i class="bi bi-lightning-charge text-primary fs-1"></i>
                        <h3 class="mt-3">Fast & Secure</h3>
                        <p class="text-muted">Our service is fast, secure, and reliable. Your links are safe with us.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4">
                        <i class="bi bi-bar-chart text-primary fs-1"></i>
                        <h3 class="mt-3">Analytics</h3>
                        <p class="text-muted">Track the performance of your links with our detailed analytics dashboard.</p>
                    </div>
                </div>
                <div class="col-md-4 mb-4">
                    <div class="p-4">
                        <i class="bi bi-phone text-primary fs-1"></i>
                        <h3 class="mt-3">Mobile Friendly</h3>
                        <p class="text-muted">Manage your links on the go with our fully responsive design.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-dark text-white py-4 text-center">
        <div class="container">
            <p class="mb-0">&copy; <?php echo date('Y'); ?> URL Shortener. All rights reserved. | <a href="terms" class="text-white">Terms & Privacy</a></p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
