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

// Check for redirection after successful sign-in
if (isset($_SESSION['redirect_after_login'])) {
    $redirect_url = $_SESSION['redirect_after_login'];
    unset($_SESSION['redirect_after_login']); // Clear the session variable
    header("Location: " . $redirect_url);
    exit();
}

$root_folder_path = dirname(__DIR__);
include_once $root_folder_path . '../../../config/Database.php';
include_once $root_folder_path . '../../../classes/Candidate.php';

require($root_folder_path . '../../../templates/header.php');

$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);

// Get candidate details using email from session
$email = $_SESSION['email'];
$query = "SELECT * FROM candidates WHERE email = ?";
$stmt = $db->prepare($query);
$stmt->execute([$email]);
$candidateDetails = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if candidate details were found
if (!$candidateDetails) {
    echo "Error: Candidate details not found";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
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
            <a href="#" class="block py-2 px-4 font-bold text-white no-underline nav-link transition-all duration-200">
                Dashboard
            </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 ml-64"> <!-- ml-64 matches sidebar width -->
            <!-- Top Navigation Bar -->
            <div class="bg-white shadow-md p-3 flex justify-between items-center sticky top-0 z-10">
                <div class="pl-4">
                    <h2 class="text-xl font-bold text-gray-800">Dashboard</h2>
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

            <!-- Dashboard Content -->
            <div class="p-10 flex-grow">
                <h1 class="text-3xl font-bold mb-8">Welcome, <?php echo htmlspecialchars($candidateDetails['first_name'] ?? 'User'); ?>!</h1>

                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Application Status</h2>
                    <div class="inline-block bg-blue-100 text-blue-800 py-1 px-3 rounded-full">
                        <?php echo htmlspecialchars($candidateDetails['status'] ?? 'Pending'); ?>
                    </div>
                </div>
                
                <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                    <h2 class="text-xl font-semibold mb-4">Your Details</h2>
                    <p class="mb-2"><strong class="font-medium">Email:</strong> <?php echo htmlspecialchars($candidateDetails['email'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">Last Name:</strong> <?php echo htmlspecialchars($candidateDetails['last_name'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">First Name:</strong> <?php echo htmlspecialchars($candidateDetails['first_name'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">Middle Name:</strong> <?php echo htmlspecialchars($candidateDetails['middle_name'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">Country:</strong> <?php echo htmlspecialchars($candidateDetails['country'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">State:</strong> <?php echo htmlspecialchars($candidateDetails['state'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">City:</strong> <?php echo htmlspecialchars($candidateDetails['city'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">Job Title:</strong> <?php echo htmlspecialchars($candidateDetails['job_title'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">Level:</strong> <?php echo htmlspecialchars($candidateDetails['level'] ?? 'N/A'); ?></p>
                    <p class="mb-2"><strong class="font-medium">Rate:</strong> <?php echo htmlspecialchars($candidateDetails['rate'] ?? 'N/A') . ' / ' . htmlspecialchars($candidateDetails['rate_period'] ?? 'N/A'); ?></p>
                </div>

                <!-- Add debug information if needed -->
                <?php if (isset($_SESSION['debug'])): ?>
                <!-- <div class="mt-4 p-4 bg-gray-100 rounded">
                    <pre><?php print_r($candidateDetails); ?></pre>
                </div> -->
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>