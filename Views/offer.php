<?php
session_start();

// Display message from session
$message = $_SESSION['message'] ?? '';
unset($_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>TripLink | Offers</title>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f7f9;
            margin: 0;
            padding: 0;
        }

        .header {
            background: #003580;
            color: white;
            padding: 20px 40px;
        }

        h1 { margin: 0; }

        .offers-container {
            max-width: 1100px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .offer-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            overflow: hidden;
            transition: 0.2s;
        }

        .offer-card:hover {
            transform: scale(1.02);
        }

        .offer-card img {
            width: 100%;
            height: 180px;
            object-fit: cover;
        }

        .offer-info {
            padding: 20px;
        }

        .offer-title {
            font-size: 1.3rem;
            font-weight: bold;
            color: #003580;
            margin-bottom: 10px;
        }

        .offer-desc {
            color: #444;
            font-size: 0.95rem;
            margin-bottom: 15px;
        }

        .offer-btn {
            display: block;
            width: 100%;
            padding: 12px;
            background: #003580;
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 1rem;
            font-weight: 600;
            text-align: center;
            transition: 0.2s;
        }

        .offer-btn:hover {
            background: #002451;
        }

        .message {
            text-align: center;
            padding: 15px;
            font-size: 1.1rem;
            color: green;
            font-weight: bold;
        }
    </style>
</head>

<body>

<div class="header">
    <h1>🔥 Hot Offers & Travel Deals</h1>
</div>

<?php if ($message): ?>
    <div class="message"><?= $message ?></div>
<?php endif; ?>

<?php
// Example: offers that later will come from DATABASE
$offers = [
    [
        "id" => 1,
        "title" => "Saudi Arabia – Flight + 3 Nights Hotel",
        "desc" => "Enjoy Riyadh with a full package including hotel & round flight.",
    ],
    [
        "id" => 2,
        "title" => "Dubai – Luxury Trip Offer (5 Days)",
        "desc" => "Free breakfast + Burj Khalifa view hotel + flight included.",
    ],
    [
        "id" => 3,
        "title" => "Egypt – Nile Cruise Special",
        "desc" => "5-star Nile cruise + transportation + hotel nights.",
    ],
    [
        "id" => 4,
        "title" => "Jeddah – Beach Vacation Offer",
        "desc" => "Sea-view hotel + free breakfast + flight included.",
    ]
];
?>

<div class="offers-container">

    <?php foreach ($offers as $offer): ?>
        <div class="offer-card">


            <div class="offer-info">
                <div class="offer-title"><?= $offer['title'] ?></div>
                <div class="offer-desc"><?= $offer['desc'] ?></div>

                <!-- SEND OFFER ID TO CONTROLLER -->
                <form method="POST" action="index.php?controller=offer&action=save">
                    <input type="hidden" name="offer_id" value="<?= $offer['id'] ?>">
                    <input type="hidden" name="offer_title" value="<?= $offer['title'] ?>">
                    <input type="hidden" name="offer_description" value="<?= $offer['desc'] ?>">

                    <button type="submit" class="offer-btn">Claim Offer</button>
                </form>

            </div>
        </div>
    <?php endforeach; ?>

</div>

</body>
</html>
