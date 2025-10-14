<?php
include("connection.php");
// connect to database MySQL
// import $conn (which holds the connection to the database)

if (isset($_POST['submit']))
    // check if the form is submitted
{
  $flight_number = $_POST['flight_number'];
  $departure = $_POST['departure_city'];
  $arrival = $_POST['arrival_city'];
  $departure_date = $_POST['departure_date'];
  $return_date = $_POST['return_date'];
  $price = $_POST['price'];
  $airline = $_POST['airline'];
 // collect form inputs and store them in PHP (temporarily, not in the database yet)
 
  $sql = "INSERT INTO flights (flight_number, departure_city, arrival_city, departure_date, return_date, price, airline)
          VALUES ('$flight_number', '$departure', '$arrival', '$departure_date', '$return_date', '$price', '$airline')";
  // creates the SQL command that adds a new row into the flights table.
  // each value corresponds to the columns listed inside the parentheses.

  if (mysqli_query($conn, $sql)) {
    echo "<script>alert('✅ Flight added successfully!');</script>";
  } else {
    echo "Error: " . mysqli_error($conn);
  }
}
// mysqli_query($conn, $sql) → sends the SQL command to the database.
// if successful → shows a JavaScript alert confirming success.
// if not → displays the specific MySQL error message (helpful for debugging).
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>🛫 Add Flight | TripLink</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at 10% 20%, #0a0a2a, #1e3c72, #2a5298);
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
      border-radius: 50%;
      animation: twinkle 4s infinite ease-in-out;
    }

    @keyframes twinkle {
      0%, 100% { opacity: 0.1; transform: scale(0.8); }
      50% { opacity: 1; transform: scale(1.4); }
    }

    .form-container {
      width: 420px;
      padding: 40px;
      background: rgba(255, 255, 255, 0.1);
      border: 1px solid rgba(255, 255, 255, 0.2);
      border-radius: 20px;
      box-shadow: 0 0 40px rgba(0, 255, 255, 0.3);
      backdrop-filter: blur(20px);
      color: #fff;
      transform-style: preserve-3d;
      transform: rotateY(10deg) rotateX(5deg);
      transition: all 0.8s ease;
    }

    .form-container:hover {
      transform: rotateY(0deg) rotateX(0deg) scale(1.03);
      box-shadow: 0 0 60px rgba(0, 255, 255, 0.6);
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
      display: block;
      font-weight: 500;
      margin-top: 15px;
      font-size: 14px;
      color: #ddd;
    }

    input, select {
      width: 100%;
      padding: 12px;
      margin-top: 6px;
      border: none;
      border-radius: 10px;
      background: rgba(255, 255, 255, 0.15);
      color: #fff;
      font-size: 14px;
      outline: none;
      transition: all 0.3s ease;
      box-shadow: 0 0 0px rgba(0,255,255,0.3);
      appearance: none;
    }

    input:focus, select:focus {
      background: rgba(255,255,255,0.25);
      box-shadow: 0 0 15px cyan;
      transform: scale(1.02);
    }

    select {
      cursor: pointer;
      background-image: linear-gradient(90deg, rgba(0,255,255,0.2), rgba(0,255,255,0.1));
      position: relative;
    }

    select option {
      background: #0a0a2a;
      color: #fff;
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

    .form-container input, select, button {
      animation: float 3s ease-in-out infinite alternate;
    }

    @keyframes float {
      0% { transform: translateY(0px); }
      100% { transform: translateY(-4px); }
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #00e0ff;
      text-decoration: none;
      font-size: 14px;
      transition: 0.3s;
    }

    .back-link:hover {
      color: #fff;
    }
  </style>
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
