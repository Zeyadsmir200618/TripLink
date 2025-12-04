<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🏨 Add Hotel | TripLink</title>
  <link rel="stylesheet" href="/../Public/css/hotel.css">
</head>
<body>

  <div class="stars" style="top:10%;left:25%;animation-delay:1s;"></div>
  <div class="stars" style="top:35%;left:70%;animation-delay:2s;"></div>
  <div class="stars" style="top:60%;left:50%;animation-delay:3s;"></div>
  <div class="stars" style="top:80%;left:30%;animation-delay:4s;"></div>

  <form class="form-container" method="POST" >
    <h2><span class="earth">🌍</span> Add Hotel Info</h2>

    <label>City</label>
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

    <button type="submit" name="submit">✨ Add Hotel</button>
    <a href="menu.php" class="back-link">⬅ Back to Home</a>
  </form>

 <script src="/Public/js/hotel.js"></script>
</body>
</html>
