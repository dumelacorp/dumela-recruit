<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'dumelaco_recruitify';
    private $username = 'root';
    private $password = '';
    public $conn;

    public function connect() {
        $this->conn = null;
        try {
            $this->conn = new PDO('mysql:host=' . $this->host . ';dbname=' . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // die("Database successfully connected.");
        } catch(PDOException $e) {
            echo 'Connection Error: ' . $e->getMessage();
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: Error connecting to database! \n", 3, 'error_log'); 
        }
        return $this->conn;
    }
}
?>
