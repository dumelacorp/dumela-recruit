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

    public function create() {
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
        // $this->status = htmlspecialchars(strip_tags($this->status));
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
    }

    public function read() {
        $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalCandidates(){
        try{
            $query = 'SELECT COUNT(*) AS total FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $total_candidates = $stmt->fetch(PDO::FETCH_ASSOC);
            return $total_candidates['total'];
        }catch(PDOException $e){
            echo "Error: " . $e->getMessage();
            return false;
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
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function findCandidatesByMultipleParameters($search_string){
        try{
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE first_name LIKE :search_string 
            OR last_name LIKE :search_string 
            OR country LIKE :search_string 
            OR job_title LIKE :search_string 
            OR level LIKE :search_string
            ORDER BY created_at DESC
            ");

            $search_string = "%" . $search_string . "%";
            $stmt->bindParam(':search_string', $search_string, PDO::PARAM_STR);

            $stmt->execute();
            
            // $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $stmt;
        }catch(PDOException $e){
            echo "Error searching for the candidate.";
            return false;
        }
    }

    public function update() {
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
    }

    public function delete() {
        $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';
        $stmt = $this->conn->prepare($query);

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':id', $this->id);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }
}
?>
