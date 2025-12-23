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

// 3. HANDLE DELETE REQUEST
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $userIdToDelete = (int)$_GET['delete'];
    $currentUserId = $_SESSION['user_id'];
    
    if ($userIdToDelete == $currentUserId) {
        $errorMessage = "You cannot delete your own account.";
    } else {
        try {
            $stmt = $pdo->prepare("DELETE FROM users WHERE id = :id");
            $stmt->execute([':id' => $userIdToDelete]);
            header("Location: manageUser.php?deleted=1");
            exit;
        } catch(PDOException $e) {
            $errorMessage = "Failed to delete user: " . $e->getMessage();
        }
    }
}

// 4. FETCH DATA
try {
    $stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database error: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users | Admin</title>
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
            <li><a href="manageHotel.php">Manage Hotels</a></li>
            <li><a href="manageUser.php" class="active">Manage Users</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="header">
            <h1>Manage Users</h1>
            <p>View registered accounts</p>
        </div>

        <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
            <div class="message success">
                User deleted successfully!
            </div>
        <?php endif; ?>
        
        <?php if (isset($errorMessage)): ?>
            <div class="message error">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <table class="data-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Joined Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (count($users) > 0): ?>
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
                        <td>
                            <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                <button class="delete-btn" disabled title="You cannot delete your own account">Delete</button>
                            <?php else: ?>
                                <a href="manageUser.php?delete=<?php echo $user['id']; ?>" 
                                   class="delete-btn" 
                                   onclick="return confirm('Are you sure you want to delete user: <?php echo htmlspecialchars($user['username']); ?>? This action cannot be undone.');">
                                    Delete
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">
                            No users found in database.
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</body>
</html>