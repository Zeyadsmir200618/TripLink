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

<link rel="stylesheet" href="/TripLink/Public/css/hotel_form.css">
</head>

<body>
<?php include __DIR__ . '/partials/navbar.php';?>


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
<?php include __DIR__ . '/partials/footer.php';?>
</body>
</html>
