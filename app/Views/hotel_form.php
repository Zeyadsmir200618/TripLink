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
<title>Add Hotel | TripLink</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/TripLink/public/css/base.css">
<link rel="stylesheet" href="/TripLink/public/css/forms.css">
</head>

<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

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