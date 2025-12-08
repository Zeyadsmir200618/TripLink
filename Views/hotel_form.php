<?php
session_start();

// Display message from session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Hotel | TripLink</title>

<style>
/* ================================ 
   GLOBAL
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
   NAVIGATION
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

input, select {
    width:100%;
    padding:12px 14px;
    margin-top:6px;
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

<!-- NAVBAR -->
<div class="navbar">
    <h1>TripLink Dashboard</h1>
    <a href="menu.php">Home</a>
</div>

<!-- FORM -->
<form class="form-container" method="POST" action="index.php?controller=hotel&action=handleRequest">

    <h2>🏨 Add Hotel Info</h2>

    <?php if($message): ?>
        <div class="message"><?= $message ?></div>
    <?php endif; ?>

    <label>Select City</label>
    <select name="city" id="city" required onchange="updateHotels()">
        <option value="">-- Select City --</option>
        <option value="Egypt">Egypt</option>
        <option value="Saudi">Saudi</option>
        <option value="Dubai">Dubai</option>
    </select>

    <label>Hotel Name</label>
    <select name="hotel_name" id="hotel_name" required>
        <option value="">-- Select Hotel --</option>
    </select>

    <label>Check-In Date</label>
    <input type="date" name="check_in" required>

    <label>Check-Out Date</label>
    <input type="date" name="check_out" required>

    <label>Price per Night ($)</label>
    <input type="number" step="0.01" name="price_per_night" required>

    <label>Rating (1–5)</label>
    <input type="number" name="rating" min="1" max="5" required>

    <button type="submit">Add Hotel</button>

    <a href="menu.php" class="back-link">⬅ Back to Home</a>
</form>

<script>
const hotelsByCity = {
    Egypt: ["Cairo Grand Hotel", "Nile View Resort", "Pyramids Palace"],
    Saudi: ["Riyadh Crown", "Jeddah Sea View", "Desert Pearl Hotel"],
    Dubai: ["Burj Luxury Suites", "Palm Resort", "Downtown Royal"]
};

function updateHotels() {
    const city = document.getElementById("city").value;
    const hotelSelect = document.getElementById("hotel_name");

    hotelSelect.innerHTML = '<option value="">-- Select Hotel --</option>';

    if (hotelsByCity[city]) {
        hotelsByCity[city].forEach(hotel => {
            const option = document.createElement("option");
            option.value = hotel;
            option.textContent = hotel;
            hotelSelect.appendChild(option);
        });
    }
}
</script>

</body>
</html>
