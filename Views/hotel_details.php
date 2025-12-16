<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/Hotel.php';

$db = new Database();
$hotelModel = new Hotel($db->conn);

// Get hotel ID from query string (supports ?id= or ?hotel_id=)
$hotelId = isset($_GET['id']) ? (int)$_GET['id'] : (int)($_GET['hotel_id'] ?? 0);

$hotel = null;
if ($hotelId > 0) {
    $hotel = $hotelModel->getHotelById($hotelId);
}

// Basic normalized values / fallbacks
if ($hotel) {
    $hotelName   = $hotel['hotel_name'] ?? 'Hotel';
    $city        = $hotel['city'] ?? 'Unknown city';
    $imageUrl    = '/TripLink/Public/images/default-hotel.jpg';
    $rating      = isset($hotel['rating']) ? (float)$hotel['rating'] : 4.5;
    $price       = isset($hotel['price_per_night']) ? (float)$hotel['price_per_night'] : 0;
    $bedType     = $hotel['bed_type'] ?? 'Standard bed';
    $maxGuests   = isset($hotel['max_guests']) ? (int)$hotel['max_guests'] : 2;
    $amenities   = $hotel['amenities'] ?? 'Free Wi-Fi, Breakfast, City View';
    $checkIn     = $hotel['check_in'] ?? 'Flexible';
    $checkOut    = $hotel['check_out'] ?? 'Flexible';
    $bedCount    = isset($hotel['bed_count']) ? (int)$hotel['bed_count'] : 1;
} else {
    $hotelName = 'Hotel not found';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | <?= htmlspecialchars($hotelName) ?></title>
    <link rel="stylesheet" href="/TripLink/Public/css/hotel_details.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';?>

<?php if (!$hotel): ?>
    <main class="hotel-details-main not-found">
        <div class="hotel-details-card">
            <h1>Hotel not found</h1>
            <p>We couldn't find the hotel you're looking for. It may have been removed or the link is incorrect.</p>
            <a href="/TripLink/Views/hotels.php" class="back-link">← Back to Hotels</a>
        </div>
    </main>
<?php else: ?>

    <header class="hotel-hero">
        <div class="hotel-hero-overlay"></div>
        <div class="hotel-hero-content">
            <div class="hotel-hero-text">
                <h1><?= htmlspecialchars($hotelName) ?></h1>
                <p class="hotel-hero-location"><?= htmlspecialchars($city) ?></p>
                <div class="hotel-hero-rating">
                    <?php
                    $fullStars = floor($rating);
                    $hasHalfStar = ($rating - $fullStars) >= 0.5;
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
                    <span class="rating-number"><?= number_format($rating, 1) ?></span>
                </div>
            </div>
        </div>
    </header>

    <main class="hotel-details-main">
        <section class="hotel-details-layout">
            <div class="hotel-details-left">
                <div class="hotel-slideshow">
                    <div class="slideshow-container">
                        <div class="slide active">
                            <img src="/TripLink/Public/images/hotel-photo-1.jpg" 
                                 alt="<?= htmlspecialchars($hotelName) ?> Photo 1"
                                 onerror="this.src='https://via.placeholder.com/900x500/4A90E2/ffffff?text=Hotel+Photo+1';">
                        </div>
                        <div class="slide">
                            <img src="/TripLink/Public/images/hotel-photo-2.jpg" 
                                 alt="<?= htmlspecialchars($hotelName) ?> Photo 2"
                                 onerror="this.src='https://via.placeholder.com/900x500/50C878/ffffff?text=Hotel+Photo+2';">
                        </div>
                        <div class="slide">
                            <img src="/TripLink/Public/images/hotel-photo-3.jpg" 
                                 alt="<?= htmlspecialchars($hotelName) ?> Photo 3"
                                 onerror="this.src='https://via.placeholder.com/900x500/FF6B6B/ffffff?text=Hotel+Photo+3';">
                        </div>
                    </div>
                    
                    <!-- Navigation arrows -->
                    <button class="slideshow-arrow slideshow-prev" aria-label="Previous photo">‹</button>
                    <button class="slideshow-arrow slideshow-next" aria-label="Next photo">›</button>
                    
                    <!-- Dots indicator -->
                    <div class="slideshow-dots">
                        <span class="dot active" data-slide="0"></span>
                        <span class="dot" data-slide="1"></span>
                        <span class="dot" data-slide="2"></span>
                    </div>
                </div>

                <div class="hotel-info-section">
                    <h2>About this stay</h2>
                    <p class="hotel-description">
                        Enjoy a comfortable stay at <?= htmlspecialchars($hotelName) ?> in <?= htmlspecialchars($city) ?>.
                        This property offers a relaxing atmosphere, modern amenities, and easy access to local attractions,
                        making it a great choice for both leisure and business travelers.
                    </p>

                    <div class="hotel-highlights">
                        <div class="highlight-item">
                            <span class="highlight-icon">🛏️</span>
                            <div>
                                <p class="highlight-title"><?= htmlspecialchars($bedType) ?></p>
                                <p class="highlight-subtitle"><?= $bedCount > 1 ? $bedCount . ' beds' : '1 bed' ?></p>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <span class="highlight-icon">👥</span>
                            <div>
                                <p class="highlight-title">Max guests</p>
                                <p class="highlight-subtitle">Up to <?= $maxGuests ?> guests</p>
                            </div>
                        </div>
                        <div class="highlight-item">
                            <span class="highlight-icon">⏰</span>
                            <div>
                                <p class="highlight-title">Check-in / Check-out</p>
                                <p class="highlight-subtitle"><?= htmlspecialchars($checkIn) ?> – <?= htmlspecialchars($checkOut) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="hotel-amenities-section">
                        <h3>Amenities</h3>
                        <p class="amenities-text"><?= htmlspecialchars($amenities) ?></p>
                    </div>
                </div>
            </div>

            <aside class="hotel-details-sidebar">
                <div class="price-card">
                    <p class="price-label">Price per night</p>
                    <p class="price-value">EGP<?= number_format($price) ?></p>
                    <p class="price-note">Includes room only. Taxes and fees may apply.</p>

                    <a href="/TripLink/Views/payment.php?hotel_id=<?= $hotelId ?>" class="book-now-btn">
                        Book this stay
                    </a>

                    <p class="secure-text">✔ Secure checkout · No booking fees</p>
                </div>
            </aside>
        </section>
    </main>
<?php endif; ?>

<?php include __DIR__ . '/partials/footer.php'; ?>

<script>
// Hotel Slideshow Functionality
(function() {
    const slides = document.querySelectorAll('.hotel-slideshow .slide');
    const dots = document.querySelectorAll('.hotel-slideshow .dot');
    const prevBtn = document.querySelector('.hotel-slideshow .slideshow-prev');
    const nextBtn = document.querySelector('.hotel-slideshow .slideshow-next');
    let currentSlide = 0;
    const totalSlides = slides.length;

    function showSlide(index) {
        // Remove active class from all slides and dots
        slides.forEach(slide => slide.classList.remove('active'));
        dots.forEach(dot => dot.classList.remove('active'));

        // Add active class to current slide and dot
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

    // Event listeners
    if (nextBtn) {
        nextBtn.addEventListener('click', nextSlide);
    }
    if (prevBtn) {
        prevBtn.addEventListener('click', prevSlide);
    }

    // Dot navigation
    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentSlide = index;
            showSlide(currentSlide);
        });
    });

    // Keyboard navigation
    document.addEventListener('keydown', (e) => {
        if (e.key === 'ArrowLeft') prevSlide();
        if (e.key === 'ArrowRight') nextSlide();
    });
})();
</script>

</body>
</html>


