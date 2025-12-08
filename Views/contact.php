<?php
// Front-end contact page (same UI style as menu.php)
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | TripLink</title>

<style>
/* ---- Base ---- */
* { margin:0; padding:0; box-sizing:border-box; font-family:Arial, sans-serif; }
body {
    background:#f1f4f9;
    color:#333;
    padding:0;
}

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

.logo {
    font-size:28px;
    font-weight:bold;
    color:#0066ff;
}

.nav-links a {
    text-decoration:none;
    margin-left:25px;
    color:#333;
    font-weight:600;
    padding:8px 14px;
    border-radius:6px;
}

.nav-links a:hover {
    background:#e6efff;
}

.active {
    background:#0066ff;
    color:white !important;
}

/* ---- Contact Container ---- */
.contact-container {
    background:white;
    width:90%;
    max-width:550px;
    margin:60px auto;
    padding:35px 40px;
    border-radius:12px;
    box-shadow:0 0 15px rgba(0,0,0,0.1);
}

.contact-container h1 {
    font-size:28px;
    margin-bottom:10px;
    color:#0066ff;
}

.contact-container p {
    color:#555;
    margin-bottom:25px;
}

/* ---- Form ---- */
form { display:flex; flex-direction:column; gap:15px; }

input, textarea {
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #ccc;
    font-size:1rem;
}

input:focus, textarea:focus {
    border-color:#0066ff;
    outline:none;
}

textarea { height:120px; }

/* ---- Button ---- */
button {
    background:#0066ff;
    color:white;
    font-weight:bold;
    border:none;
    padding:12px;
    border-radius:8px;
    cursor:pointer;
    font-size:1rem;
}

button:hover {
    background:#0052cc;
}

/* ---- Back Home ---- */
.back-home {
    text-decoration:none;
    display:inline-block;
    margin-top:25px;
    padding:10px 22px;
    background:#0066ff;
    color:white;
    font-weight:bold;
    border-radius:8px;
}

.back-home:hover {
    background:#0052cc;
}

/* ---- Footer ---- */
footer {
    margin-top:40px;
    text-align:center;
    color:#666;
    padding-bottom:30px;
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="navbar">
    <div class="logo">TripLink</div>
    <div class="nav-links">
        <a href="menu.php">Home</a>
        <a href="about_us.php">About</a>
        <a href="contact_us.php" class="active">Contact</a>
    </div>
</div>

<!-- Contact Section -->
<div class="contact-container">
    <h1>Contact Us</h1>
    <p>Have questions? Send us a message and we’ll get back to you soon.</p>

    <form method="post" action="#">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message..." required></textarea>
        <button type="submit">Send Message</button>
    </form>

    <a href="menu.php" class="back-home">← Back to Home</a>
</div>

<!-- Footer -->
<footer>
    © 2025 TripLink — All Rights Reserved
</footer>

</body>
</html>
