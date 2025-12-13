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
<link rel="stylesheet" href="/TripLink/Public/css/offer.css">
     
</head>

<body>
<?php include __DIR__ . '/partials/navbar.php';?>
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
<?php include __DIR__ . '/partials/footer.php';?>
</body>
</html>
