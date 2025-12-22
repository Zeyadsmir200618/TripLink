<?php
session_start();

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

<style>
/* ================================ 
   GLOBAL 
================================ */
* {
    margin: 0; padding: 0; box-sizing: border-box;
}

body {
    background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
    min-height: 100vh;
    display: flex;
    flex-direction: column;
    align-items: center;
    padding-top: 80px;
    color: #333;
    
    /* 🔴 FIX: This makes the font look smooth and not pixelated */
    font-family: 'Poppins', sans-serif;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

/* ================================ 
   NAVIGATION 
================================ */
.navbar {
    width: 100%;
    position: fixed;
    top: 0; left: 0;
    background: white;
    padding: 15px 40px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    z-index: 1000;
}

.navbar .brand {
    font-size: 1.5rem;
    font-weight: 700;
    color: #003580;
}

.navbar .nav-links a {
    text-decoration: none;
    font-weight: 500;
    color: #003580;
    margin-left: 20px;
    padding: 8px 15px;
    border-radius: 5px;
    transition: 0.2s;
    font-size: 0.95rem;
}

.navbar .nav-links a:hover, 
.navbar .nav-links a.active {
    background-color: #f0f4fa;
    color: #002861;
}

/* ================================ 
   CONTACT CARD 
================================ */
.contact-container {
    width: 100%;
    max-width: 600px;
    background: white;
    padding: 45px;
    border-radius: 16px;
    box-shadow: 0 15px 35px rgba(0, 53, 128, 0.1);
    margin: 40px 20px;
    animation: slideUp 0.5s ease-out;
}

@keyframes slideUp {
  from { opacity: 0; transform: translateY(20px); }
  to   { opacity: 1; transform: translateY(0); }
}

/* ================================ 
   HEADINGS & INFO 
================================ */
h1 {
    text-align: center;
    font-size: 1.8rem;
    font-weight: 700;
    color: #1a1a1a;
    margin-bottom: 10px;
}

h1 span { color: #003580; }

.subtitle {
    text-align: center;
    color: #666;
    margin-bottom: 30px;
    font-size: 0.95rem;
}

.info-bar {
    display: flex;
    justify-content: space-around;
    margin-bottom: 35px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.info-item {
    text-align: center;
    font-size: 0.85rem;
    color: #555;
}

.info-item i {
    display: block;
    font-size: 1.5rem;
    margin-bottom: 5px;
    font-style: normal;
}

/* ================================ 
   FORM INPUTS (FIXED)
================================ */
form { display: flex; flex-direction: column; gap: 20px; }

label {
    font-weight: 500;
    font-size: 0.9rem;
    color: #4a5568;
    margin-bottom: -10px;
    display: block;
}

/* 🔴 FIX: Explicitly set font-family for inputs to override browser defaults */
input, textarea, button {
    width: 100%;
    padding: 14px;
    border: 2px solid transparent;
    border-radius: 8px;
    background: #f7f9fc;
    font-family: 'Poppins', sans-serif; /* FORCE THE FONT HERE */
    font-size: 0.95rem;
    color: #333;
    outline: none;
    transition: all 0.3s ease;
    resize: vertical;
}

input::placeholder, textarea::placeholder {
    color: #a0aec0; /* Softer placeholder color */
}

input:focus, textarea:focus {
    background: white;
    border-color: #003580;
    box-shadow: 0 4px 12px rgba(0, 53, 128, 0.08);
}

textarea { min-height: 120px; }

/* ================================ 
   BUTTON 
================================ */
button {
    width: 100%;
    padding: 15px;
    font-size: 1rem;
    font-weight: 600;
    color: white;
    border: none;
    cursor: pointer;
    border-radius: 8px;
    background: linear-gradient(90deg, #003580 0%, #004cb8 100%);
    box-shadow: 0 4px 15px rgba(0, 53, 128, 0.2);
    transition: all 0.3s ease;
}

button:hover {
    background: linear-gradient(90deg, #002861 0%, #003580 100%);
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 53, 128, 0.3);
}

/* ================================ 
   FOOTER 
================================ */
.footer-text {
    margin-top: 40px;
    color: #888;
    font-size: 0.85rem;
    text-align: center;
    padding-bottom: 20px;
}
</style>
</head>

<body>

    <div class="navbar">
        <div class="brand">TripLink</div>
        <div class="nav-links">
            <a href="menu.php">Home</a>
            <a href="contact.php" class="active">Contact</a>
        </div>
    </div>

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