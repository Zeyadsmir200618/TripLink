<link rel="stylesheet" href="/TripLink/Public/css/navbar.css">
<nav class="navbar">
    <div class="nav-container">
        <a href="/TripLink/Views/aboutus.php" class="logo">TripLink</a>
        <div class="nav-links">
            <a href="/TripLink/Views/hotels.php" class="nav-link">Book a trip</a>
            <a href="/TripLink/Views/customer_dashboard.php" class="nav-link">My dashboard</a>
            <a href="/TripLink/Views/contact.php" class="nav-link">Support</a>
            <div class="dropdown">
                <button class="dropdown-btn">EN ▼</button>
            </div>
            <div class="dropdown">
                <button class="dropdown-btn flag">🇪🇬 ▼</button>
            </div>
            <?php
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $isLoggedIn = isset($_SESSION['user_id']);
            ?>
            <a href="<?= $isLoggedIn ? '/TripLink/Views/dashboard.php' : '/TripLink/Views/login.php' ?>" class="profile-icon">
                <span>👤</span>
            </a>
        </div>
    </div>
</nav>