<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

/* ─────────────── CONFIG & CONTROLLERS ─────────────── */
require_once __DIR__ . '/Config/database.php';
require_once __DIR__ . '/Controllers/AboutController.php';
require_once __DIR__ . '/Controllers/ContactController.php';
require_once __DIR__ . '/Controllers/FlightController.php';
require_once __DIR__ . '/Controllers/HotelController.php';
require_once __DIR__ . '/Controllers/AuthController.php';
require_once __DIR__ . '/Controllers/OfferController.php';   // ✅ ADDED


/* ─────────────── DB CONNECTION ─────────────── */
$db = new Database();
$conn = $db->conn;


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
        if (method_exists($c, $action)) $c->$action($_POST);
        break;

    case 'hotel':
        $c = new HotelController();
        if (method_exists($c, $action)) $c->$action($_POST);
        break;

    case 'auth':
        $c = new AuthController($conn);
        if (method_exists($c, $action)) $c->$action($_POST);
        break;

    case 'offer':
        $c = new OfferController();
        if (method_exists($c, $action)) $c->$action($_POST);
        break;

    default:
        http_response_code(404);
        echo "❌ Controller not found.";
}
