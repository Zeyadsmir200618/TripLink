<?php
include 'connection.php';

if (isset($_POST['submit'])) {
  $flight_number = $_POST['flight_number'];
  $departure = $_POST['departure_city'];
  $arrival = $_POST['arrival_city'];
  $departure_date = $_POST['departure_date'];
  $return_date = $_POST['return_date'];
  $price = $_POST['price'];
  $airline = $_POST['airline'];

  $sql = "INSERT INTO flights (flight_number, departure_city, arrival_city, departure_date, return_date, price, airline)
          VALUES ('$flight_number', '$departure', '$arrival', '$departure_date', '$return_date', '$price', '$airline')";

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Flight added successfully!');</script>";
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🛫 Add Flight | TripLink</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    /* === 3D background gradient animation === */
    body {
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at 10% 20%, #2b1055, #7597de);
      overflow-x: hidden;
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      perspective: 1000px;
    }

    .stars {
      position: absolute;
      width: 2px;
      height: 2px;
      background: white;
      animation: twinkle 4s infinite ease-in-out;
      border-radius: 50%;
    }

    @keyframes twinkle {
      0%, 100% { opacity: 0.1; transform: scale(0.8); }
      50% { opacity: 1; transform: scale(1.5); }
    }

    /* === Floating 3D card === */
    .form-container {
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 0 40px rgba(0, 255, 255, 0.3);
      backdrop-filter: blur(20px);
      padding: 40px;
      width: 420px;
      color: #fff;
      transform-style: preserve-3d;
      transform: rotateY(10deg) rotateX(5deg);
      transition: transform 0.8s ease;
    }

    .form-container:hover {
      transform: rotateY(0deg) rotateX(0deg) scale(1.03);
      box-shadow: 0 0 50px rgba(0, 255, 255, 0.6);
    }

    h2 {
      text-align: center;
      font-weight: 600;
      margin-bottom: 25px;
      text-shadow: 0 0 10px cyan;
    }

    .earth {
      display: inline-block;
      animation: spin 6s linear infinite;
      font-size: 1.4em;
    }

    @keyframes spin {
      from { transform: rotate(0deg); }
      to { transform: rotate(360deg); }
    }

    label {
      font-weight: 500;
      font-size: 14px;
      display: block;
      margin-top: 15px;
    }

    input {
      width: 100%;
      padding: 12px;
      margin-top: 6px;
      border: none;
      border-radius: 10px;
      background: rgba(255,255,255,0.15);
      color: #fff;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
      box-shadow: 0 0 0px rgba(0,255,255,0.3);
    }

    input:focus {
      background: rgba(255,255,255,0.25);
      box-shadow: 0 0 15px cyan;
      transform: scale(1.02);
    }

    button {
      margin-top: 25px;
      width: 100%;
      padding: 12px;
      background: linear-gradient(90deg, #00ffff, #007bff);
      color: #000;
      font-weight: 600;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      font-size: 15px;
      box-shadow: 0 0 20px rgba(0,255,255,0.4);
      transition: all 0.4s ease;
    }

    button:hover {
      background: linear-gradient(90deg, #007bff, #00ffff);
      transform: translateY(-3px) scale(1.03);
      box-shadow: 0 0 35px rgba(0,255,255,0.7);
    }

    /* Floating animation for inputs */
    .form-container input, button {
      animation: float 3s ease-in-out infinite alternate;
    }

    @keyframes float {
      0% { transform: translateY(0px); }
      100% { transform: translateY(-4px); }
    }

  </style>
</head>
<body>
  <!-- Animated stars background -->
  <div class="stars" style="top:10%;left:20%;animation-delay:1s;"></div>
  <div class="stars" style="top:40%;left:70%;animation-delay:2s;"></div>
  <div class="stars" style="top:60%;left:50%;animation-delay:3s;"></div>

  <form method="POST" class="form-container">
    <h2><span class="earth">🌍</span> Add Flight Info</h2>

    <label>Flight Number:</label>
    <input type="text" name="flight_number" required>

    <label>Departure City:</label>
    <input type="text" name="departure_city" required>

    <label>Arrival City:</label>
    <input type="text" name="arrival_city" required>

    <label>Departure Date:</label>
    <input type="date" name="departure_date" required>

    <label>Return Date:</label>
    <input type="date" name="return_date">

    <label>Price:</label>
    <input type="number" step="0.01" name="price" required>

    <label>Airline:</label>
    <input type="text" name="airline" required>

    <button type="submit" name="submit">🚀 Add Flight</button>
  </form>
</body>
</html>
