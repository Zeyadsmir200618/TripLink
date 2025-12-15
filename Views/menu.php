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

<?php include __DIR__ . '/partials/footer.php'; ?>

<script src="/TripLink/Public/js/menu.js"></script>
</body>
</html>
