<?php
// Helper to get correct relative path based on current location
$current_page = basename($_SERVER['PHP_SELF']);
$current_path = dirname($_SERVER['PHP_SELF']);

// Determine base prefix
if ($current_path == '/auth' || strpos($current_path, '/auth/') !== false) {
    $base_prefix = '../';
} elseif ($current_path == '/users' || strpos($current_path, '/users/') !== false) {
    $base_prefix = '../';
} elseif ($current_path == '/pages' || strpos($current_path, '/pages/') !== false) {
    $base_prefix = '../';
} else {
    $base_prefix = ''; // Root level
}
?>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="<?php echo $base_prefix; ?>index"><i class="bi bi-link-45deg"></i> URL Shortener</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
               
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_prefix; ?>dashboard">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_prefix; ?>settings">Settings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_prefix; ?>logout">Logout (<?php echo htmlspecialchars($_SESSION['username']); ?>)</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_prefix; ?>index">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_prefix; ?>login">Login</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?php echo $base_prefix; ?>register">Register</a>
                    </li>
                     <li class="nav-item">
                    <a class="nav-link" href="<?php echo $base_prefix; ?>terms">Terms</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
