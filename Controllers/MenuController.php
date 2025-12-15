<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/Hotel.php';
require_once __DIR__ . '/../Models/Flight.php';
require_once __DIR__ . '/../Models/Offer.php';

class MenuController {
    private $conn;
    private $hotelModel;
    private $flightModel;
    private $offerModel;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->conn;
        $this->hotelModel = new Hotel($this->conn);
        $this->flightModel = new Flight($this->conn);
        $this->offerModel = new Offer($this->conn);
    }

    public function view() {
        $activeTab = $_GET['tab'] ?? 'hotels';
        
        $hotels = $this->getHotelsData();
        $flights = $this->getFlightsData();
        $offers = $this->getOffersData();
        
        // Include the view
        include __DIR__ . '/../Views/menu.php';
    }

    private function getHotelsData() {
        $hotelsFromDb = $this->hotelModel->getAllHotels();
        $hotels = [];
        
        foreach ($hotelsFromDb as $hotel) {
            $hotels[] = [
                'id' => $hotel['ID'],
                'hotel_name' => $hotel['hotel_name'],
                'city' => $hotel['city'],
                'image_url' => '/TripLink/Public/images/default-hotel.jpg',
                'bed_type' => 'King',
                'max_guests' => 2,
                'amenities' => 'WiFi, AC, Room Service',
                'price_per_night' => $hotel['price_per_night'],
                'rating' => $hotel['rating'] ?? 4.5,
                'check_in' => $hotel['check_in'],
                'check_out' => $hotel['check_out']
            ];
        }
        
        return $hotels;
    }

    private function getFlightsData() {
        return $this->flightModel->getAllFlights();
    }

    private function getOffersData() {
        return $this->offerModel->getAllOffers();
    }
}