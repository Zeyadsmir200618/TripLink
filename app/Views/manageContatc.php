<?php
// 1. ENABLE ERROR REPORTING
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

// 2. SECURITY CHECK
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 3. DATABASE CONNECTION
$host = 'localhost';
$dbname = 'booking_app_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // 4. FETCH MESSAGES (Newest first)
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Messages | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
        body { background: #f4f7fa; display: flex; min-height: 100vh; }
        
        /* SIDEBAR STYLES */
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
        
        /* MAIN CONTENT */
        .main-content { flex-grow: 1; padding: 40px; }
        .header { margin-bottom: 30px; }
        
        /* TABLE STYLES */
        .data-table {
            width: 100%; background: white; border-radius: 12px; overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05); border-collapse: collapse;
        }
        .data-table th, .data-table td { padding: 15px 20px; text-align: left; border-bottom: 1px solid #eee; vertical-align: top; }
        .data-table th { background: #f8f9fa; color: #555; font-weight: 600; }
        .data-table tr:hover { background: #f1f1f1; }
        
        /* Limit message width so it doesn't break layout */
        .msg-content { max-width: 400px; line-height: 1.5; color: #444; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TripLink Admin</h2>
        <ul class="nav-links">
            <li><a href="admin.php">Dashboard</a></li>
            <li><a href="manageFlight.php">Manage Flights</a></li>
            <li><a href="manageHotels.php">Manage Hotels</a></li>
            <li><a href="manageUsers.php">Manage Users</a></li>
            <li><a href="manageContact.php" class="active">Messages</a></li> </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>User Messages</h1>
            <p>Inquiries sent from the Contact Us page.</p>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 50px;">ID</th>
                    <th style="width: 150px;">Name</th>
                    <th style="width: 200px;">Email</th>
                    <th>Message</th>
                    <th style="width: 150px;">Date Sent</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($messages) > 0): ?>
                    <?php foreach ($messages as $msg): ?>
                    <tr>
                        <td style="font-weight:bold; color:#003580;">#<?php echo htmlspecialchars($msg['id']); ?></td>
                        <td><?php echo htmlspecialchars($msg['name']); ?></td>
                        <td>
                            <a href="mailto:<?php echo htmlspecialchars($msg['email']); ?>" style="color:#003580; text-decoration:none;">
                                <?php echo htmlspecialchars($msg['email']); ?>
                            </a>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($msg['subject']); ?></strong><br>
                            <div class="msg-content"><?php echo nl2br(htmlspecialchars($msg['message'])); ?></div>
                        </td>
                        <td style="color:#777; font-size:0.9rem;">
                            <?php echo date("M d, Y", strtotime($msg['created_at'])); ?><br>
                            <?php echo date("h:i A", strtotime($msg['created_at'])); ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding:30px; color:#888;">
                            No messages received yet.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>