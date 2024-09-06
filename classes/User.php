<?php
class User {
    private $conn;
    private $table = 'users';

    public $id;
    public $username;
    public $password;
    public $created_at;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register() {
        try{
            $query = 'INSERT INTO ' . $this->table . ' SET username = :username, password = :password';
            $stmt = $this->conn->prepare($query);

            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);

            // $stmt->bindParam(':username', $this->username);
            // $stmt->bindParam(':password', $this->password);

            if($stmt->execute()) {
                return true;
            }

            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, '../public/dashboard/error_log'); 
            error_log("$timestamp: Something went wrong while creating a user in the database! \n", 3, '../public/dashboard/error_log'); 
        }
    }

    public function login() {
        try{
            $query = 'SELECT id, username, password FROM ' . $this->table . ' WHERE username = :username LIMIT 0,1';
            $stmt = $this->conn->prepare($query);

            $this->username = htmlspecialchars(strip_tags($this->username));
            $stmt->bindParam(':username', $this->username);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(password_verify($this->password, $row['password'])) {
                    $this->id = $row['id'];
                    $this->username = $row['username'];
                    return true;
                }
            }
            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, '../public/dashboard/error_log'); 
            error_log("$timestamp: Something went wrong while logging in the user in the database! \n", 3, '../public/dashboard/error_log'); 
        }
    }

    public function read() {
        try{
            $query = 'SELECT id, username, created_at FROM ' . $this->table . ' ORDER BY created_at DESC';
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            return $stmt;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, '../public/dashboard/error_log'); 
            error_log("$timestamp: Something went wrong while reading a user from the database! \n", 3, '../public/dashboard/error_log'); 
        }
    }

    public function update() {
        try{
            $query = 'UPDATE ' . $this->table . ' SET username = :username, password = :password WHERE id = :id';
            $stmt = $this->conn->prepare($query);

            $this->username = htmlspecialchars(strip_tags($this->username));
            $this->password = password_hash($this->password, PASSWORD_BCRYPT);
            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':username', $this->username);
            $stmt->bindParam(':password', $this->password);
            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()) {
                return true;
            }

            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, '../public/dashboard/error_log'); 
            error_log("$timestamp: Something went wrong while updating a user in the database! \n", 3, '../public/dashboard/error_log'); 
        }
    }

    public function delete() {
        try{
            $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
            $stmt = $this->conn->prepare($query);

            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()) {
                return true;
            }

            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, '../public/dashboard/error_log'); 
            error_log("$timestamp: Something went wrong while removing a user from the database! \n", 3, '../public/dashboard/error_log'); 
        }
    }
}
?>
