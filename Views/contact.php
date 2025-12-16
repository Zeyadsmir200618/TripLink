<?php
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | TripLink</title>
<link rel="stylesheet" href="/TripLink/Public/css/contactus.css">
</head>
<body>

<?php include __DIR__ . '/partials/navbar.php';?>
<div class="contact-container">
    <h1>Contact Us</h1>
    <p>Have questions? Send us a message and we’ll get back to you soon.</p>

    <form method="post" action="#">
        <input type="text" name="name" placeholder="Your Name" required>
        <input type="email" name="email" placeholder="Your Email" required>
        <textarea name="message" placeholder="Your Message..." required></textarea>
        <button type="submit">Send Message</button>
    </form>

    <a href="menu.php" class="back-home">← Back to Home</a>
</div>

<?php include __DIR__ . '/partials/footer.php';?>
</body>
</html>
