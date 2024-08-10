<?php
    session_start();
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    include_once '../../config/Database.php';
    include_once '../../classes/Candidate.php';

    $database = new Database();
    $db = $database->connect();
    $candidate = new Candidate($db);


    
    $total_candidates = $candidate->getTotalCandidates();
    
    $candidates_per_page = 10;

    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_text'])) {
        $search_string = $_POST['search_text'];
        $candidates = $candidate->findCandidatesByMultipleParameters($search_string);
    }else{
        $candidates = $candidate->read();
    }

    // if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['candidate_action']) && $_POST['candidate_action'] == 'delete') {
    //     die("We are preparing to delete the candidate.");
    // }


    // if(isset($_POST['candidate_action'])){
    //     if($_POST['candidate_action'] == 'delete'){
    //         die("Deleting candidate with id ". $_POST['id']);
    //         // $_SESSION['page'] = 'candidates/update.php';
    //         // include 'candidates/update.php';
    //     }
    // }


?>
<!-- Topbar Search -->
<div class="form-row">
    <div class="col">
        <form class="navbar-search border border-primary rounded-right rounded-left" method='post'>
            <div class="input-group">
                <input type="text" id="search" name="search_text" class="form-control bg-light border-0 small" placeholder="Search for candidate by first name, last name, country, job title, or level" 
                value="<?php 
                        if(isset($_POST['search_text'])){
                            echo $_POST['search_text'];
                        }
                    ?>"
                    aria-label="Search" aria-describedby="basic-addon2">
                <div class="input-group-append">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search fa-sm"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<h3>Candidates</h3>
    <table class="table">
        <thead>
            <tr>
                <!-- <th>ID</th> -->
                <th>First Name</th>
                <!-- <th>Middle Name</th> -->
                <th>Last Name</th>
                <!-- <th>Email</th> -->
                <th>Country</th>
                <th>State</th>
                <!-- <th>City</th> -->
                <th>Job Title</th>
                <th>Level</th>
                <th>Resume</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($row = $candidates->fetch(PDO::FETCH_ASSOC)): ?>
            <tr>
                <!-- <td><?php // echo $row['id']; ?></td> -->
                <td><?php echo $row['first_name']; ?></td>
                <!-- <td><?php // echo $row['middle_name']; ?></td> -->
                <td><?php echo $row['last_name']; ?></td>
                <!-- <td><?php // echo $row['email']; ?></td> -->
                <td><?php echo $row['country']; ?></td>
                <td><?php echo $row['state']; ?></td>
                <!-- <td><?php // echo $row['city']; ?></td> -->
                <td><?php echo $row['job_title']; ?></td>
                <td><?php echo $row['level']; ?></td>
                <td><a href="../../uploads/<?php echo $row['resume']; ?>" target="_blank">View</a></td>
                <td>
                    <form method="get" enctype="multipart/form-data" style="display:inline-block;">
                        <input type="hidden" name="candidate_action" value="update">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="existing_resume" value="<?php echo $row['resume']; ?>">
                        <button type="submit" class="btn btn-outline-info">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                            </svg> Update
                        </button>
                    <!-- onclick="loadUpdateForm(<?php // echo $row['id']; ?>)" -->

                    </form>

                    <!-- DELETE -->

                    <!-- <form method="post" style="display:inline-block;">
                        <input type="hidden" name="candidate_action" value="delete">
                        <input type="hidden" name="id" value="<?php // echo $row['id']; ?>">
                        <button type="submit" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">
                        <a type="button" class="btn btn-danger" href="index.php?page=delete&id=<?php // echo $row['id'];?>" data-toggle="modal" data-target="#deleteModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                            <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg> Delete
                        </a>

                    </form> -->
                    <!-- </button> -->

                    <!-- <form method="get" style="display:inline-block;">
                        <input type="hidden" name="candidate_action" value="delete">
                        <input type="hidden" name="id" value="<?php // echo $row['id']; ?>">
                        <button type="button" class="btn btn-outline-danger" data-toggle="modal" data-target="#deleteModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3-fill" viewBox="0 0 16 16">
                                <path d="M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5"/>
                            </svg> Delete
                        </button>
                    </form> -->

                    <form method="get" enctype="multipart/form-data" style="display:inline-block;">
                        <input type="hidden" name="candidate_action" value="delete">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="existing_resume" value="<?php echo $row['resume']; ?>">
                        <button type="submit" class="btn btn-outline-danger">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pen-fill" viewBox="0 0 16 16">
                                <path d="m13.498.795.149-.149a1.207 1.207 0 1 1 1.707 1.708l-.149.148a1.5 1.5 0 0 1-.059 2.059L4.854 14.854a.5.5 0 0 1-.233.131l-4 1a.5.5 0 0 1-.606-.606l1-4a.5.5 0 0 1 .131-.232l9.642-9.642a.5.5 0 0 0-.642.056L6.854 4.854a.5.5 0 1 1-.708-.708L9.44.854A1.5 1.5 0 0 1 11.5.796a1.5 1.5 0 0 1 1.998-.001"/>
                            </svg> Delete
                        </button>
                    <!-- onclick="loadUpdateForm(<?php // echo $row['id']; ?>)" -->

                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <!-- <form method="post" style="display:inline-block;"> -->
                        <!-- <input type="hidden" name="candidate_action" value="delete"> -->
                        <!-- <input type="hidden" name="id" value="<?php // echo $_GET['id']; ?>"> -->
    <!-- <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Sure you want to delete?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Delete" below if you are ready to <strong>delete</strong> the Candidate.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a role="button" class="btn btn-danger" href="index.php?page=delete&id=">Delete</a>
                </div>
            </div>
        </div>
    </div>
    </form> -->


    <!-- FUTURE Modal DELETE CONFIRMATION -->

    <!-- <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Confirm Delete</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="submitForm()">Delete</button>
            </div>
            </div>
        </div>
    </div> -->

<!-- FUTURE MODAL DELETE -->
    <!-- <script>
        function submitForm() {
            document.querySelector('form').submit();
        }
    </script> -->


<!-- FUTURE AJAX CALL ON SEARCH -->

<!-- <script>
    $(document).ready(function() {
        $('#search').on('input', function() {
            let query = $(this).val();

            $.ajax({
                url: 'search.php',
                type: 'GET',
                data: { search_string: query },
                success: function(data) {
                    $('#results').html(data);
                },
                error: function() {
                    $('#results').html('<p>An error occurred</p>');
                }
            });
        });
    });
</script> -->