<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, go to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /TripLink/Views/customer_dashboard.php');
    exit;
}

$error = $_SESSION['auth_error'] ?? '';
unset($_SESSION['auth_error']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Sign In</title>
    <link rel="stylesheet" href="/TripLink/Public/css/auth.css">
</head>
<body class="auth-body">
    <div class="auth-background"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Welcome to TripLink</h1>
                <p>Sign in to plan your next adventure</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="auth-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/TripLink/index.php?controller=auth&action=login" class="auth-form">
                <div class="auth-field">
                    <label for="user_input">Email or Username</label>
                    <input
                        type="text"
                        id="user_input"
                        name="user_input"
                        placeholder="Enter your email or username"
                        required
                    >
                </div>

                <div class="auth-field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                </div>

                <button type="submit" class="auth-primary-btn">
                    Sign In
                </button>
            </form>

            <div class="auth-footer-text">
                <span>Don't have an account?</span>
                <a href="/TripLink/Views/register.php">Create account</a>
            </div>

            <p class="auth-small-text">
                Connect with travelers worldwide and plan amazing trips together
            </p>
        </div>
    </div>
</body>
</html>


