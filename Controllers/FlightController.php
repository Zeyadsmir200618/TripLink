<?php
require_once __DIR__ . '/../Models/FlightModel.php';

class FlightController {
    private $model;

    public function __construct() {
        $this->model = new FlightModel();
    }

    public function add() {
        $success = null;
        $error = null;

        if (isset($_POST['submit'])) {
            $data = [
                'flight_number' => $_POST['flight_number'],
                'departure_city' => $_POST['departure_city'],
                'arrival_city' => $_POST['arrival_city'],
                'departure_date' => $_POST['departure_date'],
                'return_date' => $_POST['return_date'],
                'price' => $_POST['price'],
                'airline' => $_POST['airline']
            ];

            if ($this->model->insertFlight($data)) {
                $success = "Flight added successfully!";
            } else {
                $error = "Error adding flight!";
            }
        }

        // Load the view
        include __DIR__ . '/../Views/addFlight.php';
    }
}
?>
