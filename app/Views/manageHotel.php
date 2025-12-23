<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 1. SECURITY CHECK
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. DATABASE CONNECTION
require_once __DIR__ . '/../config/database.php';
$pdo = Database::getInstance()->getConnection();

// 3. FETCH DATA
try {
    $stmt = $pdo->query("SELECT * FROM hotels");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Hotels | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TripLink/public/css/base.css">
    <link rel="stylesheet" href="/TripLink/public/css/admin.css">
</head>
<body>

    <div class="sidebar">
        <h2>TripLink Admin</h2>
        <ul class="nav-links">
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="manageFlight.php">Manage Flights</a></li>
            <li><a href="manageHotel.php" class="active">Manage Hotels</a></li>
            <li><a href="manageUser.php">Manage Users</a></li>
        </ul>
    </div>

    <div class="main-content">
        <h1>Manage Hotels</h1>
        <p>Current hotel listings.</p>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hotel Name</th>
                    <th>Location</th>
                    <th>Price</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($hotels) > 0): ?>
                    <?php foreach ($hotels as $hotel): ?>
                    <tr>
                        <td>#<?php echo $hotel['id']; ?></td>
                        <td><?php echo htmlspecialchars($hotel['name'] ?? 'N/A'); ?></td>
                        <td><?php echo htmlspecialchars($hotel['city'] ?? $hotel['location'] ?? 'N/A'); ?></td>
                        <td>$<?php echo htmlspecialchars($hotel['price'] ?? '0'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="4">No hotels found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>