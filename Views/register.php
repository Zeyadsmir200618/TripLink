<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If already logged in, go straight to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /TripLink/Views/dashboard.php');
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
    <title>TripLink | Create Account</title>
    <link rel="stylesheet" href="/TripLink/Public/css/auth.css">
</head>
<body class="auth-body">
    <div class="auth-background"></div>

    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-header">
                <h1>Create your TripLink account</h1>
                <p>Join to start planning your next adventure</p>
            </div>

            <button class="auth-google-btn" type="button">
                <span class="google-icon">G</span>
                Sign up with Google
            </button>

            <div class="auth-divider">
                <span>or</span>
            </div>

            <?php if (!empty($error)): ?>
                <div class="auth-error">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="/TripLink/index.php?controller=auth&action=register" class="auth-form">
                <div class="auth-field">
                    <label for="username">Username</label>
                    <input
                        type="text"
                        id="username"
                        name="username"
                        placeholder="Choose a username"
                        required
                    >
                </div>

                <div class="auth-field">
                    <label for="email">Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="Enter your email"
                        required
                    >
                </div>

                <div class="auth-field">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Create a password"
                        required
                    >
                </div>

                <div class="auth-field">
                    <label for="confirm">Confirm Password</label>
                    <input
                        type="password"
                        id="confirm"
                        name="confirm"
                        placeholder="Re-enter your password"
                        required
                    >
                </div>

                <button type="submit" class="auth-primary-btn">
                    Create account
                </button>
            </form>

            <div class="auth-footer-text">
                <span>Already have an account?</span>
                <a href="/TripLink/Views/login.php">Sign in</a>
            </div>

            <p class="auth-small-text">
                Connect with travelers worldwide and plan amazing trips together
            </p>
        </div>
    </div>
</body>
</html>


