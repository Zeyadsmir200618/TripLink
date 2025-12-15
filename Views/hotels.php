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
        'bed_type'       => $hotel['bed_type'] ?? '',
        'max_guests'     => $hotel['max_guests'] ?? 0,
        'amenities'      => $hotel['amenities'] ?? 'WiFi, AC, Room Service',
        'price_per_night'=> $hotel['price_per_night'] ?? 0,
        'rating'         => $hotel['rating'] ?? 4.5,
        'check_in'       => $hotel['check_in'] ?? '',
        'check_out'      => $hotel['check_out'] ?? '',
        'bed_count'      => $hotel['bed_count'] ?? 1,
    ];
}

$filters = [
    'location'   => $_GET['location']   ?? '',
    'search'     => $_GET['search']     ?? '',
    'amenities'  => isset($_GET['amenities']) ? (array)$_GET['amenities'] : [],
    'bed_type'   => $_GET['bed_type']   ?? '',
];

// Normalize string for comparisons
function hotels_normalize(string $value): string {
    return mb_strtolower(trim($value));
}


$filteredHotels = array_filter($hotels, function(array $hotel) use ($filters) {
    $city       = hotels_normalize($hotel['city'] ?? '');
    $name       = hotels_normalize($hotel['hotel_name'] ?? '');
    $amenities  = hotels_normalize($hotel['amenities'] ?? '');
    $bedType    = hotels_normalize($hotel['bed_type'] ?? '');

    // Location (hero search)
    if ($filters['location'] !== '') {
        $needle = hotels_normalize($filters['location']);
        if (mb_strpos($city, $needle) === false && mb_strpos($name, $needle) === false) {
            return false;
        }
    }


    // Amenities – require all selected amenities to be present in the amenities string
    if (!empty($filters['amenities'])) {
        foreach ($filters['amenities'] as $amenity) {
            $needle = hotels_normalize($amenity);
            if ($needle !== '' && mb_strpos($amenities, $needle) === false) {
                return false;
            }
        }
    }

    if ($filters['bed_type'] !== '') {
        if ($bedType === '' || $bedType !== hotels_normalize($filters['bed_type'])) {
            return false;
        }
    }

    return true;
});

// check if any filters are actually applied
$hasHotelFilters =
    trim($filters['location']) !== '' ||
    trim($filters['search'])   !== '' ||
    !empty($filters['amenities'])     ||
    $filters['bed_type'] !== '';

// Decide what to show
$hotelsToShow = $hasHotelFilters ? $filteredHotels : $hotels;
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

<form class="hotel-filters" method="GET" action="">
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
                <input
                    type="text"
                    name="location"
                    placeholder="Insert location"
                    class="location-input"
                    value="<?= htmlspecialchars($filters['location']) ?>"
                >
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <aside class="sidebar">
        
        <div class="filter-section">
            <h3>Amenities</h3>
            <?php
                $amenityOptions = [
                    'wifi'        => 'Free WiFi',
                    'ac'          => 'Air Conditioning',
                    'room-service'=> 'Room Service',
                    'minibar'     => 'Mini Bar',
                    'ocean-view'  => 'Ocean View',
                    'balcony'     => 'Balcony',
                ];
            ?>
            <?php foreach ($amenityOptions as $value => $label): ?>
                <label class="checkbox-label">
                    <input
                        type="checkbox"
                        name="amenities[]"
                        value="<?= htmlspecialchars($value) ?>"
                        <?= in_array($value, $filters['amenities'], true) ? 'checked' : '' ?>
                    >
                    <span><?= htmlspecialchars($label) ?></span>
                </label>
            <?php endforeach; ?>
        </div>
        
        <div class="filter-section">
            <h3>Bed Type</h3>
            <?php
                $bedTypes = ['king' => 'King', 'queen' => 'Queen', 'twin' => 'Twin', 'single' => 'Single'];
            ?>
            <?php foreach ($bedTypes as $value => $label): ?>
                <label class="radio-label">
                    <input
                        type="radio"
                        name="bed_type"
                        value="<?= htmlspecialchars($value) ?>"
                        <?= $filters['bed_type'] === $value ? 'checked' : '' ?>
                    >
                    <span><?= htmlspecialchars($label) ?></span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="filter-section">
            <button type="submit" class="flight-search-button">Apply Filters</button>
        </div>
    </aside>
   </form>

    <main class="rooms-section">
        <h2>Available Rooms</h2>

        <?php if (empty($hotelsToShow)): ?>
            <div class="no-results">No results found.</div>
        <?php else: ?>
        <div class="rooms-grid">
            <?php foreach ($hotelsToShow as $hotel): ?>
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
        <?php endif; ?>
    </main>
</div>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script src="/TripLink/Public/js/menu.js"></script>
</body>
</html>


