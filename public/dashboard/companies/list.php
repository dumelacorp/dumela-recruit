<?php
    // session_start();

    $current_url = $_SERVER['PHP_SELF'] . '?company=list';

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    include_once '../../config/Database.php';
    include_once '../../classes/Company.php';

    $database = new Database();
    $db = $database->connect();
    $company = new Company($db);
    


    
    $total_companies = $company->getTotalCompanies();

    $companies_per_page = 10;
    $current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
    $offset = ($current_page - 1) * $companies_per_page;

    if (isset($_GET['search_text'])) {
        $search_string = $_GET['search_text'];
        $companies = $company->findCompaniesByMultipleParameters($search_string, $companies_per_page, $offset);
        $total_companies = $company->getTotalCompaniesForSearch($search_string);
    } else {
        $companies = $company->read($companies_per_page, $offset);
        $total_companies = $company->getTotalCompanies();
    }

    $total_pages = max(1, ceil($total_companies / $companies_per_page));
    $current_page = min($current_page, $total_pages); // Ensure page doesn't exceed total pages

    // Determine the base URL
    $base_url = 'index.php?company=list';

?>
<!-- Topbar Search -->
<div class="container-fluid"> <div class="row">
        <div class="col-12"> 
            <form class="navbar-search border border-primary rounded" method='get' action="index.php">
                <input type="hidden" name="company" value="list">
                <div class="input-group">
                    <input type="text" id="search" name="search_text" class="form-control bg-light border-0 small" 
                          placeholder="Search for company by company name, email, country, city, specialization, or level" 
                          value="<?php echo isset($_GET['search_text']) ? htmlspecialchars($_GET['search_text']) : ''; ?>" 
                          aria-label="Search" aria-describedby="basic-addon2">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit"> 
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>

<h3>Candidates</h3>
<div class="table-responsive">
  <table class="table table-striped table-hover">
    <thead class="thead-light">
      <tr>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Country</th>
        <th scope="col">State</th>
        <th scope="col">City</th>
        <th scope="col">Specialization</th>
        <th scope="col">Contact</th>
        <th scope="col">Contact Person</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $companies->fetch(PDO::FETCH_ASSOC)): ?>
      <tr>
        <td><?php echo htmlspecialchars($row['company_name']); ?></td>
        <td><?php echo htmlspecialchars($row['email']); ?></td>
        <td><?php echo htmlspecialchars($row['country']); ?></td>
        <td><?php echo htmlspecialchars($row['state']); ?></td>
        <td><?php echo htmlspecialchars($row['city']); ?></td>
        <td><?php echo htmlspecialchars($row['specialization']); ?></td>
        <td><?php echo htmlspecialchars($row['contact']); ?></td>
        <td><?php echo htmlspecialchars($row['contact_person']); ?></td>
    
        <td>
          <div class="d-flex flex-column flex-sm-row">
            <form method="get" class="mb-2 mb-sm-0 me-sm-2">
              <input type="hidden" name="company_action" value="update">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-outline-info btn-sm w-100">
                <i class="bi bi-pencil-fill"></i> Update
              </button>
            </form>
            <form method="get">
              <input type="hidden" name="company_action" value="delete">
              <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
              <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                <i class="bi bi-trash-fill"></i> Delete
              </button>
            </form>
          </div>
        </td>
      </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

<div class="d-flex justify-content-center mt-4">
    <nav aria-label="Page navigation">
        <ul class="pagination">
        <?php if ($current_page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $base_url; ?>&current_page=<?php echo $current_page - 1; ?><?php echo isset($_GET['search_text']) ? '&search_text=' . urlencode($_GET['search_text']) : ''; ?>">&laquo; Previous</a>
                </li>
            <?php endif; ?>

            <?php
            $start_page = max(1, $current_page - 2);
            $end_page = min($total_pages, $current_page + 2);

            for ($i = $start_page; $i <= $end_page; $i++) : ?>
                <li class="page-item <?php echo $current_page == $i ? 'active' : ''; ?>">
                    <a class="page-link" href="<?php echo $base_url; ?>&current_page=<?php echo $i; ?><?php echo isset($_GET['search_text']) ? '&search_text=' . urlencode($_GET['search_text']) : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                </li>
            <?php endfor; ?>

            <?php if ($current_page < $total_pages): ?>
                <li class="page-item">
                    <a class="page-link" href="<?php echo $base_url; ?>&current_page=<?php echo $current_page + 1; ?><?php echo isset($_GET['search_text']) ? '&search_text=' . urlencode($_GET['search_text']) : ''; ?>">Next &raquo;</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>