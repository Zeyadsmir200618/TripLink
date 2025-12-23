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
<title>Add Flight | TripLink</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/TripLink/public/css/base.css">
<link rel="stylesheet" href="/TripLink/public/css/forms.css">
</head>

<body>

<?php include __DIR__ . '/partials/navbar.php'; ?>

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