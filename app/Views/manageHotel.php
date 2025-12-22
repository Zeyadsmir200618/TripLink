<?php
// 1. Enable Error Reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. DEBUGGING: If you still get redirected, uncomment the lines below to see why:
// echo "Role in Session: " . $_SESSION['role'] . "<br>";
// echo "User ID: " . $_SESSION['user_id'] . "<br>";
// exit; 

// 3. SECURITY CHECK
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If this fails, it means your session is still 'user' or empty.
    header("Location: login.php");
    exit;
}

// 4. DATABASE CONNECTION - USING SINGLETON PATTERN
require_once __DIR__ . '/../config/database.php';

try {
    // Get the singleton instance
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // 5. FETCH DATA (Hotels)
    $stmt = $pdo->query("SELECT * FROM hotels");
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Database Error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Hotels | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        body { background: #f4f7fa; display: flex; min-height: 100vh; }
        
        .sidebar { width: 260px; background: #003580; color: white; padding: 20px; display: flex; flex-direction: column; }
        .sidebar h2 { text-align: center; margin-bottom: 40px; }
        .nav-links { list-style: none; }
        .nav-links a { display: block; padding: 12px 15px; color: rgba(255,255,255,0.8); text-decoration: none; border-radius: 8px; margin-bottom: 10px; }
        .nav-links a:hover, .nav-links a.active { background: rgba(255,255,255,0.15); color: white; }
        
        .main-content { flex-grow: 1; padding: 40px; }
        
        .data-table { width: 100%; background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-collapse: collapse; margin-top: 20px; }
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