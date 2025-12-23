<?php
// app/Controllers/HotelController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../Models/Hotel.php';
require_once __DIR__ . '/../Models/Booking.php';

class HotelController {
    private Hotel $hotel;
    private Booking $booking;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->hotel = new Hotel($db);
        $this->booking = new Booking($db);
    }

    public function handleRequest(?array $postData = null): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $postData) {
            $this->addHotel($postData);
        } else {
            $this->viewForm();
        }
    }

    public function save(?array $postData = null): void {
        if ($postData) {
            $this->addHotel($postData);
        } else {
            $this->viewForm();
        }
    }

    public function addHotel(array $data): void {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['message'] = "You must be logged in to book a hotel.";
            header("Location: login.php");
            exit;
        }

        $required = ['hotel_name', 'city', 'check_in', 'check_out', 'price_per_night', 'rating'];
        
        foreach ($required as $field) {
            if (empty(trim($data[$field] ?? ''))) {
                $_SESSION['message'] = "All fields are required.";
                header("Location: index.php?controller=hotel");
                exit;
            }
        }

        if (strtotime($data['check_out']) <= strtotime($data['check_in'])) {
             $_SESSION['message'] = "Check-out date must be after Check-in.";
             header("Location: index.php?controller=hotel");
             exit;
        }

        if (!is_numeric($data['price_per_night']) || $data['price_per_night'] <= 0) {
            $_SESSION['message'] = "Price must be a positive number.";
            header("Location: index.php?controller=hotel");
            exit;
        }

        $hotelData = [
            'hotel_name'      => $data['hotel_name'],
            'city'            => $data['city'],
            'check_in'        => $data['check_in'],
            'check_out'       => $data['check_out'],
            'price_per_night' => $data['price_per_night'],
            'stars'           => $data['rating']
        ];

        $hotelId = $this->hotel->addHotel($hotelData);
        
        if ($hotelId) {
            $userId = $_SESSION['user_id'];
            $bookingResult = $this->booking->createBooking($userId, 'hotel', null, $hotelId);
            
            if ($bookingResult) {
                $_SESSION['popup_success'] = true;
                $_SESSION['message'] = 'Hotel booked successfully!';
            } else {
                $_SESSION['message'] = 'Hotel added, but booking creation failed.';
            }
        } else {
            $_SESSION['message'] = 'Failed to add hotel.';
        }

        header("Location: index.php?controller=hotel");
        exit;
    }

    private function viewForm(): void {
        $message = $_SESSION['message'] ?? '';
        unset($_SESSION['message']);
        
        $showPopup = false;
        if (isset($_SESSION['popup_success'])) {
            $showPopup = true;
            unset($_SESSION['popup_success']);
        }
        
        $viewPath = __DIR__ . '/../Views/hotel_form.php';

        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            die("Error: View file not found at: " . $viewPath);
        }
    }
    public function listHotels(): array { return $this->hotel->getAll(); }
    public function getHotel(int $id): ?array { return $this->hotel->getById($id); }
    public function deleteHotel(int $id): array { return $this->hotel->delete($id) ? ["status"=>"success"] : ["status"=>"error"]; }
}
?>