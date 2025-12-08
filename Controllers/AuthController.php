<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct($db) {
        $this->userModel = new User($db);
    }

    public function login($data) {
        // FIX: correct variable name
        $userInput = trim($data['user_input'] ?? '');
        $password  = trim($data['password'] ?? '');

        if ($userInput === '' || $password === '') {
            return ["status" => "error", "message" => "Please fill in both fields"];
        }

        // FIX: check both username AND email
        $user = $this->userModel->getByEmailOrUsername($userInput);

        if (!$user) {
            return ["status" => "error", "message" => "Invalid username/email or password"];
        }

        if (!password_verify($password, $user['password'])) {
            return ["status" => "error", "message" => "Invalid username/email or password"];
        }

        $_SESSION['user_id'] = $user['id'];
        return ["status" => "success"];
    }

    public function register($data) {
        $username = trim($data['username'] ?? '');
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');
        $confirm = trim($data['confirm'] ?? '');

        if (!$username || !$email || !$password || !$confirm) {
            return ["status" => "error", "message" => "All fields are required."];
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return ["status" => "error", "message" => "Invalid email format."];
        }

        if ($password !== $confirm) {
            return ["status" => "error", "message" => "Passwords do not match."];
        }

        if ($this->userModel->getByEmail($email)) {
            return ["status" => "error", "message" => "Email already registered."];
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $ok = $this->userModel->create($username, $email, $hashed);

        return $ok ? ["status" => "success"] :
                     ["status" => "error", "message" => "Registration failed."];
    }

    public function logout() {
        session_unset();
        session_destroy();
        return ["status" => "success"];
    }
}
