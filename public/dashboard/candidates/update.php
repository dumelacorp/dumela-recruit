<?php 
    // session_start();

    $page = 'candidates/update.php';

    include_once '../../config/Database.php';
    include_once '../../classes/Candidate.php';

    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $candidate = new Candidate($db);

    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $row = $candidate->getCandidateDetailsById($id);
    }

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        if(isset($_POST['candidate_action'])) {
            if($_POST['candidate_action'] == 'update'){
                $candidate->id = $_POST['id'];
                $candidate->first_name = $_POST['first_name'];
                $candidate->middle_name = $_POST['middle_name'];
                $candidate->last_name = $_POST['last_name'];
                $candidate->email = $_POST['email'];
                $candidate->country = $_POST['country'];
                $candidate->state = $_POST['state'];
                $candidate->city = $_POST['city'];
                $candidate->job_title = $_POST['job_title'];
                $candidate->level = $_POST['level'];
                $candidate->resume = $_FILES['resume']['name'];

                $candidate->rate = $_POST['rate'];
                $candidate->rate_period = $_POST['rate_period'];
                $candidate->status = $_POST['status'];
                $candidate->outsource_rate = $_POST['outsource_rate'];
                $candidate->outsource_rate_period = $_POST['outsource_rate_period'];
    
                $target_dir = "../../uploads/";
                // Handle file upload
                if ($_FILES['resume']['name']) {
                    $target_file = $target_dir . basename($_FILES["resume"]["name"]);
                    move_uploaded_file($_FILES["resume"]["tmp_name"], $target_file);
                } else {
                    $candidate->resume = $_POST['existing_resume'];
                }
                
                try{
                    $candidate->update();
                    echo("<meta http-equiv='refresh' content='.5'>");
                    $_SESSION['alert']['type'] = 'success';
                    $_SESSION['alert']['message'] = "Candidate data has been updated.";
                }catch(PDOException $e){
                    $_SESSION['alert']['type'] = 'error';
                    $_SESSION['alert']['message'] = "Something went wrong. Couldn't update the data.";
                    $timestamp = date('Y-m-d H:i:s'); 
                    error_log("$timestamp: $e \n", 3, 'error_log'); 
                    error_log("$timestamp: Something went wrong while updating a candidate! \n", 3, 'error_log'); 
                }
            }
        }
    }
    

?>


    <form method="post" enctype="multipart/form-data">
        <input type="hidden" name="candidate_action" value="update">
        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
        <input type="hidden" name="existing_resume" value="<?php echo $row['resume']; ?>">

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control" value="<?php echo $row['first_name']; ?>" required>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control" value="<?php echo $row['last_name']; ?>" required>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Middle Name</label>
                    <input type="text" name="middle_name" class="form-control" value="<?php echo $row['middle_name']; ?>">
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control" value="<?php echo $row['email']; ?>" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label for='country'>Country</label>
                    <select id="country" name="country" class="form-control" required>
                        <option value="<?php echo $row['country']; ?>"><?php echo $row['country']; ?></option>
                        <option value="">Select a country</option>
                        <option value="Afghanistan">Afghanistan</option>
                        <option value="Albania">Albania</option>
                        <option value="Algeria">Algeria</option>
                        <option value="Andorra">Andorra</option>
                        <option value="Angola">Angola</option>
                        <option value="Antigua and Barbuda">Antigua and Barbuda</option>
                        <option value="Argentina">Argentina</option>
                        <option value="Armenia">Armenia</option>
                        <option value="Australia">Australia</option>
                        <option value="Austria">Austria</option>
                        <option value="Azerbaijan">Azerbaijan</option>
                        <option value="Bahamas">Bahamas</option>
                        <option value="Bahrain">Bahrain</option>
                        <option value="Bangladesh">Bangladesh</option>
                        <option value="Barbados">Barbados</option>
                        <option value="Belarus">Belarus</option>
                        <option value="Belgium">Belgium</option>
                        <option value="Belize">Belize</option>
                        <option value="Benin">Benin</option>
                        <option value="Bhutan">Bhutan</option>
                        <option value="Bolivia">Bolivia</option>
                        <option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option>
                        <option value="Botswana">Botswana</option>
                        <option value="Brazil">Brazil</option>
                        <option value="Brunei">Brunei</option>
                        <option value="Bulgaria">Bulgaria</option>
                        <option value="Burkina Faso">Burkina Faso</option>
                        <option value="Burundi">Burundi</option>
                        <option value="Cabo Verde">Cabo Verde</option>
                        <option value="Cambodia">Cambodia</option>
                        <option value="Cameroon">Cameroon</option>
                        <option value="Canada">Canada</option>
                        <option value="Central African Republic">Central African Republic</option>
                        <option value="Chad">Chad</option>
                        <option value="Chile">Chile</option>
                        <option value="China">China</option>
                        <option value="Colombia">Colombia</option>
                        <option value="Comoros">Comoros</option>
                        <option value="Congo">Congo</option>
                        <option value="Costa Rica">Costa Rica</option>
                        <option value="Croatia">Croatia</option>
                        <option value="Cuba">Cuba</option>
                        <option value="Cyprus">Cyprus</option>
                        <option value="Czech Republic">Czech Republic</option>
                        <option value="Denmark">Denmark</option>
                        <option value="Djibouti">Djibouti</option>
                        <option value="Dominica">Dominica</option>
                        <option value="Dominican Republic">Dominican Republic</option>
                        <option value="East Timor">East Timor</option>
                        <option value="Ecuador">Ecuador</option>
                        <option value="Egypt">Egypt</option>
                        <option value="El Salvador">El Salvador</option>
                        <option value="Equatorial Guinea">Equatorial Guinea</option>
                        <option value="Eritrea">Eritrea</option>
                        <option value="Estonia">Estonia</option>
                        <option value="Eswatini">Eswatini</option>
                        <option value="Ethiopia">Ethiopia</option>
                        <option value="Fiji">Fiji</option>
                        <option value="Finland">Finland</option>
                        <option value="France">France</option>
                        <option value="Gabon">Gabon</option>
                        <option value="Gambia">Gambia</option>
                        <option value="Georgia">Georgia</option>
                        <option value="Germany">Germany</option>
                        <option value="Ghana">Ghana</option>
                        <option value="Greece">Greece</option>
                        <option value="Grenada">Grenada</option>
                        <option value="Guatemala">Guatemala</option>
                        <option value="Guinea">Guinea</option>
                        <option value="Guinea-Bissau">Guinea-Bissau</option>
                        <option value="Guyana">Guyana</option>
                        <option value="Haiti">Haiti</option>
                        <option value="Honduras">Honduras</option>
                        <option value="Hungary">Hungary</option>
                        <option value="Iceland">Iceland</option>
                        <option value="India">India</option>
                        <option value="Indonesia">Indonesia</option>
                        <option value="Iran">Iran</option>
                        <option value="Iraq">Iraq</option>
                        <option value="Ireland">Ireland</option>
                        <option value="Israel">Israel</option>
                        <option value="Italy">Italy</option>
                        <option value="Ivory Coast">Ivory Coast</option>
                        <option value="Jamaica">Jamaica</option>
                        <option value="Japan">Japan</option>
                        <option value="Jordan">Jordan</option>
                        <option value="Kazakhstan">Kazakhstan</option>
                        <option value="Kenya">Kenya</option>
                        <option value="Kiribati">Kiribati</option>
                        <option value="Kuwait">Kuwait</option>
                        <option value="Kyrgyzstan">Kyrgyzstan</option>
                        <option value="Laos">Laos</option>
                        <option value="Latvia">Latvia</option>
                        <option value="Lebanon">Lebanon</option>
                        <option value="Lesotho">Lesotho</option>
                        <option value="Liberia">Liberia</option>
                        <option value="Libya">Libya</option>
                        <option value="Liechtenstein">Liechtenstein</option>
                        <option value="Lithuania">Lithuania</option>
                        <option value="Luxembourg">Luxembourg</option>
                        <option value="Madagascar">Madagascar</option>
                        <option value="Malawi">Malawi</option>
                        <option value="Malaysia">Malaysia</option>
                        <option value="Maldives">Maldives</option>
                        <option value="Mali">Mali</option>
                        <option value="Malta">Malta</option>
                        <option value="Marshall Islands">Marshall Islands</option>
                        <option value="Mauritania">Mauritania</option>
                        <option value="Mauritius">Mauritius</option>
                        <option value="Mexico">Mexico</option>
                        <option value="Micronesia">Micronesia</option>
                        <option value="Moldova">Moldova</option>
                        <option value="Monaco">Monaco</option>
                        <option value="Mongolia">Mongolia</option>
                        <option value="Montenegro">Montenegro</option>
                        <option value="Morocco">Morocco</option>
                        <option value="Mozambique">Mozambique</option>
                        <option value="Myanmar">Myanmar</option>
                        <option value="Namibia">Namibia</option>
                        <option value="Nauru">Nauru</option>
                        <option value="Nepal">Nepal</option>
                        <option value="Netherlands">Netherlands</option>
                        <option value="New Zealand">New Zealand</option>
                        <option value="Nicaragua">Nicaragua</option>
                        <option value="Niger">Niger</option>
                        <option value="Nigeria">Nigeria</option>
                        <option value="North Korea">North Korea</option>
                        <option value="North Macedonia">North Macedonia</option>
                        <option value="Norway">Norway</option>
                        <option value="Oman">Oman</option>
                        <option value="Pakistan">Pakistan</option>
                        <option value="Palau">Palau</option>
                        <option value="Panama">Panama</option>
                        <option value="Papua New Guinea">Papua New Guinea</option>
                        <option value="Paraguay">Paraguay</option>
                        <option value="Peru">Peru</option>
                        <option value="Philippines">Philippines</option>
                        <option value="Poland">Poland</option>
                        <option value="Portugal">Portugal</option>
                        <option value="Qatar">Qatar</option>
                        <option value="Romania">Romania</option>
                        <option value="Russia">Russia</option>
                        <option value="Rwanda">Rwanda</option>
                        <option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
                        <option value="Saint Lucia">Saint Lucia</option>
                        <option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
                        <option value="Samoa">Samoa</option>
                        <option value="San Marino">San Marino</option>
                        <option value="Sao Tome and Principe">Sao Tome and Principe</option>
                        <option value="Saudi Arabia">Saudi Arabia</option>
                        <option value="Senegal">Senegal</option>
                        <option value="Serbia">Serbia</option>
                        <option value="Seychelles">Seychelles</option>
                        <option value="Sierra Leone">Sierra Leone</option>
                        <option value="Singapore">Singapore</option>
                        <option value="Slovakia">Slovakia</option>
                        <option value="Slovenia">Slovenia</option>
                        <option value="Solomon Islands">Solomon Islands</option>
                        <option value="Somalia">Somalia</option>
                        <option value="South Africa">South Africa</option>
                        <option value="South Korea">South Korea</option>
                        <option value="South Sudan">South Sudan</option>
                        <option value="Spain">Spain</option>
                        <option value="Sri Lanka">Sri Lanka</option>
                        <option value="Sudan">Sudan</option>
                        <option value="Suriname">Suriname</option>
                        <option value="Sweden">Sweden</option>
                        <option value="Switzerland">Switzerland</option>
                        <option value="Syria">Syria</option>
                        <option value="Taiwan">Taiwan</option>
                        <option value="Tajikistan">Tajikistan</option>
                        <option value="Tanzania">Tanzania</option>
                        <option value="Thailand">Thailand</option>
                        <option value="Togo">Togo</option>
                        <option value="Tonga">Tonga</option>
                        <option value="Trinidad and Tobago">Trinidad and Tobago</option>
                        <option value="Tunisia">Tunisia</option>
                        <option value="Turkey">Turkey</option>
                        <option value="Turkmenistan">Turkmenistan</option>
                        <option value="Tuvalu">Tuvalu</option>
                        <option value="Uganda">Uganda</option>
                        <option value="Ukraine">Ukraine</option>
                        <option value="United Arab Emirates">United Arab Emirates</option>
                        <option value="United Kingdom">United Kingdom</option>
                        <option value="United States">United States</option>
                        <option value="Uruguay">Uruguay</option>
                        <option value="Uzbekistan">Uzbekistan</option>
                        <option value="Vanuatu">Vanuatu</option>
                        <option value="Vatican City">Vatican City</option>
                        <option value="Venezuela">Venezuela</option>
                        <option value="Vietnam">Vietnam</option>
                        <option value="Yemen">Yemen</option>
                        <option value="Zambia">Zambia</option>
                        <option value="Zimbabwe">Zimbabwe</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" class="form-control" value="<?php echo $row['state']; ?>" required>
                </div>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" class="form-control" value="<?php echo $row['city']; ?>" required>
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Job Title</label>
                    <input type="text" name="job_title" class="form-control" value="<?php echo $row['job_title']; ?>" required>
                </div>
            </div>
        </div> 

        <div class="row">
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label>Level</label>
                    <select name="level" class="form-control" required>
                        <option value="Entry" <?php echo ($row['level'] == 'Entry') ? 'selected' : ''; ?>>Entry</option>
                        <option value="Junior" <?php echo ($row['level'] == 'Junior') ? 'selected' : ''; ?>>Junior</option>
                        <option value="Intermediate" <?php echo ($row['level'] == 'Intermediate') ? 'selected' : ''; ?>>Intermediate</option>
                        <option value="Senior" <?php echo ($row['level'] == 'Senior') ? 'selected' : ''; ?>>Senior</option>
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
            <!-- <div class="col-md-6 col-sm-12"> -->           
                <div class="form-group col-md-3 col-sm-6">
                    <label>Rate (NGN)</label>
                    <input type="text" name="rate" class="form-control" value="<?php echo $row['rate']; ?>" required>
                </div>
                <div class="form-group col-md-3 col-sm-6">
                    <label>Per</label>
                    <select name="rate_period" class="form-control" required>
                        <option value="Hour" <?php echo ($row['rate_period'] == 'Hour') ? 'selected' : ''; ?>>Hour</option>
                        <option value="Day" <?php echo ($row['rate_period'] == 'Day') ? 'selected' : ''; ?>>Day</option>
                        <option value="Week" <?php echo ($row['rate_period'] == 'Week') ? 'selected' : ''; ?>>Week</option>
                        <option value="Month" <?php echo ($row['rate_period'] == 'Month') ? 'selected' : ''; ?>>Month</option>
                        <option value="Year" <?php echo ($row['rate_period'] == 'Year') ? 'selected' : ''; ?>>Year</option>
                    </select>
                </div>
                <div class="form-group col-md-6 col-sm-12">
                    <label>Status</label>
                    <select name="status" class="form-control" required>
                        <option value="Not Interviewed" <?php echo ($row['status'] == 'Not Interviewed') ? 'selected' : ''; ?>>Not Interviewed</option>
                        <option value="Interviewed (Not Selected)" <?php echo ($row['status'] == 'Interviewed (Not Selected)') ? 'selected' : ''; ?>>Interviewed (Not Selected)</option>
                        <option value="Interviewed (Selected)" <?php echo ($row['status'] == 'Interviewed (Selected)') ? 'selected' : ''; ?>>Interviewed (Selected)</option>
                    </select>
                </div>
        </div>

        <div class="form-row">
            <!-- <div class="col-md-6 col-sm-12"> -->
                <div class="form-group col-md-3 col-sm-6">
                    <label>Outsource Rate (NGN)</label>
                    <input type="text" name="outsource_rate" class="form-control" value="<?php echo $row['outsource_rate']; ?>" required>
                </div>

                <div class="form-group col-md-3 col-sm-6">
                    <label>Per</label>
                    <select name="outsource_rate_period" class="form-control" required>
                        <option value="Entry" <?php echo ($row['outsource_rate_period'] == 'Hour') ? 'selected' : ''; ?>>Hour</option>
                        <option value="Day" <?php echo ($row['outsource_rate_period'] == 'Day') ? 'selected' : ''; ?>>Day</option>
                        <option value="Week" <?php echo ($row['outsource_rate_period'] == 'Week') ? 'selected' : ''; ?>>Week</option>
                        <option value="Month" <?php echo ($row['outsource_rate_period'] == 'Month') ? 'selected' : ''; ?>>Month</option>
                        <option value="Year" <?php echo ($row['rate_period'] == 'Year') ? 'selected' : ''; ?>>Year</option>
                    </select>
                </div>
            <!-- </div> -->
            <div class="col-md-6 col-sm-12">

            </div>
        </div>

        <div class="row">
            <div class="col-md-6 cl-sm-12">
                <div class="form-group">
                    <br>
                    <!-- <button type="button"> -->
                        <a href="?page=list" class="btn btn-primary btn-block" role="button">Back</a>
                    <!-- </button> -->
                </div>
            </div>
            <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-info btn-block">Update</button>
                </div>
            </div>
        </div>
        
    </form>