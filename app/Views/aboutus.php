<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | TripLink</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/TripLink/public/css/base.css">
<link rel="stylesheet" href="/TripLink/public/css/about.css">
</head>

<body>

    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="about-container">
        <h1>About <span>TripLink</span></h1>
        
        <p>
            Welcome to <span class="highlight">TripLink</span>, your trusted partner in travel and adventure. Our mission is to make travel planning simple, seamless, and affordable — whether you're exploring new destinations or revisiting your favorite spots.
        </p>
        
        <p>
            Founded with a passion for discovery, we connect travelers with the best flight options, accommodations, and travel experiences. We believe that every trip should be as unique as you are.
        </p>

        <div class="values-grid">
            <div class="value-item">
                <span class="value-icon">🌍</span>
                <span class="value-title">Global Reach</span>
                <span class="value-desc">Connecting you to over 200+ countries and regions.</span>
            </div>
            <div class="value-item">
                <span class="value-icon">🔒</span>
                <span class="value-title">Secure Booking</span>
                <span class="value-desc">Your data and payments are protected by top-tier security.</span>
            </div>
            <div class="value-item">
                <span class="value-icon">💡</span>
                <span class="value-title">Smart Travel</span>
                <span class="value-desc">Innovative technology to find the best routes and prices.</span>
            </div>
        </div>
    </div>

    <div class="footer-text">
        © 2025 TripLink — All Rights Reserved
    </div>

</body>
</html>