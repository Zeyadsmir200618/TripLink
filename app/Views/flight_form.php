<?php
// ❌ REMOVE THIS LINE: session_start(); 
// (It is already started in index.php)

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Flight | TripLink</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
/* ================================
   GLOBAL RESET
================================ */
* {
    margin: 0; padding: 0; box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
}

body {
    /* Soft gradient background matching other pages */
    background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 80px; /* Space for fixed navbar */
    color: #333;
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
    letter-spacing: -0.5px;
}

.navbar a {
    text-decoration: none;
    font-weight: 500;
    color: #003580;
    font-size: 0.95rem;
    transition: color 0.2s;
    padding: 8px 15px;
    border-radius: 5px;
}

.navbar a:hover {
    background-color: #f0f4fa;
}

/* ================================
   FORM CARD
================================ */
.form-container {
    width: 100%;
    max-width: 550px; /* Slightly wider for flight details */
    background: white;
    padding: 40px;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0, 53, 128, 0.1);
    margin: 40px 20px;
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ================================
   HEADINGS
================================ */
h2 {
    text-align: center;
    font-size: 1.6rem;
    margin-bottom: 25px;
    font-weight: 600;
    color: #1a1a1a;
}

h2 span { color: #003580; }

/* ================================
   INPUTS & LABELS
================================ */
label {
    display: block;
    margin-bottom: 8px;
    margin-top: 15px;
    font-weight: 500;
    font-size: 0.9rem;
    color: #4a5568;
}

/* Container for input + icon */
.input-group {
    position: relative;
}

.input-group i {
    position: absolute;
    right: 15px;
    top: 50%;
    transform: translateY(-50%);
    font-style: normal; /* To display emojis correctly */
    font-size: 1.1rem;
    pointer-events: none; /* Let clicks pass through to input */
    opacity: 0.7;
}

input, select {
    width: 100%;
    padding: 12px 16px;
    padding-right: 40px; /* Space for the icon */
    border: 2px solid transparent;
    border-radius: 8px;
    background: #f7f9fc;
    font-size: 0.95rem;
    color: #333;
    outline: none;
    transition: all 0.3s ease;
}

input:focus, select:focus {
    background: white;
    border-color: #003580;
    box-shadow: 0 4px 12px rgba(0, 53, 128, 0.08);
}

/* ================================
   BUTTON
================================ */
button {
    width: 100%;
    margin-top: 30px;
    padding: 15px;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    background: linear-gradient(90deg, #003580 0%, #004cb8 100%);
    box-shadow: 0 4px 15px rgba(0, 53, 128, 0.2);
    transition: all 0.3s ease;
}

button:hover {
    background: linear-gradient(90deg, #002861 0%, #003580 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 53, 128, 0.3);
}

/* ================================
   UTILITIES
================================ */
.row {
    display: flex;
    gap: 15px;
}
.col { flex: 1; }

.back-link {
    display: block;
    margin-top: 20px;
    color: #666;
    text-decoration: none;
    text-align: center;
    font-size: 0.9rem;
    transition: color 0.2s;
}

.back-link:hover {
    color: #003580;
    text-decoration: underline;
}

.message {
    background-color: #e6fffa;
    color: #2c7a7b;
    padding: 12px;
    border-radius: 8px;
    margin-bottom: 20px;
    text-align: center;
    font-size: 0.9rem;
    font-weight: 500;
    border: 1px solid #b2f5ea;
}

/* Mobile Responsive */
@media(max-width: 500px) {
    .row { flex-direction: column; gap: 0; }
}
</style>
</head>

<body>

<div class="navbar">
    <div class="brand">TripLink Dashboard</div>
    <a href="menu.php">Back to Home</a>
</div>

<form class="form-container" method="POST" action="/TripLink/public/index.php?controller=flight&action=addFlight">
    <h2>🛫 Book <span>Flight</span></h2>

    <?php if($message): ?>
        <div class="message">✨ <?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col">
            <label>Flight Number</label>
            <div class="input-group">
                <input type="text" name="flight_number" placeholder="e.g. MS985" required>
                <i>✈️</i>
            </div>
        </div>
        <div class="col">
            <label>Price ($)</label>
            <div class="input-group">
                <input type="number" name="price" step="0.01" placeholder="0.00" required>
                <i>💲</i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>Departure City</label>
            <div class="input-group">
                <input type="text" name="departure_city" placeholder="From" required>
                <i>🛫</i>
            </div>
        </div>
        <div class="col">
            <label>Arrival City</label>
            <div class="input-group">
                <input type="text" name="arrival_city" placeholder="To" required>
                <i>🛬</i>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col">
            <label>Departure Date</label>
            <div class="input-group">
                <input type="datetime-local" name="departure_date" required>
                </div>
        </div>
        <div class="col">
            <label>Airline</label>
            <div class="input-group">
                <select name="airline" required>
                    <option value="">Select Airline</option>
                    <option value="EgyptAir">EgyptAir</option>
                    <option value="SaudiAir">SaudiAir</option>
                    <option value="Flynas">Flynas</option>
                    <option value="Flyadel">Flyadel</option>
                    <option value="QatarAir">QatarAir</option>
                    <option value="EmiratesAir">EmiratesAir</option>
                </select>
                </div>
        </div>
    </div>

    <button type="submit">Confirm Booking</button>
    <a class="back-link" href="menu.php">Cancel and return</a>
</form>

</body>
</html>