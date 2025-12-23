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

// 2. DATABASE CONNECTION - USING SINGLETON PATTERN
require_once __DIR__ . '/../config/database.php';

// Default values in case database is empty or fails
$userCount = 0;
$flightCount = 0;
$hotelCount = 0;

try {
    // Get the singleton instance
    $db = Database::getInstance();
    $pdo = $db->getConnection();

    // Count Users
    $stmt = $pdo->query("SELECT COUNT(*) FROM users");
    $userCount = $stmt->fetchColumn();

    // Count Flights
    $stmt = $pdo->query("SELECT COUNT(*) FROM flights");
    $flightCount = $stmt->fetchColumn();

    // Count Hotels
    // Note: This counts total hotels available. 
    // If you have a separate 'bookings' table, change this query to SELECT COUNT(*) FROM bookings
    $stmt = $pdo->query("SELECT COUNT(*) FROM hotels");
    $hotelCount = $stmt->fetchColumn();

} catch(PDOException $e) {
    // If connection fails, you might want to log it or show an error
    // die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard | TripLink</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TripLink/public/css/base.css">
    <link rel="stylesheet" href="/TripLink/public/css/admin.css">
</head>
<body>

    <div class="sidebar">
        <h2>TripLink Admin</h2>
        <ul class="nav-links">
            <li><a href="admin.php" class="active">Dashboard</a></li>
            <li><a href="manageFlight.php">Manage Flights</a></li>
            <li><a href="manageHotel.php">Manage Hotels</a></li>
            <li><a href="manageUser.php">Manage Users</a></li>
            <li><a href="manageContatc.php">Users Messages</a></li>

        </ul>
        <a href="login.php" class="logout-btn">Logout</a>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Welcome back!</h1>
            <div class="user-info">
                Logged in as: <strong><?php echo htmlspecialchars($_SESSION['username'] ?? 'Admin'); ?></strong>
            </div>
        </div>

        <div class="cards-grid">
            <div class="card" style="border-left-color: #003580;">
                <h3>Total Users</h3>
                <div class="number"><?php echo $userCount; ?></div> 
                <p>Registered customers</p>
            </div>

            <div class="card" style="border-left-color: #008009;">
                <h3>Active Flights</h3>
                <div class="number"><?php echo $flightCount; ?></div> 
                <p>Currently scheduled</p>
            </div>

            <div class="card" style="border-left-color: #ffa500;">
                <h3>Total Hotels</h3>
                <div class="number"><?php echo $hotelCount; ?></div> 
                <p>Available for booking</p>
            </div>
        </div>

        <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 12px; box-shadow: 0 5px 15px rgba(0,0,0,0.05);">
            <h3 style="margin-bottom: 15px;">Quick Actions</h3>
            <p>Select an option from the sidebar to start managing your database.</p>
        </div>
    </div>

</body>
</html>