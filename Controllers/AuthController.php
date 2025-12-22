<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Require necessary files
$userServicePath = __DIR__ . '/../models/User.php';
$validatorPath   = __DIR__ . '/../core/Validator.php';
$databasePath    = __DIR__ . '/../config/Database.php';

if (!file_exists($userServicePath)) {
    die("Error: User.php not found at $userServicePath");
}
if (!file_exists($validatorPath)) {
    die("Error: Validator.php not found at $validatorPath");
}
if (!file_exists($databasePath)) {
    die("Error: Database.php not found at $databasePath");
}

require_once $userServicePath;
require_once $validatorPath;
require_once $databasePath;

class AuthController {
    private UserService $userService;

    public function __construct() {
        $this->userService = new UserService();
    }

    /* -------------------------------------------------------
     * LOGIN USER (UPDATED TO RETURN ROLE)
     * ------------------------------------------------------- */
    public function login(array $data): array {
        $userInput = Validator::sanitize(trim($data['user_input'] ?? ''));
        $password  = trim($data['password'] ?? '');

        if (!Validator::required($userInput) || !Validator::required($password)) {
            return ["status" => "error", "message" => "Please fill in both fields"];
        }

        $loginResult = $this->userService->verifyLogin($userInput, $password);
        if ($loginResult['status'] !== 'success') {
            return ["status" => "error", "message" => "Invalid username/email or password"];
        }

        // Store session data
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['username'] = $loginResult['username'];
        $_SESSION['role'] = $loginResult['role'];

        // *** THIS IS THE ONLY CHANGE ***
        // We now send the role back so the login page knows where to redirect you.
        return [
            "status" => "success", 
            "role" => $loginResult['role']
        ];
    }

    /* -------------------------------------------------------
     * REGISTER NEW USER
     * ------------------------------------------------------- */
    public function register(array $data): array {
        $username = Validator::sanitize(trim($data['username'] ?? ''));
        $email    = Validator::sanitize(trim($data['email'] ?? ''));
        $password = trim($data['password'] ?? '');
        $confirm  = trim($data['confirm'] ?? '');
        $role     = Validator::sanitize(trim($data['role'] ?? 'user'));

        if (!Validator::required($username) || !Validator::required($email) ||
            !Validator::required($password) || !Validator::required($confirm)) {
            return ["status" => "error", "message" => "All fields are required."];
        }

        if (!Validator::email($email)) {
            return ["status" => "error", "message" => "Invalid email format."];
        }

        if (!Validator::minLength($password, 6)) {
            return ["status" => "error", "message" => "Password must be at least 6 characters."];
        }

        if (!Validator::match($password, $confirm)) {
            return ["status" => "error", "message" => "Passwords do not match."];
        }

        if ($this->userService->getByEmail($email)) {
            return ["status" => "error", "message" => "Email already registered."];
        }

        return $this->userService->create($username, $email, $password, $role);
    }

    /* -------------------------------------------------------
     * LOGOUT
     * ------------------------------------------------------- */
    public function logout(): array {
        session_unset();
        session_destroy();
        return ["status" => "success"];
    }

    /* =======================================================
       CRUD OPERATIONS FOR USERS
    ======================================================= */
    public function listUsers(): array {
        return $this->userService->getAll();
    }

    public function getUser(int $id): ?array {
        return $this->userService->getById($id);
    }

    public function updateUser(int $id, array $data): array {
        $username = Validator::sanitize(trim($data['username'] ?? ''));
        $email    = Validator::sanitize(trim($data['email'] ?? ''));

        if (!Validator::required($username) || !Validator::required($email)) {
            return ["status" => "error", "message" => "All fields are required."];
        }

        if (!Validator::email($email)) {
            return ["status" => "error", "message" => "Invalid email format."];
        }

        return $this->userService->update($id, $username, $email);
    }

    public function deleteUser(int $id): array {
        return $this->userService->delete($id);
    }
}
?>
