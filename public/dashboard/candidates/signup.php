<?php
session_start();

define('GOOGLE_CLIENT_ID', '1098996921334-1j8n71k6hvbnk6pp8qak0lfbfj5vh7ri.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/dumela-recruit/public/dashboard/candidates/googleauth.php');

$root_folder_path = dirname(__DIR__); 
include_once $root_folder_path . '../../../config/Database.php';
include_once $root_folder_path . '../../../classes/Candidate.php';

// Add this line to include the Google API Client
require_once $root_folder_path . '../../../vendor/autoload.php';

$script_path = $root_folder_path . '../../../assets/js/script.js';
$image = $root_folder_path . "../../../assets/img/recruit_register2.jpg";

$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

$image_path = dirname(__DIR__); 

// Google Client configuration
$client = new Google\Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri('http://localhost/dumela-recruit/public/dashboard/candidates/googleauth.php');
$client->addScope('email');
$client->addScope('profile');

// Generate Google Sign-In URL
$google_login_url = $client->createAuthUrl();

// Rest of your existing code...

// Check if we're processing a form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ... Your existing login logic ...
}

// Check if user is already logged in
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit();
}

// Display alert if set
if(isset($_SESSION['alert'])) {
    $alert_type = $_SESSION['alert']['type'];
    $alert_message = $_SESSION['alert']['message'];
    unset($_SESSION['alert']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Add Google Sign-In API -->
    <script src="https://apis.google.com/js/platform.js" async defer></script>
    <meta name="google-signin-client_id" content="YOUR_GOOGLE_CLIENT_ID">
</head>

<?php require( $root_folder_path . '../../../templates/header.php'); ?>
<body>
<div class="container py-4 my-4">
    <div class="row my-4">
        <img src="../assets/img/dum_logo.png" alt="Dumela Corp. Recruitment">
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card bg-dark text-white">
            <img class="card-img" src="../../../assets/img/recruit_register2.jpg"  alt="Card image">
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <h2 class="mt-4 pb-2">Candidate Login</h2>
            <form method="post">
                <?php if(isset($alert_type) && isset($alert_message)): ?>
                    <div class="mb-4 p-4 rounded <?php echo $alert_type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                        <?php echo $alert_message; ?>
                    </div>
                <?php endif; ?>

                <div class="form-group mt-4">
                    <label>Email:</label>
                    <input type="email" id="email" name="email" class="form-control" name="username" required>
                    <small id="emailHelp" class="form-text text-muted"></small>
                </div>

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
            
            <!-- Add Google Sign-In button -->
            <div class="mt-4">
                <a href="<?php echo $google_login_url; ?>" class="btn btn-danger">
                    <i class="fab fa-google"></i> Sign in with Google
                </a>
            </div>
        </div>
    </div>
</div>
<script src="<?php echo $script_path; ?>"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>