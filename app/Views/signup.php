<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

// Instantiate AuthController (which handles DB internally via UserService)
$authController = new AuthController();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json'); // Only send JSON for POST
    
    // Pass the POST data to the register method
    echo json_encode($authController->register($_POST));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TripLink | Sign Up</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Base Reset */
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
    
    body { 
        /* Soft gradient background matching login page */
        background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
        min-height: 100vh; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
        color: #333; 
    }

    /* Form Container */
    .signup-container {
        background: white;
        padding: 50px 40px;
        border-radius: 16px;
        max-width: 450px; /* Slightly wider than login to fit more fields */
        width: 90%;
        box-shadow: 0 15px 35px rgba(0, 53, 128, 0.1);
        position: relative;
        transition: transform 0.3s ease;
    }

    .signup-container:hover {
        transform: translateY(-2px);
    }

    /* Logo */
    .signup-container .logo {
        font-size: 2.2rem;
        font-weight: 700;
        color: #003580;
        text-align: center;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }
    
    .signup-container .logo span {
        display: block;
        font-size: 0.9rem;
        color: #666;
        font-weight: 400;
        margin-top: 5px;
        letter-spacing: 0;
    }

    /* Headline */
    .signup-container h2 {
        text-align: center;
        font-weight: 600;
        font-size: 1.4rem;
        margin-bottom: 30px;
        color: #1a1a1a;
    }

    /* Form Fields */
    .form-group {
        margin-bottom: 15px; /* Slightly tighter spacing for more fields */
        position: relative;
    }

    .form-group input {
        width: 100%;
        padding: 14px 18px;
        background: #f7f9fc;
        border: 2px solid transparent;
        border-radius: 8px;
        font-size: 0.95rem;
        color: #333;
        outline: none;
        transition: all 0.3s ease;
    }

    .form-group input::placeholder {
        color: #a0aec0;
    }

    /* Focus State */
    .form-group input:focus {
        background: white;
        border-color: #003580;
        box-shadow: 0 4px 12px rgba(0, 53, 128, 0.08);
    }

    /* Button */
    button {
        width: 100%;
        padding: 15px;
        margin-top: 10px;
        background: linear-gradient(90deg, #003580 0%, #004cb8 100%);
        color: white;
        font-weight: 600;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(0, 53, 128, 0.2);
    }

    button:hover {
        background: linear-gradient(90deg, #002861 0%, #003580 100%);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(0, 53, 128, 0.3);
    }

    button:active {
        transform: translateY(1px);
    }

    /* Error Box */
    #error-box {
        display: none;
        text-align: center;
        margin-top: 15px;
        padding: 10px;
        background: #ffe6e6;
        border-radius: 6px;
        color: #d8000c;
        font-size: 0.9rem;
        font-weight: 500;
    }

    /* Back to Login Link */
    .back-link {
        display: block;
        text-align: center;
        margin-top: 25px;
        font-size: 0.9rem;
        color: #666;
        text-decoration: none;
    }

    .back-link a {
        color: #003580;
        font-weight: 600;
        text-decoration: none;
        transition: color 0.2s;
    }

    .back-link a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Responsive */
    @media(max-width: 500px){
        .signup-container { padding: 30px 20px; }
        .signup-container .logo { font-size: 1.8rem; }
    }
</style>
</head>
<body>

<div class="signup-container">
    <div class="logo">
        TripLink
        <span>Join us today</span>
    </div>
    <h2>Create your account</h2>
    
    <form id="signupForm" autocomplete="off">
        <div class="form-group">
            <input type="text" name="username" placeholder="Username" required>
        </div>
        <div class="form-group">
            <input type="email" name="email" placeholder="Email Address" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        <div class="form-group">
            <input type="password" name="confirm" placeholder="Confirm Password" required>
        </div>
        
        <button type="submit">Sign Up</button>
        
        <div id="error-box"></div>
    </form>
    
    <div class="back-link">
        Already have an account? <a href="login.php">Log in</a>
    </div>
</div>

<script>
const form = document.getElementById('signupForm');
const errorBox = document.getElementById('error-box');

form.addEventListener('submit', async (e)=>{
    e.preventDefault();
    errorBox.style.display='none';
    const formData = new FormData(form);
    
    // POST to the same page
    const res = await fetch('', { method:'POST', body:formData }); 
    const data = await res.json();

    if(data.status==='success'){
        // Success: Redirect to login
        alert('✅ Signup successful! Redirecting to login...');
        window.location.href='login.php'; // Fixed typo here (was winAdow)
    } else {
        // Error: Show message
        errorBox.innerText = data.message;
        errorBox.style.display='block';
    }
});
</script>
</body>
</html>