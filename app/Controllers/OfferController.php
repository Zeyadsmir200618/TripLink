<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

class OfferController {

    private $conn;

    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
    }

    // ---------------------------------------------------------
    //               SAVE OFFER (VALIDATION + INSERT)
    // ---------------------------------------------------------
    public function save($data)
    {
        // ----------------------------
        //        EXTRACT INPUT
        // ----------------------------
        $title       = trim($data['title'] ?? '');
        $description = trim($data['description'] ?? '');

        // ----------------------------
        //        VALIDATION
        // ----------------------------

        // Title required
        if ($title === '') {
            $_SESSION['error'] = "❌ Offer title is required.";
            header("Location: offers.php");
            exit;
        }

        // Title length
        if (strlen($title) < 3 || strlen($title) > 200) {
            $_SESSION['error'] = "❌ Title must be between 3 and 200 characters.";
            header("Location: offers.php");
            exit;
        }

        // Description optional but must be clean if exists
        if ($description !== '' && strlen($description) > 500) {
            $_SESSION['error'] = "❌ Description must not exceed 500 characters.";
            header("Location: offers.php");
            exit;
        }

        // Prevent harmful characters
        if (!preg_match("/^[a-zA-Z0-9\s\-\.,!?()]*$/", $title)) {
            $_SESSION['error'] = "❌ Title contains invalid characters.";
            header("Location: offers.php");
            exit;
        }

        // ----------------------------
        //         INSERT OFFER
        // ----------------------------
        try {
            $stmt = $this->conn->prepare(
                "INSERT INTO offers (title, description) VALUES (?, ?)"
            );

            $stmt->execute([$title, $description]);

            $_SESSION['success'] = "🎉 Offer claimed successfully!";
            header("Location: offers.php");
            exit;

        } catch (Exception $e) {

            $_SESSION['error'] = "❌ Failed to save offer: " . $e->getMessage();
            header("Location: offers.php");
            exit;
        }
    }
}
