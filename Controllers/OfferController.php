<?php

class OfferController {

    private $conn;

    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
    }

    public function save($data)
    {
        $title = $data['title'] ?? '';
        $description = $data['description'] ?? '';

        if (empty($title)) {
            echo "Title is required";
            exit;
        }

        $stmt = $this->conn->prepare("INSERT INTO offers (title, description) VALUES (?, ?)");
        $stmt->execute([$title, $description]);

        $_SESSION['success'] = "Offer claimed successfully!";
        header("Location: offers.php");
        exit;
    }
}
