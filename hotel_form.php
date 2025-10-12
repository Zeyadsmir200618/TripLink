<?php
include("connection.php");

if (isset($_POST['submit'])) {
  $city      = $_POST['city'];
  $hotel     = $_POST['hotel_name'];
  $checkin   = $_POST['check_in'];
  $checkout  = $_POST['check_out'];
  $price     = $_POST['price_per_night'];
  $rating    = $_POST['rating'];

  $sql = "INSERT INTO hotels (hotel_name, city, check_in, check_out, price_per_night, rating)
          VALUES ('$hotel', '$city', '$checkin', '$checkout', '$price', '$rating')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Hotel added successfully!');</script>";
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TripLink | Add Hotel</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "Poppins", sans-serif;
      background: linear-gradient(135deg, #0f2027, #203a43, #2c5364);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      perspective: 1000px;
    }

    .form-container {
      width: 400px;
      padding: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0, 255, 255, 0.2);
      backdrop-filter: blur(15px);
      transform: rotateY(0deg);
      animation: float 5s ease-in-out infinite alternate;
    }

    @keyframes float {
      0% { transform: rotateY(-5deg) translateY(0); }
      100% { transform: rotateY(5deg) translateY(-10px); }
    }

    h2 {
      color: #fff;
      text-align: center;
      margin-bottom: 25px;
      text-shadow: 0 0 10px #00e0ff;
    }

    label {
      color: #ddd;
      font-weight: 500;
      display: block;
      margin-top: 15px;
      margin-bottom: 5px;
    }

    input {
      width: 100%;
      padding: 12px;
      border: none;
      outline: none;
      border-radius: 8px;
      background: rgba(255, 255, 255, 0.2);
      color: #fff;
      font-size: 15px;
      box-shadow: inset 0 0 8px rgba(0, 255, 255, 0.2);
      transition: all 0.3s ease;
    }

    input:focus {
      background: rgba(255, 255, 255, 0.3);
      box-shadow: 0 0 12px #00e0ff, inset 0 0 5px #00e0ff;
      transform: scale(1.05);
    }

    button {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      font-size: 16px;
      font-weight: bold;
      color: #fff;
      background: linear-gradient(45deg, #00c6ff, #0072ff);
      border: none;
      border-radius: 8px;
      cursor: pointer;
      box-shadow: 0 0 15px #00c6ff;
      transition: all 0.4s ease;
    }

    button:hover {
      background: linear-gradient(45deg, #0072ff, #00c6ff);
      box-shadow: 0 0 25px #00e0ff, 0 0 50px #0072ff;
      transform: translateY(-3px);
    }

    .back-link {
      display: block;
      margin-top: 15px;
      text-align: center;
      color: #00e0ff;
      text-decoration: none;
      font-size: 14px;
      transition: color 0.3s;
    }

    .back-link:hover {
      color: #fff;
    }
  </style>
</head>
<body>
  <form class="form-container" method="POST">
    <h2>🏨 Add Hotel Information</h2>

    <label>City</label>
    <input type="text" name="city" required>

    <label>Hotel Name</label>
    <input type="text" name="hotel_name" required>

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
</body>
</html>
