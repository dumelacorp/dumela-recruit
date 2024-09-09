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
        try{
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

            $stmt->bindParam(':company_name', $this->company_name);
            $stmt->bindParam(':email', $this->email);
            $stmt->bindParam(':country', $this->country);
            $stmt->bindParam(':state', $this->state);
            $stmt->bindParam(':city', $this->city);
            $stmt->bindParam(':specialization', $this->specialization);
            $stmt->bindParam(':contact', $this->contact);
            $stmt->bindParam(':contact_person', $this->contact_person);

            if($stmt->execute()) {
                return true;
            }

            return false;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while creating a company in the database! \n", 3, 'error_log'); 
        }
    }

    public function read($limit = 10, $offset = 0) {
        try{
            $query = "SELECT * FROM companies ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt;
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while reading a company from the database! \n", 3, 'error_log'); 
        }
    }

    public function getTotalCompanies(){
        try{
            $query = 'SELECT COUNT(*) AS total FROM ' . $this->table;
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $total_candidates = $stmt->fetch(PDO::FETCH_ASSOC);
            return $total_candidates['total'];
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while getting total number of companies from the database! \n", 3, 'error_log');
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
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while getting total number of companies for search from the database! \n", 3, 'error_log'); 
        }
    }

    public function getCompanyDetailsById($id) {
        
        try {
            $stmt = $this->conn->prepare("SELECT * FROM " . $this->table . " WHERE id = :id");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $candidate = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $candidate;
        } catch (PDOException $e) {
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while getting company details by their Id from the database! \n", 3, 'error_log');
        }
    }

    public function findCompaniesByMultipleParameters($search_string, $limit = 10, $offset = 0){
        try{

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
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while searching for company with multiple paramaters in the database! \n", 3, 'error_log'); 
        }
    }

    public function update() {
        try{
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
        }catch(PDOException $e){
            $timestamp = date('Y-m-d H:i:s'); 
            error_log("$timestamp: $e \n", 3, 'error_log'); 
            error_log("$timestamp: Something went wrong while updating company in the database! \n", 3, 'error_log'); 
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
            error_log("$timestamp: Something went wrong while removing company from the database! \n", 3, 'error_log'); 
        }
    }
}
?>
