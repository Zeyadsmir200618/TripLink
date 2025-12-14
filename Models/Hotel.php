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

    public function getAllHotels() {
        $sql = "SELECT * FROM {$this->table}";
        $result = $this->conn->query($sql);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getHotelById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateHotel($id, $data) {
        $sql = "UPDATE {$this->table}
                SET hotel_name=?, city=?, check_in=?, check_out=?, price_per_night=?, rating=?
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "sssddsi",
            $data['hotel_name'],
            $data['city'],
            $data['check_in'],
            $data['check_out'],
            $data['price_per_night'],
            $data['rating'],
            $id
        );

        return $stmt->execute();
    }

    public function deleteHotel($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

?>
