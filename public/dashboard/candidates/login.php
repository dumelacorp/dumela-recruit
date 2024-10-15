<?php
// login.php

define('GOOGLE_CLIENT_ID', '1098996921334-1j8n71k6hvbnk6pp8qak0lfbfj5vh7ri.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/dumela-recruit/public/dashboard/candidates/googleauth.php');

session_start();
require_once '../../../vendor/autoload.php';
//require_once 'config.php';

$client = new Google_Client();
$client->setClientId(GOOGLE_CLIENT_ID);
$client->setClientSecret(GOOGLE_CLIENT_SECRET);
$client->setRedirectUri(GOOGLE_REDIRECT_URI);
$client->addScope("email");
$client->addScope("profile");

$authUrl = $client->createAuthUrl();

// Handle regular login form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "All fields are required.";
    } else {
        // Database connection
        $conn = new mysqli("localhost", "root", "", "dumelaco_recruitify");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Check if user exists and verify password
        $stmt = $conn->prepare("SELECT id, password FROM candidate_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid email or password.";
            }
        } else {
            $error = "Invalid email or password.";
        }

        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login or Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full px-6 py-8">
                    <h2 class="text-2xl font-bold text-gray-700 text-center mb-6">Login or Sign Up</h2>
                    <?php
                    if (isset($_SESSION['error'])) {
                        echo '<p class="text-red-500 text-center mb-4">' . $_SESSION['error'] . '</p>';
                        unset($_SESSION['error']);
                    }
                    if (isset($error)) {
                        echo '<p class="text-red-500 text-center mb-4">' . $error . '</p>';
                    }
                    ?>
                    <form method="POST" action="">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">
                                Email
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" type="email" name="email" required>
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                                Password
                            </label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" id="password" type="password" name="password" required>
                        </div>
                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                Login
                            </button>
                        </div>
                    </form>
                    <div class="mt-4">
                        <p class="text-center mb-2">Or</p>
                        <a href="<?php echo $authUrl; ?>" class="block text-center bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Sign In with Google
                        </a>
                    </div>
                    <div class="mt-4 text-center">
                        <a href="signup.php" class="text-blue-500 hover:text-blue-700">Don't have an account? Sign up here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>