<?php
// Session should already be started by the parent file
// Just check if user is logged in (use isset() properly)
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? 'User';
?>

<style>
    /* Navbar Styles */
    .navbar {
        background: white;
        padding: 15px 40px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .navbar .brand {
        font-size: 1.5rem;
        font-weight: 700;
        color: #003580;
        text-decoration: none;
        letter-spacing: -0.5px;
    }

    .navbar .nav-links {
        display: flex;
        align-items: center;
        gap: 20px;
    }

    .navbar .nav-links a {
        text-decoration: none;
        font-weight: 500;
        color: #003580;
        font-size: 0.95rem;
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.2s;
    }

    .navbar .nav-links a:hover {
        background-color: #f0f4fa;
    }

    .navbar .nav-links a.active {
        background-color: #003580;
        color: white;
    }

    /* Profile Icon/Button */
    .profile-link {
        display: flex;
        align-items: center;
        gap: 8px;
        text-decoration: none;
        color: #003580;
        font-weight: 500;
        padding: 8px 15px;
        border-radius: 5px;
        transition: all 0.2s;
    }

    .profile-link:hover {
        background-color: #f0f4fa;
    }

    .profile-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        background: linear-gradient(135deg, #003580 0%, #014194 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.9rem;
    }

    /* Button Styles */
    .btn-register,
    .btn-login {
        padding: 10px 20px;
        border-radius: 6px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.2s;
        display: inline-block;
    }

    .btn-register {
        background-color: #f0f0f0;
        color: #003580;
    }

    .btn-register:hover {
        background-color: #e0e0e0;
    }

    .btn-login {
        background-color: #003580;
        color: white;
    }

    .btn-login:hover {
        background-color: #002d66;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .navbar {
            padding: 15px 20px;
            flex-wrap: wrap;
        }

        .navbar .nav-links {
            gap: 10px;
        }

        .navbar .nav-links a {
            padding: 6px 12px;
            font-size: 0.85rem;
        }
    }
</style>

<nav class="navbar">
    <a href="menu.php" class="brand">TripLink</a>
    
    <div class="nav-links">
        <?php 
        $currentPage = basename($_SERVER['PHP_SELF']);
        ?>
        <a href="contact.php" <?php echo ($currentPage == 'contact.php') ? 'class="active"' : ''; ?>>Contact us</a>
        <a href="aboutus.php" <?php echo ($currentPage == 'aboutus.php') ? 'class="active"' : ''; ?>>About us</a>
        
        <?php if ($isLoggedIn): ?>
            <!-- Logged In: Show Profile -->
            <a href="profile.php" class="profile-link">
                <div class="profile-icon"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
                <span>My Profile</span>
            </a>
        <?php else: ?>
            <!-- Logged Out: Show Register & Login -->
            <a href="signup.php" class="btn-register">Register</a>
            <a href="login.php" class="btn-login">Sign in</a>
        <?php endif; ?>
    </div>
</nav>

