<?php
class Flight {
    private $conn;
    private $table = "flights";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addFlight($data) {
        $sql = "INSERT INTO flights (flight_number, departure_city, arrival_city, departure_date, return_date, price, airline)
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "ssssdds",
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
