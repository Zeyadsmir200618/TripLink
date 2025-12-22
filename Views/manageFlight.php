<?php
// --- 1. ENABLE ERROR REPORTING (Fixes White Screen) ---
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// --- 2. SECURITY CHECK ---
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// --- 3. DATABASE CONNECTION ---
$host = 'localhost';
$dbname = 'booking_app_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // --- 4. FETCH DATA ---
    $stmt = $pdo->query("SELECT * FROM flights ORDER BY departure_date ASC");
    $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Flights | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        body { background: #f4f7fa; display: flex; min-height: 100vh; }
        
        .sidebar {
            width: 260px; background: #003580; color: white; padding: 20px;
            display: flex; flex-direction: column;
        }
        .sidebar h2 { text-align: center; margin-bottom: 40px; }
        .nav-links { list-style: none; }
        .nav-links a { 
            display: block; padding: 12px 15px; color: rgba(255,255,255,0.8); 
            text-decoration: none; border-radius: 8px; margin-bottom: 10px;
        }
        .nav-links a:hover, .nav-links a.active { background: rgba(255,255,255,0.15); color: white; }
        
        .main-content { flex-grow: 1; padding: 40px; }
        .header { margin-bottom: 30px; }
        
        .data-table {
            width: 100%; background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-collapse: collapse;
        }
        .data-table th, .data-table td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; }
        .data-table th { background: #f8f9fa; color: #555; font-weight: 600; }
        .data-table tr:hover { background: #f1f1f1; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TripLink Admin</h2>
        <ul class="nav-links">
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="manageFlight.php" class="active">Manage Flights</a></li>
            <li><a href="manageHotel.php">Manage Hotels</a></li>
            <li><a href="manageUser.php">Manage Users</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Manage Flights</h1>
            <p>Current flight schedules</p>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Flight No.</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Departure</th>
                    <th>Return</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($flights) > 0): ?>
                    <?php foreach ($flights as $flight): ?>
                    <tr>
                        <td style="font-weight:bold; color:#003580;">
                            <?php echo htmlspecialchars($flight['flight_number']); ?>
                        </td>
                        <td><?php echo htmlspecialchars($flight['departure_city']); ?></td>
                        <td><?php echo htmlspecialchars($flight['arrival_city']); ?></td>
                        <td><?php echo htmlspecialchars($flight['departure_date']); ?></td>
                        <td>
                            <?php echo $flight['return_date'] ? htmlspecialchars($flight['return_date']) : '-'; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:20px;">
                            No flights found in database.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>