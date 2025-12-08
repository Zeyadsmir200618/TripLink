<?php
session_start();
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Flight | TripLink</title>

<style>
/* ================================
   BASE
================================ */
* {
    margin:0; padding:0; box-sizing:border-box;
    font-family:'Poppins', sans-serif;
}

body {
    background:linear-gradient(135deg, #e8f1ff 0%, #cfe1ff 40%, #b5d2ff 100%);
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    padding-top:120px;
}

/* ================================
   TOP NAVIGATION
================================ */
.navbar {
    width:100%;
    position:fixed;
    top:0; left:0;
    background:white;
    padding:18px 40px;
    display:flex;
    justify-content:space-between;
    align-items:center;
    box-shadow:0 4px 20px rgba(0,0,0,0.08);
    z-index:1000;
}

.navbar h1 {
    font-size:22px;
    font-weight:700;
    color:#005eff;
}

.navbar a {
    text-decoration:none;
    font-weight:600;
    color:#005eff;
    font-size:15px;
    transition:.2s;
}

.navbar a:hover {
    color:#003eaa;
}

/* ================================
   FORM CARD
================================ */
.form-container {
    width:450px;
    background:rgba(255,255,255,0.92);
    padding:50px 45px;
    border-radius:20px;
    box-shadow:0 20px 40px rgba(0,0,0,0.12);
    animation: fadeIn .5s ease;
    backdrop-filter:blur(10px);
    transform:translateY(10px);
}

@keyframes fadeIn {
  from { opacity:0; transform:translateY(15px); }
  to   { opacity:1; transform:translateY(0); }
}

/* ================================
   TITLE
================================ */
h2 {
    text-align:center;
    font-size:1.8rem;
    margin-bottom:30px;
    font-weight:700;
    color:#004ad8;
}

/* ================================
   LABELS + INPUT FIELDS
================================ */
label {
    display:block;
    margin-top:18px;
    font-weight:600;
    font-size:14px;
    color:#333;
}

.input-group {
    position:relative;
}

.input-group i {
    position:absolute;
    right:14px;
    top:50%;
    transform:translateY(-50%);
    color:#005eff;
    font-size:18px;
}

input, select {
    width:100%;
    padding:12px 14px;
    margin-top:6px;
    padding-right:38px;
    border:1px solid #d0d7e6;
    border-radius:10px;
    background:#f9fbff;
    font-size:14px;
    transition:.2s ease;
}

input:focus, select:focus {
    border-color:#005eff;
    background:white;
    box-shadow:0 0 8px rgba(0,102,255,0.25);
    outline:none;
}

/* ================================
   BUTTON
================================ */
button {
    width:100%;
    margin-top:30px;
    padding:14px;
    font-size:16px;
    font-weight:700;
    color:white;
    border:none;
    cursor:pointer;
    border-radius:12px;
    background:linear-gradient(135deg, #0066ff, #004de6);
    box-shadow:0 10px 20px rgba(0,90,255,0.25);
    transition:.3s;
}

button:hover {
    transform:translateY(-3px);
    box-shadow:0 14px 25px rgba(0,90,255,0.35);
}

/* ================================
   BACK LINK
================================ */
.back-link {
    display:block;
    margin-top:18px;
    color:#005eff;
    text-decoration:none;
    text-align:center;
    font-size:14px;
    font-weight:600;
    transition:.2s;
}

.back-link:hover {
    color:#003eaa;
}

/* ================================
   MESSAGE BOX
================================ */
.message {
    margin-bottom:20px;
    font-weight:700;
    color:#1e9c45;
    text-align:center;
}
</style>
</head>

<body>

<!-- TOP NAV -->
<div class="navbar">
    <h1>✈️ TripLink</h1>
    <a href="menu.php">Home</a>
</div>

<!-- FORM -->
<form class="form-container" method="POST" action="index.php?controller=flight&action=handleRequest">
    <h2>🛫 Add Flight Details</h2>

    <?php if($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <label>Flight Number</label>
    <div class="input-group">
        <input type="text" name="flight_number" required>
        <i>✈️</i>
    </div>

    <label>Departure City</label>
    <div class="input-group">
        <input type="text" name="departure_city" required>
        <i>📍</i>
    </div>

    <label>Arrival City</label>
    <div class="input-group">
        <input type="text" name="arrival_city" required>
        <i>📍</i>
    </div>

    <label>Departure Date</label>
    <div class="input-group">
        <input type="date" name="departure_date" required>
        <i>📆</i>
    </div>

    <label>Return Date</label>
    <div class="input-group">
        <input type="date" name="return_date">
        <i>📆</i>
    </div>

    <label>Price ($)</label>
    <div class="input-group">
        <input type="number" name="price" step="0.01" required>
        <i>💲</i>
    </div>

    <label>Airline</label>
    <div class="input-group">
        <select name="airline" required>
            <option value="">-- Select Airline --</option>
            <option value="EgyptAir">EgyptAir</option>
            <option value="SaudiAir">SaudiAir</option>
            <option value="Flynas">Flynas</option>
            <option value="Flyadel">Flyadel</option>
            <option value="QatarAir">QatarAir</option>
            <option value="EmiratesAir">EmiratesAir</option>
        </select>
        <i>🛩️</i>
    </div>

    <button type="submit">🚀 Add Flight</button>
    <a class="back-link" href="menu.php">⬅ Back to Home</a>
</form>

</body>
</html>
