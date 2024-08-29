<?php
    session_start();

    // $page = 'candidates/register.php';
    //$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;

    //$current_page = $_GET['page'] ?? '';
    $current_page = isset($_GET['page']) ? $_GET['page'] : 'list';
    $current_company_page = isset($_GET['company']) ? $_GET['company'] : 'list';
    //$page = isset($_GET['page']) ? $_GET['page'] : 'list';
    $is_candidates_page = in_array($current_page, ['new', 'list']);
    $is_candidates_company_page = in_array($current_company_page, ['new', 'list']);

    $is_user_action_page = ($current_page === 'register');

    //$page = isset($_GET['page']) ? $_GET['page'] : 'list';

    // if ($current_page === 'list') {
    //     include 'candidates/list.php';
    // } 

    if(isset($_SESSION['page'])) {
        $page = $_SESSION['page'];
    }
    if(isset($_SESSION['company'])) {
        $page = $_SESSION['company'];
    }
    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    include_once '../../config/Database.php';
    include_once '../../classes/User.php';

    $database = new Database();
    $db = $database->connect();
    $user = new User($db);

?>

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Dumela Recruitments - Dashboard</title>

    <link rel="icon" sizes="192x192" href="../../assets/img/dum-favicon.png" type="image/png"/>
    <link rel="shortcut icon" href="../../assets/img/dum-favicon.png" type="image/png"/>
    <link rel="apple-touch-icon" href="../../assets/img/dum-favicon.png" type="image/png"/>
    <!-- Custom fonts for this template-->
    <link href="assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link rel="stylesheet" href="assets/css/style.css">
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="">
                <div class="sidebar-brand-icon">
                    <!-- <i class="fas fa-laugh-wink"></i> -->
                    <img src="assets/img/dumela_logo.webp" alt="Logo" width=60 height=35>
                </div>
                <div class="sidebar-brand-text mx-3">Dumela Recruitment</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Candidates
            </div>

            <?php
                // Determine which section is active
                $active_section = '';
                if (isset($_GET['page']) && ($_GET['page'] == 'new' || $_GET['page'] == 'list')) {
                    $active_section = 'candidates';
                } elseif (isset($_GET['company']) && ($_GET['company'] == 'new' || $_GET['company'] == 'list')) {
                    $active_section = 'companies';
                } 

                // Preserve active section when clicking "New" under Companies
                if (isset($_GET['company']) && $_GET['company'] == 'new') {
                    $active_section = 'companies';
                }
            ?>

            <li class="nav-item">
                <a class="nav-link <?php echo $active_section === 'candidates' ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseCandidates"
                aria-expanded="<?php echo $active_section === 'candidates' ? 'true' : 'false'; ?>" aria-controls="collapseCandidates">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Candidates</span>
                </a>
                <div id="collapseCandidates" class="collapse <?php echo $active_section === 'candidates' ? 'show' : ''; ?>" aria-labelledby="headingCandidates" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Candidates Actions:</h6>
                        <a class="collapse-item <?php echo isset($_GET['page']) && $_GET['page'] === 'new' ? 'active' : ''; ?>" href="?page=new">New</a>
                        <a class="collapse-item <?php echo isset($_GET['page']) && $_GET['page'] === 'list' ? 'active' : ''; ?>" href="?page=list">List</a>
                    </div>
                </div>
            </li>

            <li class="nav-item">
                <a class="nav-link <?php echo $active_section === 'companies' ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseCompanies"
                aria-expanded="<?php echo $active_section === 'companies' ? 'true' : 'false'; ?>" aria-controls="collapseCompanies">
                    <i class="fas fa-fw fa-building"></i>
                    <span>Companies</span>
                </a>
                <div id="collapseCompanies" class="collapse <?php echo $active_section === 'companies' ? 'show' : ''; ?>" aria-labelledby="headingCompanies" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Companies Actions:</h6>
                        <a class="collapse-item <?php echo isset($_GET['company']) && $_GET['company'] === 'new' ? 'active' : ''; ?>" href="?company=new">New</a>
                        <a class="collapse-item <?php echo isset($_GET['company']) && $_GET['company'] === 'list' ? 'active' : ''; ?>" href="?company=list">List</a>
                    </div>
                </div>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Users
            </div>

            <!-- Nav Item - Pages Collapse Menu -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePages"
                    aria-expanded="true" aria-controls="collapsePages">
                    <i class="fas fa-fw fa-user-circle"></i>
                    <span>Users</span>
                </a>
                <div id="collapsePages" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">User Actions:</h6>
                        <!-- <a class="collapse-item" href="login.html">Login</a> -->
                        <a class="collapse-item" href="../register.php" target="_blank">Register</a>
                    </div>
                </div>
            </li>


            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <h1 class="h3 mb-0 text-gray-800">Dashboard</h1>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-menu dropdown-menu-right p-3 shadow animated--grow-in"
                                aria-labelledby="searchDropdown">
                                <form class="form-inline mr-auto w-100 navbar-search">
                                    <div class="input-group">
                                        <input type="text" class="form-control bg-light border-0 small"
                                            placeholder="Search for..." aria-label="Search"
                                            aria-describedby="basic-addon2">
                                        <div class="input-group-append">
                                            <button class="btn btn-primary" type="button">
                                                <i class="fas fa-search fa-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </li>

                        <!-- Nav Item - Alerts -->


                        <!-- Nav Item - Messages -->

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION['username']; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="assets/img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->

                <!-- Begin Page Content -->
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <div class="d-sm-flex align-items-center justify-content-between mb-4">
                        <?php
                            if(isset($_SESSION['alert']))
                            {
                                $message = $_SESSION['alert']['message'];
                                if ($_SESSION['alert']['type'] == 'error'){
                                    echo('<div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Oops! </strong>' . $message . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>');
                                } else {
                                    echo('<div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <strong>Yay! </strong>' . $message . '
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    </div>');
                                }
                                unset($_SESSION['alert']);
                            }
                        ?>
                    </div>

                    <!-- Content Row -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="main-content">
                                <?php
                                    if (!isset($_GET['id']) && !isset($_GET['page']) && !isset($_GET['company'])){
                                        include $page;
                                    }
                                    if (isset($_GET['id'])){
                                        $id = $_GET['id'];

                                        if (isset($_GET['candidate_action'])) {
                                            switch ($_GET['candidate_action']) {
                                                case 'update':
                                                    include 'candidates/update.php';
                                                    break;
                                                case 'delete':
                                                    include 'candidates/delete.php';
                                                    break;
                                                default:
                                                    echo "Invalid candidate action";
                                            }
                                        } elseif (isset($_GET['company_action'])) {
                                            switch ($_GET['company_action']) {
                                                case 'update':
                                                    include 'companies/update.php';
                                                    break;
                                                case 'delete':
                                                    include 'companies/delete.php';
                                                    break;
                                                default:
                                                    echo "Invalid company action";
                                            }
                                        } else {
                                            echo "No action specified";
                                        }

                                    } else {
                                        echo "No ID specified";
                                    }


                                    if (isset($_GET['page'])){
                                        if ($_GET['page'] == 'new'){
                                            include 'candidates/register.php';
                                        } elseif ($_GET['page'] == 'list'){
                                            include 'candidates/list.php';
                                        } elseif ($_GET['page'] == 'update'){
                                            include 'candidates/update.php';
                                        }elseif ($_GET['page'] == 'delete'){
                                            include 'candidates/delete.php';
                                        }else {
                                            echo 'Oops! Sorry, page not found.';
                                        }
                                    }

                                    if (isset($_GET['company'])){
                                        if ($_GET['company'] == 'new'){
                                            include 'companies/register.php';
                                        } elseif ($_GET['company'] == 'list'){
                                            include 'companies/list.php';
                                        } elseif ($_GET['company'] == 'update'){
                                            include 'companies/update.php';
                                        }elseif ($_GET['company'] == 'delete'){
                                            include 'candidates/delete.php';
                                        }else {
                                            echo 'Oops! Sorry, page not found.';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->

            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Dumela Corp. 2024</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="logout.php">Logout</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="assets/vendor/jquery/jquery.min.js"></script>
    <script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

    <!-- Core plugin JavaScript-->
    <script src="assets/vendor/jquery-easing/jquery.easing.min.js"></script>

    <!-- Custom scripts for all pages-->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- Page level plugins -->
    <!-- <script src="assets/vendor/chart.js/Chart.min.js"></script> -->

    <!-- Page level custom scripts -->
    <!-- <script src="assets/js/demo/chart-area-demo.js"></script> -->
    <!-- <script src="assets/js/demo/chart-pie-demo.js"></script> -->

</body>

</html>