<?php
require_once __DIR__ . '/../Models/HotelModel.php';

class HotelController {
    private $model;
    public function __construct() {
        $this->model = new HotelModel();
    }

    public function add() {
        if (isset($_POST['submit'])) {
            $data = [
                'city' => $_POST['city'],
                'hotel_name' => $_POST['hotel_name'],
                'check_in' => $_POST['check_in'],
                'check_out' => $_POST['check_out'],
                'price_per_night' => $_POST['price_per_night'],
                'rating' => $_POST['rating']
            ];
            if ($this->model->insertHotel($data)) {
                $success = "Hotel added successfully!";
            } else {
                $error = "Error adding hotel!";
            }
        }
        include __DIR__ . '/../Views/Hotel.php';
    }
}
?>
