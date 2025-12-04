
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🛫 Add Flight | TripLink</title>
  <link rel="stylesheet" href="flight_form.php">
</head>
<body>
  
  <div class="stars" style="top:10%;left:25%;animation-delay:1s;"></div>
  <div class="stars" style="top:35%;left:70%;animation-delay:2s;"></div>
  <div class="stars" style="top:60%;left:50%;animation-delay:3s;"></div>
  <div class="stars" style="top:80%;left:30%;animation-delay:4s;"></div>

  <form class="form-container" method="POST">
    <h2><span class="earth">🌍</span> Add Flight Info</h2>

    <label>Flight Number</label>
    <input type="text" name="flight_number" required>

    <label>Departure City</label>
    <input type="text" name="departure_city" required>

    <label>Arrival City</label>
    <input type="text" name="arrival_city" required>

    <label>Departure Date</label>
    <input type="date" name="departure_date" required>

    <label>Return Date</label>
    <input type="date" name="return_date">

    <label>Price ($)</label>
    <input type="number" step="0.01" name="price" required>

    <label>Airline</label>
    <select name="airline" required>
      <option value="">-- Select Airline --</option>
      <option value="EgyptAir">EgyptAir</option>
      <option value="SaudiAir">SaudiAir</option>
      <option value="Flynas">Flynas</option>
      <option value="Flyadel">Flyadel</option>
      <option value="QatarAir">QatarAir</option>
      <option value="EmiratesAir">EmiratesAir</option>
    </select>

    <button type="submit" name="submit">🚀 Add Flight</button>
    <a href="menu.php" class="back-link">⬅ Back to Home</a>
  </form>
</body>
</html>
