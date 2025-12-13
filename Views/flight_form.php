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
<link rel="stylesheet" href="/TripLink/Public/css/flight_form.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';?>

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
<?php include __DIR__ . '/partials/footer.php';?>
</body>
</html>
