<?php
// about_us.php - clean white/blue style like menu.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>About Us | TripLink</title>
<style>
/* ---- Base ---- */
* { margin:0; padding:0; box-sizing:border-box; font-family:Arial, sans-serif; }
body { background:#f1f4f9; color:#333; }

/* ---- Navbar ---- */
.navbar {
    background:white;
    width:100%;
    padding:15px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    border-bottom:1px solid #d9d9d9;
}
.logo { font-size:28px; font-weight:bold; color:#0066ff; }
.nav-links a {
    text-decoration:none;
    margin-left:25px;
    color:#333;
    font-weight:600;
    padding:8px 14px;
    border-radius:6px;
}
.nav-links a:hover { background:#e6efff; }
.active { background:#0066ff; color:white !important; }

/* ---- About Section ---- */
.about-container {
    background:white;
    width:90%;
    max-width:900px;
    margin:80px auto;
    padding:40px 50px;
    border-radius:12px;
    box-shadow:0 0 20px rgba(0,0,0,0.1);
    text-align:center;
}
.about-container h1 {
    font-size:32px;
    margin-bottom:20px;
    color:#0066ff;
}
.about-container p {
    font-size:1.1rem;
    line-height:1.8;
    color:#555;
    margin-bottom:15px;
}
.highlight { color:#0066ff; font-weight:bold; }

/* ---- Footer ---- */
footer {
    margin-top:40px;
    text-align:center;
    color:#666;
    padding:30px 0;
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">TripLink</div>
    <div class="nav-links">
        <a href="menu.php">Home</a>
        <a href="about_us.php" class="active">About</a>
        <a href="contact_us.php">Contact</a>
    </div>
</div>

<!-- About Section -->
<section class="about-container">
    <h1>About TripLink</h1>
    <p>Welcome to <span class="highlight">TripLink</span>, your trusted partner in travel and adventure. Our mission is to make travel planning simple, seamless, and affordable — whether you're exploring new destinations or revisiting your favorite spots.</p>
    <p>Founded with a passion for discovery, <span class="highlight">TripLink</span> connects travelers with the best flight options, accommodations, and travel experiences. We believe that every trip should be as unique as you are.</p>
    <p>With a dedicated team and innovative technology, we strive to bring convenience, speed, and satisfaction to every traveler. Join us on a journey that turns your travel dreams into reality.</p>
</section>

<!-- Footer -->
<footer>
    &copy; 2025 TripLink — All Rights Reserved
</footer>

</body>
</html>
