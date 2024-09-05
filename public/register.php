<?php

session_start();

if(!isset($_SESSION['user_id'])){
    header("location: login.php");
}

include_once '../config/Database.php';
include_once '../classes/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = $_POST['username'];

    if (!preg_match("/^[^@]+@dumelacorp\.com$/", $username)) {
        $error = "Username must be an email ending with @dumelacorp.com";
    }

    if (!isset($error) || $error == '') {
        $user->username = $_POST['username'];
        $user->password = $_POST['password'];

        if($user->register()) {
            echo 'User registered successfully!';
        } else {
            die("It got here!");
            echo 'User registration failed!';
        }
    } else {
        echo "<div class='alert alert-danger'>$error</div>";
        $timestamp = date('Y-m-d H:i:s'); 
        error_log("$timestamp: User Registration Failed! \n", 3, 'dashboard/error_log'); 
    }
}
?>

<?php require('../templates/header.php'); ?>
<body>

<div class="container py-4 my-4">
    <div class="row my-4">
        <img src="../assets/img/dum_logo.png" alt="Dumela Corp. Recruitment">
    </div>
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="card bg-dark text-white">
            <img class="card-img" src="../assets/img/recruit_register2.jpg" alt="Card image">
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <h2 class="mt-4 pb-2">Recruitment Portal Registration</h2>
            <form method="post">
                <div class="form-group mt-4">
                    <label>Email (Username)</label>
                    <input type="email" id="username" class="form-control" name="username" required>
                    <small id="emailHelp" class="form-text text-muted"></small>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <div class="input-group">
                        <input type="password" id="password" class="form-control" name="password" required>
                        <div class="input-group-append">
                            <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                                <i class="fas fa-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">Register</button>
            </form>
        </div>
    </div>
</div>


<!-- OLD CODE -->

<!-- <div class="container">
    <h2>Register</h2>
    <form method="post">

        <div class="form-group">
            <label>Email (Username)</label>
            <input type="email" id="username" class="form-control" name="username" placeholder="example@dumelacorp.com" required>
            <small id="emailHelp" class="form-text text-muted">Must end with @dumelacorp.com</small>
        </div>

        <div class="form-group">
            <label>Password</label>
            <div class="input-group">
                <input type="password" id="password" class="form-control" name="password" required>
                <div class="input-group-append">
                    <button type="button" class="btn btn-outline-secondary" onclick="togglePassword()">
                        <i class="fas fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Register</button>
    </form>
</div> -->
<script src="../assets/js/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
