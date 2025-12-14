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

    public function getAllFlights() {
        $sql = "SELECT * FROM " . $this->table;
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getFlightById($id) {
        $sql = "SELECT * FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    public function editFlight($id, $data) {
        $sql = "UPDATE " . $this->table . " 
                SET flight_number = ?, departure_city = ?, arrival_city = ?, departure_date = ?, return_date = ?, price = ?, airline = ?
                WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "ssssddsi",
            $data['flight_number'],
            $data['departure_city'],
            $data['arrival_city'],
            $data['departure_date'],
            $data['return_date'],
            $data['price'],
            $data['airline'],
            $id
        );

        return $stmt->execute();
    }

    public function removeFlight($id) {
        $sql = "DELETE FROM " . $this->table . " WHERE id = ?";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>
