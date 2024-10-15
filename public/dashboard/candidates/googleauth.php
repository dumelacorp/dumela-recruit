<?php
// googleauth.php

define('GOOGLE_CLIENT_ID', '1098996921334-1j8n71k6hvbnk6pp8qak0lfbfj5vh7ri.apps.googleusercontent.com');
define('GOOGLE_CLIENT_SECRET', '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');
define('GOOGLE_REDIRECT_URI', 'http://localhost/dumela-recruit/public/dashboard/candidates/googleauth.php');

session_start();
require_once '../../../vendor/autoload.php';

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

$client = new Google_Client([
    'client_id' => GOOGLE_CLIENT_ID,
    'client_secret' => GOOGLE_CLIENT_SECRET,
    'redirect_uri' => GOOGLE_REDIRECT_URI,
]);

$client->addScope('email');
$client->addScope('profile');

// Set SSL verification to false (only for debugging, not recommended for production)
$client->setHttpClient(new GuzzleHttp\Client(['verify' => false]));

if (isset($_GET['code'])) {
    try {
        $token = $client->fetchAccessTokenWithAuthCode($_GET['code']);
        
        if (isset($token['error'])) {
            throw new Exception('Error fetching access token: ' . $token['error_description']);
        }
        
        $client->setAccessToken($token);

        // Get user information
        $google_oauth = new Google_Service_Oauth2($client);
        $google_account_info = $google_oauth->userinfo->get();
        $email = $google_account_info->email;
        $google_id = $google_account_info->id;

        // Database connection
        $conn = new mysqli("localhost", "root", "", "dumelaco_recruitify");

        if ($conn->connect_error) {
            throw new Exception("Connection failed: " . $conn->connect_error);
        }

        // Check if user exists
        $stmt = $conn->prepare("SELECT id FROM candidate_users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User exists, log them in
            $user = $result->fetch_assoc();
            $_SESSION['user_id'] = $user['id'];
        } else {
            // Create new user
            $stmt = $conn->prepare("INSERT INTO candidate_users (email, google_id) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $google_id);

            if ($stmt->execute()) {
                $_SESSION['user_id'] = $stmt->insert_id;
            } else {
                throw new Exception("Error: " . $stmt->error);
            }
        }

        $stmt->close();
        $conn->close();

        // Redirect to dashboard
        header("Location: dashboard.php");
        exit();
    } catch (Exception $e) {
        // Log the error and display it (for debugging purposes)
        error_log($e->getMessage());
        echo "An error occurred: " . $e->getMessage();
        exit();
    }
} else {
    // If no code is present, initiate the Google login flow
    $auth_url = $client->createAuthUrl();
    header("Location: " . filter_var($auth_url, FILTER_SANITIZE_URL));
    exit();
}
?>