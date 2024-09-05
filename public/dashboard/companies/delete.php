<?php 
    // session_start();

    $page = 'companies/delete.php';

    include_once '../../config/Database.php';
    include_once '../../classes/Company.php';

    $database = new Database();
    $db = $database->connect();
    $company = new Company($db);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $row = $company->getCompanyDetailsById($id);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['company_action'])) {
            if($_POST['company_action'] == 'delete'){
                $company->id = $_POST['id'];
                
                try{
                    $company->delete();
                    echo("<meta http-equiv='refresh' content='.5'>");
                    $_SESSION['alert']['type'] = 'success';
                    $_SESSION['alert']['message'] = "Company data has been successfully deleted.";
                    echo "<script>window.location.href='../dashboard/index.php?company=list';</script>";
                    exit;
                }catch(PDOException $e){
                    $_SESSION['alert']['type'] = 'error';
                    $_SESSION['alert']['message'] = "Something went wrong. We couldn't delete the data.";
                    $timestamp = date('Y-m-d H:i:s'); 
                    error_log("$timestamp: Something went wrong while deleting a company! \n", 3, 'error_log'); 
                }
            }
        }
    }
    

?>

<div class="alert alert-danger alert-dismissible fade show" role="alert">
  <strong>You were redirected here because we want you to be sure about this action</strong> You <strong>CANNOT</strong> undo the changes you make here.
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>

    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="company_action" value="delete">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Company Name</label>
                    <input type="text" name="company_name" class="form-control" value="<?php echo $row['company_name']; ?>" readonly>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" readonly>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for='country'>Country</label>
                    <select id="country" name="country" class="form-control" readonly>
                        <option value="<?php echo $row['country']; ?>"><?php echo $row['country']; ?></option>
                    </select>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" class="form-control" value="<?php echo $row['state']; ?>" readonly>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="<?php echo $row['city']; ?>" readonly>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" class="form-control" value="<?php echo $row['specialization']; ?>" readonly>
                </div>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Contact</label>
                    <select name="contact" class="form-control" readonly>
                        <option value="<?php echo $row['contact']; ?>"><?php echo $row['contact']; ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Contact Person</label>
                    <select name="contact_person" class="form-control" readonly>
                        <option value="<?php echo $row['contact_person']; ?>"><?php echo $row['contact_person']; ?></option>
                    </select>
                </div>
            </div>
        </div>
        

        <div class="form-row">
            <div class="col-md-6 col-sm-12">
                <br><br>
                <label class='text-danger'><strong>Do you still wish to delete this data? Click the <em>Delete</em> button if you are sure.</strong></label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 cl-sm-12">
                <div class="form-group">
                    <br>
                        <a href="?company=list" class="btn btn-primary btn-block" role="button">Back</a>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-danger btn-block">Delete</button>
                </div>
            </div>
        </div>
        
    </form>