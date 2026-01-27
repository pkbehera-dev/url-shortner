<?php
require_once '../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Fetch user's URLs
$stmt = $conn->prepare("SELECT * FROM urls WHERE user_id = ? ORDER BY created_at DESC LIMIT 10");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$link_count = $result->num_rows;

// Calculate total clicks
$total_clicks = 0;
$recent_links = [];
while ($row = $result->fetch_assoc()) {
    $total_clicks += $row['clicks'];
    $recent_links[] = $row;
}

// Get most clicked link
$most_clicked_stmt = $conn->prepare("SELECT * FROM urls WHERE user_id = ? ORDER BY clicks DESC LIMIT 1");
$most_clicked_stmt->bind_param("i", $user_id);
$most_clicked_stmt->execute();
$most_clicked_result = $most_clicked_stmt->get_result();
$most_clicked = $most_clicked_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body class="bg-light">
    <?php include '../includes/navbar.php'; ?>

    <div class="container py-4">
        <!-- Header -->
        <div class="mb-4">
            <h4 class="mb-0">HII <?php echo htmlspecialchars($username); ?> 👋</h4>
        </div>

        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <h3 class="fw-bold text-primary mb-1"><?php echo $link_count; ?></h3>
                        <p class="text-muted small mb-0">Total Links</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <h3 class="fw-bold text-success mb-1"><?php echo $total_clicks; ?></h3>
                        <p class="text-muted small mb-0">Total Clicks</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <h3 class="fw-bold text-warning mb-1"><?php echo $most_clicked ? $most_clicked['clicks'] : 0; ?></h3>
                        <p class="text-muted small mb-0">Top Link</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body text-center py-3">
                        <span class="badge <?php echo $link_count >= 100 ? 'bg-danger' : 'bg-success'; ?>"><?php echo $link_count; ?>/100</span>
                        <p class="text-muted small mb-0 mt-1">Quota</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Shorten -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body">
                <form id="quickShortenForm" class="d-flex gap-2">
                    <input type="url" class="form-control" id="quickLongUrl" placeholder="Paste URL to shorten..." required>
                    <button class="btn btn-primary" type="submit">Shorten</button>
                </form>
                <div id="quickResult" class="mt-3 d-none">
                    <div class="input-group">
                        <input type="text" class="form-control" id="quickShortUrl" readonly>
                        <button class="btn btn-outline-secondary" type="button" id="quickCopyBtn">Copy</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Links Table -->
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title mb-3">Your Links</h5>
                
                <?php if (!empty($recent_links)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>Original URL</th>
                                    <th>Short URL</th>
                                    <th>Clicks</th>
                                    <th style="width: 80px;">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recent_links as $row): ?>
                                    <tr id="row-<?php echo $row['id']; ?>">
                                        <td class="text-truncate" style="max-width: 250px;">
                                            <a href="<?php echo htmlspecialchars($row['long_url']); ?>" target="_blank" title="<?php echo htmlspecialchars($row['long_url']); ?>">
                                                <?php echo htmlspecialchars($row['long_url']); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <a href="<?php echo BASE_URL . $row['short_code']; ?>" target="_blank">
                                                <?php echo BASE_URL . $row['short_code']; ?>
                                            </a>
                                        </td>
                                        <td><?php echo $row['clicks']; ?></td>
                                        <td>
                                            <button class="btn btn-sm btn-outline-primary copy-btn me-1" data-url="<?php echo BASE_URL . $row['short_code']; ?>">
                                                <i class="bi bi-clipboard"></i>
                                            </button>
                                            <button class="btn btn-sm btn-outline-danger" onclick="deleteUrl(<?php echo $row['id']; ?>)">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="bi bi-link-45deg text-muted fs-1"></i>
                        <p class="text-muted mt-2 mb-2">No links yet</p>
                        <a href="../pages/index.php" class="btn btn-primary btn-sm">Create First Link</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Copy buttons
        document.querySelectorAll('.copy-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const url = this.getAttribute('data-url');
                navigator.clipboard.writeText(url).then(() => {
                    this.innerHTML = '<i class="bi bi-check"></i>';
                    this.classList.replace('btn-outline-primary', 'btn-success');
                    setTimeout(() => {
                        this.innerHTML = '<i class="bi bi-clipboard"></i>';
                        this.classList.replace('btn-success', 'btn-outline-primary');
                    }, 2000);
                });
            });
        });

        // Quick shorten form
        document.getElementById('quickShortenForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const longUrl = document.getElementById('quickLongUrl').value;
            const formData = new FormData();
            formData.append('long_url', longUrl);
            
            fetch('../api/shorten.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    document.getElementById('quickShortUrl').value = data.short_url;
                    document.getElementById('quickResult').classList.remove('d-none');
                } else {
                    alert(data.message || 'Error shortening URL');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred');
            });
        });

        // Quick copy button
        document.getElementById('quickCopyBtn').addEventListener('click', function() {
            const url = document.getElementById('quickShortUrl').value;
            navigator.clipboard.writeText(url).then(() => {
                this.textContent = 'Copied!';
                this.classList.replace('btn-outline-secondary', 'btn-success');
                setTimeout(() => {
                    this.textContent = 'Copy';
                    this.classList.replace('btn-success', 'btn-outline-secondary');
                }, 2000);
            });
        });

        function deleteUrl(id) {
            if (confirm('Delete this URL?')) {
                const formData = new FormData();
                formData.append('id', id);

                fetch('../api/delete_url.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        const row = document.getElementById('row-' + id);
                        row.remove();
                        location.reload();
                    } else {
                        alert(data.message || 'Error deleting URL');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred');
                });
            }
        }
    </script>
</body>
</html>
