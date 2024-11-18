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
    public $notes;
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

            if($stmt->rowCount() >= 1) {
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
            $query = 'UPDATE candidates 
                        SET first_name = :first_name, 
                            middle_name = :middle_name, 
                            last_name = :last_name, 
                            email = :email,
                            country = :country,
                            state = :state,
                            city = :city,
                            job_title = :job_title,
                            level = :level,
                            resume = :resume,
                            rate = :rate,
                            rate_period = :rate_period,
                            status = :status,
                            outsource_rate = :outsource_rate,
                            outsource_rate_period = :outsource_rate_period,
                            notes = :notes
                        WHERE id = :id';

            $stmt = $this->conn->prepare($query);

            // Bind the parameters
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
            $stmt->bindParam(':notes', $this->notes);
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

    public function updateDetails() {
        try {
            // Start transaction
            $this->conn->beginTransaction();
    
            $fields_to_update = array();
            $params = array();
    
            // Handle regular fields
            $fields = [
                'first_name', 'middle_name', 'last_name',
                'country', 'state', 'city',
                'job_title', 'level', 'rate'
            ];
    
            foreach ($fields as $field) {
                if (isset($this->$field)) {
                    $fields_to_update[] = "$field = :$field";
                    $params[":$field"] = $this->$field;
                }
            }
    
            // Handle resume upload if provided
            if (isset($_FILES['resume']) && $_FILES['resume']['error'] === 0) {
                $upload_dir = dirname(__DIR__) . '/uploads/';
                
                // Create uploads directory if it doesn't exist
                if (!file_exists($upload_dir)) {
                    mkdir($upload_dir, 0755, true);
                }
    
                // Get old resume filename if exists
                $stmt = $this->conn->prepare("SELECT resume FROM " . $this->table . " WHERE email = :email");
                $stmt->execute([':email' => $this->email]);
                $old_resume = $stmt->fetchColumn();
    
                // Generate unique filename
                $file_extension = strtolower(pathinfo($_FILES['resume']['name'], PATHINFO_EXTENSION));
                $unique_filename = uniqid('resume_') . '.' . $file_extension;
                $target_file = $upload_dir . $unique_filename;
    
                // Validate file type
                $allowed_types = ['pdf', 'doc', 'docx'];
                if (!in_array($file_extension, $allowed_types)) {
                    throw new Exception("Invalid file type. Only PDF, DOC, and DOCX files are allowed.");
                }
    
                // Upload new file
                if (move_uploaded_file($_FILES['resume']['tmp_name'], $target_file)) {
                    // Delete old resume if exists
                    if ($old_resume && file_exists($upload_dir . $old_resume)) {
                        unlink($upload_dir . $old_resume);
                    }
    
                    $fields_to_update[] = "resume = :resume";
                    $params[":resume"] = $unique_filename;
                } else {
                    throw new Exception("Failed to upload resume.");
                }
            }
    
            if (empty($fields_to_update)) {
                return false;
            }
    
            // Construct and execute the SQL query
            $sql = "UPDATE " . $this->table . " 
                    SET " . implode(", ", $fields_to_update) . " 
                    WHERE email = :email";
            $params[':email'] = $this->email;
    
            $stmt = $this->conn->prepare($sql);
            $result = $stmt->execute($params);
    
            // Commit transaction
            $this->conn->commit();
            return $result;
    
        } catch (Exception $e) {
            // Rollback transaction on error
            $this->conn->rollBack();
            error_log("Update failed: " . $e->getMessage());
            throw $e;
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
