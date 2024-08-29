<?php
class Company {
    private $conn;
    private $table = 'companies';

    public $id;
    public $company_name;
    public $email;
    public $country;
    public $state;
    public $city;
    public $specialization;
    public $contact;
    public $contact_person;

    public $search_string;

    public $created_at;
    

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = 'INSERT INTO ' . $this->table . ' SET
            company_name = :company_name,
            email = :email,
            country = :country,
            state = :state,
            city = :city,
            specialization = :specialization,
            contact = :contact,
            contact_person = :contact_person';

        $stmt = $this->conn->prepare($query);

        $this->company_name = ucwords(htmlspecialchars(strip_tags($this->company_name)));
        $this->email = ucwords(htmlspecialchars(strip_tags($this->email)));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->state = ucwords(htmlspecialchars(strip_tags($this->state)));
        $this->city = ucwords(htmlspecialchars(strip_tags($this->city)));
        $this->specialization = ucwords(htmlspecialchars(strip_tags($this->specialization)));
        $this->contact = htmlspecialchars(strip_tags($this->contact));
        $this->contact_person = htmlspecialchars(strip_tags($this->contact_person));

        // $this->rate = htmlspecialchars(strip_tags($this->rate));
        // $this->rate_period = htmlspecialchars(strip_tags($this->rate_period));
        // $this->status = htmlspecialchars(strip_tags($this->status));
        // $this->status = $this->status;
        // $this->outsource_rate = htmlspecialchars(strip_tags($this->outsource_rate));
        // $this->outsource_rate_period = htmlspecialchars(strip_tags($this->outsource_rate_period));

        // $this->resume = htmlspecialchars(strip_tags($this->resume));

        $stmt->bindParam(':company_name', $this->company_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':specialization', $this->specialization);
        $stmt->bindParam(':contact', $this->contact);
        $stmt->bindParam(':contact_person', $this->contact_person);

        // $stmt->bindParam(':rate', $this->rate);
        // $stmt->bindParam(':rate_period', $this->rate_period);
        // $stmt->bindParam(':status', $this->status);
        // $stmt->bindParam(':outsource_rate', $this->outsource_rate);
        // $stmt->bindParam(':outsource_rate_period', $this->outsource_rate_period);

        // $stmt->bindParam(':resume', $this->resume);

        if($stmt->execute()) {
            return true;
        }

        return false;
    }

    // public function read() {
    //     $query = 'SELECT * FROM ' . $this->table . ' ORDER BY created_at DESC';
    //     $stmt = $this->conn->prepare($query);
    //     $stmt->execute();
    //     return $stmt;
    // }

    public function read($limit = 10, $offset = 0) {
        // $query = 'SELECT * FROM ' . $this->table . ' LIMIT :limit OFFSET :offset ORDER BY created_at DESC';
        $query = "SELECT * FROM companies ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt;
    }

    public function getTotalCompanies(){
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

    public function getTotalCompaniesForSearch($search_string) {
        try {
            $query = "SELECT COUNT(*) as total FROM companies WHERE 
                company_name LIKE :search OR 
                email LIKE :search OR 
                country LIKE :search OR 
                city LIKE :search OR 
                specialization LIKE :search";
            $stmt = $this->conn->prepare($query);
            $search_param = "%{$search_string}%";
            $stmt->bindParam(':search', $search_param);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $row['total'];
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
            return false;
        }
    }

    public function getCompanyDetailsById($id) {
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

    public function findCompaniesByMultipleParameters($search_string, $limit = 10, $offset = 0){
        try{
            // $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE first_name LIKE :search_string 
            // OR last_name LIKE :search_string 
            // OR country LIKE :search_string 
            // OR job_title LIKE :search_string 
            // OR level LIKE :search_string
            // ORDER BY created_at DESC
            // ");

            // $search_string = "%" . $search_string . "%";
            // $stmt->bindParam(':search_string', $search_string, PDO::PARAM_STR);

            // $stmt->execute();
            
            // // $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // return $stmt;

            $query = "SELECT * FROM companies WHERE 
                company_name LIKE :search OR 
                email LIKE :search OR 
                country LIKE :search OR 
                city LIKE :search OR 
                specialization LIKE :search
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
            echo "Error searching for the company.";
            return false;
        }
    }

    public function update() {
        $query = 'UPDATE ' . $this->table . ' SET
            company_name = :company_name,
            email = :email,
            country = :country,
            state = :state,
            city = :city,
            specialization = :specialization,
            contact = :contact,
            contact_person = :contact_person
            WHERE id = :id';

        $stmt = $this->conn->prepare($query);

        $this->company_name = ucwords(htmlspecialchars(strip_tags($this->company_name)));
        $this->email = ucwords(htmlspecialchars(strip_tags($this->email)));
        $this->country = htmlspecialchars(strip_tags($this->country));
        $this->state = ucwords(htmlspecialchars(strip_tags($this->state)));
        $this->city = ucwords(htmlspecialchars(strip_tags($this->city)));
        $this->specialization = ucwords(htmlspecialchars(strip_tags($this->specialization)));
        $this->contact = htmlspecialchars(strip_tags($this->contact));
        $this->contact_person = htmlspecialchars(strip_tags($this->contact_person));

        $this->id = htmlspecialchars(strip_tags($this->id));

        $stmt->bindParam(':company_name', $this->company_name);
        $stmt->bindParam(':email', $this->email);
        $stmt->bindParam(':country', $this->country);
        $stmt->bindParam(':state', $this->state);
        $stmt->bindParam(':city', $this->city);
        $stmt->bindParam(':specialization', $this->specialization);
        $stmt->bindParam(':contact', $this->contact);
        $stmt->bindParam(':contact_person', $this->contact_person);

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
