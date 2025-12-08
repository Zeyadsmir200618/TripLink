<?php
// Correct paths using __DIR__
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Hotel.php';

class HotelController {
    private $hotel;

    public function __construct() {
        $db = new Database();
        $this->hotel = new Hotel($db->conn);
    }

    public function handleRequest($postData = null) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postData) {
            $this->addHotel($postData);
        } else {
            $this->viewForm();
        }
    }

    public function addHotel($postData) {
        if ($this->hotel->addHotel($postData)) {
            $_SESSION['message'] = '✅ Hotel added successfully!';
        } else {
            $_SESSION['message'] = '❌ Failed to add hotel.';
        }
        header('Location: hotel_form.php'); // redirect after POST
        exit;
    }

    private function viewForm() {
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        $this->render('hotel_form', ['message' => $message]);
    }

    private function render($view, $data = []) {
        extract($data);
        include __DIR__ . "/../views/{$view}.php";
    }
}
?>

