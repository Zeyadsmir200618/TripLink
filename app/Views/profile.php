<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Database connection
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/User.php';

$db = Database::getInstance();
$pdo = $db->getConnection();
$userService = new UserService();

$userId = $_SESSION['user_id'];
$currentTab = $_GET['tab'] ?? 'account';

// Get current user info
$user = $userService->getById($userId);

// Handle form submissions
$message = '';
$messageType = '';

// Handle Account Info Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_account'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    
    $result = $userService->update($userId, $username, $email);
    if ($result['status'] === 'success') {
        $_SESSION['username'] = $username;
        $message = 'Account information updated successfully!';
        $messageType = 'success';
        $user = $userService->getById($userId); // Refresh user data
    } else {
        $message = $result['message'] ?? 'Failed to update account information.';
        $messageType = 'error';
    }
}

// Handle Password Update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_password'])) {
    $password = trim($_POST['password'] ?? '');
    $confirm = trim($_POST['confirm_password'] ?? '');
    
    $result = $userService->updatePassword($userId, $password, $confirm);
    if ($result['status'] === 'success') {
        $message = 'Password updated successfully!';
        $messageType = 'success';
    } else {
        $message = $result['message'] ?? 'Failed to update password.';
        $messageType = 'error';
    }
}

// Handle Logout
if (isset($_GET['logout'])) {
    require_once __DIR__ . '/../Controllers/AuthController.php';
    $authController = new AuthController();
    $authController->logout();
    header("Location: login.php");
    exit;
}

// Get user bookings
$bookings = [];
try {
    $bookingsQuery = "SELECT b.*, 
        f.flight_number, f.departure_city, f.arrival_city, f.departure_date, f.return_date, f.price as flight_price, f.airline,
        h.hotel_name, h.city as hotel_city, h.price_per_night, h.check_in, h.check_out
        FROM bookings b
        LEFT JOIN flights f ON b.flight_id = f.id
        LEFT JOIN hotels h ON b.hotel_id = h.id
        WHERE b.user_id = :user_id
        ORDER BY b.created_at DESC";
    $stmt = $pdo->prepare($bookingsQuery);
    $stmt->execute([':user_id' => $userId]);
    $bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    // If bookings table doesn't exist, show empty array
    // User will see "No bookings yet" message
    $bookings = [];
    if (strpos($e->getMessage(), "doesn't exist") !== false) {
        $message = 'Bookings table not found. Please run the SQL script to create it.';
        $messageType = 'error';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile | TripLink</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TripLink/public/css/base.css">
    <link rel="stylesheet" href="/TripLink/public/css/profile.css">
</head>
<body>

    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="profile-container">
        <div class="profile-sidebar">
            <div class="profile-header">
                <div class="profile-avatar">
                    <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                </div>
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <p><?php echo htmlspecialchars($user['email']); ?></p>
            </div>
            
            <nav class="sidebar-nav">
                <a href="?tab=account" class="nav-item <?php echo $currentTab === 'account' ? 'active' : ''; ?>">
                    <span class="icon">👤</span>
                    <span>Account Info</span>
                </a>
                <a href="?tab=bookings" class="nav-item <?php echo $currentTab === 'bookings' ? 'active' : ''; ?>">
                    <span class="icon">📋</span>
                    <span>My Bookings</span>
                </a>
                <a href="?logout=1" class="nav-item logout">
                    <span class="icon">🚪</span>
                    <span>Logout</span>
                </a>
            </nav>
        </div>

        <div class="profile-content">
            <?php if ($message): ?>
                <div class="message <?php echo $messageType; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <?php if ($currentTab === 'account'): ?>
                <!-- Account Info Tab -->
                <div class="tab-content">
                    <h1>Account Information</h1>
                    <p class="subtitle">Update your account details</p>

                    <div class="form-section">
                        <h2>Personal Information</h2>
                        <form method="POST" action="?tab=account">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($user['username']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input type="email" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <button type="submit" name="update_account" class="btn-primary">Update Account</button>
                        </form>
                    </div>

                    <div class="form-section">
                        <h2>Change Password</h2>
                        <form method="POST" action="?tab=account">
                            <div class="form-group">
                                <label for="password">New Password</label>
                                <input type="password" id="password" name="password" 
                                       placeholder="Enter new password" minlength="6" required>
                            </div>
                            
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password</label>
                                <input type="password" id="confirm_password" name="confirm_password" 
                                       placeholder="Confirm new password" minlength="6" required>
                            </div>
                            
                            <button type="submit" name="update_password" class="btn-primary">Update Password</button>
                        </form>
                    </div>
                </div>

            <?php elseif ($currentTab === 'bookings'): ?>
                <!-- Bookings Tab -->
                <div class="tab-content">
                    <h1>My Bookings</h1>
                    <p class="subtitle">View all your flight and hotel bookings</p>

                    <?php if (count($bookings) > 0): ?>
                        <div class="bookings-list">
                            <?php foreach ($bookings as $booking): ?>
                                <div class="booking-card">
                                    <div class="booking-header">
                                        <div class="booking-type">
                                            <?php if ($booking['booking_type'] === 'flight'): ?>
                                                <span class="type-badge flight">✈️ Flight</span>
                                            <?php else: ?>
                                                <span class="type-badge hotel">🏨 Hotel</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="booking-date">
                                            Booked: <?php echo date('M d, Y', strtotime($booking['created_at'])); ?>
                                        </div>
                                    </div>

                                    <div class="booking-details">
                                        <?php if ($booking['booking_type'] === 'flight' && $booking['flight_id']): ?>
                                            <div class="detail-row">
                                                <span class="label">Flight Number:</span>
                                                <span class="value"><?php echo htmlspecialchars($booking['flight_number']); ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Route:</span>
                                                <span class="value">
                                                    <?php echo htmlspecialchars($booking['departure_city']); ?> → 
                                                    <?php echo htmlspecialchars($booking['arrival_city']); ?>
                                                </span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Departure:</span>
                                                <span class="value"><?php echo date('M d, Y', strtotime($booking['departure_date'])); ?></span>
                                            </div>
                                            <?php if ($booking['return_date']): ?>
                                                <div class="detail-row">
                                                    <span class="label">Return:</span>
                                                    <span class="value"><?php echo date('M d, Y', strtotime($booking['return_date'])); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="detail-row">
                                                <span class="label">Airline:</span>
                                                <span class="value"><?php echo htmlspecialchars($booking['airline']); ?></span>
                                            </div>
                                            <div class="detail-row price">
                                                <span class="label">Price:</span>
                                                <span class="value">$<?php echo number_format($booking['flight_price'], 2); ?></span>
                                            </div>
                                        <?php elseif ($booking['booking_type'] === 'hotel' && $booking['hotel_id']): ?>
                                            <div class="detail-row">
                                                <span class="label">Hotel:</span>
                                                <span class="value"><?php echo htmlspecialchars($booking['hotel_name']); ?></span>
                                            </div>
                                            <div class="detail-row">
                                                <span class="label">Location:</span>
                                                <span class="value"><?php echo htmlspecialchars($booking['hotel_city']); ?></span>
                                            </div>
                                            <?php if ($booking['check_in'] && $booking['check_in'] != '1111-02-11'): ?>
                                                <div class="detail-row">
                                                    <span class="label">Check-in:</span>
                                                    <span class="value"><?php echo date('M d, Y', strtotime($booking['check_in'])); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if ($booking['check_out'] && $booking['check_out'] != '2222-02-22'): ?>
                                                <div class="detail-row">
                                                    <span class="label">Check-out:</span>
                                                    <span class="value"><?php echo date('M d, Y', strtotime($booking['check_out'])); ?></span>
                                                </div>
                                            <?php endif; ?>
                                            <div class="detail-row price">
                                                <span class="label">Price per Night:</span>
                                                <span class="value">$<?php echo number_format($booking['price_per_night'], 2); ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <div class="no-bookings">
                            <div class="no-bookings-icon">📋</div>
                            <h3>No bookings yet</h3>
                            <p>You haven't made any bookings. Start exploring our flights and hotels!</p>
                            <a href="menu.php" class="btn-primary">Browse Now</a>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

</body>
</html>
