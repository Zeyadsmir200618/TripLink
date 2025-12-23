# TripLink - Travel Booking System ✈️🏨

TripLink is a comprehensive web application designed to simplify travel planning by allowing users to book flights and hotels in one unified platform.

## 🚀 Features
* **User Authentication:** Login and Registration system.
* **Flight Booking:** Search and book available flights.
* **Hotel Reservation:** Browse hotels and make reservations.
* **Admin Dashboard:** Manage users, bookings, and offers.

## 🛠️ Architecture & Design Patterns
This project follows strictly structured software engineering principles:

### 1. MVC Architecture (Model-View-Controller)
The project logic is encapsulated within the `app/` directory:
* **Models:** Handle database interactions (e.g., `User.php`, `Flight.php`).
* **Views:** Handle the user interface.
* **Controllers:** Manage the flow between models and views.

### 2. Singleton Pattern
Used for the **Database Connection** to ensure a single instance of the PDO connection is used throughout the request lifecycle, optimizing resource usage.

## 📊 System Diagrams
### Class Diagram
![Class Diagram]<img width="843" height="336" alt="class diagram" src="https://github.com/user-attachments/assets/3ad5ba0b-bd3b-4ab5-b391-06489041d935" />


### Use Case Diagram
![Use Case Diagram]<img width="361" height="815" alt="usecase digram" src="https://github.com/user-attachments/assets/a3958534-be82-412b-a903-ea3c40501a2c" />


## 💻 Setup & Installation
1. Clone the repository to your `htdocs` folder.
2. Open XAMPP and start **Apache** and **MySQL**.
3. Import the database file into PHPMyAdmin.
4. Configure `app/Config/Database.php` if you have a password set for MySQL.
5. Open your browser and go to `http://localhost/TripLink/public`.

## 👨‍💻 Tech Stack
* **Backend:** PHP (Native)
* **Frontend:** HTML, CSS, JavaScript
* **Database:** MySQL
