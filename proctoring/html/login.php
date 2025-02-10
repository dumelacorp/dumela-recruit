<?php
session_start();

$root_folder_path = dirname(__DIR__); 
include_once $root_folder_path . '../../config/Database.php';

$database = new Database();
$db = $database->connect();

// Initialize error message variable
$error = '';

if($db){
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve and sanitize input
        $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
        $password = $_POST['password'];

        // Validate input
        if (empty($email) || empty($password)) {
            $error = 'Email and Password are required!';
        } else {
            try {
                // Prepare the SQL statement
                $stmt = $db->prepare("SELECT * FROM `candidate_users` WHERE email = :email");
                $stmt->execute(['email' => $email]);
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verify user and password
                if ($user && password_verify($password, $user['password'])) {
                    // Set session variables
                    $_SESSION['user_id'] = $user['id']; 
                    $_SESSION['email'] = $user['email'];

                    // Redirect to the instructions page
                    header('Location: ../html/instructions/instruction1.html');
                    exit;
                } else {
                    // Authentication failed
                    $error = 'Invalid email or password!';
                }
            } catch (PDOException $e) {
                // Handle database errors
                $error = 'An error occurred while accessing the database!';
                echo $e;
            }
        }
    }
}else{
    echo "Database not connected";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script type="text/javascript" src="https://appleid.cdn-apple.com/appleauth/static/jsapi/appleid/1/en_US/appleid.auth.js"></script>

    <!-- <link rel="stylesheet" href="/LoginPage/css/style.css"> -->
    <link rel="stylesheet" href = "../css/style.css">
    
</head>
<body>
    <div> 
        <div>
            <a href="../html/login.html"><img class="logo" src="../img/dum_logo.png" alt="Dumela Corp" width="148px"; height="48px"></a>
        </div> 
        <div class="image"><img class="background-img rotate-image" src="../img/protoring.jpg" alt=""></div>
    </div>
    <div id="login-area">
        <div class="label">
            <div class="text-wrapper">Login</div>
        </div>

        <!-- Login Form -->
        <div class="row">
            <form action="login.php" method="POST" class="form-group">
                <div class="row">
                    <input type="text" name="email" id="email" class="form__input" placeholder="Email">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="row">
                    <input type="password" name="password" id="password" class="form__input" placeholder="Password">
                    <i class='bx bxs-lock'></i>
                </div>
                <div class="row">
                    <button id="submit" type="submit" class="login">Login</button>
                </div>
                <!-- Display error message if any -->
                <?php if ($error): ?>
                    <div class="error-message" style="color: red;"><?php echo htmlspecialchars($error); ?></div>
                    <?php $error = ''; ?>
                <?php endif; ?>
            </form>
        </div>
    </div>
</body>
<!-- <body>
    <div> 
        <div>
            <a href="../html/login.html"><img class="logo" src="../img/dum_logo.png" alt="Dumela Corp" width="148px"; height="48px"></a>
        </div> 
        <div class="image"><img class="background-img rotate-image" src="../img/protoring.jpg" alt=""></div>
    </div>
    
    <div id="login-area">
        <div class="label">
            <div class="text-wrapper">Login</div>
        </div>

        <div class="row">
            <form control="" class="form-group">
                <div class="row">
                    <input type="text" name="email" id="email" class="form__input" placeholder="email">
                    <i class='bx bxs-user'></i>
                </div>
                <div class="row">
                    <span class="fa fa-lock"></span>
                    <input type="password" name="password" id="password" class="form__input" placeholder="Password">
                    <i class='bx bxs-lock'></i>
                </div>
                <div class="row">
                    <a href="../html/login.html"><label class="forgot-password" for="forgot-password">Forgot Password?</label></a>
                </div>
                <div class="row">
                    <input type="submit" value="Submit" class="btn">
                    <button id="submit" type="submit" class="login"><a class="login-button" href="../html/instructions/instruction1.html">Login</a></button>
                </div>
                <div class="row">
                    <div class="sign-in">
                        <h1>Or Sign in With</h1>

                        <button id="google-signin" type="submit" class="google"><i class='bx bxl-google'></i>  Sign in with Google</button>
                        
                        <button id="appleid-signin" type="submit" class="apple"><i class='bx bxl-apple'></i>  Sign in with Apple</button>

                    </div>
                </div>
            </form>
		</div>
		<div class="row">
    </div>

</body> -->
</html>