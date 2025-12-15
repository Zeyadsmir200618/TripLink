<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/Offer.php';

$db = new Database();
$offerModel = new Offer($db->conn);
$offersFromDb = $offerModel->getAllOffers();
$offers = [];

// Helper function to calculate days between dates
function calculateDays($checkIn, $checkOut) {
    if (empty($checkIn) || empty($checkOut)) return 7; // default
    try {
        $start = new DateTime($checkIn);
        $end = new DateTime($checkOut);
        $diff = $start->diff($end);
        return max(1, $diff->days);
    } catch (Exception $e) {
        return 7;
    }
}

// Helper function to get offer value safely
function offer_value(array $row, array $keys, $default = '') {
    foreach ($keys as $key) {
        if (isset($row[$key]) && $row[$key] !== '' && $row[$key] !== null) {
            return $row[$key];
        }
    }
    return $default;
}

// Normalize offers data and add computed fields
foreach ($offersFromDb as $offer) {
    $checkIn = offer_value($offer, ['check_in', 'check_in_date']);
    $checkOut = offer_value($offer, ['check_out', 'check_out_date']);
    $days = calculateDays($checkIn, $checkOut);
    $price = (float) offer_value($offer, ['price_per_night', 'price'], 0);
    $rating = (float) offer_value($offer, ['rating'], 4.5);
    
    // Calculate discount (random 20-30% for demo, or use discount_percentage if available)
    $discountPercent = (int) offer_value($offer, ['discount_percentage', 'discount'], rand(20, 30));
    $originalPrice = $price > 0 ? round($price / (1 - $discountPercent / 100)) : 0;
    
    // Generate tags based on offer name or city (or use tags field if available)
    $tags = [];
    $offerName = strtolower(offer_value($offer, ['offer_name', 'name'], ''));
    $city = strtolower(offer_value($offer, ['city', 'destination'], ''));
    
    if (strpos($offerName, 'honeymoon') !== false || strpos($city, 'paris') !== false || strpos($city, 'maldives') !== false) {
        $tags[] = 'Honeymoon Special';
    } elseif (strpos($offerName, 'luxury') !== false || strpos($city, 'maldives') !== false) {
        $tags[] = 'Luxury Package';
    } elseif (strpos($offerName, 'family') !== false) {
        $tags[] = 'Family Friendly';
    } else {
        $tags[] = 'Best Seller';
    }
    
    // Add second tag
    if (strpos($city, 'maldives') !== false) {
        $tags[] = 'Water Villa';
    } elseif (strpos($city, 'paris') !== false) {
        $tags[] = 'Free Cancellation';
    } elseif (strpos($city, 'tokyo') !== false) {
        $tags[] = 'Cultural Experience';
    } else {
        $tags[] = 'Popular';
    }
    
    // Get amenities or use defaults
    $amenities = offer_value($offer, ['amenities', 'inclusions'], 'Wi-Fi, Breakfast, City View');
    
    $offers[] = [
        'id' => offer_value($offer, ['ID', 'id'], 0),
        'offer_name' => offer_value($offer, ['offer_name', 'name'], 'Special Package'),
        'hotel_name' => offer_value($offer, ['hotel_name', 'hotel'], 'Grand Hotel'),
        'city' => offer_value($offer, ['city', 'destination'], 'Unknown'),
        'check_in' => $checkIn,
        'check_out' => $checkOut,
        'days' => $days,
        'price' => $price,
        'original_price' => $originalPrice,
        'discount_percent' => $discountPercent,
        'rating' => $rating,
        'amenities' => $amenities,
        'tags' => $tags,
        'max_guests' => (int) offer_value($offer, ['max_guests', 'guests'], 2),
        'image_url' => '/TripLink/Public/images/package-placeholder.jpg',
    ];
}

// Filters from query string
$filters = [
    'location' => $_GET['location'] ?? '',
];

// Normalize string for comparisons
function offers_normalize(string $value): string {
    return mb_strtolower(trim($value));
}

// Filter offers
$filteredOffers = array_filter($offers, function(array $offer) use ($filters) {
    $city = offers_normalize($offer['city'] ?? '');
    $name = offers_normalize($offer['offer_name'] ?? '');
    $hotel = offers_normalize($offer['hotel_name'] ?? '');
    
    // Location search - match in city, offer name, or hotel name
    if ($filters['location'] !== '') {
        $needle = offers_normalize($filters['location']);
        if (mb_strpos($city, $needle) === false && 
            mb_strpos($name, $needle) === false && 
            mb_strpos($hotel, $needle) === false) {
            return false;
        }
    }
    
    return true;
});

// Check if any filters are actually applied
$hasFilters = trim($filters['location']) !== '';

// Decide what to show
$offersToShow = $hasFilters ? $filteredOffers : $offers;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Packages & Offers</title>
    <link rel="stylesheet" href="/TripLink/Public/css/offers.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';?>

<form method="GET" action="" class="offers-filters">
<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="tab-buttons">
            <a href="/TripLink/Views/hotels.php" class="tab-btn">Hotels</a>
            <a href="/TripLink/Views/flights.php" class="tab-btn">Flights</a>
            <a href="/TripLink/Views/offers.php" class="tab-btn active">Packages</a>
        </div>
        
        <div class="search-hero" id="offers-search">
            <div class="location-search">
                <span class="search-label">Search Packages</span>
                <span class="search-icon">🔍</span>
                <input 
                    type="text" 
                    name="location"
                    placeholder="Destination or offer" 
                    class="location-input"
                    value="<?= htmlspecialchars($filters['location']) ?>"
                >
            </div>
        </div>
    </div>
</div>
</form>

<div class="main-content">
    <main class="rooms-section" style="flex: 1;">
        <h2>Available Packages</h2>

        <?php if (empty($offersToShow)): ?>
            <div class="no-results">No results found.</div>
        <?php else: ?>
        <div class="packages-grid">
            <?php foreach ($offersToShow as $offer): ?>
            <div class="package-card">
                <div class="package-image-wrapper">
                    <img 
                        src="<?= htmlspecialchars($offer['image_url']) ?>" 
                        alt="<?= htmlspecialchars($offer['city']) ?>"
                        class="package-image"
                        onerror="this.src='https://via.placeholder.com/400x250/4A90E2/ffffff?text=Package+Image'"
                    >
                    
                    <!-- Tags overlay (top left) -->
                    <div class="package-tags">
                        <?php foreach (array_slice($offer['tags'], 0, 2) as $tag): ?>
                            <span class="package-tag"><?= htmlspecialchars($tag) ?></span>
                        <?php endforeach; ?>
                    </div>
                    
                    <!-- Discount badge (top right) -->
                    <div class="discount-badge">
                        <?= $offer['discount_percent'] ?>% OFF
                    </div>
                </div>

                <div class="package-content">
                    <div class="package-header">
                        <h3 class="package-destination"><?= htmlspecialchars($offer['city']) ?></h3>
                        <p class="package-hotel"><?= htmlspecialchars($offer['hotel_name']) ?></p>
                    </div>

                    <div class="package-details">
                        <span class="detail-item">
                            <span class="detail-icon">📅</span>
                            <?= $offer['days'] ?> Days Flight + Hotel
                        </span>
                        <span class="detail-item">
                            <span class="detail-icon">👥</span>
                            Up to <?= $offer['max_guests'] ?> guests
                        </span>
                        <span class="detail-item">
                            <span class="detail-icon">✨</span>
                            <?= htmlspecialchars($offer['amenities']) ?>
                        </span>
                    </div>

                    <div class="package-rating">
                        <?php 
                        $fullStars = floor($offer['rating']);
                        $hasHalfStar = ($offer['rating'] - $fullStars) >= 0.5;
                        for ($i = 0; $i < $fullStars; $i++): ?>
                            <span class="star filled">★</span>
                        <?php endfor; ?>
                        <?php if ($hasHalfStar): ?>
                            <span class="star half">★</span>
                        <?php endif; ?>
                        <?php 
                        $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                        for ($i = 0; $i < $emptyStars; $i++): ?>
                            <span class="star empty">★</span>
                        <?php endfor; ?>
                    </div>

                    <div class="package-footer">
                        <div class="package-pricing">
                            <span class="original-price">From $<?= number_format($offer['original_price']) ?></span>
                            <span class="discounted-price">From $<?= number_format($offer['price']) ?>/package</span>
                        </div>
                        <button class="book-btn">Book Now</button>
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
<script>
// Auto-submit form on location input change
document.querySelector('.location-input')?.addEventListener('change', function() {
    this.closest('form').submit();
});
</script>
</body>
</html>
