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
    $stmt = $pdo->query("SELECT * FROM flights ORDER BY departure_date ASC");
    $flights = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Flights | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TripLink/public/css/base.css">
    <link rel="stylesheet" href="/TripLink/public/css/admin.css">
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