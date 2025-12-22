<?php
// Display message from session
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

    <style>
        /* ================================ 
           GLOBAL 
        ================================ */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7f9;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* ================================ 
           NAVIGATION 
        ================================ */
        .navbar {
            background: white;
            padding: 15px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .navbar .brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: #003580;
        }

        .navbar a {
            text-decoration: none;
            font-weight: 500;
            color: #003580;
            padding: 8px 15px;
            border-radius: 5px;
            transition: 0.2s;
        }

        .navbar a:hover { background: #f0f4fa; }

        /* ================================ 
           HERO SECTION 
        ================================ */
        .hero {
            background: linear-gradient(135deg, #003580 0%, #004cb8 100%);
            color: white;
            padding: 60px 20px;
            text-align: center;
        }

        .hero h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.1rem;
            opacity: 0.9;
            font-weight: 300;
        }

        /* ================================ 
           OFFERS GRID 
        ================================ */
        .container {
            max-width: 1200px;
            margin: -40px auto 40px; /* Pull up into hero */
            padding: 0 20px;
        }

        .offers-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
        }

        /* ================================ 
           OFFER CARD 
        ================================ */
        .offer-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            position: relative;
            display: flex;
            flex-direction: column;
        }

        .offer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.12);
        }

        /* Visual Top Part (Placeholder for Image) */
        .card-visual {
            height: 140px;
            background: #eef3f9;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 4rem;
            position: relative;
        }

        /* Discount Badge */
        .badge {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #febb02; /* Booking Orange */
            color: #333;
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }

        .card-body {
            padding: 25px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .offer-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #003580;
            margin-bottom: 10px;
            line-height: 1.3;
        }

        .offer-desc {
            color: #666;
            font-size: 0.95rem;
            line-height: 1.6;
            margin-bottom: 20px;
            flex-grow: 1;
        }

        /* ================================ 
           BUTTON 
        ================================ */
        .offer-btn {
            width: 100%;
            padding: 12px;
            background: white;
            color: #003580;
            border: 2px solid #003580;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            transition: 0.2s;
        }

        .offer-btn:hover {
            background: #003580;
            color: white;
        }

        /* ================================ 
           MESSAGE 
        ================================ */
        .message-box {
            background: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
            border: 1px solid #c3e6cb;
        }
    </style>
</head>

<body>

    <div class="navbar">
        <div class="brand">TripLink</div>
        <a href="menu.php">Back to Home</a>
    </div>

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