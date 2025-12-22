<?php

// ===========================
// Validator Class
// ===========================
class Validator {

    public static function sanitize(string $input): string {
        return htmlspecialchars(strip_tags(trim($input)));
    }

    public static function required(string $input): bool {
        return !empty($input);
    }

    public static function email(string $input): bool {
        return filter_var($input, FILTER_VALIDATE_EMAIL) !== false;
    }

    public static function minLength(string $input, int $length): bool {
        return strlen($input) >= $length;
    }

    public static function match(string $input1, string $input2): bool {
        return $input1 === $input2;
    }
}

// ===========================
// User Repository (CRUD + login)
// ===========================
class UserRepository {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function getAll(): array {
        // FIX: Added 'role' to select
        $stmt = $this->conn->prepare("SELECT id, username, email, role FROM users");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        // FIX: Added 'role' to select
        $stmt = $this->conn->prepare("SELECT id, username, email, role FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByEmail(string $email): ?array {
        // FIX: Added 'role' to select
        $stmt = $this->conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getByEmailOrUsername(string $input): ?array {
        // FIX: Added 'role' to select so we can save it to session later
        $stmt = $this->conn->prepare("SELECT id, username, email, password, role FROM users WHERE email = :input OR username = :input LIMIT 1");
        $stmt->execute([':input' => $input]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $username, string $email, string $hashedPassword, string $role = 'user'): bool {
        // FIX: Added 'role' to the INSERT statement to save the user type
        $stmt = $this->conn->prepare("INSERT INTO users (username, email, password, role) VALUES (:username, :email, :password, :role)");
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email,
            ':password' => $hashedPassword,
            ':role' => $role
        ]);
    }

    public function update(int $id, string $username, string $email): bool {
        $stmt = $this->conn->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
        return $stmt->execute([
            ':id' => $id,
            ':username' => $username,
            ':email' => $email
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }

    // FIX: Changed return type to ?array to return full user data (not just ID)
    public function verifyLogin(string $userInput, string $password): ?array {
        $user = $this->getByEmailOrUsername($userInput);
        if (!$user) return null;
        
        // Verify hash
        if (password_verify($password, $user['password'])) {
            unset($user['password']); // Remove password for security before returning
            return $user; // Returns [id, username, email, role]
        }
        return null;
    }
}

// ===========================
// User Service (Business Logic + Validation)
// ===========================
class UserService {
    private UserRepository $repository;

    public function __construct() {
        // Ensure Database class is loaded
        if (class_exists('Database')) {
            $db = Database::getInstance()->getConnection();
            $this->repository = new UserRepository($db);
        } else {
             // Fallback include if not autoloaded
             require_once __DIR__ . '/../config/database.php';
             $db = Database::getInstance()->getConnection();
             $this->repository = new UserRepository($db);
        }
    }

    // CRUD Methods
    public function getAll(): array {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?array {
        return $this->repository->getById($id);
    }

    public function getByEmail(string $email): ?array {
        return $this->repository->getByEmail($email);
    }

    public function getByEmailOrUsername(string $input): ?array {
        return $this->repository->getByEmailOrUsername($input);
    }

    public function create(string $username, string $email, string $password, string $role = 'user'): array {
        // Validation
        if (!Validator::required($username) || !Validator::required($email) || !Validator::required($password)) {
            return ['status' => 'error', 'message' => 'All fields are required'];
        }
        if (!Validator::email($email)) {
            return ['status' => 'error', 'message' => 'Invalid email format'];
        }
        if (!Validator::minLength($password, 6)) {
            return ['status' => 'error', 'message' => 'Password must be at least 6 characters'];
        }
        
        if ($this->repository->getByEmail($email)) {
            return ['status' => 'error', 'message' => 'Email already registered'];
        }

        $hashed = password_hash($password, PASSWORD_DEFAULT);
        
        $created = $this->repository->create($username, $email, $hashed, $role);

        return $created ? ['status' => 'success'] : ['status' => 'error', 'message' => 'Failed to create user'];
    }

    public function update(int $id, string $username, string $email): array {
        if (!Validator::required($username) || !Validator::required($email)) {
            return ['status' => 'error', 'message' => 'All fields are required'];
        }
        if (!Validator::email($email)) {
            return ['status' => 'error', 'message' => 'Invalid email format'];
        }

        $updated = $this->repository->update($id, $username, $email);
        return $updated ? ['status' => 'success'] : ['status' => 'error', 'message' => 'Failed to update user'];
    }

    public function delete(int $id): array {
        $deleted = $this->repository->delete($id);
        return $deleted ? ['status' => 'success'] : ['status' => 'error', 'message' => 'Failed to delete user'];
    }

    // FIX: Updated to handle array return from repository
    public function verifyLogin(string $userInput, string $password): array {
        $user = $this->repository->verifyLogin($userInput, $password);
        
        if ($user) {
            // Return success with all data needed for the Session
            return [
                'status' => 'success', 
                'user_id' => $user['id'],
                'username' => $user['username'], // Needed for session
                'role' => $user['role'] ?? 'user' // Needed for session
            ];
        } else {
            return ['status' => 'error', 'message' => 'Invalid credentials'];
        }
    }
}
?>
