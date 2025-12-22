<?php
// Display message from session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Add Hotel | TripLink</title>

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
    color: #003580; /* Booking Blue */
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
    max-width: 500px; /* Consistent width */
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
   HEADINGS & TEXT 
================================ */
h2 {
    text-align: center;
    font-size: 1.6rem;
    margin-bottom: 25px;
    font-weight: 600;
    color: #1a1a1a;
}

h2 span {
    color: #003580;
}

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

input, select {
    width: 100%;
    padding: 12px 16px;
    border: 2px solid transparent; /* No border initially */
    border-radius: 8px;
    background: #f7f9fc; /* Light gray-blue bg */
    font-size: 0.95rem;
    color: #333;
    outline: none;
    transition: all 0.3s ease;
}

/* Focus State */
input:focus, select:focus {
    background: white;
    border-color: #003580;
    box-shadow: 0 4px 12px rgba(0, 53, 128, 0.08);
}

/* Date inputs specifics */
input[type="date"] {
    color: #555;
    font-family: 'Poppins', sans-serif;
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
    /* Premium Gradient */
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

/* Success/Error Message */
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
</style>
</head>

<body>

<div class="navbar">
    <div class="brand">TripLink Dashboard</div>
    <a href="menu.php">Back to Home</a>
</div>

<form class="form-container" method="POST" action="/TripLink/public/index.php?controller=hotel&action=save">

    <h2>🏨 Add <span>Hotel</span></h2>

    <?php if($message): ?>
        <div class="message">✨ <?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <label>Destination City</label>
    <select name="city" id="city" required onchange="updateHotels()">
        <option value="">-- Select City --</option>
        <option value="Egypt">Egypt</option>
        <option value="Saudi">Saudi</option>
        <option value="Dubai">Dubai</option>
    </select>

    <label>Hotel Property</label>
    <select name="hotel_name" id="hotel_name" required>
        <option value="">-- Select Hotel --</option>
    </select>

    <div style="display: flex; gap: 15px;">
        <div style="flex: 1;">
            <label>Check-In</label>
            <input type="date" name="check_in" required>
        </div>
        <div style="flex: 1;">
            <label>Check-Out</label>
            <input type="date" name="check_out" required>
        </div>
    </div>

    <div style="display: flex; gap: 15px;">
        <div style="flex: 1;">
            <label>Price per Night ($)</label>
            <input type="number" step="0.01" name="price_per_night" placeholder="0.00" required>
        </div>
        <div style="flex: 1;">
            <label>Rating (1–5)</label>
            <input type="number" name="rating" min="1" max="5" placeholder="5" required>
        </div>
    </div>

    <button type="submit">Save Booking</button>

    <a href="menu.php" class="back-link">Cancel and return</a>
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