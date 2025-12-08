<?php
class User {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

    /**
     * Fetch user by email
     */
    public function getByEmail($email) {
        $stmt = $this->conn->prepare("SELECT id, username, email, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns associative array or null
    }

    /**
     * Fetch user by email OR username
     */
    public function getByEmailOrUsername($input) {
        $stmt = $this->conn->prepare("SELECT id, username, email, password FROM users WHERE email = ? OR username = ? LIMIT 1");
        $stmt->bind_param("ss", $input, $input);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc(); // returns associative array or null
    }

    /**
     * Create a new user (registration)
     */
    public function create($username, $email, $hashedPassword): bool {
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        return $stmt->execute(); // returns true on success, false on failure
    }

    /**
     * Verify login credentials (supports username OR email)
     */
    public function verifyLogin($userInput, $password) {
        $user = $this->getByEmailOrUsername($userInput);
        if (!$user) return false;

        if (password_verify($password, $user['password'])) {
            return $user['id']; // login successful, return user id
        }
        return false; // password mismatch
    }
}
?>
