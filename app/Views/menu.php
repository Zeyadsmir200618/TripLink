<?php
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

    <style>
        /* Base Reset */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --booking-blue: #003580;
            --booking-blue-light: #004cb8;
            --booking-orange: #febb02;
            --booking-orange-hover: #e3a802;
            --light-bg: #f4f7f9;
            --text-dark: #1a1a1a;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            color: var(--text-dark);
            min-height: 100vh;
        }

        /* --- Header Section --- */
        .header-container {
            /* Premium Gradient Background */
            background: linear-gradient(135deg, #003580 0%, #014194 100%);
            padding-bottom: 80px; /* Extra space for floating search bar */
            color: white;
        }

        /* Top Nav */
        .top-nav {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 15px 50px;
            font-size: 0.9rem;
        }

        .top-nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .top-nav a:hover { opacity: 0.8; }

        /* Action Buttons */
        .top-nav .btn-action {
            padding: 8px 18px;
            border-radius: 4px;
            font-weight: 600;
            transition: all 0.2s ease;
        }

        .top-nav .btn-register {
            background-color: white;
            color: var(--booking-blue);
        }
        .top-nav .btn-register:hover { background-color: #f0f0f0; }

        .top-nav .btn-sign-in {
            background-color: rgba(255,255,255,0.1);
            color: white;
            border: 1px solid white;
        }
        .top-nav .btn-sign-in:hover { background-color: rgba(255,255,255,0.2); }

        /* Main Navigation Tabs */
        .main-nav {
            display: flex;
            padding: 10px 50px;
            gap: 15px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            border-radius: 30px; /* Pill shape */
            border: 1px solid transparent;
            transition: all 0.2s;
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-item.active {
            background-color: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255,255,255,0.3);
        }

        .nav-item .icon {
            font-size: 1.2rem;
            margin-right: 10px;
        }

        /* Headline */
        .headline-section {
            padding: 60px 20px 40px;
            text-align: center;
        }

        .headline-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .headline-section h2 {
            font-size: 1.5rem;
            font-weight: 300;
            opacity: 0.9;
        }

        /* --- Search Bar (The Hero Element) --- */
        .search-bar-wrapper {
            display: flex;
            justify-content: center;
            padding: 0 20px;
            margin-top: -30px; /* Pull up to overlap header */
            position: relative;
            z-index: 10;
        }

        .search-bar {
            display: flex;
            background: var(--booking-orange);
            padding: 4px; /* Creates the yellow border effect */
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15); /* Deep shadow */
            max-width: 1100px;
            width: 100%;
            gap: 4px; /* Spacing between inputs */
        }

        .search-field {
            flex-grow: 1;
            background: white;
            position: relative;
            display: flex;
            align-items: center;
            border-radius: 4px; /* Slight rounding for inner fields */
        }
        
        .search-field input, .search-field select {
            width: 100%;
            padding: 16px 20px;
            border: none;
            outline: none;
            font-size: 1rem;
            color: #333;
            background: transparent;
            border-radius: 4px;
        }

        /* Search Button */
        .search-btn {
            background-color: var(--booking-blue);
            color: white;
            padding: 0 40px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: background 0.2s;
        }

        .search-btn:hover {
            background-color: var(--booking-blue-light);
        }

        /* --- Content Section --- */
        .content-section {
            padding: 80px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .content-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .info-cards-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 25px;
        }

        .info-card {
            background: white;
            border-radius: 12px;
            padding: 30px 25px;
            text-align: center;
            /* Soft shadow */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid #f0f0f0;
        }

        /* Card Hover Effect - Lift up */
        .info-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .info-card .icon {
            font-size: 2.5rem;
            margin-bottom: 15px;
            display: block;
        }

        .info-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--booking-blue);
            margin-bottom: 10px;
        }

        .info-card p {
            font-size: 0.95rem;
            color: #666;
            line-height: 1.5;
        }

        /* --- Footer --- */
        footer {
            margin-top: auto;
            padding: 30px;
            text-align: center;
            font-size: 0.9rem;
            color: #888;
            background: #fff;
            border-top: 1px solid #eee;
        }

        /* Responsive */
        @media (max-width: 900px) {
            .search-bar {
                flex-direction: column;
                background: white; /* Remove yellow background on mobile */
                gap: 10px;
                padding: 10px;
            }
            .search-field, .search-btn {
                width: 100%;
                border: 1px solid #eee;
            }
            .search-btn {
                padding: 15px;
            }
            .main-nav {
                justify-content: center;
                flex-wrap: wrap;
            }
            .headline-section h1 { font-size: 2rem; }
        }
    </style>
</head>
<body>

    <div class="header-container">
        <div class="top-nav">
            <a href="contact.php">Contact us</a>
            <a href="aboutus.php">About us</a>
            <a href="signup.php" class="btn-action btn-register">Register</a>
            <a href="login.php" class="btn-action btn-sign-in">Sign in</a>
        </div>

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
        <form class="search-bar" action="#" method="GET">
            <div class="search-field">
                <input type="text" placeholder="Where are you going? 📍">
            </div>
            <div class="search-field">
                <input type="text" placeholder="Check-in - Check-out 📅" onfocus="(this.type='date')">
            </div>
            <div class="search-field">
                <select>
                    <option>2 adults · 0 children · 1 room</option>
                    <option>1 adult · 0 children · 1 room</option>
                    <option>2 adults · 1 child · 1 room</option>
                </select>
            </div>
            <button type="submit" class="search-btn">Search</button>
        </form>
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