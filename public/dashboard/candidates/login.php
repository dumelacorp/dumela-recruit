<?php
session_start();

// Check if Google Sign-In is configured
$googleSignInConfigured = false;

// Google Sign-In configuration (only if needed)
if ($googleSignInConfigured) {
    require_once '../../../vendor/autoload.php';

    define('GOOGLE_CLIENT_ID', '1098996921334-1j8n71k6hvbnk6pp8qak0lfbfj5vh7ri.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET', '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');
    define('GOOGLE_REDIRECT_URI', 'http://localhost/dumela-recruit/public/dashboard/candidates/googleauth.php');

    $client = new Google_Client();
    $client->setClientId(GOOGLE_CLIENT_ID);
    $client->setClientSecret(GOOGLE_CLIENT_SECRET);
    $client->setRedirectUri(GOOGLE_REDIRECT_URI);
    $client->addScope("email");
    $client->addScope("profile");

    $authUrl = $client->createAuthUrl();
}

$email = '';
if (isset($_SESSION['login_email'])) {
    $email = $_SESSION['login_email'];
    unset($_SESSION['login_email']); // Clear it after using
}

$error = "";

// Handle Google Sign-In
if ($googleSignInConfigured && isset($_GET['code'])) {
    $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
    if (!isset($token['error'])) {
        $client->setAccessToken($token['access_token']);
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = $google_account_info->email;
        $name = $google_account_info->name;

        $_SESSION['user_id'] = 1; 
        $_SESSION['email'] = $email;
        $_SESSION['name'] = $name;
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Error authenticating with Google. Please try again.";
    }
}

// Handle regular login
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (empty($email) || empty($password)) {
        $error = "Email and password are required.";
    } else {
        $conn = new mysqli("localhost", "root", "", "dumelaco_recruitify");

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $stmt = $conn->prepare("SELECT id, name, password FROM candidate_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $user['name'];
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
    <title>Login - Dumela Recruitment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .card-img {
            height: 100%;
            object-fit: cover;
        }
        .bg-gradient-primary {
            background: linear-gradient(180deg, #4e73df 10%, #224abe 100%);
        }
        .google-btn {
            background: #fff;
            color: #757575;
            border: 1px solid #ddd;
            transition: all 0.3s ease;
        }
        .google-btn:hover {
            background: #f1f1f1;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Logo Section -->
        <div class="text-center mb-8">
            <img src="../assets/img/dum_logo.png" alt="Dumela Corp. Recruitment" class="h-16 mx-auto">
        </div>

        <!-- Main Content -->
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="p-8">
                <h2 class="text-3xl font-bold text-gray-800 mb-8 text-center">Login</h2>

                <?php if (!empty($error)): ?>
                    <div class="mb-4 p-4 rounded bg-red-100 text-red-700">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email Address</label>
                        <div class="mt-1">
                            <input type="email" id="email" name="email" 
                                value="<?php echo htmlspecialchars($email); ?>"
                                required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        <div class="mt-1 relative">
                            <input type="password" id="password" name="password" required
                                class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="togglePassword()" 
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <i class="fas fa-eye text-gray-400" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <button type="submit" 
                                class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gradient-primary hover:opacity-90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                            Sign In
                        </button>
                    </div>
                </form>

                <?php if ($googleSignInConfigured): ?>
                    <div class="mt-6">
                        <div class="relative">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-300"></div>
                            </div>
                            <div class="relative flex justify-center text-sm">
                                <span class="px-2 bg-white text-gray-500">Or continue with</span>
                            </div>
                        </div>

                        <div class="mt-6">
                            <a href="<?php echo htmlspecialchars($authUrl); ?>" 
                            class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <img class="h-5 w-5 mr-2" src="https://www.svgrepo.com/show/475656/google-color.svg" alt="Google">
                                Sign in with Google
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="text-center mt-4 mb-4">
                <a href="signup.php" class="text-sm text-blue-600 hover:text-blue-500">
                    Don't have an account? Sign up!
                </a>
            </div>

        </div>

    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('togglePasswordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Initialize toastr
        toastr.options = {
            "closeButton": true,
            "progressBar": true,
            "positionClass": "toast-top-right",
            "timeOut": "3000"
        };

        <?php if (!empty($error)): ?>
            toastr.error('<?php echo addslashes($error); ?>');
        <?php endif; ?>
    </script>
</body>
</html>