<?php

session_start();
require_once '../../../vendor/autoload.php';
require_once './google-config.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// $client = new Google_Client([
//     'client_id' => GOOGLE_CLIENT_ID,
//     'client_secret' => GOOGLE_CLIENT_SECRET,
//     'redirect_uri' => GOOGLE_REDIRECT_URI,
// ]);

// $client->addScope('email');
// $client->addScope('profile');

// Set SSL verification to false (only for debugging, not recommended for production)
// $client->setHttpClient(new GuzzleHttp\Client(['verify' => false]));

try {
    $client = getGoogleClient();
    
    if (isset($_GET['code'])) {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (!isset($token['error'])) {
            $client->setAccessToken($token['access_token']);
            
            // Get user information
            $google_oauth = new Google_Service_Oauth2($client);
            $google_account_info = $google_oauth->userinfo->get();
            
            $email = $google_account_info->email;
            $name = $google_account_info->name;
            
            // Connect to database
            $database = new Database();
            $db = $database->connect();
            
            // Check if user exists
            $stmt = $db->prepare("SELECT id, name FROM candidate_users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                // User exists - log them in
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $email;
                $_SESSION['name'] = $user['name'];
                header("Location: dashboard.php");
                exit();
            } else {
                // New user - create account
                $hashed_password = password_hash(bin2hex(random_bytes(10)), PASSWORD_DEFAULT); // Random secure password
                
                $stmt = $db->prepare("INSERT INTO candidate_users (name, email, password) VALUES (?, ?, ?)");
                if ($stmt->execute([$name, $email, $hashed_password])) {
                    $_SESSION['signup_email'] = $email;
                    header("Location: upload.php");
                    exit();
                } else {
                    throw new Exception("Failed to create user account");
                }
            }
        } else {
            throw new Exception("Error getting access token");
        }
    }
} catch (Exception $e) {
    $_SESSION['error'] = "Authentication failed: " . $e->getMessage();
    header("Location: login.php");
    exit();
}
?>