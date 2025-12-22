<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';

class ContactController {

    private $db;

    public function __construct()
    {
        // Use Singleton Database pattern
        $this->db = Database::getInstance()->getConnection();
    }

    // ---------------------------------------------------------
    //                  SHOW CONTACT FORM
    // ---------------------------------------------------------
    public function view()
    {
        $this->render('contact', [
            "success" => $_SESSION['success'] ?? null,
            "error"   => $_SESSION['error'] ?? null
        ]);

        unset($_SESSION['success']);
        unset($_SESSION['error']);
    }

    // ---------------------------------------------------------
    //               HANDLE CONTACT FORM SUBMISSION
    // ---------------------------------------------------------
    public function submit($data)
    {
        $name    = trim($data['name'] ?? '');
        $email   = trim($data['email'] ?? '');
        $message = trim($data['message'] ?? '');

        // ----------------------
        //   VALIDATION
        // ----------------------
        if ($name === '' || $email === '' || $message === '') {
            $_SESSION['error'] = "❌ All fields are required.";
            header("Location: index.php?controller=contact&action=view");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = "❌ Please enter a valid email address.";
            header("Location: index.php?controller=contact&action=view");
            exit;
        }

        if (strlen($message) < 10) {
            $_SESSION['error'] = "❌ Message must be at least 10 characters.";
            header("Location: index.php?controller=contact&action=view");
            exit;
        }

        if (strlen($message) > 1000) {
            $_SESSION['error'] = "❌ Message too long (max 1000 characters).";
            header("Location: index.php?controller=contact&action=view");
            exit;
        }

        // ----------------------
        // SAVE TO DB USING PDO
        // ----------------------
        try {
            $stmt = $this->db->prepare("
                INSERT INTO contact_messages (name, email, message)
                VALUES (:name, :email, :message)
            ");
            $stmt->execute([
                ':name' => $name,
                ':email' => $email,
                ':message' => $message
            ]);

        } catch (Exception $e) {
            $_SESSION['error'] = "❌ Failed to save message.";
            header("Location: index.php?controller=contact&action=view");
            exit;
        }

        // ----------------------
        // SUCCESS RESPONSE
        // ----------------------
        $_SESSION['success'] = "🎉 Message sent successfully!";
        header("Location: index.php?controller=contact&action=view");
        exit;
    }

    // ---------------------------------------------------------
    //                     RENDER VIEW
    // ---------------------------------------------------------
    private function render($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../views/{$view}.php";
    }
}
?>
