<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TripLink | View Data</title>
  <link rel="stylesheet" href="/Triplink/Public/css/bookings.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';?>
  <h2>🌍 Flights</h2>
  <div class="table-container">
    <table>
      <tr>
        <th>ID</th><th>Flight Number</th><th>Departure</th><th>Arrival</th>
        <th>Departure Date</th><th>Return Date</th><th>Airline</th><th>Price</th>
      </tr>
      <?php foreach($flights as $row): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['flight_number'] ?></td>
          <td><?= $row['departure_city'] ?></td>
          <td><?= $row['arrival_city'] ?></td>
          <td><?= $row['departure_date'] ?></td>
          <td><?= $row['return_date'] ?></td>
          <td><?= $row['airline'] ?></td>
          <td><?= $row['price'] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

  <h2>🏨 Hotels</h2>
  <div class="table-container">
    <table>
      <tr>
        <th>ID</th><th>City</th><th>Hotel Name</th><th>Check-In</th>
        <th>Check-Out</th><th>Price/Night</th><th>Rating</th>
      </tr>
      <?php foreach($hotels as $row): ?>
        <tr>
          <td><?= $row['id'] ?></td>
          <td><?= $row['city'] ?></td>
          <td><?= $row['hotel_name'] ?></td>
          <td><?= $row['check_in'] ?></td>
          <td><?= $row['check_out'] ?></td>
          <td><?= $row['price_per_night'] ?></td>
          <td><?= $row['rating'] ?></td>
        </tr>
      <?php endforeach; ?>
    </table>
  </div>

<?php include __DIR__ . '/partials/footer.php';?>

</body>
</html>

