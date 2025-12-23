<?php
// app/Controllers/FlightController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/Flight.php';
require_once __DIR__ . '/../Models/Booking.php';

class FlightController {
    private Flight $flight;
    private Booking $booking;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->flight = new Flight($db);
        $this->booking = new Booking($db);
    }

    public function handleRequest(?array $postData = null): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postData) {
            $this->addFlight($postData);
        } else {
            $this->viewForm();
        }
    }

    public function addFlight(array $data): void {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "You must be logged in to book a flight.";
            header("Location: login.php");
            exit;
        }

        $required = ['flight_number', 'departure_city', 'arrival_city', 'departure_date', 'price', 'airline'];
        
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                $_SESSION['message'] = "All fields are required.";
                header("Location: index.php?controller=flight");
                exit;
            }
        }

        if (!preg_match("/^[a-zA-Z\s]+$/", $data['departure_city']) || 
            !preg_match("/^[a-zA-Z\s]+$/", $data['arrival_city'])) {
            $_SESSION['message'] = "Invalid city name (Letters only).";
            header("Location: index.php?controller=flight");
            exit;
        }

        $departureDate = $data['departure_date'];
        if (strpos($departureDate, 'T') !== false) {
            $departureDate = explode('T', $departureDate)[0];
        }
        $flightDate = strtotime($departureDate);
        if (!$flightDate || $flightDate < strtotime(date("Y-m-d"))) {
            $_SESSION['message'] = "Departure date must be today or in the future.";
            header("Location: index.php?controller=flight");
            exit;
        }

        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            $_SESSION['message'] = "Price must be a positive number.";
            header("Location: index.php?controller=flight");
            exit;
        }

        $flightData = [
            'flight_number'  => $data['flight_number'],
            'departure_city' => $data['departure_city'],
            'arrival_city'   => $data['arrival_city'],
            'departure_date' => $departureDate,
            'return_date'    => !empty($data['return_date']) ? $data['return_date'] : null,
            'price'          => $data['price'],
            'airline'        => $data['airline']
        ];

        $flightId = $this->flight->addFlight($flightData);
        
        if ($flightId) {
            $userId = $_SESSION['user_id'];
            $bookingResult = $this->booking->createBooking($userId, 'flight', $flightId, null);
            
            if ($bookingResult) {
                $_SESSION['message'] = 'Flight booked successfully!';
            } else {
                $_SESSION['message'] = 'Flight added, but booking creation failed.';
            }
        } else {
            $_SESSION['message'] = 'Failed to add flight.';
        }

        header('Location: index.php?controller=flight');
        exit;
    }

    private function viewForm(): void {
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        
        $viewPath = __DIR__ . '/../Views/flight_form.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            die("Error: View file not found at: " . $viewPath);
        }
    }

    public function listFlights(): array {
        return $this->flight->getAll();
    }

    public function getFlight(int $id): ?array {
        return $this->flight->getById($id);
    }

    public function updateFlight(int $id, array $data): array {
        $required = ['departure_city', 'arrival_city', 'departure_date', 'price'];
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                return ["status"=>"error", "message"=>"All fields are required."];
            }
        }

        $updated = $this->flight->update($id, $data);
        return $updated ? ["status"=>"success"] : ["status"=>"error", "message"=>"Update failed."];
    }

    public function deleteFlight(int $id): array {
        $deleted = $this->flight->delete($id);
        return $deleted ? ["status"=>"success"] : ["status"=>"error", "message"=>"Delete failed."];
    }
}
?>