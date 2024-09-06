<?php 
    // session_start();

    $page = 'candidates/delete.php';

    include_once '../../config/Database.php';
    include_once '../../classes/Candidate.php';

    $database = new Database();
    $db = $database->connect();
    $candidate = new Candidate($db);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $row = $candidate->getCandidateDetailsById($id);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['candidate_action'])) {
            if($_POST['candidate_action'] == 'delete'){
                $candidate->id = $_POST['id'];
                
                try{
                    $candidate->delete();
                    echo("<meta http-equiv='refresh' content='.5'>");
                    $_SESSION['alert']['type'] = 'success';
                    $_SESSION['alert']['message'] = "Candidate data has been successfully deleted.";
                    echo "<script>window.location.href='../dashboard/index.php?page=list';</script>";
                    exit;
                }catch(PDOException $e){
                    $_SESSION['alert']['type'] = 'error';
                    $_SESSION['alert']['message'] = "Something went wrong. We couldn't delete the data.";
                    $timestamp = date('Y-m-d H:i:s'); 
                    error_log("$timestamp: $e \n", 3, 'error_log'); 
                    error_log("$timestamp: Something went wrong while deleting a candidate! \n", 3, 'error_log'); 
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
        <input type="hidden" name="candidate_action" value="delete">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="existing_resume" value="<?php echo $row['resume']; ?>">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']; ?>" readonly>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']; ?>" readonly>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="<?php echo $row['middle_name']; ?>" readonly>
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
                    <label>Job Title</label>
                    <input type="text" name="job_title" class="form-control" value="<?php echo $row['job_title']; ?>" readonly>
                </div>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control" readonly>
                        <option value="<?php echo $row['level']; ?>"><?php echo $row['level']; ?></option>
                    </select>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Resume</label>
                    <input type="file" name="resume" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="form-row">        
                <div class="form-group col-md-3 col-sm-6">
                    <label>Rate</label>
                    <input type="text" name="rate" class="form-control" value="<?php echo $row['rate']; ?>" readonly>
                </div>
                <div class="form-group col-md-3 col-sm-6">
                    <label>Per</label>
                    <select name="rate_period" class="form-control" readonly>
                        <option value="<?php echo $row['rate_period']; ?>"><?php echo $row['rate_period']; ?></option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>Status</label>
                    <select name="status" class="form-control" readonly>
                        <option value="<?php echo $row['status']; ?>"><?php echo $row['status']; ?></option>
                    </select>
                </div>
        </div>

        <div class="form-row">
                <div class="form-group col-md-3 col-sm-6">
                    <label>Outsource Rate</label>
                    <input type="text" name="outsource_rate" class="form-control" value="<?php echo $row['outsource_rate']; ?>" readonly>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label>Per</label>
                    <select name="outsource_rate_period" class="form-control" readonly>
                      <option value="<?php echo $row['outsource_rate_period']; ?>"><?php echo $row['outsource_rate_period']; ?></option>
                    </select>
                </div>
            <!-- </div> -->
            <div class="col-md-6 col-sm-12">
                <br><br>
                <label class='text-danger'><strong>Do you still wish to delete this data? Click the <em>Delete</em> button if you are sure.</strong></label>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 cl-sm-12">
                <div class="form-group">
                    <br>
                        <a href="?page=list" class="btn btn-primary btn-block" role="button">Back</a>
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