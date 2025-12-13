<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Find your next stay</title>
   <link rel="stylesheet" href="/TripLink/Public/css/menu.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';?>
    <div class="header-container">
        <div class="top-nav">
            <span>EGP 🇪🇬</span>
            <a href="contact.php">Contact us</a>
            <a href="signup.php" class="btn-register">Register</a>
            <a href="login.php" class="btn-sign-in">Sign in</a>
            <a href="aboutus.php" class="btn-aboutus">About us</a>
        </div>

        <div class="main-nav">
            <a href="#" class="nav-item active">
                <span class="icon">🏨</span> Stays
            </a>
            <a href="flight_form.php" class="nav-item">
                <span class="icon">🛫</span> Flights booking
            </a>
            <a href="hotel_form.php" class="nav-item">
                <span class="icon">🚗</span> Hotel booking
            </a>
            <a href="offer.php" class="nav-item">
                <span class="icon">🎟️</span> offers
            </a>
            
        </div>

        <div class="headline-section">
            <h1>Find your next stay</h1>
            <h2>Search deals on hotels, Flights, and much more...</h2>
        </div>
        
    </div>

    <div class="search-bar-wrapper">
        <form class="search-bar" action="#" method="GET">
            <div class="search-field">
                <input type="text" placeholder="Where are you going?">
            </div>
            <div class="search-field">
                <input type="date" placeholder="Check-in date">
            </div>
            <div class="search-field">
                <input type="date" placeholder="Check-out date">
            </div>
            <div class="search-field">
                <select>
                    <option>2 adults - 0 children - 1 room</option>
                    <option>Change details...</option>
                </select>
            </div>
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

    <div class="content-section">
        <h2>Why TripLink?</h2>
        <div class="info-cards-container">
            <div class="info-card">
                <span class="icon">✅</span>
                <h3>Book now, pay at the property</h3>
                <p>FREE cancellation on most rooms.</p>
            </div>

            <div class="info-card">
                <span class="icon">👍</span>
                <h3>300M+ reviews from fellow travelers</h3>
                <p>Get trusted information from guests who've actually stayed.</p>
            </div>

            <div class="info-card">
                <span class="icon">🗺️</span>
                <h3>2+ million properties worldwide</h3>
                <p>Hotels, guest houses, apartments; find your perfect spot.</p>
            </div>

            <div class="info-card">
                <span class="icon">🧑‍💻</span>
                <h3>Trusted 24/7 customer service</h3>
                <p>We're always here to help you with your booking.</p>
            </div>
        </div>
    </div>

   <?php include __DIR__ . '/partials/footer.php';?>
</body>
</html>
