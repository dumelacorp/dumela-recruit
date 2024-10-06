<?php
class Candidate {
    private $conn;
    private $table = 'candidates';

    public $id;
    public $first_name;
    public $middle_name;
    public $last_name;
    public $email;
    public $country;
    public $state;
    public $city;
    public $job_title;
    public $level;
    public $resume;

    public $rate;
    public $rate_period;
    public $status;
    public $outsource_rate;
    public $outsource_rate_period;
    public $search_string;

    public $created_at;
    

    public function __construct($db) {
        $this->conn = $db;
    }

    public function emailExists() {
        try{
            $query = "SELECT id, email, first_name, status FROM candidates WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->email);
            $stmt->execute();

            if($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row['id'];
                $this->first_name = $row['first_name'];
                $this->status = $row['status'];
                return true;
            }
            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while checking if candidate exists in the database! \n", 3, 'error_log '); 
        }
    }

    public function getDetails() {
        try{
            $query = "SELECT id, first_name, status FROM candidates WHERE email = ?";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(1, $this->email);
            $stmt->execute();

            if($stmt->rowCount() == 1) {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                $this->id = $row['id'];
                $this->first_name = $row['first_name'];
                $this->status = $row['status'];
                return true;
            }
            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while getting candidate details from the database! \n", 3, 'error_log '); 
        }
    }

    public function create() {
        try{
            $query = 'INSERT INTO ' . $this->table . ' SET
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                email = :email,
                country = :country,
                state = :state,
                city = :city,
                job_title = :job_title,
                level = :level,
                rate = :rate,
                rate_period = :rate_period,
                status = :status,
                outsource_rate = :outsource_rate,
                outsource_rate_period = :outsource_rate_period,
                resume = :resume';

            $stmt = $this->conn->prepare($query);

            $this->first_name = ucwords(htmlspecialchars(strip_tags($this->first_name)));
            $this->middle_name = ucwords(htmlspecialchars(strip_tags($this->middle_name)));
            $this->last_name = ucwords(htmlspecialchars(strip_tags($this->last_name)));
            $this->email = ucwords(htmlspecialchars(strip_tags($this->email)));
            $this->country = htmlspecialchars(strip_tags($this->country));
            $this->state = ucwords(htmlspecialchars(strip_tags($this->state)));
            $this->city = ucwords(htmlspecialchars(strip_tags($this->city)));
            $this->job_title = ucwords(htmlspecialchars(strip_tags($this->job_title)));
            $this->level = htmlspecialchars(strip_tags($this->level));

            $this->rate = htmlspecialchars(strip_tags($this->rate));
            $this->rate_period = htmlspecialchars(strip_tags($this->rate_period));
            $this->status = $this->status;
            $this->outsource_rate = htmlspecialchars(strip_tags($this->outsource_rate));
            $this->outsource_rate_period = htmlspecialchars(strip_tags($this->outsource_rate_period));

            $this->resume = htmlspecialchars(strip_tags($this->resume));

            $stmt->bindParam(':first_name', $this->first_name);
            $stmt->bindParam(':middle_name', $this->middle_name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':country', $this->country);
            $stmt->bindParam(':state', $this->state);
            $stmt->bindParam(':city', $this->city);
            $stmt->bindParam(':job_title', $this->job_title);
            $stmt->bindParam(':level', $this->level);

            $stmt->bindParam(':rate', $this->rate);
            $stmt->bindParam(':rate_period', $this->rate_period);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':outsource_rate', $this->outsource_rate);
            $stmt->bindParam(':outsource_rate_period', $this->outsource_rate_period);

            $stmt->bindParam(':resume', $this->resume);

            if($stmt->execute()) {
                return true;
            }

            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while creating a candidate in the database! \n", 3, 'error_log '); 
        }
    }


    public function read($limit = 10, $offset = 0) {
        try{
            // $query = 'SELECT * FROM ' . $this->table . ' LIMIT :limit OFFSET :offset ORDER BY created_at DESC';
            $query = "SELECT * FROM candidates ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while reading a candidate from the database! \n", 3, 'error_log'); 
        }
    }

    public function getTotalCandidates(){
        try{
            $query = 'SELECT COUNT(*) AS total FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $total_candidates = $stmt->fetch(PDO::FETCH_ASSOC);
            return $total_candidates['total'];
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while getting total number of candidates from the database! \n", 3, 'error_log'); 
        }
    }

    public function getTotalCandidatesForSearch($search_string) {
        try {
            $query = "SELECT COUNT(*) as total FROM candidates WHERE 
                first_name LIKE :search OR 
                last_name LIKE :search OR 
                country LIKE :search OR 
                job_title LIKE :search OR 
                level LIKE :search";
            $stmt = $this->conn->prepare($query);
            $search_param = "%{$search_string}%";
            $stmt->bindParam(':search', $search_param);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) {
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while getting total number of candidates for search from the database! \n", 3, 'error_log'); 
        }
    }

    public function getCandidateDetailsById($id) {
        // $pdo = getDbConnection();
        
        try {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $candidate;
        } catch (PDOException $e) {
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while getting candidate details by their Id from the database! \n", 3, 'error_log'); 
        }
    }

    public function findCandidatesByMultipleParameters($search_string, $limit = 10, $offset = 0){
        try{

            $query = "SELECT * FROM candidates WHERE 
              first_name LIKE :search OR 
              last_name LIKE :search OR 
              country LIKE :search OR 
              job_title LIKE :search OR 
              level LIKE :search
              ORDER BY created_at DESC
              LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $search_param = "%{$search_string}%";
            $stmt->bindParam(':search', $search_param);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
            
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while searching for candidate with multiple paramaters in the database! \n", 3, 'error_log'); 
        }
    }

    public function update() {
        try{
            $query = 'UPDATE ' . $this->table . ' SET
                first_name = :first_name,
                middle_name = :middle_name,
                last_name = :last_name,
                email = :email,
                country = :country,
                state = :state,
                city = :city,
                job_title = :job_title,
                level = :level,
                rate = :rate,
                rate_period = :rate_period,
                status = :status,
                outsource_rate = :outsource_rate,
                outsource_rate_period = :outsource_rate_period,
                resume = :resume
                WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            $this->first_name = htmlspecialchars(strip_tags($this->first_name));
            $this->middle_name = htmlspecialchars(strip_tags($this->middle_name));
            $this->last_name = htmlspecialchars(strip_tags($this->last_name));
            $this->email = htmlspecialchars(strip_tags($this->email));
            $this->country = htmlspecialchars(strip_tags($this->country));
            $this->state = htmlspecialchars(strip_tags($this->state));
            $this->city = htmlspecialchars(strip_tags($this->city));
            $this->job_title = htmlspecialchars(strip_tags($this->job_title));
            $this->level = htmlspecialchars(strip_tags($this->level));
            $this->resume = htmlspecialchars(strip_tags($this->resume));

            $this->rate = htmlspecialchars(strip_tags($this->rate));
            $this->rate_period = htmlspecialchars(strip_tags($this->rate_period));
            // $this->status = htmlspecialchars(strip_tags($this->status));
            $this->status = $this->status;
            $this->outsource_rate = htmlspecialchars(strip_tags($this->outsource_rate));
            $this->outsource_rate_period = htmlspecialchars(strip_tags($this->outsource_rate_period));

            $this->id = htmlspecialchars(strip_tags($this->id));

            $stmt->bindParam(':first_name', $this->first_name);
            $stmt->bindParam(':middle_name', $this->middle_name);
            $stmt->bindParam(':last_name', $this->last_name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':country', $this->country);
            $stmt->bindParam(':state', $this->state);
            $stmt->bindParam(':city', $this->city);
            $stmt->bindParam(':job_title', $this->job_title);
            $stmt->bindParam(':level', $this->level);
            $stmt->bindParam(':resume', $this->resume);

            $stmt->bindParam(':rate', $this->rate);
            $stmt->bindParam(':rate_period', $this->rate_period);
            $stmt->bindParam(':status', $this->status);
            $stmt->bindParam(':outsource_rate', $this->outsource_rate);
            $stmt->bindParam(':outsource_rate_period', $this->outsource_rate_period);

            $stmt->bindParam(':id', $this->id);

            if($stmt->execute()) {
                return true;
            }

            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while updating candidate in the database! \n", 3, 'error_log'); 
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
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while removing candidate from the database! \n", 3, 'error_log'); 
        }
    }
}
?>
