<?php
session_start();

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate->first_name = $_POST['first_name'];
    $candidate->last_name = $_POST['last_name'];
    // Add more fields as necessary

    if ($candidate->updateDetails()) {
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Failed to update profile. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
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
                <a href="dashboard.php" class="block py-2 px-4 font-bold text-white hover:bg-blue-700 transition duration-200">
                    Dashboard
                </a>
                <a href="#" class="block py-2 px-4 font-bold text-white bg-blue-700">
                    Profile
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Navigation Bar -->
            <div class="bg-white shadow-md p-4 flex justify-end items-center">
                <div class="flex items-center space-x-4">
                    <a href="#" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-user-circle text-2xl"></i>
                    </a>
                    <a href="dashboard.php?action=logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                        Logout
                    </a>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="p-10 flex-grow">
                <h1 class="text-3xl font-bold mb-8">Edit Your Profile</h1>
                
                <?php if (isset($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $success_message; ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo $error_message; ?></span>
                    </div>
                <?php endif; ?>

                <form action="profile.php" method="POST" class="bg-white shadow-md rounded-lg p-6">
                    <div class="mb-4">
                        <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                        <input type="text" id="first_name" name="first_name" value="<?php echo htmlspecialchars($candidate->first_name); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                        <input type="text" id="last_name" name="last_name" value="<?php echo htmlspecialchars($candidate->last_name); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                    </div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                        <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($candidate->email); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" disabled>
                    </div>
                    <!-- Add more fields as necessary -->
                    <div class="flex items-center justify-between">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>