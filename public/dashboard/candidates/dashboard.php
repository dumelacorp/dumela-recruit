<?php
session_start();

if (isset($_GET['action']) && $_GET['action'] == 'logout') {
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit();
}

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
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Sidebar -->
        <div class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion w-64 h-screen shadow-lg">
            <div class="p-4">
                <img src="dum_logo.png" alt="Logo" class="w-16 h-11 mb-4">
                <h1 class="text-2xl font-bold mb-6 text-white">DUMELA RECRUITMENT</h1>
            </div>
            <nav>
                <a href="#" class="block py-2 px-4 font-bold text-white hover:bg-blue-700 transition duration-200">
                    Dashboard
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation Bar -->
            <div class="bg-white shadow-md p-4 flex justify-end items-center">
                <div class="flex items-center space-x-4">
                    <a href="profile.php" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-user-circle text-2xl"></i>
                    </a>
                    <a href="?action=logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                        Logout
                    </a>
                </div>
            </div>

            <!-- Dashboard Content -->
            <div class="p-10 flex-grow">
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
            </div>
        </div>
    </div>
</body>
</html>