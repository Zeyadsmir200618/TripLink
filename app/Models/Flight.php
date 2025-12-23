<?php

class Flight {
    private PDO $conn;
    private string $table = "flights";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function addFlight(array $data): bool|int {
        $sql = "INSERT INTO {$this->table} 
                (flight_number, departure_city, arrival_city, departure_date, return_date, price, airline)
                VALUES (:flight_number, :departure_city, :arrival_city, :departure_date, :return_date, :price, :airline)";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([
            ':flight_number' => $data['flight_number'],
            ':departure_city' => $data['departure_city'],
            ':arrival_city' => $data['arrival_city'],
            ':departure_date' => $data['departure_date'],
            ':return_date' => $data['return_date'] ?? null,
            ':price' => $data['price'],
            ':airline' => $data['airline']
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
        $flight = $stmt->fetch(PDO::FETCH_ASSOC);
        return $flight ?: null;
    }

    public function update(int $id, array $data): bool {
        $sql = "UPDATE {$this->table} 
                SET flight_number = :flight_number, departure_city = :departure_city, arrival_city = :arrival_city,
                    departure_date = :departure_date, return_date = :return_date, price = :price, airline = :airline
                WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':flight_number' => $data['flight_number'],
            ':departure_city' => $data['departure_city'],
            ':arrival_city' => $data['arrival_city'],
            ':departure_date' => $data['departure_date'],
            ':return_date' => $data['return_date'] ?? null,
            ':price' => $data['price'],
            ':airline' => $data['airline'],
            ':id' => $id
        ]);
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

class FlightService {
    private Flight $repository;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->repository = new Flight($db);
    }

    public function addFlight(array $data): bool|int {
        return $this->repository->addFlight($data);
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