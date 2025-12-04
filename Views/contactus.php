<?php
// contact_us.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Contact Us | TripLink</title>
  <link ref="stylesheet" href="/../Public/css/contactus.css">
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
