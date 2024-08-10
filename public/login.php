<?php
session_start();
include_once '../config/Database.php';
include_once '../classes/User.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];

    if($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        // $_SESSION['page'] = 'candidates/register.php';
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => "You're now logged in."
        ];
        header('Location: dashboard/index.php?page=list');
    } else {
        $_SESSION['alert'] = [
            'type' => 'error',
            'message' => "Log in failed."
        ];
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
            <h2 class="mt-4 pb-2">Recruitment Portal Login</h2>
            <form method="post">
                <div class="form-group mt-4">
                    <label>Username</label>
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

                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</div>
<script src="../assets/js/script.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</body>
</html>
