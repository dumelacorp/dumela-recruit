<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
    exit();
}

$root_folder_path = dirname(__DIR__);
include_once $root_folder_path . '../../../config/Database.php';
include_once $root_folder_path . '../../../classes/Candidate.php';

require($root_folder_path . '../../../templates/header.php');


$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);
$candidate->email = $_SESSION['email'];
$candidate->getDetails();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
        theme: {
            extend: {
                colors: {
                    secondary: '#4A5568', 
                },

            }
        }
    }
    </script>
</head>
<body class="bg-gray-100">

    <header class="bg-secondary text-white p-.5">
        <div class="container mx-auto flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex items-center justify-center py-4"> 
                    <img src="dum_logo.png" alt="Logo" class="w-16 h-11 mr-4"> 
                    <h1 class="text-2xl font-bold">DUMELA RECRUITMENT</h1> 
                </div>   
            </div>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Welcome, <?php echo htmlspecialchars($candidate->first_name); ?>!</h1>
        
        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Your Details</h2>
            <p class="mb-2"><strong class="font-medium">Email:</strong> <?php echo htmlspecialchars($candidate->email); ?></p>
            <p class="mb-2"><strong class="font-medium">First Name:</strong> <?php echo htmlspecialchars($candidate->first_name); ?></p>
        </div>

        <div class="bg-white shadow-md rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold mb-4">Application Status</h2>
            <div class="inline-block bg-blue-100 text-blue-800 py-1 px-3 rounded-full">
                <?php echo htmlspecialchars($candidate->status); ?>
            </div>
        </div>

        <div class="mt-8">
        <a href="?action=logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                Logout
            </a>
        </div>
    </div>
</body>
</html>