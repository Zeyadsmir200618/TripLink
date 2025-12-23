<?php
// app/Models/Booking.php

class Booking {
    private PDO $conn;
    private string $table = "bookings";

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function createBooking(int $userId, string $bookingType, ?int $flightId = null, ?int $hotelId = null): bool|int {
        if (!in_array($bookingType, ['flight', 'hotel'])) {
            return false;
        }

        if ($bookingType === 'flight' && empty($flightId)) {
            return false;
        }
        if ($bookingType === 'hotel' && empty($hotelId)) {
            return false;
        }

        $sql = "INSERT INTO {$this->table} (user_id, flight_id, hotel_id, booking_type)
                VALUES (:user_id, :flight_id, :hotel_id, :booking_type)";
        
        $stmt = $this->conn->prepare($sql);
        
        $result = $stmt->execute([
            ':user_id' => $userId,
            ':flight_id' => $bookingType === 'flight' ? $flightId : null,
            ':hotel_id' => $bookingType === 'hotel' ? $hotelId : null,
            ':booking_type' => $bookingType
        ]);

        if ($result) {
            return (int)$this->conn->lastInsertId();
        }
        
        return false;
    }

    public function getBookingsByUserId(int $userId): array {
        $sql = "SELECT b.*, 
                f.flight_number, f.departure_city, f.arrival_city, f.departure_date, f.return_date, f.price as flight_price, f.airline,
                h.hotel_name, h.city as hotel_city, h.price_per_night, h.check_in, h.check_out
                FROM {$this->table} b
                LEFT JOIN flights f ON b.flight_id = f.id
                LEFT JOIN hotels h ON b.hotel_id = h.id
                WHERE b.user_id = :user_id
                ORDER BY b.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $userId]);
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $booking = $stmt->fetch(PDO::FETCH_ASSOC);
        return $booking ?: null;
    }

    public function delete(int $id): bool {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        return $stmt->execute([':id' => $id]);
    }
}
?>

