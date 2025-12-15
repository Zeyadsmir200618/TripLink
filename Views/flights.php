<?php
require_once __DIR__ . '/../Config/database.php';
require_once __DIR__ . '/../Models/Flight.php';

$db = new Database();
$flightModel = new Flight($db->conn);
$flights = $flightModel->getAllFlights();

// Helper to safely read values from different DB column name variants
function flight_value(array $row, array $keys, $default = '')
{
    foreach ($keys as $key) {
        if (isset($row[$key]) && $row[$key] !== '') {
            return $row[$key];
        }
    }
    return $default;
}

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
    // Normalized values based on possible DB column names
    $fromCity    = flight_value($flight, ['departure_city', 'source']);
    $toCity      = flight_value($flight, ['arrival_city', 'destination']);
    $departDate  = flight_value($flight, ['departure_date', 'depart date']);
    $returnDate  = flight_value($flight, ['return_date', 'return date']);
    $paymentType = strtolower(flight_value($flight, ['payment_type', 'payment type']));
    $flightClass = strtolower(flight_value($flight, ['flight_class', 'Flight Class']));
    $passengers  = (int) flight_value($flight, ['passenger_number', 'passenger_number'], 1);
    $roundTrip   = (int) flight_value($flight, ['round_trip', 'round-trip'], 1); // 1 = round trip, 0 = one way

    // From city
    if ($filters['from'] !== '') {
        if (stripos($fromCity, $filters['from']) === false) {
            return false;
        }
    }

    // To city
    if ($filters['to'] !== '') {
        if (stripos($toCity, $filters['to']) === false) {
            return false;
        }
    }

    // Trip type: if user picked one_way, only show one-way flights (roundTrip == 0)
    if ($filters['trip_type'] === 'one_way' && $roundTrip !== 0) {
        return false;
    }

    // Depart date (on or after)
    if ($filters['depart'] !== '' && $departDate !== '') {
        if ($departDate < $filters['depart']) {
            return false;
        }
    }

    // Return date (on or before) – ignore for one-way
    if ($filters['trip_type'] !== 'one_way' && $filters['return'] !== '' && $returnDate !== '') {
        if ($returnDate > $filters['return']) {
            return false;
        }
    }

    // Payment type (if not "any")
    if ($filters['payment_type'] !== 'any') {
        if ($paymentType === '' || $paymentType !== strtolower($filters['payment_type'])) {
            return false;
        }
    }

    // Flight class
    if (!empty($filters['flight_class'])) {
        if ($flightClass === '' || $flightClass !== strtolower($filters['flight_class'])) {
            return false;
        }
    }

    // Passengers: require there are at least that many seats (passenger_number >= selected)
    if (!empty($filters['passengers'])) {
        if ($passengers > 0 && $passengers < (int)$filters['passengers']) {
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
                <?php
                    // Normalize keys to avoid undefined index notices with different DB column names
                    $flightNumber  = flight_value($flight, ['flight_number', 'Flight Number']);
                    $airline       = flight_value($flight, ['airline', 'Airline']);
                    $departureCity = flight_value($flight, ['departure_city', 'source']);
                    $arrivalCity   = flight_value($flight, ['arrival_city', 'destination']);
                    $departDate    = flight_value($flight, ['departure_date', 'depart date']);
                    $returnDate    = flight_value($flight, ['return_date', 'return date']);
                    $priceValue    = flight_value($flight, ['price', 'Price'], 0);
                ?>
                <div class="room-card">
                    <div class="room-info">
                        <h3>
                            <?= htmlspecialchars($flightNumber !== '' ? $flightNumber : 'Flight') ?>
                            <?php if ($airline !== ''): ?>
                                - <?= htmlspecialchars($airline) ?>
                            <?php endif; ?>
                        </h3>
                        <p class="hotel-name">
                            <?= htmlspecialchars($departureCity) ?>
                            <?php if ($arrivalCity !== ''): ?>
                                → <?= htmlspecialchars($arrivalCity) ?>
                            <?php endif; ?>
                        </p>

                        <div class="room-details">
                            <span>🛫 Depart: <?= htmlspecialchars($departDate) ?></span>
                            <span>🛬 Return: <?= htmlspecialchars($returnDate) ?></span>
                        </div>

                        <div class="room-footer">
                            <span class="price">From EGP<?= number_format((float)$priceValue) ?></span>
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


