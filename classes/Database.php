<?php
class Database {
    private $host = "localhost"; // Change if needed
    private $dbname = "domein-zoeker"; // Change to your database name
    private $username = "root"; // Change to your database username
    private $password = ""; // Change to your database password
    private $conn;

    public function getConnection() {
        if ($this->conn === null) {
            try {
                $this->conn = new PDO("mysql:host={$this->host};dbname={$this->dbname};charset=utf8", $this->username, $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Database verbinding mislukt: " . $e->getMessage());
            }
        }
        return $this->conn;
    }
}
?>
