<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/User.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in (you may want to add admin role check here)
if (!isset($_SESSION['user_id'])) {
    header('Location: /TripLink/Views/login.php');
    exit;
}

$db = new Database();
$userModel = new User($db->conn);

// Handle logout
if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    header('Location: /TripLink/Views/login.php');
    exit;
}

// Handle user actions (suspend, activate, delete)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $userId = isset($_POST['user_id']) ? (int)$_POST['user_id'] : 0;
    
    if ($userId > 0) {
        if ($action === 'delete') {
            // Delete user
            $stmt = $db->conn->prepare("DELETE FROM users WHERE id = ?");
            if ($stmt) {
                $stmt->bind_param("i", $userId);
                $stmt->execute();
            }
        } elseif ($action === 'suspend' || $action === 'activate') {
            // Update user status (assuming you have a status column)
            $status = $action === 'activate' ? 'active' : 'inactive';
            try {
                $stmt = $db->conn->prepare("UPDATE users SET status = ? WHERE id = ?");
                if ($stmt) {
                    $stmt->bind_param("si", $status, $userId);
                    $stmt->execute();
                }
            } catch (\mysqli_sql_exception $e) {
                // Status column might not exist, ignore
            }
        }
    }
    header('Location: /TripLink/Views/admin_dashboard.php');
    exit;
}

// Get search query and status filter
$search = $_GET['search'] ?? '';
$statusFilter = $_GET['status'] ?? 'all';

// Get all users
$allUsers = $userModel->getAllUsers();

// Filter users
$filteredUsers = $allUsers;
if (!empty($search)) {
    $searchLower = strtolower($search);
    $filteredUsers = array_filter($filteredUsers, function($user) use ($searchLower) {
        $firstName = strtolower($user['first_name'] ?? '');
        $lastName = strtolower($user['last_name'] ?? '');
        $email = strtolower($user['email'] ?? '');
        $username = strtolower($user['username'] ?? '');
        
        // If no first_name/last_name, use username or email
        if (empty($firstName) && !empty($username)) {
            $fullName = strtolower($username);
        } else {
            $fullName = trim($firstName . ' ' . $lastName);
        }
        
        return strpos($firstName, $searchLower) !== false ||
               strpos($lastName, $searchLower) !== false ||
               strpos($fullName, $searchLower) !== false ||
               strpos($email, $searchLower) !== false ||
               strpos($username, $searchLower) !== false;
    });
}

if ($statusFilter !== 'all') {
    $filteredUsers = array_filter($filteredUsers, function($user) use ($statusFilter) {
        $userStatus = strtolower($user['status'] ?? 'active');
        return $userStatus === strtolower($statusFilter);
    });
}

$filteredUsers = array_values($filteredUsers); // Re-index array
$totalUsers = count($filteredUsers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelAdmin | Admin Dashboard</title>
    <link rel="stylesheet" href="/TripLink/Public/css/admin_dashboard.css">
</head>
<body>
    <div class="admin-container">
        <!-- Left Sidebar -->
        <aside class="admin-sidebar">
            <div class="sidebar-header">
                <h1 class="sidebar-title">TravelAdmin</h1>
                <p class="sidebar-subtitle">Admin Dashboard</p>
            </div>

            <nav class="sidebar-nav">
                <a href="#" class="nav-item" data-tab="dashboard">
                    <span class="nav-icon">▦</span>
                    <span>Dashboard Overview</span>
                </a>
                <a href="#" class="nav-item active" data-tab="users">
                    <span class="nav-icon">👥</span>
                    <span>User Management</span>
                </a>
                <a href="#" class="nav-item" data-tab="providers">
                    <span class="nav-icon">🏢</span>
                    <span>Service Providers</span>
                </a>
                <a href="#" class="nav-item" data-tab="statistics">
                    <span class="nav-icon">📊</span>
                    <span>Statistics</span>
                </a>
                <a href="#" class="nav-item" data-tab="settings">
                    <span class="nav-icon">⚙</span>
                    <span>Account Settings</span>
                </a>
            </nav>

            <div class="sidebar-footer">
                <a href="/TripLink/Views/admin_dashboard.php?logout=1" class="logout-link">
                    <span>Logout</span>
                    <span class="logout-icon">→</span>
                </a>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="admin-main">
            <!-- Logout Button Top Left -->
            <div class="top-logout">
                <a href="/TripLink/Views/admin_dashboard.php?logout=1" class="logout-btn-top">Logout</a>
            </div>

            <!-- User Management Tab -->
            <div class="tab-content active" id="tab-users">
                <div class="page-header">
                    <div>
                        <h1 class="page-title">User Management</h1>
                        <p class="page-subtitle">Manage registered users and their account status.</p>
                    </div>
                </div>

                <form method="GET" action="" class="search-filter-bar">
                    <div class="search-wrapper">
                        <span class="search-icon">🔍</span>
                        <input 
                            type="text" 
                            name="search" 
                            placeholder="Search by name or email..." 
                            class="search-input"
                            value="<?= htmlspecialchars($search) ?>"
                        >
                    </div>
                    <div class="filter-wrapper">
                        <span class="filter-icon">🔽</span>
                        <select name="status" class="status-filter" onchange="this.form.submit()">
                            <option value="all" <?= $statusFilter === 'all' ? 'selected' : '' ?>>All Status</option>
                            <option value="active" <?= $statusFilter === 'active' ? 'selected' : '' ?>>Active</option>
                            <option value="inactive" <?= $statusFilter === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </form>

                <div class="table-card">
                    <h2 class="table-title">All Users (<?= $totalUsers ?>)</h2>
                    <div class="table-wrapper">
                        <table class="users-table">
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (empty($filteredUsers)): ?>
                                    <tr>
                                        <td colspan="5" class="no-users">No users found</td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($filteredUsers as $user): ?>
                                        <?php
                                        // Handle different database schemas
                                        $firstName = $user['first_name'] ?? '';
                                        $lastName = $user['last_name'] ?? '';
                                        $username = $user['username'] ?? '';
                                        $email = $user['email'] ?? 'N/A';
                                        $status = strtolower($user['status'] ?? 'active');
                                        $userId = $user['id'] ?? $user['ID'] ?? 0;
                                        
                                        // If no first_name/last_name, try to split username or use email
                                        if (empty($firstName) && !empty($username)) {
                                            $nameParts = explode(' ', $username, 2);
                                            $firstName = $nameParts[0] ?? $username;
                                            $lastName = $nameParts[1] ?? '';
                                        }
                                        if (empty($firstName)) {
                                            $emailParts = explode('@', $email);
                                            $firstName = $emailParts[0] ?? 'User';
                                            $lastName = '';
                                        }
                                        ?>
                                        <tr>
                                            <td><?= htmlspecialchars($firstName) ?></td>
                                            <td><?= htmlspecialchars($lastName) ?></td>
                                            <td><?= htmlspecialchars($email) ?></td>
                                            <td>
                                                <span class="status-badge <?= $status === 'active' ? 'active' : 'inactive' ?>">
                                                    <?= ucfirst($status) ?>
                                                </span>
                                            </td>
                                            <td class="actions-cell">
                                                <form method="POST" action="" style="display: inline;">
                                                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                                                    <?php if ($status === 'active'): ?>
                                                        <button type="submit" name="action" value="suspend" class="action-btn suspend-btn">
                                                            <span>👤⭐</span>
                                                            Suspend
                                                        </button>
                                                    <?php else: ?>
                                                        <button type="submit" name="action" value="activate" class="action-btn activate-btn">
                                                            <span>👤⭐</span>
                                                            Activate
                                                        </button>
                                                    <?php endif; ?>
                                                </form>
                                                <form method="POST" action="" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    <input type="hidden" name="user_id" value="<?= $userId ?>">
                                                    <button type="submit" name="action" value="delete" class="action-btn delete-btn">
                                                        <span>🗑️</span>
                                                        Delete
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Placeholder tabs (to be implemented later) -->
            <div class="tab-content" id="tab-dashboard">
                <h1>Dashboard Overview</h1>
                <p>Coming soon...</p>
            </div>
            <div class="tab-content" id="tab-providers">
                <h1>Service Providers</h1>
                <p>Coming soon...</p>
            </div>
            <div class="tab-content" id="tab-statistics">
                <h1>Statistics</h1>
                <p>Coming soon...</p>
            </div>
            <div class="tab-content" id="tab-settings">
                <h1>Account Settings</h1>
                <p>Coming soon...</p>
            </div>
        </main>
    </div>

    <script>
        // Tab switching
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all nav items
                document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                // Hide all tab contents
                document.querySelectorAll('.tab-content').forEach(tab => tab.classList.remove('active'));
                
                // Show selected tab
                const tabId = 'tab-' + this.dataset.tab;
                const tabContent = document.getElementById(tabId);
                if (tabContent) {
                    tabContent.classList.add('active');
                }
            });
        });

        // Auto-submit search on input
        const searchInput = document.querySelector('.search-input');
        const searchForm = document.querySelector('.search-filter-bar');
        if (searchInput && searchForm) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    searchForm.submit();
                }, 500);
            });
        }
    </script>
</body>
</html>

