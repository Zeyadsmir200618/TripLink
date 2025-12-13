<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Flight.php';

class FlightController {
    private $flight;

    public function __construct() {
        $db = new Database();
        $this->flight = new Flight($db->conn);
    }

    // Handles both GET (show form) and POST (add flight)
    public function handleRequest($postData = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postData) {
            $this->addFlight($postData);
        } else {
            $this->viewForm();
        }
    }

    public function addFlight($postData) {
        if ($this->flight->addFlight($postData)) {
            $_SESSION['message'] = '✅ Flight added successfully!';
        } else {
            $_SESSION['message'] = '❌ Failed to add flight.';
        }
        header('Location: index.php?controller=flight&action=handleRequest');
        exit;
    }

    private function viewForm() {
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        include __DIR__ . '/../views/flight_form.php';
    }
}
