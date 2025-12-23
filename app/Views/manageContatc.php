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

// 3. FETCH MESSAGES
try {
    $stmt = $pdo->query("SELECT * FROM contact_messages ORDER BY created_at DESC");
    $messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Messages | Admin</title>
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