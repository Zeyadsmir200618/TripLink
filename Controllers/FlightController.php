<?php
// app/Controllers/FlightController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/Flight.php';

class FlightController {
    private Flight $flight;

    public function __construct() {
        // Use Singleton Database pattern
        $db = Database::getInstance()->getConnection();
        $this->flight = new Flight($db);
    }

    /**
     * Handles GET (show form) and POST (add flight)
     * This makes your controller very flexible.
     */
    public function handleRequest(?array $postData = null): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postData) {
            $this->addFlight($postData);
        } else {
            $this->viewForm();
        }
    }

    /**
     * Add flight with full validation
     */
    public function addFlight(array $data): void {
        // 1. Validate Required Fields
        $required = ['flight_number', 'departure_city', 'arrival_city', 'departure_date', 'price', 'airline'];
        
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                $_SESSION['message'] = "❌ All fields are required.";
                // Redirect back to the form
                header("Location: index.php?controller=flight");
                exit;
            }
        }

        // 2. Validate Cities (Letters only)
        if (!preg_match("/^[a-zA-Z\s]+$/", $data['departure_city']) || 
            !preg_match("/^[a-zA-Z\s]+$/", $data['arrival_city'])) {
            $_SESSION['message'] = "❌ Invalid city name (Letters only).";
            header("Location: index.php?controller=flight");
            exit;
        }

        // 3. Validate Date
        $flightDate = strtotime($data['departure_date']);
        if (!$flightDate || $flightDate < strtotime(date("Y-m-d"))) {
            $_SESSION['message'] = "❌ Departure date must be today or in the future.";
            header("Location: index.php?controller=flight");
            exit;
        }

        // 4. Validate Price
        if (!is_numeric($data['price']) || $data['price'] <= 0) {
            $_SESSION['message'] = "❌ Price must be a positive number.";
            header("Location: index.php?controller=flight");
            exit;
        }

        // 5. Prepare Data for Model
        // (CRITICAL FIX: Ensure return_date is NULL if empty, otherwise SQL fails)
        $flightData = [
            'flight_number'  => $data['flight_number'],
            'departure_city' => $data['departure_city'],
            'arrival_city'   => $data['arrival_city'],
            'departure_date' => $data['departure_date'],
            'return_date'    => !empty($data['return_date']) ? $data['return_date'] : null,
            'price'          => $data['price'],
            'airline'        => $data['airline']
        ];

        // 6. Send to Model
        if ($this->flight->addFlight($flightData)) {
            $_SESSION['message'] = '✅ Flight added successfully!';
        } else {
            $_SESSION['message'] = '❌ Failed to add flight.';
        }

        // Redirect to refresh page and show message
        header('Location: index.php?controller=flight');
        exit;
    }

    /**
     * Show flight form
     */
    private function viewForm(): void {
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        
        $viewPath = __DIR__ . '/../Views/flight_form.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            die("❌ Error: View file not found at: " . $viewPath);
        }
    }

    /* ==========================
       CRUD OPERATIONS FOR FLIGHTS
    ========================== */

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
