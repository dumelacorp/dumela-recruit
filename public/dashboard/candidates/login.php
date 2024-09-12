<?php
session_start();

$root_folder_path = dirname(__DIR__); 
include_once $root_folder_path . '../../../config/Database.php';
include_once $root_folder_path . '../../../classes/Candidate.php';

$database = new Database();
$db = $database->connect();
$candidate = new Candidate($db);


$root_path = $_SERVER['DOCUMENT_ROOT']; 

//$image_path = $root_path . '/assets/img/dumela_logo.webp'; 

$image_path = dirname(__DIR__); 

// Check if we're processing a form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $candidate->email = $_POST['email'];

    if ($candidate->emailExists()) {
        $_SESSION['email'] = $candidate->email;
        $_SESSION['alert']['type'] = 'success';
        $_SESSION['alert']['message'] = "You're now logged in.";
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $_SESSION['alert']['type'] = 'error';
        $_SESSION['alert']['message'] = "Email not found. Please check your email or register.";
        $timestamp = date('Y-m-d H:i:s');  
        error_log("$timestamp: Email not found for candidate login attempt: {$candidate->email} \n", 3, 'error_log');
        
        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

// Check if user is already logged in
if (isset($_SESSION['email'])) {
    header("Location: dashboard.php");
    exit();
}

// Display alert if set
if(isset($_SESSION['alert'])) {
    $alert_type = $_SESSION['alert']['type'];
    $alert_message = $_SESSION['alert']['message'];
    unset($_SESSION['alert']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Login</title>
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
<body class="bg-gray-80">
    <!-- Responsive Header -->
    <header class="bg-secondary text-white p-2">
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
        <div class="max-w-md mx-auto">
            <?php if(isset($alert_type) && isset($alert_message)): ?>
                <div class="mb-4 p-4 rounded <?php echo $alert_type === 'error' ? 'bg-red-100 text-red-700' : 'bg-green-100 text-green-700'; ?>">
                    <?php echo $alert_message; ?>
                </div>
            <?php endif; ?>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6 text-center">Candidate Login</h2>
                <form method="post"> 
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email:</label>
                        <input type="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="email" name="email" required>
                    </div> 
                    <button type="submit" class="bg-secondary hover:bg-secondary-dark text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full transition duration-300">
                        Login
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>