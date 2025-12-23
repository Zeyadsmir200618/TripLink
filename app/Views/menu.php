<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Database connection
require_once __DIR__ . '/../config/database.php';

// Get search parameters
$searchLocation = trim($_GET['location'] ?? '');
$searchCheckIn = $_GET['check_in'] ?? '';
$searchCheckOut = $_GET['check_out'] ?? '';

// Get database connection
$db = Database::getInstance();
$pdo = $db->getConnection();

// Build query with filters
$sql = "SELECT * FROM hotels WHERE 1=1";
$params = [];

// Location filter: search in city field
if (!empty($searchLocation)) {
    $sql .= " AND city LIKE :location";
    $params[':location'] = '%' . $searchLocation . '%';
}

// Date filtering: Hotel must be available for the entire requested stay period
// Hotel's check_in must be <= user's check_in (hotel available from user's check-in or earlier)
// Hotel's check_out must be >= user's check_out (hotel available until user's check-out or later)
if (!empty($searchCheckIn) && !empty($searchCheckOut)) {
    // Validate that check-out is after check-in
    if ($searchCheckOut > $searchCheckIn) {
        // Filter out invalid placeholder dates (1111-02-11 and 2222-02-22)
        $sql .= " AND (check_in IS NULL OR (check_in != '1111-02-11' AND check_in <= :check_in))";
        $sql .= " AND (check_out IS NULL OR (check_out != '2222-02-22' AND check_out >= :check_out))";
        $params[':check_in'] = $searchCheckIn;
        $params[':check_out'] = $searchCheckOut;
    }
} elseif (!empty($searchCheckIn)) {
    // Only check-in provided: hotel must be available from check-in date or earlier
    $sql .= " AND (check_in IS NULL OR (check_in != '1111-02-11' AND check_in <= :check_in))";
    $params[':check_in'] = $searchCheckIn;
} elseif (!empty($searchCheckOut)) {
    // Only check-out provided: hotel must be available until check-out date or later
    $sql .= " AND (check_out IS NULL OR (check_out != '2222-02-22' AND check_out >= :check_out))";
    $params[':check_out'] = $searchCheckOut;
} else {
    // No date filters: exclude hotels with invalid placeholder dates
    $sql .= " AND (check_in IS NULL OR check_in != '1111-02-11')";
    $sql .= " AND (check_out IS NULL OR check_out != '2222-02-22')";
}

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$hotels = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                <input type="text" name="location" placeholder="Where are you going? 📍" value="<?php echo htmlspecialchars($searchLocation); ?>">
            </div>
            <div class="search-field">
                <input type="date" name="check_in" placeholder="Check-in 📅" value="<?php echo htmlspecialchars($searchCheckIn); ?>">
            </div>
            <div class="search-field">
                <input type="date" name="check_out" placeholder="Check-out 📅" value="<?php echo htmlspecialchars($searchCheckOut); ?>">
            </div>
            <div class="search-field">
                <select name="guests">
                    <option>2 adults · 0 children · 1 room</option>
                    <option>1 adult · 0 children · 1 room</option>
                    <option>2 adults · 1 child · 1 room</option>
                </select>
            </div>
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

    <!-- Hotels Display Section -->
    <div class="hotels-section">
        <div class="container">
            <h2 class="section-title">Available Hotels</h2>
            
            <?php if (count($hotels) > 0): ?>
                <div class="hotels-grid">
                    <?php foreach ($hotels as $hotel): ?>
                        <div class="hotel-card">
                            <div class="hotel-header">
                                <h3 class="hotel-name"><?php echo htmlspecialchars($hotel['hotel_name']); ?></h3>
                                <div class="hotel-stars">
                                    <?php 
                                    $stars = $hotel['stars'] ?? 0;
                                    for ($i = 0; $i < 5; $i++): 
                                        echo $i < $stars ? '⭐' : '☆';
                                    endfor; 
                                    ?>
                                </div>
                            </div>
                            <div class="hotel-info">
                                <div class="hotel-location">
                                    <span class="icon">📍</span>
                                    <span><?php echo htmlspecialchars($hotel['city']); ?></span>
                                </div>
                                <?php if (!empty($hotel['check_in']) && $hotel['check_in'] != '1111-02-11'): ?>
                                    <div class="hotel-dates">
                                        <span class="icon">📅</span>
                                        <span>
                                            <?php 
                                            $checkIn = date('M d, Y', strtotime($hotel['check_in']));
                                            $checkOut = !empty($hotel['check_out']) && $hotel['check_out'] != '2222-02-22' 
                                                ? date('M d, Y', strtotime($hotel['check_out'])) 
                                                : 'N/A';
                                            echo $checkIn . ' - ' . $checkOut;
                                            ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="hotel-footer">
                                <div class="hotel-price">
                                    <span class="price-amount">$<?php echo number_format($hotel['price_per_night'], 2); ?></span>
                                    <span class="price-label">per night</span>
                                </div>
                                <button class="book-btn">Book Now</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div class="no-results">
                    <div class="no-results-icon">🔍</div>
                    <h3>No results found</h3>
                    <p>Try adjusting your search criteria or <a href="menu.php">view all hotels</a></p>
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