# TripLink - Travel Booking System ✈️

TripLink is a comprehensive web application designed to simplify travel planning by allowing users to book flights and hotels in one unified platform.

 🚀 Features
* **User Authentication:** Login and Registration system.
* **Flight Booking:** Search and book available flights.
* **Hotel Reservation:** Browse hotels and make reservations.
* **Admin Dashboard:** Manage users, bookings, and offers.

 🛠️ Architecture & Design Patterns
This project follows strictly structured software engineering principles:

 1. MVC Architecture (Model-View-Controller)
The project logic is encapsulated within the `app/` directory:
* **Models:** Handle database interactions (e.g., `User.php`, `Flight.php`).
* **Views:** Handle the user interface.
* **Controllers:** Manage the flow between models and views.

 2. Singleton Pattern
Used for the **Database Connection** to ensure a single instance of the PDO connection is used throughout the request lifecycle, optimizing resource usage.

 📊 System Diagrams
#Class Diagram
![Class Diagram](Class%20diagram.png)

 Use Case Diagram
![Use Case Diagram](Use%20case%20diagram.png)

 💻 Setup & Installation
1. Clone the repository to your `htdocs` folder.
2. Open XAMPP and start **Apache** and **MySQL**.
3. Import the database file into PHPMyAdmin.
4. Configure `app/Config/Database.php` if you have a password set for MySQL.
5. Open your browser and go to `http://localhost/TripLink/public`.

 👨‍💻 Tech Stack
* **Backend:** PHP 
* **Frontend:** HTML, CSS, JavaScript
* **Database:** MySQL
