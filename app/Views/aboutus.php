<?php
// Standard session handling pattern
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

<style>
/* ================================ 
   GLOBAL 
================================ */
* {
    margin: 0; padding: 0; box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 80px; /* Navbar space */
    color: #444;
    
    /* Font Smoothing for crisp text */
    font-family: 'Poppins', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ================================ 
   NAVIGATION 
================================ */
.navbar {
    width: 100%;
    position: fixed;
    top: 0; left: 0;
    background: white;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    z-index: 1000;
}

.navbar .brand {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003580;
}

.navbar .nav-links a {
    text-decoration: none;
    font-weight: 500;
    color: #003580;
    margin-left: 20px;
    padding: 8px 15px;
    border-radius: 5px;
    transition: 0.2s;
    font-size: 0.95rem;
}

.navbar .nav-links a:hover, 
.navbar .nav-links a.active {
    background-color: #f0f4fa;
    color: #002861;
}

/* ================================ 
   ABOUT CARD 
================================ */
.about-container {
    width: 100%;
    max-width: 850px;
    background: white;
    padding: 50px;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0, 53, 128, 0.1);
    margin: 40px 20px;
    animation: slideUp 0.5s ease-out;
    text-align: center;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ================================ 
   TYPOGRAPHY 
================================ */
h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 20px;
}

h1 span { color: #003580; }

p {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #555;
    margin-bottom: 25px;
}

.highlight {
    color: #003580;
    font-weight: 600;
}

/* ================================ 
   VALUES GRID 
================================ */
.values-grid {
    display: flex;
    justify-content: space-between;
    margin-top: 40px;
    gap: 20px;
    border-top: 1px solid #eee;
    padding-top: 40px;
}

.value-item {
    flex: 1;
    text-align: center;
}

.value-icon {
    font-size: 2.5rem;
    margin-bottom: 10px;
    display: block;
}

.value-title {
    font-weight: 700;
    color: #003580;
    margin-bottom: 5px;
    display: block;
}

.value-desc {
    font-size: 0.9rem;
    color: #666;
}

/* Responsive Grid */
@media(max-width: 600px) {
    .values-grid { flex-direction: column; }
}

/* ================================ 
   FOOTER 
================================ */
.footer-text {
    margin-top: 40px;
    color: #888;
    font-size: 0.85rem;
    text-align: center;
    padding-bottom: 20px;
}
</style>
</head>

<body>

    <div class="navbar">
        <div class="brand">TripLink</div>
        <div class="nav-links">
            <a href="menu.php">Home</a>
            <a href="aboutus.php" class="active">About</a>
            <a href="contact.php">Contact</a>
        </div>
    </div>

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