<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// --- 1. DATABASE CONNECTION - USING SINGLETON PATTERN ---
require_once __DIR__ . '/../config/database.php';

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Get the singleton instance
        $db = Database::getInstance();
        $pdo = $db->getConnection();

        // Collect and sanitize input
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $msg_content = trim($_POST['message'] ?? '');
        $subject = "General Inquiry"; // Default subject since your form doesn't have one

        // Simple validation
        if (!empty($name) && !empty($email) && !empty($msg_content)) {
            // Insert into database
            // Note: If you created the table with a 'subject' column, we pass a default value
            $stmt = $pdo->prepare("INSERT INTO contact_messages (name, email, subject, message) VALUES (?, ?, ?, ?)");
            $stmt->execute([$name, $email, $subject, $msg_content]);

            // Success: Set session message and reload to prevent resubmission
            $_SESSION['message'] = "Thank you! Your message has been sent successfully.";
            header("Location: contact.php"); 
            exit;
        } else {
            $_SESSION['message'] = "Please fill in all fields.";
            header("Location: contact.php");
            exit;
        }

    } catch(PDOException $e) {
        // Error: Show what went wrong
        $_SESSION['message'] = "Database Error: " . $e->getMessage();
        header("Location: contact.php");
        exit;
    }
}

// --- 2. DISPLAY MESSAGE ---
// This grabs the message from the session (set above) and clears it
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Contact Us | TripLink</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/TripLink/public/css/base.css">
<link rel="stylesheet" href="/TripLink/public/css/contact.css">
</head>

<body>

    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="contact-container">
        <h1>Get in <span>Touch</span></h1>
        <p class="subtitle">We are here to help you plan your next journey.</p>

        <div class="info-bar">
            <div class="info-item">
                <i>📞</i>
                <span>+20 123 456 789</span>
            </div>
            <div class="info-item">
                <i>📧</i>
                <span>support@triplink.com</span>
            </div>
            <div class="info-item">
                <i>📍</i>
                <span>Cairo, Egypt</span>
            </div>
        </div>

        <?php if($message): ?>
            <div style="background:#d4edda; color:#155724; padding:15px; border-radius:8px; text-align:center; font-weight:600; margin-bottom:20px; border:1px solid #c3e6cb;">
                <?= htmlspecialchars($message) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="contact.php">
            
            <div>
                <label>Full Name</label>
                <input type="text" name="name" placeholder="John Doe" required>
            </div>

            <div>
                <label>Email Address</label>
                <input type="email" name="email" placeholder="john@example.com" required>
            </div>

            <div>
                <label>Message</label>
                <textarea name="message" placeholder="How can we help you today?" required></textarea>
            </div>

            <button type="submit">Send Message</button>
        </form>

    </div>

    <div class="footer-text">
        © 2025 TripLink — Your Journey Starts Here
    </div>

</body>
</html>