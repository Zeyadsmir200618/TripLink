<?php
// Start session FIRST before any output
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TripLink | Exclusive Offers</title>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/TripLink/public/css/base.css">
    <link rel="stylesheet" href="/TripLink/public/css/offer.css">
</head>

<body>

    <?php include __DIR__ . '/partials/navbar.php'; ?>

    <div class="hero">
        <h1>Deals of the Month 🔥</h1>
        <p>Save at least 15% on stays worldwide, from relaxing retreats to off-the-grid adventures.</p>
    </div>

    <div class="container">
        
        <?php if ($message): ?>
            <div class="message-box">✅ <?= htmlspecialchars($message) ?></div>
        <?php endif; ?>

        <?php
        // Simulated Database Data
        $offers = [
            [
                "id" => 1,
                "icon" => "🇸🇦",
                "title" => "Saudi Arabia – Riyadh Weekend",
                "desc" => "Includes round-trip flight + 3 nights at a 5-star hotel in the heart of Riyadh.",
                "discount" => "Save 20%"
            ],
            [
                "id" => 2,
                "icon" => "🇦🇪",
                "title" => "Dubai – Luxury Escape (5 Days)",
                "desc" => "Experience the Burj Khalifa. Free breakfast + City View room included.",
                "discount" => "Hot Deal 🔥"
            ],
            [
                "id" => 3,
                "icon" => "🇪🇬",
                "title" => "Egypt – Nile Cruise Special",
                "desc" => "7 days sailing from Luxor to Aswan. Full board + guided tours included.",
                "discount" => "Best Value"
            ],
            [
                "id" => 4,
                "icon" => "🌊",
                "title" => "Jeddah – Red Sea Resort",
                "desc" => "Relax by the beach. Private chalet access + dinner buffet included.",
                "discount" => "15% Off"
            ],
            [
                "id" => 5,
                "icon" => "🗼",
                "title" => "Paris – Romantic Getaway",
                "desc" => "Flight + 2 nights near Eiffel Tower. Includes river cruise tickets.",
                "discount" => "Limited Time"
            ],
             [
                "id" => 6,
                "icon" => "🏝️",
                "title" => "Maldives – All Inclusive",
                "desc" => "Water villa experience with seaplane transfer included.",
                "discount" => "Premium"
            ]
        ];
        ?>

        <div class="offers-grid">
            <?php foreach ($offers as $offer): ?>
                <div class="offer-card">
                    <div class="card-visual">
                        <?= $offer['icon'] ?>
                        <span class="badge"><?= $offer['discount'] ?></span>
                    </div>

                    <div class="card-body">
                        <div class="offer-title"><?= $offer['title'] ?></div>
                        <div class="offer-desc"><?= $offer['desc'] ?></div>

                        <form method="POST" action="index.php?controller=offer&action=save">
                            <input type="hidden" name="offer_id" value="<?= $offer['id'] ?>">
                            <input type="hidden" name="offer_title" value="<?= $offer['title'] ?>">
                            <input type="hidden" name="offer_description" value="<?= $offer['desc'] ?>">

                            <button type="submit" class="offer-btn">Claim Deal</button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

</body>
</html>