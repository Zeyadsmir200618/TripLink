<?php
// contact_us.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | TripLink</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;800&display=swap');

    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Poppins', sans-serif;
    }

    body {
      margin: 0;
      padding: 0;
      background: radial-gradient(circle at 20% 30%, #001f3f, #003366, #001a33);
      min-height: 100vh;
      display: flex;
      flex-direction: column;
      align-items: center;
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

    /* --- MAIN CONTENT --- */
    .contact-container {
      background: rgba(255,255,255,0.1);
      border: 1px solid rgba(255,255,255,0.2);
      backdrop-filter: blur(12px);
      padding: 40px 50px;
      border-radius: 20px;
      box-shadow: 0 0 25px rgba(0,255,255,0.3);
      width: 85%;
      max-width: 550px;
      text-align: center;
      margin-top: 130px;
      margin-bottom: 70px;
      overflow-y: auto;
    }

    .contact-container h1 {
      font-size: 2.3em;
      color: #00e0ff;
      text-shadow: 0 0 25px #00e0ff;
      margin-bottom: 15px;
    }

    .contact-container p {
      color: #cce6ff;
      font-size: 1em;
      margin-bottom: 25px;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 15px;
    }

    input, textarea {
      background: rgba(255,255,255,0.15);
      border: 1px solid rgba(255,255,255,0.3);
      padding: 12px;
      border-radius: 10px;
      color: #fff;
      font-size: 1em;
      outline: none;
      transition: 0.3s;
      width: 100%;
    }

    input:focus, textarea:focus {
      border-color: #00ffff;
      box-shadow: 0 0 15px rgba(0,255,255,0.5);
    }

    textarea {
      resize: none;
      height: 100px;
    }

    button {
      padding: 12px;
      background: linear-gradient(135deg, #00ffff, #007bff);
      border: none;
      border-radius: 10px;
      font-weight: 700;
      color: #000;
      font-size: 1em;
      cursor: pointer;
      box-shadow: 0 0 25px rgba(0,255,255,0.4);
      transition: 0.3s;
    }

    button:hover {
      transform: translateY(-3px) scale(1.05);
      box-shadow: 0 0 35px rgba(0,255,255,0.8);
      background: linear-gradient(135deg, #007bff, #00ffff);
    }

    /* Back Button */
    .back-home {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 28px;
      border-radius: 10px;
      text-decoration: none;
      font-weight: 600;
      color: #000;
      background: linear-gradient(135deg, #00ffff, #007bff);
      box-shadow: 0 0 20px rgba(0,255,255,0.4);
      transition: 0.3s;
    }

    .back-home:hover {
      transform: translateY(-3px) scale(1.05);
      background: linear-gradient(135deg, #007bff, #00ffff);
      box-shadow: 0 0 35px rgba(0,255,255,0.7);
    }

    /* --- FOOTER --- */
    footer {
      position: relative;
      bottom: 10px;
      color: #aaa;
      font-size: 0.9em;
      text-align: center;
      margin-bottom: 10px;
    }

    @media (max-width: 768px) {
      .contact-container {
        padding: 30px;
        width: 90%;
      }

      .nav-links {
        gap: 15px;
      }

      .logo {
        font-size: 1.7em;
      }
    }
  </style>
</head>
<body>

  <div class="navbar">
    <div class="logo">TripLink</div>
    <div class="nav-links">
      <a href="menu.php">Home</a>
      <a href="aboutus.php">About</a>
      <a href="contact.php" style="background: linear-gradient(90deg, #00ffff, #007bff); color: #000;">Contact</a>
    </div>
  </div>

  <div class="contact-container">
    <h1>Contact Us</h1>
    <p>Have questions or feedback? We’d love to hear from you!  
       Fill out the form below, and our team will get back to you shortly.</p>
    
    <form action="#" method="post">
      <input type="text" name="name" placeholder="Your Name" required>
      <input type="email" name="email" placeholder="Your Email" required>
      <textarea name="message" placeholder="Your Message..." required></textarea>
      <button type="submit">Send Message</button>
    </form>

    <a href="menu.php" class="back-home">← Back to Home</a>
  </div>

  <footer>
    &copy; 2025 TripLink. All rights reserved.
  </footer>

</body>
</html>
