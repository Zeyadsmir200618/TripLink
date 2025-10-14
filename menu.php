<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TripLink | Explore the World</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at 20% 30%, #001f3f, #003366, #001a33);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      overflow: hidden;
      color: #fff;
      animation: bgShift 15s ease-in-out infinite;
      background-size: 200% 200%;
    }

    @keyframes bgShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* --- NAVBAR --- */
    .navbar {
      position: absolute;
      top: 20px;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 50px;
      box-sizing: border-box;
    }

    .logo {
      font-size: 2em;
      font-weight: 800;
      color: #00e0ff;
      text-shadow: 0 0 20px #00e0ff;
      letter-spacing: 1px;
      cursor: pointer;
      transition: 0.3s;
    }

    .logo:hover {
      transform: scale(1.1);
      text-shadow: 0 0 35px #00ffff;
    }

    .nav-links {
      display: flex;
      gap: 25px;
    }

    .nav-links a {
      text-decoration: none;
      color: #fff;
      font-weight: 600;
      font-size: 1em;
      padding: 10px 18px;
      border-radius: 8px;
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      backdrop-filter: blur(8px);
      transition: 0.3s;
    }

    .nav-links a:hover {
      background: linear-gradient(90deg, #00ffff, #007bff);
      color: #000;
      box-shadow: 0 0 25px rgba(0,255,255,0.7);
      transform: translateY(-3px);
    }

    /* --- HEADINGS --- */
    h1 {
      font-size: 3em;
      font-weight: 800;
      text-align: center;
      letter-spacing: 2px;
      color: #00e0ff;
      margin-bottom: 10px;
      text-shadow: 0 0 25px #00e0ff;
      margin-top: 120px;
    }

    h2 {
      text-align: center;
      font-weight: 500;
      margin-bottom: 35px;
      color: #cce6ff;
      font-size: 1.3em;
    }

    /* --- ACTION BUTTONS --- */
    .actions {
      margin-top: 40px;
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
      justify-content: center;
    }

    .actions a {
      text-decoration: none;
      color: #fff;
      font-weight: 600;
      padding: 14px 30px;
      border-radius: 12px;
      background: linear-gradient(135deg, #00ffff, #007bff);
      box-shadow: 0 0 20px rgba(0,255,255,0.4);
      transition: 0.3s;
    }

    .actions a:hover {
      transform: translateY(-5px) scale(1.05);
      box-shadow: 0 0 35px rgba(0,255,255,0.7);
      background: linear-gradient(135deg, #007bff, #00ffff);
    }

    /* --- PLANE ANIMATION --- */
    .plane {
      position: absolute;
      top: 15%;
      left: -100px;
      font-size: 2em;
      animation: flyAcross 12s linear infinite;
    }

    @keyframes flyAcross {
      0% { transform: translateX(-200px) translateY(0); opacity: 0; }
      10% { opacity: 1; }
      50% { transform: translateX(50vw) translateY(-40px); }
      100% { transform: translateX(110vw); opacity: 0; }
    }

    /* --- FOOTER --- */
    footer {
      position: absolute;
      bottom: 10px;
      color: #aaa;
      font-size: 0.9em;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <div class="navbar">
    <div class="logo">TripLink🌍</div>
    <div class="nav-links">
      <a href="aboutus.php">About Us</a>
      <a href="contact.php">Contact Us</a>
    </div>
  </div>

 
  <div class="plane">✈️</div>

  
  <h1>Find Your Next Adventure 🌍</h1>
  <h2>Flights, Hotels & More — all in one place</h2>

  <div class="actions">
    <a href="flight_form.php">🛫 Book a Flight</a>
    <a href="hotel_form.php">🏨 Book a Hotel</a>
    <a href="views.php">📊 View Bookings</a>
  </div>

  <footer>© 2025 TripLink • Explore the World with Confidence</footer>

</body>
</html>
