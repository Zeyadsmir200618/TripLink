<?php

class Hotel {
    private PDO $conn;
    private string $table = "hotels";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function addHotel(array $data): bool|int {
        $sql = "INSERT INTO {$this->table} (hotel_name, city, check_in, check_out, price_per_night, stars)
                VALUES (:hotel_name, :city, :check_in, :check_out, :price_per_night, :stars)";
        
        $stmt = $this->conn->prepare($sql);
        
        $result = $stmt->execute([
            ':hotel_name'      => $data['hotel_name'],
            ':city'            => $data['city'],
            ':check_in'        => $data['check_in'] ?? null,
            ':check_out'       => $data['check_out'] ?? null,
            ':price_per_night' => $data['price_per_night'],
            ':stars'           => $data['stars']
        ]);
        
        if ($result) {
            return (int)$this->conn->lastInsertId();
        }
        
        return false;
    }

    public function getAll(): array {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
        return $hotel ?: null;
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} 
                SET hotel_name = :hotel_name, city = :city, price_per_night = :price_per_night, stars = :stars
                WHERE id = :id";
        
        $stmt = $this->conn->prepare($sql);
        
        return $stmt->execute([
            ':hotel_name'      => $data['hotel_name'],
            ':city'            => $data['city'],
            ':price_per_night' => $data['price_per_night'],
            ':stars'           => $data['stars'],
            ':id'              => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

class HotelService {
    private Hotel $repository;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->repository = new Hotel($db);
    }
    public function addHotel(array $data): bool|int {
        return $this->repository->addHotel($data);
    }

    public function getAll(): array {
        return $this->repository->getAll();
    }

    public function getById(int $id): ?array {
        return $this->repository->getById($id);
    }

    public function update(int $id, array $data): bool {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool {
        return $this->repository->delete($id);
    }
}
?>