<?php
class Hotel {
    private $conn;
    private $table = "hotels";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addHotel($data) {
        $sql = "INSERT INTO hotels (hotel_name, city, check_in, check_out, price_per_night, rating)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "sssdds",
            $data['hotel_name'],
            $data['city'],
            $data['check_in'],
            $data['check_out'],
            $data['price_per_night'],
            $data['rating']
        );

        return $stmt->execute();
    }
}
?>
