<?php
// about_us.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>About Us | TripLink</title>
  <style>
    /* --- GLOBAL STYLES --- */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at 20% 30%, #001f3f, #003366, #001a33);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
      overflow-x: hidden;
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
      position: fixed;
      top: 20px;
      width: 100%;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 0 60px;
      z-index: 100;
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

    /* --- ABOUT SECTION --- */
    .about-container {
      max-width: 900px;
      margin-top: 150px;
      padding: 60px;
      background: rgba(255, 255, 255, 0.08);
      border-radius: 20px;
      backdrop-filter: blur(15px);
      box-shadow: 0 0 30px rgba(0, 255, 255, 0.2);
      text-align: center;
      animation: fadeIn 1.2s ease-in-out;
    }

    .about-container h1 {
      font-size: 2.8em;
      font-weight: 800;
      color: #00e0ff;
      text-shadow: 0 0 25px #00e0ff;
      margin-bottom: 25px;
    }

    .about-container p {
      font-size: 1.1em;
      line-height: 1.9;
      color: #d9ecff;
      margin-bottom: 20px;
    }

    .highlight {
      color: #00e0ff;
      font-weight: 600;
      text-shadow: 0 0 10px #00ffff;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    /* --- FOOTER --- */
    footer {
      margin-top: 80px;
      text-align: center;
      color: #a5c9ff;
      font-size: 0.9em;
      padding: 20px;
      animation: fadeIn 1.5s ease-in-out;
    }

    /* --- PLANE ICON ANIMATION --- */
    .plane {
      position: absolute;
      top: 18%;
      left: -100px;
      font-size: 2em;
      animation: flyAcross 12s linear infinite;
    }

    @keyframes flyAcross {
      0% { transform: translateX(-200px); opacity: 0; }
      10% { opacity: 1; }
      50% { transform: translateX(50vw) translateY(-40px); }
      100% { transform: translateX(110vw); opacity: 0; }
    }
  </style>
</head>
<body>

  <div class="navbar">
    <div class="logo">TripLink</div>
    <div class="nav-links">
      <a href="menu.php">Home</a>
    </div>
  </div>

  <div class="plane">✈️</div>

  <section class="about-container">
    <h1>About TripLink</h1>
    <p>
      Welcome to <span class="highlight">TripLink</span>, your trusted partner in travel and adventure.
      Our mission is to make travel planning simple, seamless, and affordable — whether you're exploring new destinations or revisiting your favorite spots.
    </p>
    <p>
      Founded with passion for discovery, <span class="highlight">TripLink</span> connects travelers with the best flight options, accommodations, and travel experiences.
      We believe that every trip should be as unique as you are.
    </p>
    <p>
      With a dedicated team and innovative technology, we strive to bring convenience, speed, and satisfaction to every traveler.
      Join us on a journey that turns your travel dreams into reality.
    </p>
  </section>

  <footer>
    &copy; 2025 TripLink. All rights reserved.
  </footer>

</body>
</html>
