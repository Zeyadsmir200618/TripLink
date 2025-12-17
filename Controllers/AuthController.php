<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../Models/User.php';

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function login($data) {
        $userInput = trim($data['user_input'] ?? '');
        $password  = trim($data['password'] ?? '');

        if ($userInput === '' || $password === '') {
            $_SESSION['auth_error'] = "Please fill in both fields";
            header('Location: /TripLink/Views/login.php');
            exit;
        }

        $user = $this->userModel->getByEmailOrUsername($userInput);


		if (!$user || !password_verify($password, $user['password'])) {
		$_SESSION['auth_error'] = "Invalid email or password";
		header('Location: /TripLink/Views/login.php');
		exit;
		}

        $userId = $user['ID'] ?? null;
        if (!$userId) {
            $_SESSION['auth_error'] = "User ID not found";
            header('Location: /TripLink/Views/login.php');
            exit;
        }

        $_SESSION['user_id'] = $userId;
        header('Location: /TripLink/Views/customer_dashboard.php');
        exit;
    }


    public function register($data) {
        $username = trim($data['name'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $confirm = trim($data['confirm'] ?? '');

        if (!$name || !$email || !$password || !$confirm) {
            $_SESSION['auth_error'] = "All fields are required.";
            header('Location: /TripLink/Views/register.php');
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['auth_error'] = "Invalid email format.";
            header('Location: /TripLink/Views/register.php');
            exit;
        }

        if ($password !== $confirm) {
            $_SESSION['auth_error'] = "Passwords do not match.";
            header('Location: /TripLink/Views/register.php');
            exit;
        }

        if ($this->userModel->getByEmail($email)) {
            $_SESSION['auth_error'] = "Email already registered.";
            header('Location: /TripLink/Views/register.php');
            exit;
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $ok = $this->userModel->create($name, $email, $hashed);

        if ($ok) {
            $newUser = $this->userModel->getByEmail($email);
            if ($newUser) {
                $userId =  $newUser['ID'] ?? null;
                if ($userId) {
                    $_SESSION['user_id'] = $userId;
                    header('Location: /TripLink/Views/customer_dashboard.php');
                    exit;
                }
            }
        }

        $_SESSION['auth_error'] = "Registration failed.";
        header('Location: /TripLink/Views/register.php');
        exit;
    }

    public function logout() {
        session_unset();
        session_destroy();
        return ["status" => "success"];
    }
}
