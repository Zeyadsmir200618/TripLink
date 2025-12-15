<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/Flight.php';

$db = new Database();
$flightModel = new Flight($db->conn);
$flights = $flightModel->getAllFlights();

// Read filters from query string
$filters = [
    'trip_type'    => $_GET['trip_type']    ?? 'round_trip',
    'from'         => $_GET['from']         ?? '',
    'to'           => $_GET['to']           ?? '',
    'depart'       => $_GET['depart']       ?? '',
    'return'       => $_GET['return']       ?? '',
    'payment_type' => $_GET['payment_type'] ?? 'any',
    'flight_class' => $_GET['flight_class'] ?? 'economy',
    'passengers'   => $_GET['passengers']   ?? '1',
];

// Simple in-memory filtering to keep things functional
$filteredFlights = array_filter($flights, function ($flight) use ($filters) {
    // From city
    if ($filters['from'] !== '') {
        if (stripos($flight['departure_city'], $filters['from']) === false &&
            stripos($flight['departure_airport'] ?? '', $filters['from']) === false) {
            return false;
        }
    }

    // To city
    if ($filters['to'] !== '') {
        if (stripos($flight['arrival_city'], $filters['to']) === false &&
            stripos($flight['arrival_airport'] ?? '', $filters['to']) === false) {
            return false;
        }
    }

    // Depart date (on or after)
    if ($filters['depart'] !== '') {
        if (isset($flight['departure_date']) && $flight['departure_date'] < $filters['depart']) {
            return false;
        }
    }

    // Return date (on or before)
    if ($filters['return'] !== '') {
        if (isset($flight['return_date']) && $flight['return_date'] > $filters['return']) {
            return false;
        }
    }

    return true;
});

// If no filters applied (all default) or no results, show all flights
$hasUserFilters = trim($filters['from'] . $filters['to'] . $filters['depart'] . $filters['return']) !== '';
$flightsToShow = ($hasUserFilters && !empty($filteredFlights)) ? $filteredFlights : $flights;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Flights</title>
    <link rel="stylesheet" href="/TripLink/Public/css/flights.css">
</head>
<body>
<?php include __DIR__ . '/partials/navbar.php';?>

<div class="hero-section">
    <div class="hero-overlay"></div>
    <div class="hero-content">
        <div class="tab-buttons">
            <a href="/TripLink/Views/hotels.php" class="tab-btn">Hotels</a>
            <a href="/TripLink/Views/flights.php" class="tab-btn active">Flights</a>
            <a href="/TripLink/Views/offers.php" class="tab-btn">Packages</a>
        </div>
        
        <div class="search-hero" id="flights-search">
            <form class="flight-search-card" method="GET" action="">
                <div class="trip-type-toggle">
                    <label>
                        <input type="radio" name="trip_type" value="round_trip" <?= $filters['trip_type'] === 'one_way' ? '' : 'checked' ?>>
                        <span>Round Trip</span>
                    </label>
                    <label>
                        <input type="radio" name="trip_type" value="one_way" <?= $filters['trip_type'] === 'one_way' ? 'checked' : '' ?>>
                        <span>One Way</span>
                    </label>
                </div>

                <div class="flight-search-grid">
                    <div class="field-group">
                        <label for="from">From</label>
                        <input
                            type="text"
                            id="from"
                            name="from"
                            placeholder="New York (JFK)"
                            value="<?= htmlspecialchars($filters['from']) ?>"
                        >
                    </div>

                    <div class="field-group">
                        <label for="to">To</label>
                        <input
                            type="text"
                            id="to"
                            name="to"
                            placeholder="Los Angeles (LAX)"
                            value="<?= htmlspecialchars($filters['to']) ?>"
                        >
                    </div>

                    <div class="field-group">
                        <label for="depart">Depart</label>
                        <input
                            type="date"
                            id="depart"
                            name="depart"
                            value="<?= htmlspecialchars($filters['depart']) ?>"
                        >
                    </div>

                    <div class="field-group">
                        <label for="return">Return</label>
                        <input
                            type="date"
                            id="return"
                            name="return"
                            value="<?= htmlspecialchars($filters['return']) ?>"
                            <?= $filters['trip_type'] === 'one_way' ? 'disabled' : '' ?>
                        >
                    </div>

                    <div class="field-group">
                        <label for="payment_type">Payment Type</label>
                        <select id="payment_type" name="payment_type">
                            <option value="any" <?= $filters['payment_type'] === 'any' ? 'selected' : '' ?>>Any</option>
                            <option value="card" <?= $filters['payment_type'] === 'card' ? 'selected' : '' ?>>Card</option>
                            <option value="cash" <?= $filters['payment_type'] === 'cash' ? 'selected' : '' ?>>Cash</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="flight_class">Flight Class</label>
                        <select id="flight_class" name="flight_class">
                            <option value="economy" <?= $filters['flight_class'] === 'economy' ? 'selected' : '' ?>>Economy</option>
                            <option value="business" <?= $filters['flight_class'] === 'business' ? 'selected' : '' ?>>Business</option>
                            <option value="first" <?= $filters['flight_class'] === 'first' ? 'selected' : '' ?>>First</option>
                        </select>
                    </div>

                    <div class="field-group">
                        <label for="passengers">Passengers</label>
                        <select id="passengers" name="passengers">
                            <?php for ($i = 1; $i <= 6; $i++): ?>
                                <option value="<?= $i ?>" <?= (string)$filters['passengers'] === (string)$i ? 'selected' : '' ?>>
                                    <?= $i ?> Passenger<?= $i > 1 ? 's' : '' ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                </div>

                <div class="flight-search-action">
                    <button type="submit" class="flight-search-button">Search Flights</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="main-content">
    <main class="rooms-section" style="flex: 1;">
        <h2>Available Flights</h2>
        <div class="rooms-grid">
            <?php foreach ($flightsToShow as $flight): ?>
            <div class="room-card">
                <div class="room-info">
                    <h3><?= htmlspecialchars($flight['flight_number']) ?> - <?= htmlspecialchars($flight['airline']) ?></h3>
                    <p class="hotel-name">
                        <?= htmlspecialchars($flight['departure_city']) ?> → <?= htmlspecialchars($flight['arrival_city']) ?>
                    </p>

                    <div class="room-details">
                        <span>🛫 Depart: <?= htmlspecialchars($flight['departure_date']) ?></span>
                        <span>🛬 Return: <?= htmlspecialchars($flight['return_date']) ?></span>
                    </div>

                    <div class="room-footer">
                        <span class="price">From EGP<?= number_format($flight['price']) ?></span>
                        <button class="book-btn">Book Flight</button>
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


