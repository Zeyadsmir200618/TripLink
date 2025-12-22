<?php
// app/Models/Hotel.php

// ❌ REMOVED 'class Database' block
// We rely on 'config/database.php' to define the connection logic.

// ===========================
// HOTEL MODEL (The Database Worker)
// ===========================
class Hotel {
    
    // 1. PROPERTIES
    // $conn: The active link to the database.
    // $table: The name of the table in MySQL ("hotels").
    private PDO $conn;
    private string $table = "hotels";

    // 2. CONSTRUCTOR (Dependency Injection)
    // We expect the database connection ($db) to be passed in from the Controller.
    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    /* -------------------------------------------------------
     * CREATE: Add a new hotel
     * ------------------------------------------------------- */
    public function addHotel(array $data): bool {
        // Define the SQL.
        // We use placeholders (:hotel_name, etc.) for security.
        $sql = "INSERT INTO {$this->table} (hotel_name, city, check_in, check_out, price_per_night, stars)
                VALUES (:hotel_name, :city, :check_in, :check_out, :price_per_night, :stars)";
        
        $stmt = $this->conn->prepare($sql);
        
        // Execute the query by mapping PHP variables to the SQL placeholders.
        // We use the Null Coalescing Operator ('?? null') for dates to avoid errors if they are missing.
        return $stmt->execute([
            ':hotel_name'      => $data['hotel_name'],
            ':city'            => $data['city'],
            ':check_in'        => $data['check_in'] ?? null,
            ':check_out'       => $data['check_out'] ?? null,
            ':price_per_night' => $data['price_per_night'],
            ':stars'           => $data['stars']
        ]);
    }

    /* -------------------------------------------------------
     * READ: Get all hotels
     * ------------------------------------------------------- */
    public function getAll(): array {
        // Get all rows, sorted by newest first (id DESC).
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} ORDER BY id DESC");
        $stmt->execute();
        
        // Return an associative array (keys are column names).
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /* -------------------------------------------------------
     * READ: Get one hotel by ID
     * ------------------------------------------------------- */
    public function getById(int $id): ?array {
        // Find the specific hotel.
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        
        $hotel = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Return null if no hotel was found.
        return $hotel ?: null;
    }

    /* -------------------------------------------------------
     * UPDATE: Edit an existing hotel
     * ------------------------------------------------------- */
    public function update(int $id, array $data): bool {
        // Note: We usually don't update check_in/check_out dates in a simple edit form,
        // but you can add them here if needed.
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

    /* -------------------------------------------------------
     * DELETE: Remove a hotel
     * ------------------------------------------------------- */
    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}

// ===========================
// HOTEL SERVICE (Business Logic Layer)
// ===========================
// This acts as a middleman between the Controller and the Model.
// It keeps the Controller clean by handling any complex logic here (though currently, it just passes data through).
class HotelService {
    
    private Hotel $repository;

    public function __construct() {
        // 1. Get the single DB connection instance.
        $db = Database::getInstance()->getConnection();
        // 2. Create the Hotel Model with that connection.
        $this->repository = new Hotel($db);
    }

    // Pass-through methods
    public function addHotel(array $data): bool {
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