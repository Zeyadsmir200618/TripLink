<?php
session_start();

// 1. SECURITY CHECK (The Bouncer)
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// 2. DATABASE CONNECTION - USING SINGLETON PATTERN
require_once __DIR__ . '/../config/database.php';

try {
    // Get the singleton instance
    $db = Database::getInstance();
    $pdo = $db->getConnection();
    
    // 3. FETCH DATA
    // We select all users from the database
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Admin</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        /* REUSING YOUR DASHBOARD STYLES FOR CONSISTENCY */
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

        /* TABLE STYLES */
        .data-table {
            width: 100%;
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            border-collapse: collapse;
        }
        .data-table th, .data-table td {
            padding: 15px 20px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }
        .data-table th {
            background: #f8f9fa;
            color: #555;
            font-weight: 600;
        }
        .data-table tr:hover { background: #f1f1f1; }
        .role-badge {
            padding: 5px 10px; border-radius: 20px; font-size: 0.8rem; font-weight: 600;
        }
        .role-admin { background: #e3f2fd; color: #1976d2; }
        .role-user { background: #e8f5e9; color: #388e3c; }
    </style>
</head>
<body>

    <div class="sidebar">
        <h2>TripLink Admin</h2>
        <ul class="nav-links">
            <li><a href="admin.php">Dashboard</a></li>
             <li><a href="manageFlight.php">Manage Flights</a></li>
            <li><a href="manageHotel.php">Manage Hotels</a></li>
            <li><a href="manageUser.php" class="active">Manage Users</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Manage Users</h1>
            <p>View registered accounts</p>
        </div>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td>#<?php echo $user['id']; ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                    <td>
                        <span class="role-badge <?php echo ($user['role'] == 'admin') ? 'role-admin' : 'role-user'; ?>">
                            <?php echo ucfirst($user['role']); ?>
                        </span>
                    </td>
                    <td><?php echo $user['created_at']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

</body>
</html>