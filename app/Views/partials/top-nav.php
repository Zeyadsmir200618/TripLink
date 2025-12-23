<?php
$isLoggedIn = isset($_SESSION['user_id']);
$username = $_SESSION['username'] ?? 'User';
?>

<style>
    .top-nav {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        padding: 15px 50px;
        font-size: 0.9rem;
    }

    .top-nav a {
        color: white;
        text-decoration: none;
        margin-left: 20px;
        font-weight: 500;
        transition: opacity 0.2s;
    }

    .top-nav a:hover { 
        opacity: 0.8; 
    }

    /* Action Buttons */
    .top-nav .btn-action {
        padding: 8px 18px;
        border-radius: 4px;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .top-nav .btn-register {
        background-color: white;
        color: #003580;
    }
    .top-nav .btn-register:hover { 
        background-color: #f0f0f0; 
    }

    .top-nav .btn-sign-in {
        background-color: rgba(255,255,255,0.1);
        color: white;
        border: 1px solid white;
    }
    .top-nav .btn-sign-in:hover { 
        background-color: rgba(255,255,255,0.2); 
    }

    /* Profile Link for Top Nav */
    .top-nav .profile-link {
        display: flex;
        align-items: center;
        gap: 8px;
        color: white;
        text-decoration: none;
        margin-left: 20px;
        font-weight: 500;
        padding: 8px 18px;
        border-radius: 4px;
        background-color: rgba(255,255,255,0.1);
        border: 1px solid rgba(255,255,255,0.3);
        transition: all 0.2s ease;
    }

    .top-nav .profile-link:hover {
        background-color: rgba(255,255,255,0.2);
        opacity: 1;
    }

    .top-nav .profile-icon {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.85rem;
        border: 1px solid rgba(255,255,255,0.3);
    }

    @media (max-width: 900px) {
        .top-nav {
            padding: 15px 20px;
            flex-wrap: wrap;
            justify-content: center;
        }
        .top-nav a {
            margin-left: 10px;
            font-size: 0.85rem;
        }
    }
</style>

<div class="top-nav">
    <a href="contact.php">Contact us</a>
    <a href="aboutus.php">About us</a>
    
    <?php if ($isLoggedIn): ?>
        <a href="profile.php" class="profile-link">
            <div class="profile-icon"><?php echo strtoupper(substr($username, 0, 1)); ?></div>
            <span>My Profile</span>
        </a>
    <?php else: ?>
        <a href="signup.php" class="btn-action btn-register">Register</a>
        <a href="login.php" class="btn-action btn-sign-in">Sign in</a>
    <?php endif; ?>
</div>