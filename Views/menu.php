<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Find your next stay</title>
    <link rel="stylesheet" href="/TripLink/Public/css/menu.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';;?>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="tab-buttons">
            <button class="tab-btn active" data-tab="hotels">Hotels</button>
            <button class="tab-btn" data-tab="flights">Flights</button>
            <button class="tab-btn" data-tab="packages">Packages</button>
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
                 alt="<?= htmlspecialchars($hotel['room_name']) ?>">
        </div>

        <div class="room-info">
            <h3><?= htmlspecialchars($hotel['room_name']) ?></h3>
            <p class="hotel-name"><?= htmlspecialchars($hotel['hotel_name']) ?></p>

            <div class="room-details">
                <span>🛏️ <?= htmlspecialchars($hotel['bed_type']) ?></span>
                <span>👥 Up to <?= (int)$hotel['max_guests'] ?> guests</span>
            </div>

            <div class="room-amenities">
                <span><?= htmlspecialchars($hotel['amenities']) ?></span>
            </div>

            <div class="room-footer">
                <span class="price">
                    From $<?= number_format($hotel['price_per_night']) ?>/night
                </span>

                <a href="book.php?room_id=<?= $hotel['id'] ?>" class="book-btn">
                    Book Now
                </a>
            </div>
        </div>
    </div>
<?php endforeach; ?>

            
            
<?php include __DIR__ . '/partials/footer.php';?>

<script src="/TripLink/Public/js/menu.js"> </script>
</body>
</html>
