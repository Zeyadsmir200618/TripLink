<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Find your next stay</title>
    <style>
        /* Base Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Variables and Base Body Styling */
        :root {
            --booking-blue: #003580;
            --booking-orange: #febb02;
            --light-bg: #f4f7f9;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background: var(--light-bg);
            color: #333;
            min-height: 100vh;
        }

        /* --- Main Header Section (Blue Area) --- */
        .header-container {
            background-color: var(--booking-blue);
            padding-bottom: 50px; /* Space above search */
        }

        /* Top Bar Navigation (Simplified) */
        .top-nav {
            display: flex;
            justify-content: flex-end; /* Push items to the right */
            align-items: center;
            padding: 10px 40px;
            color: white;
            font-size: 0.9rem;
        }

        .top-nav a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
            padding: 5px 10px;
            border-radius: 3px;
            font-weight: 500;
        }

        .top-nav .btn-register {
            background-color: white;
            color: var(--booking-blue);
            padding: 8px 15px;
            font-weight: 600;
            margin-left: 15px;
        }

        .top-nav .btn-sign-in {
            background-color: var(--booking-blue);
            border: 1px solid white;
            color: white;
            padding: 8px 15px;
            font-weight: 600;
        }

        /* Main Navigation Tabs */
        .main-nav {
            display: flex;
            padding: 0 40px;
            gap: 20px;
            margin-top: 15px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            border-radius: 8px;
            transition: background-color 0.2s;
        }

        .nav-item.active {
            background-color: white;
            color: var(--booking-blue);
        }

        .nav-item.active .icon {
            color: var(--booking-blue);
        }

        .nav-item:not(.active):hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .nav-item .icon {
            font-size: 1.5rem;
            margin-right: 8px;
            color: white;
        }

        /* Main Headline */
        .headline-section {
            padding: 50px 40px 30px;
            text-align: center;
            color: white;
        }

        .headline-section h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .headline-section h2 {
            font-size: 1.5rem;
            font-weight: 400;
        }

        /* Search Form (Simplified) */
        .search-bar-wrapper {
            display: flex;
            justify-content: center;
            padding: 0 40px;
            margin-top: 20px;
            transform: translateY(50%); /* Lift the search bar over the blue/white divide */
        }

        .search-bar {
            display: flex;
            background: var(--booking-orange);
            border: 3px solid var(--booking-orange);
            border-radius: 5px;
            max-width: 900px;
            width: 100%;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .search-field {
            flex-grow: 1;
            padding: 15px 20px;
            background-color: white;
            border: none;
            font-size: 1rem;
            color: #333;
            border-right: 1px solid #ddd;
        }

        .search-field:last-of-type {
            border-right: none;
        }
        
        .search-field input, .search-field select {
            width: 100%;
            border: none;
            outline: none;
            font-size: 1rem;
            padding: 0;
            margin: 0;
            color: #333;
        }

        .search-btn {
            background-color: var(--booking-blue);
            color: white;
            padding: 15px 30px;
            border: none;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
        }


        /* --- Content Below Search --- */
        .content-section {
            padding-top: 100px; /* To account for the search bar offset */
            padding-bottom: 40px;
            max-width: 1000px;
            margin: 0 auto;
        }

        .content-section h2 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
            padding: 0 20px;
        }

        .info-cards-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 0 20px;
        }

        .info-card {
            background: white;
            border-radius: 5px;
            padding: 20px;
            width: 220px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
            min-height: 200px;
        }

        .info-card .icon {
            font-size: 2rem;
            margin-bottom: 10px;
            display: inline-block;
        }

        .info-card h3 {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--booking-blue);
            margin-bottom: 8px;
        }

        .info-card p {
            font-size: 0.9rem;
            color: #666;
        }

        /* --- Footer --- */
        footer {
            margin-top: 40px;
            padding: 20px;
            text-align: center;
            font-size: 0.8rem;
            color: #999;
            border-top: 1px solid #eee;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            .header-container {
                padding-bottom: 30px;
            }
            .top-nav {
                padding: 10px 20px;
                justify-content: center;
            }
            .main-nav {
                padding: 0 20px;
                justify-content: center;
                gap: 10px;
                flex-wrap: wrap;
            }
            .headline-section h1 {
                font-size: 2.5rem;
            }
            .search-bar-wrapper {
                padding: 0 20px;
            }
            .search-bar {
                flex-direction: column;
            }
            .search-field {
                border-right: none;
                border-bottom: 1px solid #ddd;
            }
            .search-btn {
                padding: 15px;
            }
            .info-cards-container {
                flex-direction: column;
                align-items: center;
            }
            .info-card {
                width: 90%;
                max-width: 300px;
            }
            .content-section {
                padding-top: 80px;
            }
        }
    </style>
</head>
<body>

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

    <footer>
        &copy; 2025 TripLink. All rights reserved.
    </footer>

</body>
</html>
