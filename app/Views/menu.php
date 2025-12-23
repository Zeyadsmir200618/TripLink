<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require_once __DIR__ . '/../config/database.php';
$pdo = Database::getInstance()->getConnection();

// Get search parameters
$searchLocation = trim($_GET['location'] ?? '');
$searchCheckIn = $_GET['check_in'] ?? '';
$searchCheckOut = $_GET['check_out'] ?? '';

// Build query based on search criteria
$hotels = [];
$params = [];

try {
    $sql = "SELECT * FROM hotels WHERE 1=1";
    
    // Filter by location/city
    if (!empty($searchLocation)) {
        $sql .= " AND (city LIKE :location OR hotel_name LIKE :location)";
        $params[':location'] = '%' . $searchLocation . '%';
    }
    
    // Filter by check-in date (hotel check_in should be <= search check_in)
    if (!empty($searchCheckIn)) {
        $sql .= " AND check_in <= :check_in";
        $params[':check_in'] = $searchCheckIn;
    }
    
    // Filter by check-out date (hotel check_out should be >= search check_out)
    if (!empty($searchCheckOut)) {
        $sql .= " AND check_out >= :check_out";
        $params[':check_out'] = $searchCheckOut;
    }
    
    $sql .= " ORDER BY id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    // Silently handle errors - just show empty results
    $hotels = [];
}

// Function kept for future use, though currently the menu is hardcoded in HTML below
function renderMenu(array $items): void {
    echo '<ul>';
    foreach ($items as $item) {
        echo '<li>';
        echo '<a href="'.htmlspecialchars($item['link']).'">'.htmlspecialchars($item['title']).'</a>';
        if (isset($item['children'])) {
            renderMenu($item['children']);
        }
        echo '</li>';
    }
    echo '</ul>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Find your next stay</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TripLink/public/css/base.css">
    <link rel="stylesheet" href="/TripLink/public/css/menu.css">
</head>
<body>

    <div class="header-container">
        <?php include __DIR__ . '/partials/top-nav.php'; ?>

        <div class="main-nav">
            <a href="#" class="nav-item active">
                <span class="icon">🏨</span> Stays
            </a>
            <a href="flight_form.php" class="nav-item">
                <span class="icon">🛫</span> Flights
            </a>
            <a href="hotel_form.php" class="nav-item">
                <span class="icon">🏨</span> Hotels
            </a>
            <a href="offer.php" class="nav-item">
                <span class="icon">🎟️</span> Offers
            </a>
        </div>

        <div class="headline-section">
            <h1>Find your next stay</h1>
            <h2>Search deals on hotels, homes, and much more...</h2>
        </div>
    </div>

    <div class="search-bar-wrapper">
        <form class="search-bar" action="menu.php" method="GET">
            <div class="search-field">
                <input type="text" name="location" placeholder="Where are you going? 📍" value="<?= htmlspecialchars($searchLocation) ?>">
            </div>
            <div class="search-field">
                <input type="date" name="check_in" placeholder="Check-in 📅" value="<?= htmlspecialchars($searchCheckIn) ?>">
            </div>
            <div class="search-field">
                <input type="date" name="check_out" placeholder="Check-out 📅" value="<?= htmlspecialchars($searchCheckOut) ?>">
            </div>
            <div class="search-field">
                <select name="guests">
                    <option value="2-0-1">2 adults · 0 children · 1 room</option>
                    <option value="1-0-1">1 adult · 0 children · 1 room</option>
                    <option value="2-1-1">2 adults · 1 child · 1 room</option>
                </select>
            </div>
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

    <!-- Hotels Display Section -->
    <div class="hotels-section">
        <div class="hotels-container">
            <h2 class="hotels-title">Available Hotels</h2>
            
            <?php if (empty($hotels)): ?>
                <div class="no-results">
                    <div class="no-results-icon">🔍</div>
                    <h3>No results found</h3>
                    <p>Try adjusting your search criteria or check back later for new listings.</p>
                </div>
            <?php else: ?>
                <div class="hotels-grid">
                    <?php foreach ($hotels as $hotel): ?>
                        <div class="hotel-card">
                            <div class="hotel-image">
                                <span class="hotel-icon">🏨</span>
                            </div>
                            <div class="hotel-content">
                                <div class="hotel-header">
                                    <h3 class="hotel-name"><?= htmlspecialchars($hotel['hotel_name']) ?></h3>
                                    <div class="hotel-stars">
                                        <?php for ($i = 0; $i < ($hotel['stars'] ?? 0); $i++): ?>
                                            <span class="star">⭐</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="hotel-location">
                                    <span class="location-icon">📍</span>
                                    <span><?= htmlspecialchars($hotel['city']) ?></span>
                                </div>
                                <div class="hotel-dates">
                                    <div class="date-item">
                                        <span class="date-label">Check-in:</span>
                                        <span class="date-value"><?= $hotel['check_in'] ? date('M d, Y', strtotime($hotel['check_in'])) : 'N/A' ?></span>
                                    </div>
                                    <div class="date-item">
                                        <span class="date-label">Check-out:</span>
                                        <span class="date-value"><?= $hotel['check_out'] ? date('M d, Y', strtotime($hotel['check_out'])) : 'N/A' ?></span>
                                    </div>
                                </div>
                                <div class="hotel-footer">
                                    <div class="hotel-price">
                                        <span class="price-amount">$<?= number_format($hotel['price_per_night'], 2) ?></span>
                                        <span class="price-label">per night</span>
                                    </div>
                                    <button class="book-btn">Book Now</button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="content-section">
        <h2>Why book with TripLink?</h2>
        <div class="info-cards-container">
            <div class="info-card">
                <span class="icon">💳</span>
                <h3>Flexibility</h3>
                <p>Book now, pay at the property. Free cancellation on most rooms.</p>
            </div>

            <div class="info-card">
                <span class="icon">🌟</span>
                <h3>Verified Reviews</h3>
                <p>300M+ reviews from real travelers to help you make the right choice.</p>
            </div>

            <div class="info-card">
                <span class="icon">🌍</span>
                <h3>Global Coverage</h3>
                <p>2+ million properties worldwide. From cozy cottages to luxury resorts.</p>
            </div>

            <div class="info-card">
                <span class="icon">🎧</span>
                <h3>24/7 Support</h3>
                <p>Our customer service team is always here to help you, day or night.</p>
            </div>
        </div>
    </div>

    <footer>
        &copy; 2025 TripLink. All rights reserved. <br>
        <small>Your journey starts here.</small>
    </footer>

</body>
</html>