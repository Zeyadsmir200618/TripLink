<?php include("connection.php"); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TripLink | View Data</title>
  <style>
    /* === GLOBAL STYLING === */
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

    body {
      font-family: 'Poppins', sans-serif;
      background: linear-gradient(135deg, #001f3f, #0077b6, #00b4d8);
      background-size: 400% 400%;
      animation: bgShift 15s ease infinite;
      margin: 0;
      padding: 40px 0;
      color: #fff;
      min-height: 100vh;
    }

    @keyframes bgShift {
      0% {background-position: 0% 50%;}
      50% {background-position: 100% 50%;}
      100% {background-position: 0% 50%;}
    }

    /* === 3D HEADER === */
    h2 {
      text-align: center;
      font-size: 3rem;
      color: #fff;
      text-transform: uppercase;
      letter-spacing: 3px;
      margin: 60px 0 20px;
      text-shadow:
        0 1px 0 #ccc,
        0 2px 0 #ccc,
        0 3px 0 #ccc,
        0 4px 0 #ccc,
        0 5px 0 #ccc,
        0 6px 10px rgba(0,0,0,0.6),
        0 0 20px #00e0ff,
        0 0 40px #00e0ff;
      animation: floatText 3s ease-in-out infinite;
    }

    @keyframes floatText {
      0%, 100% { transform: translateY(0); text-shadow:
        0 1px 0 #ccc, 0 2px 0 #ccc, 0 3px 0 #ccc,
        0 4px 0 #ccc, 0 5px 0 #ccc,
        0 6px 10px rgba(0,0,0,0.6),
        0 0 20px #00e0ff,
        0 0 40px #00e0ff;
      }
      50% { transform: translateY(-10px); text-shadow:
        0 1px 0 #ccc, 0 2px 0 #ccc, 0 3px 0 #ccc,
        0 4px 0 #ccc, 0 5px 0 #ccc,
        0 6px 15px rgba(0,0,0,0.8),
        0 0 30px #00e0ff,
        0 0 60px #00e0ff;
      }
    }

    /* === TABLE CONTAINER === */
    .table-container {
      width: 90%;
      margin: 50px auto;
      perspective: 1000px;
      animation: fadeIn 1s ease forwards;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(50px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* === TABLE STYLE === */
    table {
      width: 100%;
      border-collapse: collapse;
      border-radius: 15px;
      overflow: hidden;
      background: rgba(255, 255, 255, 0.12);
      backdrop-filter: blur(10px);
      color: #fff;
      box-shadow: 0 10px 40px rgba(0,0,0,0.3);
      transform: rotateX(4deg);
      transition: all 0.6s ease;
    }

    table:hover {
      transform: rotateX(0deg) scale(1.02);
      box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }

    th {
      background: rgba(0, 0, 0, 0.6);
      color: #00e0ff;
      text-transform: uppercase;
      padding: 14px;
      font-weight: 600;
      letter-spacing: 1px;
    }

    td {
      padding: 12px;
      text-align: center;
      border-bottom: 1px solid rgba(255,255,255,0.2);
      transition: background 0.3s ease, transform 0.3s ease;
    }

    tr:hover td {
      background: rgba(0, 224, 255, 0.2);
      transform: scale(1.02);
    }

    /* === FOOTER === */
    footer {
      text-align: center;
      color: #fff;
      margin-top: 60px;
      opacity: 0.7;
      font-size: 0.9rem;
    }
  </style>
</head>
<body>

  <h2>🌍 Flights</h2>
  <div class="table-container">
    <table>
      <tr>
        <th>ID</th><th>Flight Number</th><th>Departure</th><th>Arrival</th>
        <th>Departure Date</th><th>Return Date</th><th>Airline</th><th>Price</th>
      </tr>
      <?php
        $result = mysqli_query($conn, "SELECT * FROM flights");
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['flight_number']}</td>
            <td>{$row['departure_city']}</td>
            <td>{$row['arrival_city']}</td>
            <td>{$row['departure_date']}</td>
            <td>{$row['return_date']}</td>
            <td>{$row['airline']}</td>
            <td>{$row['price']}</td>
          </tr>";
        }
      ?>
    </table>
  </div>

  <h2>🏨 Hotels</h2>
  <div class="table-container">
    <table>
      <tr>
        <th>ID</th><th>City</th><th>Hotel Name</th><th>Check-In</th>
        <th>Check-Out</th><th>Price/Night</th><th>Rating</th>
      </tr>
      <?php
        $result = mysqli_query($conn, "SELECT * FROM hotels");
        while ($row = mysqli_fetch_assoc($result)) {
          echo "<tr>
            <td>{$row['id']}</td>
            <td>{$row['city']}</td>
            <td>{$row['hotel_name']}</td>
            <td>{$row['check_in']}</td>
            <td>{$row['check_out']}</td>
            <td>{$row['price_per_night']}</td>
            <td>{$row['rating']}</td>
          </tr>";
        }
      ?>
    </table>
  </div>

  <footer>✨ TripLink | Travel Data Dashboard © 2025 ✈️</footer>

</body>
</html>
