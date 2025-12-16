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
        // Try multiple column combinations
        $queries = [
            "SELECT id, username, email, password FROM users WHERE email = ?",
            "SELECT id, name, email, password FROM users WHERE email = ?",
            "SELECT id, email, password FROM users WHERE email = ?"
        ];

        foreach ($queries as $query) {
            try {
                $stmt = $this->conn->prepare($query);
                if ($stmt) {
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result ? $result->fetch_assoc() : null;
                    if ($user) {
                        return $user;
                    }
                }
            } catch (\mysqli_sql_exception $e) {
                continue;
            }
        }

        // Fallback with uppercase ID
        try {
            $stmt = $this->conn->prepare("SELECT ID, email, password FROM users WHERE email = ?");
            if ($stmt) {
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result ? $result->fetch_assoc() : null;
                if ($user && isset($user['ID'])) {
                    $user['id'] = $user['ID'];
                    unset($user['ID']);
                }
                return $user;
            }
        } catch (\mysqli_sql_exception $e) {
            // Ignore
        }

        return null;
    }

    /**
     * Fetch user by email OR username/name
     */
    public function getByEmailOrUsername($input) {
        // Try multiple column combinations to support different database schemas
        $queries = [
            // Try with username column
            "SELECT id, username, email, password FROM users WHERE email = ? OR username = ? LIMIT 1",
            // Try with name column
            "SELECT id, name, email, password FROM users WHERE email = ? OR name = ? LIMIT 1",
            // Try with both username and name
            "SELECT id, username, name, email, password FROM users WHERE email = ? OR username = ? OR name = ? LIMIT 1"
        ];

        foreach ($queries as $query) {
            try {
                $stmt = $this->conn->prepare($query);
                if ($stmt) {
                    // Bind parameters based on query
                    if (strpos($query, 'username = ? OR name = ?') !== false) {
                        // Query with both username and name
                        $stmt->bind_param("sss", $input, $input, $input);
                    } else {
                        // Query with username OR name (2 params)
                        $stmt->bind_param("ss", $input, $input);
                    }
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result ? $result->fetch_assoc() : null;
                    if ($user) {
                        return $user;
                    }
                }
            } catch (\mysqli_sql_exception $e) {
                // Try next query
                continue;
            }
        }

        // Final fallback: email-only
        try {
            $stmt = $this->conn->prepare("SELECT id, email, password FROM users WHERE email = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $input);
                $stmt->execute();
                $result = $stmt->get_result();
                return $result ? $result->fetch_assoc() : null;
            }
        } catch (\mysqli_sql_exception $e) {
            // Last resort: try with uppercase ID
            $stmt = $this->conn->prepare("SELECT ID, email, password FROM users WHERE email = ? LIMIT 1");
            if ($stmt) {
                $stmt->bind_param("s", $input);
                $stmt->execute();
                $result = $stmt->get_result();
                $user = $result ? $result->fetch_assoc() : null;
                if ($user && isset($user['ID'])) {
                    $user['id'] = $user['ID'];
                    unset($user['ID']);
                }
                return $user;
            }
        }

        return null;
    }

    /**
     * Create a new user (registration)
     */
    public function create($username, $email, $hashedPassword): bool {
        try {
            // Try insert with username column
            $stmt = $this->conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if ($stmt) {
                $stmt->bind_param("sss", $username, $email, $hashedPassword);
                return $stmt->execute(); // returns true on success, false on failure
            }
        } catch (\mysqli_sql_exception $e) {
            // Fallback for schema without username column: store only email + password
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
