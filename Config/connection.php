<?php
class DB {
    private $conn;

    public function connect() {
        if (!$this->conn) {
            $this->conn = new mysqli("localhost", "root", "", "booking_app_db");
            if ($this->conn->connect_error) {
                die("Connection failed: " . $this->conn->connect_error);
            }
            $this->conn->set_charset("utf8");
        }
        return $this->conn;
    }
}
?>


