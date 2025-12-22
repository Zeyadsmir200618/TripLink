<?php
// app/Controllers/HotelController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/Hotel.php'; // Ensure Capital 'M' if your folder is Models

class HotelController {
    private Hotel $hotel;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->hotel = new Hotel($db);
    }

    public function handleRequest(?array $postData = null): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postData) {
            $this->addHotel($postData);
        } else {
            $this->viewForm();
        }
    }

    public function addHotel(array $data): void {
        // 1. Validate Required Fields
        // Note: The form sends 'rating', so we check for that.
        $required = ['hotel_name', 'city', 'check_in', 'check_out', 'price_per_night', 'rating'];
        
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                $_SESSION['message'] = "❌ All fields are required.";
                header("Location: index.php?controller=hotel");
                exit;
            }
        }

        // 2. Validate Logic (Dates & Price)
        if (strtotime($data['check_out']) <= strtotime($data['check_in'])) {
             $_SESSION['message'] = "❌ Check-out date must be after Check-in.";
             header("Location: index.php?controller=hotel");
             exit;
        }

        if (!is_numeric($data['price_per_night']) || $data['price_per_night'] <= 0) {
            $_SESSION['message'] = "❌ Price must be a positive number.";
            header("Location: index.php?controller=hotel");
            exit;
        }

        // 3. Prepare Data for Model
        // ✅ FIX: Map 'rating' (from form) to 'stars' (for Database)
        $hotelData = [
            'hotel_name'      => $data['hotel_name'],
            'city'            => $data['city'],
            'check_in'        => $data['check_in'],
            'check_out'       => $data['check_out'],
            'price_per_night' => $data['price_per_night'],
            'stars'           => $data['rating'] // Mapping here
        ];

        // 4. Save & Trigger Popup
        if ($this->hotel->addHotel($hotelData)) {
            // ✅ THIS TRIGGERS THE POPUP
            $_SESSION['popup_success'] = true;
            $_SESSION['message'] = '✅ Hotel added successfully!';
        } else {
            $_SESSION['message'] = '❌ Failed to add hotel.';
        }

        // Redirect back to show the message/popup
        header("Location: index.php?controller=hotel");
        exit;
    }

    private function viewForm(): void {
        // Prepare message variables for the view
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        
        // Check for popup flag
        $showPopup = false;
        if (isset($_SESSION['popup_success'])) {
            $showPopup = true;
            unset($_SESSION['popup_success']);
        }
        
        // ✅ FIX: Correct path to View
        $viewPath = __DIR__ . '/../Views/hotel_form.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            die("❌ Error: View file not found at: " . $viewPath);
        }
    }
    
    // Additional CRUD Methods (Optional, kept for compatibility)
    public function listHotels(): array { return $this->hotel->getAll(); }
    public function getHotel(int $id): ?array { return $this->hotel->getById($id); }
    public function deleteHotel(int $id): array { return $this->hotel->delete($id) ? ["status"=>"success"] : ["status"=>"error"]; }
}
?>