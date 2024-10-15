<?php 
    // Include necessary files and initialize database connection
    include_once '../../config/Database.php';
    include_once '../../classes/Candidate.php';

    $database = new Database();
    $db = $database->connect();
    $candidate = new Candidate($db);

    // Check if an ID is provided in the URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        // Fetch candidate details
        $candidate->id = $id;
        $candidateDetails = $candidate->getCandidateDetailsById($id);
        
        if (!$candidateDetails) {
            echo "Candidate not found";
            exit;
        }
    } else {
        echo "No candidate ID provided";
        exit;
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidate Details</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-6">Candidate Details</h1>
        <form method="post" enctype="multipart/form-data" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <input type="hidden" name="candidate_action" value="update">
            <input type="hidden" name="id" value="<?php echo $candidateDetails['id']; ?>">
            <input type="hidden" name="existing_resume" value="<?php echo $candidateDetails['resume']; ?>">

            <div class="mb-4 flex flex-wrap -mx-2">
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="first_name">First Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="first_name" value="<?php echo $candidateDetails['first_name']; ?>" required>
                </div>
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="last_name">Last Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="last_name" value="<?php echo $candidateDetails['last_name']; ?>" required>
                </div>
            </div>

            <div class="mb-4 flex flex-wrap -mx-2">
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="middle_name">Middle Name</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" name="middle_name" value="<?php echo $candidateDetails['middle_name']; ?>">
                </div>
                <div class="w-full md:w-1/2 px-2 mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">Email</label>
                    <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="email" name="email" value="<?php echo $candidateDetails['email']; ?>" required>
                </div>
            </div>

            <!-- Add the rest of your form fields here, following the same structure -->

            <!-- <div class="flex items-center justify-between mt-6">
                <a href="dashboard/index.php?page=list" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Back</a>
            </div> -->
        </form>
    </div>
    <script src="<?php echo $script_path; ?>"></script>
</body>
</html>