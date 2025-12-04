<?php
require_once __DIR__ . '/../config/DB.php';

class FlightModel {
    private $db;

    public function __construct() {
        $this->db = new DB();
    }

    // Function to get all flights
    public function getAllFlights() {
        $conn = $this->db->connect();
        $result = $conn->query("SELECT * FROM flights");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Function to insert a new flight
    public function insertFlight($data) {
        $conn = $this->db->connect();
        $stmt = $conn->prepare("
            INSERT INTO flights (flight_number, departure_city, arrival_city, departure_date, return_date, price, airline)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param(
            "ssssdis",
            $data['flight_number'],
            $data['departure_city'],
            $data['arrival_city'],
            $data['departure_date'],
            $data['return_date'],
            $data['price'],
            $data['airline']
        );
        return $stmt->execute();
    }
}
?>

