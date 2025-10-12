<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>TripLink | Home</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

    /* === BACKGROUND === */
    body {
      margin: 0;
      padding: 0;
      font-family: 'Poppins', sans-serif;
      background: radial-gradient(circle at 20% 30%, #001f3f, #003366, #001a33);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      color: #fff;
      overflow: hidden;
      background-size: 200% 200%;
      animation: bgShift 15s ease-in-out infinite;
    }

    @keyframes bgShift {
      0% { background-position: 0% 50%; }
      50% { background-position: 100% 50%; }
      100% { background-position: 0% 50%; }
    }

    /* === FLOATING PLANET ORBS === */
    .orb {
      position: absolute;
      border-radius: 50%;
      filter: blur(100px);
      opacity: 0.4;
      animation: float 6s ease-in-out infinite alternate;
    }
    .orb1 {
      width: 250px;
      height: 250px;
      background: #00e0ff44;
      top: 15%;
      left: 10%;
    }
    .orb2 {
      width: 300px;
      height: 300px;
      background: #007bff33;
      bottom: 10%;
      right: 15%;
    }

    @keyframes float {
      0% { transform: translateY(0); }
      100% { transform: translateY(-20px); }
    }

    /* === MAIN TITLE === */
    h1 {
      font-size: 3.2em;
      margin-bottom: 60px;
      text-transform: uppercase;
      letter-spacing: 2px;
      text-align: center;
      text-shadow:
        0 1px 0 #ccc,
        0 2px 0 #ccc,
        0 3px 0 #ccc,
        0 4px 0 #00e0ff,
        0 0 20px #00e0ff,
        0 0 40px #00e0ff;
      animation: glow 3s ease-in-out infinite alternate;
    }

    @keyframes glow {
      0% { text-shadow: 0 0 10px #00e0ff, 0 0 20px #00e0ff; transform: translateY(0); }
      100% { text-shadow: 0 0 30px #00ffff, 0 0 60px #00e0ff; transform: translateY(-10px); }
    }

    /* === BUTTONS === */
    .button-container {
      display: flex;
      gap: 25px;
      flex-wrap: wrap;
      justify-content: center;
    }

    a {
      position: relative;
      display: inline-block;
      text-decoration: none;
      color: #fff;
      font-weight: 600;
      letter-spacing: 0.5px;
      padding: 15px 40px;
      border-radius: 12px;
      background: linear-gradient(135deg, #00ffff, #007bff);
      box-shadow: 0 8px 25px rgba(0, 255, 255, 0.3);
      transform: perspective(600px) translateZ(0);
      transition: all 0.4s ease;
      overflow: hidden;
    }

    a:hover {
      transform: perspective(600px) rotateX(8deg) translateY(-5px) scale(1.08);
      box-shadow: 0 15px 40px rgba(0, 255, 255, 0.6);
      background: linear-gradient(135deg, #007bff, #00ffff);
    }

    /* === NEON GLOW EFFECT === */
    a::before {
      content: '';
      position: absolute;
      top: -50%;
      left: -50%;
      width: 200%;
      height: 200%;
      background: radial-gradient(circle, rgba(255,255,255,0.2) 10%, transparent 60%);
      opacity: 0;
      transition: opacity 0.5s ease;
    }

    a:hover::before {
      opacity: 1;
      animation: pulse 2s infinite;
    }

    @keyframes pulse {
      0%, 100% { transform: scale(1); opacity: 0.4; }
      50% { transform: scale(1.2); opacity: 0.8; }
    }

    /* === FLOATING PLANE === */
    .plane {
      position: absolute;
      top: 20%;
      left: -100px;
      font-size: 2.2em;
      animation: flyAcross 10s linear infinite;
    }

    @keyframes flyAcross {
      0% { transform: translateX(-200px) translateY(0) rotate(10deg); opacity: 0; }
      10% { opacity: 1; }
      50% { transform: translateX(50vw) translateY(-30px) rotate(0deg); }
      100% { transform: translateX(110vw) translateY(0) rotate(-5deg); opacity: 0; }
    }
  </style>
</head>
<body>
  <div class="orb orb1"></div>
  <div class="orb orb2"></div>
  <div class="plane">✈️</div>

  <h1>Welcome to <span style="color:#00e0ff; text-shadow: 0 0 15px #00e0ff;">TripLink 🌍</span></h1>

  <div class="button-container">
    <a href="flight_form.php">🛫 Book a Flight</a>
    <a href="hotel_form.php">🏨 Book a Hotel</a>
    <a href="views.php">📊 View Data</a>
  </div>
</body>
</html>
