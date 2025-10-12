<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
// These two lines are for debugging and development.
// error_reporting(E_ALL) → tells PHP to report all types of errors, including warnings and notices.
// ini_set('display_errors', 1) → enables displaying errors on the web page (instead of hiding them).

$servername = "localhost";
// $servername = "localhost"; → means the MySQL server is hosted on the same computer as your PHP code (XAMPP/WAMP).
$username = "root";
// $username = "root"; → default username for local MySQL installations.
$password = "";
// $password = ""; → empty password (no password is required to log into the MySQL server.).
$dbname = "booking_app_db";


$conn = new mysqli($servername, $username, $password, $dbname);
//creates the database connection using the MySQLi (MySQL Improved) extension.
// $conn is now your connection object used in other PHP files (like inserting or selecting data).
// new mysqli() creates an object representing a connection to the MySQL server.
if ($conn->connect_error) {
    die("❌ Database connection failed: " . $conn->connect_error);
}
// check if there is any connection error


$conn->set_charset("utf8");
//handling diff languages like arabic,accented,speical char
//prevent errorsd like ???
?>
