<?php
class Offer {
    private $conn;
    private $table = "offers";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function addOffer($data) {
        $sql = "INSERT INTO offers (offer_name, city, check_in, check_out, price_per_night)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);

        if (!$stmt) {
            die("Prepare failed: " . $this->conn->error);
        }

        $stmt->bind_param(
            "sssdds",
            $data['offer_name'],
            $data['city'],
            $data['check_in'],
            $data['check_out'],
            $data['price_per_night'],
            $data['rating']
        );
        return $stmt->execute();
    }

    public function getAllOffers() {
        $sql = "SELECT * FROM {$this->table}";
        $result = $this->conn->query($sql);

        return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
    }

    public function getOfferById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return null;

        $stmt->bind_param("i", $id);
        $stmt->execute();

        return $stmt->get_result()->fetch_assoc();
    }

    public function updateOffer($id, $data) {
        $sql = "UPDATE {$this->table}
                SET offer_name=?, city=?, check_in=?, check_out=?, price_per_night=?, rating=?
                WHERE id=?";

        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param(
            "sssddsi",
            $data['offer_name'],
            $data['city'],
            $data['check_in'],
            $data['check_out'],
            $data['price_per_night'],
            $data['rating'],
            $id
        );

        return $stmt->execute();
    }

    public function deleteOffer($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        if (!$stmt) return false;

        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}

?>