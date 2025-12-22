<?php
session_start();

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
    <style>
        /* Base Styles */
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        
        body { 
            background: #f4f7fa;
            color: #333;
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Navigation */
        .sidebar {
            width: 260px;
            background: #003580; /* Booking.com Blue */
            color: white;
            display: flex;
            flex-direction: column;
            padding: 20px;
            box-shadow: 4px 0 10px rgba(0,0,0,0.1);
        }

        .sidebar h2 {
            font-size: 1.5rem;
            margin-bottom: 40px;
            text-align: center;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .nav-links {
            list-style: none;
            flex-grow: 1;
        }

        .nav-links li {
            margin-bottom: 15px;
        }

        .nav-links a {
            text-decoration: none;
            color: rgba(255,255,255,0.8);
            font-size: 1rem;
            display: block;
            padding: 12px 15px;
            border-radius: 8px;
            transition: all 0.3s ease;
            font-weight: 500;
        }

        .nav-links a:hover, .nav-links a.active {
            background: rgba(255,255,255,0.15);
            color: white;
            transform: translateX(5px);
        }

        .logout-btn {
            background: #d32f2f;
            color: white;
            text-align: center;
            padding: 12px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: background 0.3s;
        }

        .logout-btn:hover { background: #b71c1c; }

        /* Main Content Area */
        .main-content {
            flex-grow: 1;
            padding: 40px;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .header h1 { color: #1a1a1a; font-size: 1.8rem; }
        .user-info { font-size: 1rem; color: #666; }

        /* Dashboard Cards */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            transition: transform 0.3s;
            border-left: 5px solid #003580;
        }

        .card:hover { transform: translateY(-5px); }

        .card h3 { font-size: 1.1rem; color: #555; margin-bottom: 10px; }
        .card .number { font-size: 2.5rem; font-weight: 700; color: #003580; }
        .card p { font-size: 0.9rem; color: #888; margin-top: 5px; }

    </style>
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