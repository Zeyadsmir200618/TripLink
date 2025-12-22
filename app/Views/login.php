<?php
session_start();
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../controllers/AuthController.php';

// Get singleton Database instance
$db = Database::getInstance();
$conn = $db->getConnection(); // MySQLi connection

// Pass connection to AuthController
$authController = new AuthController($conn);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');

    $userInput = trim($_POST['user_input'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($userInput === '' || $password === '') {
        echo json_encode([
            'status' => 'error',
            'message' => 'Please fill in both fields'
        ]);
        exit;
    }

    $result = $authController->login([
        'user_input' => $userInput,
        'password' => $password
    ]);

    echo json_encode($result);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>TripLink | Login</title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<style>
    /* Base Reset */
    * { margin:0; padding:0; box-sizing:border-box; font-family:'Poppins', sans-serif; }
    
    body { 
        /* Soft gradient background instead of flat color */
        background: linear-gradient(135deg, #f0f4f8 0%, #d9e2ec 100%);
        color: #333; 
        min-height: 100vh; 
        display: flex; 
        justify-content: center; 
        align-items: center; 
    }

    /* Container */
    .login-container {
        background: white;
        padding: 50px 40px;
        border-radius: 16px; /* softer corners */
        max-width: 420px;
        width: 90%;
        /* Deep, soft shadow for "floating" effect */
        box-shadow: 0 15px 35px rgba(0, 53, 128, 0.1);
        position: relative;
        transition: transform 0.3s ease;
    }

    .login-container:hover {
        transform: translateY(-2px);
    }

    /* Logo */
    .login-container .logo {
        font-size: 2.2rem;
        font-weight: 700;
        color: #003580;
        text-align: center;
        margin-bottom: 10px;
        letter-spacing: -1px;
    }
    
    /* Subtitle beneath logo */
    .login-container .logo span {
        display: block;
        font-size: 0.9rem;
        color: #666;
        font-weight: 400;
        margin-top: 5px;
        letter-spacing: 0;
    }

    /* Headline */
    .login-container h2 {
        text-align: center;
        font-weight: 600;
        font-size: 1.4rem;
        margin-bottom: 35px;
        color: #1a1a1a;
    }

    /* Form Fields */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group input {
        width: 100%;
        padding: 14px 18px;
        /* Light grey-blue background for inputs */
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

    /* Focus State: White bg + Blue Border */
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
        /* Gradient for a premium feel */
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

    /* Signup Link */
    p {
        text-align: center;
        margin-top: 25px;
        font-size: 0.9rem;
        color: #666;
    }

    p a {
        color: #003580;
        text-decoration: none;
        font-weight: 600;
        transition: color 0.2s;
    }

    p a:hover {
        color: #0056b3;
        text-decoration: underline;
    }

    /* Responsive */
    @media(max-width: 500px){
        .login-container { padding: 30px 20px; }
        .login-container .logo { font-size: 1.8rem; }
    }
</style>
</head>
<body>

<div class="login-container">
    <div class="logo">
        TripLink
        <span>Your journey starts here</span>
    </div>
    
    <h2>Welcome Back</h2> 
    
    <form id="loginForm" method="POST">
        <div class="form-group">
            <input type="text" name="user_input" placeholder="Username or Email" required>
        </div>
        <div class="form-group">
            <input type="password" name="password" placeholder="Password" required>
        </div>
        
        <button type="submit">Log In</button>
        
        <div id="error-box"></div>
    </form>
    
    <p>New to TripLink? <a href="signup.php">Create an account</a></p>
</div>

<script>
const form = document.getElementById('loginForm');
const errorBox = document.getElementById('error-box');

form.addEventListener('submit', async (e) => {
    e.preventDefault();
    errorBox.style.display = 'none';

    const formData = new FormData(form);

    const response = await fetch(form.action || window.location.href, {
        method: 'POST',
        body: formData
    });

    const result = await response.json();

    if(result.status === 'success'){
        window.location.href = 'menu.php';
    } else {
        errorBox.innerText = result.message;
        errorBox.style.display = 'block';
    }
});
</script>

</body>
</html>