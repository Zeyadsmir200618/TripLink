<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/User.php';
require_once __DIR__ . '/../Models/Offer.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /TripLink/Views/login.php');
    exit;
}

$db = new Database();
$userModel = new User($db->conn);
$offerModel = new Offer($db->conn);

// Get user info
$userId = $_SESSION['user_id'];
$user = null;
try {
    $stmt = $db->conn->prepare("SELECT id, username, email FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }
} catch (\mysqli_sql_exception $e) {
    $stmt = $db->conn->prepare("SELECT id, email FROM users WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
    }
}

// Get user's display name
$userName = 'Guest';
if ($user) {
    $userName = $user['username'] ?? explode('@', $user['email'] ?? 'guest@example.com')[0] ?? 'Guest';
    $userName = ucfirst($userName);
}

$allOffers = $offerModel->getAllOffers();
$latestOffers = array_slice(array_reverse($allOffers), 0, 3);

// Helper function to calculate days between dates
function calculateDays($checkIn, $checkOut) {
    if (empty($checkIn) || empty($checkOut)) return 7;
    try {
        $start = new DateTime($checkIn);
        $end = new DateTime($checkOut);
        $diff = $start->diff($end);
        return max(1, $diff->days);
    } catch (Exception $e) {
        return 7;
    }
}

// Get upcoming trip (placeholder - you'll need to create a bookings table)
$upcomingTrip = null;
try {
    // Try to get from bookings table if it exists
    $stmt = $db->conn->prepare("SELECT * FROM bookings WHERE user_id = ? AND departure_date >= CURDATE() ORDER BY departure_date ASC LIMIT 1");
    if ($stmt) {
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $upcomingTrip = $result->fetch_assoc();
    }
} catch (\mysqli_sql_exception $e) {
   
}

if (!$upcomingTrip && !empty($latestOffers)) {
    $firstOffer = $latestOffers[0];
    $departDate = $firstOffer['check_in'] ?? null;
    if ($departDate && strtotime($departDate) >= time()) {
        $upcomingTrip = [
            'destination' => $firstOffer['city'] ?? 'Unknown',
            'description' => $firstOffer['offer_name'] ?? 'Luxury villa with ocean view',
            'departure_date' => $departDate,
            'return_date' => $firstOffer['check_out'] ?? null,
            'duration' => calculateDays($departDate, $firstOffer['check_out'] ?? $departDate),
            'total_price' => ($firstOffer['price_per_night'] ?? 0) * calculateDays($departDate, $firstOffer['check_out'] ?? $departDate),
            'image_url' => '/TripLink/Public/images/package-placeholder.jpg'
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Dashboard</title>
    <link rel="stylesheet" href="/TripLink/Public/css/customer_dashboard.css">
</head>
<body>
   <?php include __DIR__ . '/partials/navbar.php';?>

    <main class="dashboard-main">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h1 class="welcome-title">Welcome back, <?= htmlspecialchars($userName) ?>!</h1>
            <p class="welcome-subtitle">Ready for your next adventure?</p>
        </div>

        <!-- Upcoming Trip Card -->
        <div class="upcoming-trip-card">
            <div class="trip-card-header">
                <h2 class="trip-card-title">
                    <span class="status-dot"></span>
                    UPCOMING TRIP
                </h2>
                <?php if ($upcomingTrip): ?>
                    <div class="countdown-badge" id="countdown">
                        <span class="countdown-icon">🕐</span>
                        <span id="countdown-text">Loading...</span>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ($upcomingTrip): ?>
                <?php
                $departDate = $upcomingTrip['departure_date'] ?? $upcomingTrip['check_in'] ?? '';
                $returnDate = $upcomingTrip['return_date'] ?? $upcomingTrip['check_out'] ?? '';
                $duration = $upcomingTrip['duration'] ?? calculateDays($departDate, $returnDate);
                $destination = $upcomingTrip['destination'] ?? $upcomingTrip['city'] ?? 'Unknown';
                $description = $upcomingTrip['description'] ?? $upcomingTrip['offer_name'] ?? 'Luxury villa with ocean view';
                $totalPrice = $upcomingTrip['total_price'] ?? 0;
                $imageUrl = $upcomingTrip['image_url'] ?? '/TripLink/Public/images/package-placeholder.jpg';
                ?>
                <div class="trip-image-wrapper">
                    <img src="<?= htmlspecialchars($imageUrl) ?>" 
                         alt="<?= htmlspecialchars($destination) ?>"
                         onerror="this.src='https://images.unsplash.com/photo-1570077188670-e3a8d69ac5ff?w=1200&h=600&fit=crop';">
                    <div class="trip-image-overlay">
                        <div class="trip-location">
                            <span class="location-icon">📍</span>
                            <span><?= htmlspecialchars($destination) ?></span>
                        </div>
                        <p class="trip-description"><?= htmlspecialchars($description) ?></p>
                    </div>
                </div>
                <div class="trip-details">
                    <div class="trip-detail-item">
                        <span class="detail-label">Departure</span>
                        <span class="detail-value"><?= date('M j, Y', strtotime($departDate)) ?></span>
                    </div>
                    <div class="trip-detail-item">
                        <span class="detail-label">Return</span>
                        <span class="detail-value"><?= date('M j, Y', strtotime($returnDate)) ?></span>
                    </div>
                    <div class="trip-detail-item">
                        <span class="detail-label">Duration</span>
                        <span class="detail-value"><?= $duration ?> nights</span>
                    </div>
                    <div class="trip-detail-item">
                        <span class="detail-label">Total Price</span>
                        <span class="detail-value">$<?= number_format($totalPrice) ?></span>
                    </div>
                </div>
                <script>
                    // Countdown timer
                    (function() {
                        const departDate = new Date('<?= $departDate ?>').getTime();
                        function updateCountdown() {
                            const now = new Date().getTime();
                            const distance = departDate - now;
                            
                            if (distance < 0) {
                                document.getElementById('countdown-text').textContent = 'Trip started';
                                return;
                            }
                            
                            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            
                            document.getElementById('countdown-text').textContent = 
                                `${days.toString().padStart(2, '0')}d : ${hours.toString().padStart(2, '0')}h : ${minutes.toString().padStart(2, '0')}m : ${seconds.toString().padStart(2, '0')}s`;
                        }
                        updateCountdown();
                        setInterval(updateCountdown, 1000);
                    })();
                </script>
            <?php else: ?>
                <div class="no-trip-message">
                    <p>No upcoming trips</p>
                    <a href="/TripLink/Views/hotels.php" class="book-now-link">Book your first trip →</a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="/TripLink/Views/hotels.php" class="action-btn book-trip-btn">
                <div class="btn-icon">✈</div>
                <div class="btn-content">
                    <div class="btn-title">Book a Trip</div>
                    <div class="btn-subtitle">Plan your next adventure</div>
                </div>
            </a>
            <a href="/TripLink/Views/offers.php" class="action-btn view-bundles-btn">
                <div class="btn-icon">📦</div>
                <div class="btn-content">
                    <div class="btn-title">View Bundles</div>
                    <div class="btn-subtitle">Explore package deals</div>
                </div>
            </a>
        </div>

        <!-- Featured Deals Carousel -->
        <div class="featured-deals-card">
            <div class="featured-deals-header">
                <div>
                    <h2 class="featured-title">Featured Deals</h2>
                    <p class="featured-subtitle">Exclusive offers just for you</p>
                </div>
                <div class="carousel-nav-buttons">
                    <button class="carousel-nav-btn carousel-prev" aria-label="Previous">‹</button>
                    <button class="carousel-nav-btn carousel-next" aria-label="Next">›</button>
                </div>
            </div>

            <div class="featured-carousel">
                <?php if (empty($latestOffers)): ?>
                    <div class="featured-slide active">
                        <div class="featured-image-placeholder">
                            <p>No featured deals available</p>
                        </div>
                    </div>
                <?php else: ?>
                    <?php foreach ($latestOffers as $index => $offer): ?>
                        <?php
                        $offerName = $offer['offer_name'] ?? 'Special Package';
                        $city = $offer['city'] ?? 'Unknown';
                        $checkIn = $offer['check_in'] ?? '';
                        $checkOut = $offer['check_out'] ?? '';
                        $days = calculateDays($checkIn, $checkOut);
                        $price = isset($offer['price_per_night']) ? (float)$offer['price_per_night'] : 0;
                        $totalPrice = $price * $days;
                        ?>
                        <div class="featured-slide <?= $index === 0 ? 'active' : '' ?>">
                            <div class="featured-image-wrapper">
                                <img src="/TripLink/Public/images/package-placeholder.jpg" 
                                     alt="<?= htmlspecialchars($city) ?>"
                                     onerror="this.src='https://images.unsplash.com/photo-1571896349842-33c89424de2d?w=800&h=400&fit=crop';">
                                <div class="featured-overlay">
                                    <div class="featured-info">
                                        <h3 class="featured-destination"><?= htmlspecialchars($city) ?></h3>
                                        <p class="featured-description"><?= $days ?> nights luxury resort package</p>
                                        <p class="featured-price">$<?= number_format($totalPrice) ?></p>
                                    </div>
                                    <a href="/TripLink/Views/offers.php" class="view-deal-btn">View Deal</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="carousel-dots">
                <?php for ($i = 0; $i < min(3, count($latestOffers) ?: 1); $i++): ?>
                    <span class="dot <?= $i === 0 ? 'active' : '' ?>" data-slide="<?= $i ?>"></span>
                <?php endfor; ?>
            </div>
        </div>

        <!-- Promo Code Section -->
        <div class="promo-code-card">
            <div class="promo-header">
                <span class="promo-icon">🏷️</span>
                <div>
                    <h2 class="promo-title">Have a Promo Code?</h2>
                    <p class="promo-subtitle">Enter your code to unlock special discounts</p>
                </div>
            </div>
            <form class="promo-form" method="POST" action="">
                <input type="text" 
                       name="promo_code" 
                       placeholder="Enter promo code" 
                       class="promo-input">
                <button type="submit" class="promo-apply-btn">Apply</button>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/partials/footer.php'; ?>

    <script>
        // Featured Deals Carousel
        (function() {
            const slides = document.querySelectorAll('.featured-slide');
            const dots = document.querySelectorAll('.carousel-dots .dot');
            const prevBtn = document.querySelector('.carousel-prev');
            const nextBtn = document.querySelector('.carousel-next');
            let currentSlide = 0;
            const totalSlides = slides.length;

            function showSlide(index) {
                slides.forEach(slide => slide.classList.remove('active'));
                dots.forEach(dot => dot.classList.remove('active'));
                
                if (slides[index]) {
                    slides[index].classList.add('active');
                }
                if (dots[index]) {
                    dots[index].classList.add('active');
                }
            }

            function nextSlide() {
                currentSlide = (currentSlide + 1) % totalSlides;
                showSlide(currentSlide);
            }

            function prevSlide() {
                currentSlide = (currentSlide - 1 + totalSlides) % totalSlides;
                showSlide(currentSlide);
            }

            if (nextBtn) nextBtn.addEventListener('click', nextSlide);
            if (prevBtn) prevBtn.addEventListener('click', prevSlide);

            dots.forEach((dot, index) => {
                dot.addEventListener('click', () => {
                    currentSlide = index;
                    showSlide(currentSlide);
                });
            });
        })();
    </script>
</body>
</html>

