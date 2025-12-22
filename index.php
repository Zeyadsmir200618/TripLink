<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/* ─────────────── CONFIG & CONTROLLERS ─────────────── */
// ✅ FIXED: Changed 'controllers' to 'Controllers' to match your folder structure
require_once __DIR__ . '/../app/config/database.php';
require_once __DIR__ . '/../app/Controllers/AboutController.php';
require_once __DIR__ . '/../app/Controllers/ContactController.php';
require_once __DIR__ . '/../app/Controllers/FlightController.php';
require_once __DIR__ . '/../app/Controllers/HotelController.php';
require_once __DIR__ . '/../app/Controllers/AuthController.php';
require_once __DIR__ . '/../app/Controllers/OfferController.php';

/* ─────────────── DB CONNECTION ─────────────── */
$db = Database::getInstance(); 
$conn = $db->getConnection();

/* ─────────────── ROUTER ─────────────── */
$controller = $_GET['controller'] ?? 'about';
$action     = $_GET['action'] ?? 'view';

switch ($controller) {

    case 'about':
        $c = new AboutController();
        if (method_exists($c, $action)) $c->$action();
        break;

    case 'contact':
        $c = new ContactController();
        if (method_exists($c, $action)) $c->$action();
        break;

    case 'flight':
        $c = new FlightController();
        // ✅ FIXED: Added an 'else' block. If 'view' doesn't exist, it loads the form anyway.
        if (method_exists($c, $action)) {
            $c->$action($_POST);
        } else {
            $c->handleRequest($_POST); // Fallback to load the form
        }
        break;

    case 'hotel':
        $c = new HotelController();
        // ✅ FIXED: Added fallback for hotel too
        if (method_exists($c, $action)) {
            $c->$action($_POST);
        } else {
            $c->handleRequest($_POST);
        }
        break;

    case 'auth':
        $c = new AuthController($conn);
        if (method_exists($c, $action)) $c->$action($_POST);
        break;
        
    case 'offer':
        $c = new OfferController($conn);
        if (method_exists($c, $action)) $c->$action($_POST);
        break;

    default:
        http_response_code(404);
        echo "❌ Controller not found.";
}
