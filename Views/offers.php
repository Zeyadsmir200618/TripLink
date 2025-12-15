<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/Offer.php';

$db = new Database();
$offerModel = new Offer($db->conn);
$offers = $offerModel->getAllOffers();
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
                <input type="text" placeholder="Destination or offer" class="location-input">
            </div>
        </div>
    </div>
</div>

<div class="main-content">
    <main class="rooms-section" style="flex: 1;">
        <h2>Available Packages</h2>
        <div class="rooms-grid">
            <?php foreach ($offers as $offer): ?>
            <div class="room-card">
                <div class="room-info">
                    <h3><?= htmlspecialchars($offer['offer_name'] ?? $offer['city']) ?></h3>
                    <p class="hotel-name"><?= htmlspecialchars($offer['city']) ?></p>

                    <div class="room-details">
                        <span>📅 <?= htmlspecialchars($offer['check_in']) ?> → <?= htmlspecialchars($offer['check_out']) ?></span>
                    </div>

                    <div class="room-footer">
                        <span class="price">From EGP<?= number_format($offer['price_per_night']) ?>/night</span>
                        <button class="book-btn">View Offer</button>
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


