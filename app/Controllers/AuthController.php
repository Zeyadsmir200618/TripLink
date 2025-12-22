<?php
// 1. SESSION MANAGEMENT
// Check if a session is already started. If not, start one.
// This is crucial because we need $_SESSION to store the logged-in user's data.
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. FILE DEPENDENCIES
// We define the paths to other important files this controller needs to work.
// __DIR__ gives the current folder of this file. 
// '/../' moves up one folder to find the 'models', 'core', and 'config' folders.
$userServicePath = __DIR__ . '/../models/User.php';     // Handles database logic for users
$validatorPath   = __DIR__ . '/../core/Validator.php';  // Handles checking input (empty? valid email?)
$databasePath    = __DIR__ . '/../config/Database.php'; // Handles connecting to the database

// 3. SAFETY CHECKS
// Before trying to load the files, we check if they actually exist.
// This prevents the "White Screen of Death" and gives a clear error message if a file is missing.
if (!file_exists($userServicePath)) {
    die("Error: User.php not found at $userServicePath");
}
if (!file_exists($validatorPath)) {
    die("Error: Validator.php not found at $validatorPath");
}
if (!file_exists($databasePath)) {
    die("Error: Database.php not found at $databasePath");
}

// 4. LOAD FILES
// require_once ensures the file is loaded. If it was already loaded elsewhere, it won't load it again (preventing errors).
require_once $userServicePath;
require_once $validatorPath;
require_once $databasePath;

// -------------------------------------------------------------
// CLASS DEFINITION
// -------------------------------------------------------------
class AuthController {
    
    // Property to hold the UserService object.
    // We use this object to talk to the database.
    private UserService $userService;

    // 5. CONSTRUCTOR
    // This runs automatically when you do 'new AuthController()'.
    // It initializes the UserService so the controller is ready to use immediately.
    public function __construct() {
        $this->userService = new UserService();
    }

    /* -------------------------------------------------------
     * LOGIN USER
     * Receives array $data (usually $_POST)
     * ------------------------------------------------------- */
    public function login(array $data): array {
        
        // Sanitize input: remove dangerous characters and extra spaces.
        // We use the Null Coalescing operator (??) to handle cases where the field might be missing.
        $userInput = Validator::sanitize(trim($data['user_input'] ?? ''));
        $password  = trim($data['password'] ?? '');

        // Validation: Check if fields are empty.
        if (!Validator::required($userInput) || !Validator::required($password)) {
            return ["status" => "error", "message" => "Please fill in both fields"];
        }

        // Database Check: Ask UserService to find the user and check the password.
        $loginResult = $this->userService->verifyLogin($userInput, $password);
        
        // If the database says "failure", we tell the user "Invalid credentials".
        if ($loginResult['status'] !== 'success') {
            return ["status" => "error", "message" => "Invalid username/email or password"];
        }

        // SUCCESS: Store user data in the Session.
        // This is what "logs them in". As long as this session exists, they are logged in.
        $_SESSION['user_id'] = $loginResult['user_id'];
        $_SESSION['username'] = $loginResult['username'];
        $_SESSION['role'] = $loginResult['role'];

        // Return success AND the role, so the frontend knows where to redirect (Admin vs Menu).
        return [
            "status" => "success", 
            "role" => $loginResult['role']
        ];
    }

    /* -------------------------------------------------------
     * REGISTER NEW USER
     * Receives array $data (usually $_POST)
     * ------------------------------------------------------- */
    public function register(array $data): array {
        
        // Sanitize all inputs.
        $username = Validator::sanitize(trim($data['username'] ?? ''));
        $email    = Validator::sanitize(trim($data['email'] ?? ''));
        $password = trim($data['password'] ?? '');
        $confirm  = trim($data['confirm'] ?? '');
        // Default role is 'user' unless specified otherwise.
        $role     = Validator::sanitize(trim($data['role'] ?? 'user'));

        // Validation 1: Check required fields.
        if (!Validator::required($username) || !Validator::required($email) ||
            !Validator::required($password) || !Validator::required($confirm)) {
            return ["status" => "error", "message" => "All fields are required."];
        }

        // Validation 2: Check email format (must look like x@y.com).
        if (!Validator::email($email)) {
            return ["status" => "error", "message" => "Invalid email format."];
        }

        // Validation 3: Password security (minimum 6 chars).
        if (!Validator::minLength($password, 6)) {
            return ["status" => "error", "message" => "Password must be at least 6 characters."];
        }

        // Validation 4: Confirm password match.
        if (!Validator::match($password, $confirm)) {
            return ["status" => "error", "message" => "Passwords do not match."];
        }

        // Validation 5: Check if email already exists in DB.
        if ($this->userService->getByEmail($email)) {
            return ["status" => "error", "message" => "Email already registered."];
        }

        // If all checks pass, create the user in the database.
        return $this->userService->create($username, $email, $password, $role);
    }

    /* -------------------------------------------------------
     * LOGOUT
     * ------------------------------------------------------- */
    public function logout(): array {
        // Remove all session variables.
        session_unset();
        // Destroy the session completely on the server.
        session_destroy();
        return ["status" => "success"];
    }

    /* =======================================================
       CRUD OPERATIONS (Used by Admin Panel)
    ======================================================= */
    
    // Get list of all users
    public function listUsers(): array {
        return $this->userService->getAll();
    }

    // Get a single user by ID
    public function getUser(int $id): ?array {
        return $this->userService->getById($id);
    }

    // Update user details (Admin function)
    public function updateUser(int $id, array $data): array {
        $username = Validator::sanitize(trim($data['username'] ?? ''));
        $email    = Validator::sanitize(trim($data['email'] ?? ''));

        // Basic validation for updates
        if (!Validator::required($username) || !Validator::required($email)) {
            return ["status" => "error", "message" => "All fields are required."];
        }

        if (!Validator::email($email)) {
            return ["status" => "error", "message" => "Invalid email format."];
        }

        return $this->userService->update($id, $username, $email);
    }

    // Delete a user (Admin function)
    public function deleteUser(int $id): array {
        return $this->userService->delete($id);
    }
}
?>