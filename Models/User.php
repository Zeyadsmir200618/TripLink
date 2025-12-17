<?php
class User {
    private $conn;

    public function __construct($connection) {
        $this->conn = $connection;
    }

   public function getByEmail($email) {
    $query = "SELECT ID, name, email, password FROM users WHERE email = ?";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    if (!$result) {
        return null;
    }

    $user = $result->fetch_assoc();
    if (!$user) {
        return null;
    }

    $user['id'] = $user['ID'];
    unset($user['ID']);

    return $user;
}

   public function getByEmailOrUsername($input) {
    $query = "
        SELECT ID, name, email, password
        FROM users
        WHERE email = ? OR name = ?
        LIMIT 1
    ";

    $stmt = $this->conn->prepare($query);
    if (!$stmt) {
        return null;
    }

    $stmt->bind_param("ss", $input, $input);
    $stmt->execute();

    $result = $stmt->get_result();
    if (!$result) {
        return null;
    }

    $user = $result->fetch_assoc();
    if (!$user) {
        return null;
    }

    $user['id'] = $user['ID'];
    unset($user['ID']);

    return $user;
}


    public function create($username, $email, $hashedPassword): bool {
        try {
            $stmt = $this->conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $name, $email, $hashedPassword);
                return $stmt->execute();
            }
        } catch (\mysqli_sql_exception $e) {
        }

        $stmt = $this->conn->prepare("INSERT INTO users (email, password) VALUES (?, ?)");
        if (!$stmt) {
            return false;
        }
        $stmt->bind_param("ss", $email, $hashedPassword);
        return $stmt->execute();
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

    /**
     * Get all users from database
     */
    public function getAllUsers() {
        try {
            // Try to get users with all possible columns
            $stmt = $this->conn->prepare("SELECT id, name, email, first_name, last_name, status FROM users ORDER BY id DESC");
            if ($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
            }
        } catch (\mysqli_sql_exception $e) {
            try {
                $stmt = $this->conn->prepare("SELECT id, name, email FROM users ORDER BY id DESC");
                if ($stmt) {
                    $stmt->execute();
                    $result = $stmt->get_result();
                    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
                }
            } catch (\mysqli_sql_exception $e2) {
                $stmt = $this->conn->query("SELECT ID, name, email FROM users ORDER BY ID DESC");
                if ($stmt) {
                    return $stmt->fetch_all(MYSQLI_ASSOC);
                }
            }
        }
        return [];
    }
}
?>
