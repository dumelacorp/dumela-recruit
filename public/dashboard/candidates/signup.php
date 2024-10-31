<?php
// Start session at the very beginning of the file
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Function to log debug information
function debug_log($message) {
    error_log(date('[Y-m-d H:i:s] ') . $message . "\n", 3, 'debug.log');
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    debug_log("Received signup request for email: " . $email);

    if (empty($name) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif ($password !== $confirm_password) {
        $error = "Passwords do not match.";
    } elseif (strlen($password) < 8) {
        $error = "Password must be at least 8 characters long.";
    } else {
        try {
            $conn = new mysqli("localhost", "root", "", "dumelaco_recruitify");

            if ($conn->connect_error) {
                debug_log("Database connection failed: " . $conn->connect_error);
                throw new Exception("Connection failed: " . $conn->connect_error);
            }

            debug_log("Database connection successful");

            // Check if email already exists
            $stmt = $conn->prepare("SELECT id FROM candidate_users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $error = "Email already exists.";
                debug_log("Email already exists: " . $email);
            } else {
                // Hash the password
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                debug_log("Password hashed successfully");

                // Insert new user
                $stmt = $conn->prepare("INSERT INTO candidate_users (name, email, password) VALUES (?, ?, ?)");
                $stmt->bind_param("sss", $name, $email, $hashed_password);

                if ($stmt->execute()) {
                    debug_log("User inserted successfully. ID: " . $stmt->insert_id);
                    
                    // Store email in session and ensure session is written
                    $_SESSION['signup_email'] = $email;
                    session_write_close();
                    
                    debug_log("Redirecting to upload.php with email: " . $email);
                    
                    // Make sure there's no output before redirect
                    ob_clean();
                    header("Location: upload.php");
                    exit();
                } else {
                    throw new Exception("Error inserting user: " . $stmt->error);
                }
            }
            $stmt->close();
            $conn->close();
        } catch (Exception $e) {
            $error = "An error occurred. Please try again.";
            debug_log("Error: " . $e->getMessage());
        }
    }
}

// Debug session data
debug_log("Current session data: " . print_r($_SESSION, true));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white rounded-lg overflow-hidden md:max-w-lg">
            <div class="md:flex">
                <div class="w-full px-6 py-8">
                    <h2 class="text-2xl font-bold text-gray-700 text-center mb-6">Sign Up</h2>
                    <?php if (!empty($error)): ?>
                        <p class="text-red-500 text-center mb-4"><?php echo htmlspecialchars($error); ?></p>
                    <?php endif; ?>
                    <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Name</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="name" type="text" name="name" required 
                                   value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="email" type="email" name="email" required 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="password" type="password" name="password" required minlength="8">
                        </div>
                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2" for="confirm_password">Confirm Password</label>
                            <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline" 
                                   id="confirm_password" type="password" name="confirm_password" required minlength="8">
                        </div>
                        <div class="flex items-center justify-between">
                            <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full" 
                                    type="submit">Sign Up</button>
                        </div>
                    </form>
                    <div class="mt-4 text-center">
                        <a href="login.php" class="text-blue-500 hover:text-blue-700">Already have an account? Login here</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>