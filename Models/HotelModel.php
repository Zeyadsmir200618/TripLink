<?php
require_once __DIR__ . '/../Config/connection.php';
class HotelModel {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    // Insert a new hotel
    public function insertHotel($data) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("INSERT INTO hotels (hotel_name, city, check_in, check_out, price_per_night, rating) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "ssssdi",
            $data['hotel_name'],
            $data['city'],
            $data['check_in'],
            $data['check_out'],
            $data['price_per_night'],
            $data['rating']
        );
        return $stmt->execute();
    }

    // Get all hotels
    public function getAllHotels() {
        $conn = $this->db->connect();
        $result = $conn->query("SELECT * FROM hotels");
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
