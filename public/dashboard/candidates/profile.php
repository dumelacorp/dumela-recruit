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

// Error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);

$root_folder_path = dirname(__DIR__);
include_once $root_folder_path . '../../../config/Database.php';
include_once $root_folder_path . '../../../classes/Candidate.php';

require($root_folder_path . '../../../templates/header.php');

try {
    $database = new Database();
    $db = $database->connect();
    $candidate = new Candidate($db);

    // Get candidate details using email from session
    $email = $_SESSION['email'];
    $query = "SELECT * FROM candidates WHERE email = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$email]);
    $candidateDetails = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$candidateDetails) {
        throw new Exception("Candidate details not found");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Sanitize and validate input fields
        $fields = [
            'first_name', 'middle_name', 'last_name', 
            'country', 'state', 'city', 
            'job_title', 'level', 'rate', 'github'
        ];

        foreach ($fields as $field) {
            if (isset($_POST[$field])) {
                $candidate->$field = htmlspecialchars(trim($_POST[$field]));
            }
        }
        
        $candidate->email = $email;

        if ($candidate->updateDetails()) {
            $success_message = "Profile updated successfully!";
            // Refresh candidate details
            $stmt->execute([$email]);
            $candidateDetails = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $error_message = "Failed to update profile. Please try again.";
        }
    }
} catch (Exception $e) {
    $error_message = "An error occurred: " . $e->getMessage();
    error_log($e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - Dumela Recruitment</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex">
        <!-- Fixed Sidebar -->
        <div class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion fixed top-0 left-0 bottom-0 w-64 overflow-y-auto scrollbar-hide shadow-lg flex-shrink-0">
            <div class="p-4">
                <img src="dum_logo.png" alt="Logo" class="w-16 h-11 mb-4">
                <h1 class="text-2xl font-bold mb-6 text-white">DUMELA RECRUITMENT</h1>
            </div>
            <nav>
                <a href="dashboard.php" class="block py-2 px-4 font-bold text-white no-underline hover:bg-opacity-25 hover:bg-white transition-all duration-200">
                    Dashboard
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64">
            <!-- Top Navigation Bar -->
            <div class="bg-white shadow-md p-3 flex justify-between items-center sticky top-0 z-10">
                <div class="pl-4">
                    <h2 class="text-xl font-bold text-gray-800">Profile</h2>
                </div>

                <!-- Right side - Profile and Logout -->
                <div class="flex items-center space-x-4">
                    <a href="profile.php" class="text-gray-600 hover:text-gray-800">
                        <i class="fas fa-user-circle text-2xl"></i>
                    </a>
                    <a href="?action=logout" class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded transition duration-300 ease-in-out">
                        Logout
                    </a>
                </div>
            </div>

            <!-- Profile Content -->
            <div class="p-10">
                <h1 class="text-3xl font-bold mb-8">Edit Your Profile</h1>
                
                <?php if (isset($success_message)): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($success_message); ?></span>
                    </div>
                <?php endif; ?>

                <?php if (isset($error_message)): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
                    </div>
                <?php endif; ?>

                <form action="profile.php" method="POST" class="bg-white shadow-md rounded-lg p-6">
                    <!-- Email field (disabled) -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="mb-4">
                            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                            <input type="email" id="email" name="email" 
                                value="<?php echo htmlspecialchars($candidateDetails['email'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 bg-gray-100 leading-tight focus:outline-none focus:shadow-outline" 
                                disabled>
                        </div>

                        <div class="mb-4">
                            <label for="first_name" class="block text-gray-700 text-sm font-bold mb-2">First Name</label>
                            <input type="text" id="first_name" name="first_name" 
                                value="<?php echo htmlspecialchars($candidateDetails['first_name'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="middle_name" class="block text-gray-700 text-sm font-bold mb-2">Middle Name</label>
                            <input type="text" id="middle_name" name="middle_name" 
                                value="<?php echo htmlspecialchars($candidateDetails['middle_name'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="last_name" class="block text-gray-700 text-sm font-bold mb-2">Last Name</label>
                            <input type="text" id="last_name" name="last_name" 
                                value="<?php echo htmlspecialchars($candidateDetails['last_name'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="country" class="block text-gray-700 text-sm font-bold mb-2">Country</label>
                            <input type="text" id="country" name="country" 
                                value="<?php echo htmlspecialchars($candidateDetails['country'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="state" class="block text-gray-700 text-sm font-bold mb-2">State</label>
                            <input type="text" id="state" name="state" 
                                value="<?php echo htmlspecialchars($candidateDetails['state'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="city" class="block text-gray-700 text-sm font-bold mb-2">City</label>
                            <input type="text" id="city" name="city" 
                                value="<?php echo htmlspecialchars($candidateDetails['city'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="job_title" class="block text-gray-700 text-sm font-bold mb-2">Job Title</label>
                            <input type="text" id="job_title" name="job_title" 
                                value="<?php echo htmlspecialchars($candidateDetails['job_title'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="level" class="block text-gray-700 text-sm font-bold mb-2">Level</label>
                            <input type="text" id="level" name="level" 
                                value="<?php echo htmlspecialchars($candidateDetails['level'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="rate" class="block text-gray-700 text-sm font-bold mb-2">Rate</label>
                            <input type="text" id="rate" name="rate" 
                                value="<?php echo htmlspecialchars($candidateDetails['rate'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>

                        <div class="mb-4">
                            <label for="github" class="block text-gray-700 text-sm font-bold mb-2">GitHub</label>
                            <input type="text" id="github" name="github" 
                                value="<?php echo htmlspecialchars($candidateDetails['github'] ?? ''); ?>" 
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                        </div>
                    </div>

                    <div class="flex items-center justify-between mt-6">
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Profile
                        </button>
                        <a href="dashboard.php" class="text-blue-500 hover:text-blue-700 font-bold">
                            Back to Dashboard
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>