<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/Hotel.php';

$db = new Database();
$hotelModel = new Hotel($db->conn);
$hotelsFromDb = $hotelModel->getAllHotels();
$hotels = [];

foreach ($hotelsFromDb as $hotel) {
    $hotels[] = [
        'id'             => $hotel['ID'] ?? $hotel['id'],
        'hotel_name'     => $hotel['hotel_name'],
        'city'           => $hotel['city'],
        'image_url'      => '/TripLink/Public/images/default-hotel.jpg',
        'bed_type'       => 'King',
        'max_guests'     => 2,
        'amenities'      => 'WiFi, AC, Room Service',
        'price_per_night'=> $hotel['price_per_night'],
        'rating'         => $hotel['rating'] ?? 4.5,
        'check_in'       => $hotel['check_in'],
        'check_out'      => $hotel['check_out']
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Hotels</title>
    <link rel="stylesheet" href="/TripLink/Public/css/hotels.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';?>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="tab-buttons">
            <a href="/TripLink/Views/hotels.php" class="tab-btn active">Hotels</a>
            <a href="/TripLink/Views/flights.php" class="tab-btn">Flights</a>
            <a href="/TripLink/Views/offers.php" class="tab-btn">Packages</a>
        </div>
        
        <div class="search-hero" id="hotels-search">
            <div class="location-search">
                <span class="search-label">Search Hotels</span>
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Insert location" class="location-input">
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <aside class="sidebar">
        <div class="filter-section">
            <h3>Search</h3>
            <div class="search-box">
                <span class="search-icon">🔍</span>
                <input type="text" placeholder="Search rooms...">
            </div>
        </div>
        
        <div class="filter-section">
            <h3>Amenities</h3>
            <label class="checkbox-label">
                <input type="checkbox" name="amenity" value="wifi">
                <span>Free WiFi</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="amenity" value="ac">
                <span>Air Conditioning</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="amenity" value="room-service">
                <span>Room Service</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="amenity" value="minibar">
                <span>Mini Bar</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="amenity" value="ocean-view">
                <span>Ocean View</span>
            </label>
            <label class="checkbox-label">
                <input type="checkbox" name="amenity" value="balcony">
                <span>Balcony</span>
            </label>
        </div>
        
        <div class="filter-section">
            <h3>Bed Type</h3>
            <label class="radio-label">
                <input type="radio" name="bed-type" value="king">
                <span>King</span>
            </label>
            <label class="radio-label">
                <input type="radio" name="bed-type" value="queen">
                <span>Queen</span>
            </label>
            <label class="radio-label">
                <input type="radio" name="bed-type" value="twin">
                <span>Twin</span>
            </label>
            <label class="radio-label">
                <input type="radio" name="bed-type" value="single">
                <span>Single</span>
            </label>
        </div>
        
        <div class="filter-section">
            <h3>Bed Count</h3>
            <label class="radio-label">
                <input type="radio" name="bed-count" value="1">
                <span>1 Bed</span>
            </label>
            <label class="radio-label">
                <input type="radio" name="bed-count" value="2">
                <span>2 Beds</span>
            </label>
            <label class="radio-label">
                <input type="radio" name="bed-count" value="3">
                <span>3 Beds</span>
            </label>
        </div>
    </aside>
   
    <main class="rooms-section">
        <h2>Available Rooms</h2>
        <div class="rooms-grid">
            <?php foreach ($hotels as $hotel): ?>
            <div class="room-card">
                <div class="room-image">
                    <img src="<?= htmlspecialchars($hotel['image_url']) ?>" 
                         alt="<?= htmlspecialchars($hotel['hotel_name']) ?>">
                </div>

                <div class="room-info">
                    <h3><?= htmlspecialchars($hotel['hotel_name']) ?></h3>
                    <p class="hotel-name"><?= htmlspecialchars($hotel['city']) ?></p>

                    <div class="room-details">
                        <span>🛏️ <?= htmlspecialchars($hotel['bed_type']) ?></span>
                        <span>👥 Up to <?= (int)$hotel['max_guests'] ?> guests</span>
                    </div>

                    <div class="room-amenities">
                        <span><?= htmlspecialchars($hotel['amenities']) ?></span>
                    </div>

                    <div class="room-footer">
                        <span class="price">
                            From EGP<?= number_format($hotel['price_per_night']) ?>/night
                        </span>

                        <a href="book.php?hotel_id=<?= $hotel['id'] ?>" class="book-btn">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script src="/TripLink/Public/js/menu.js"></script>
</body>
</html>


