<?php
    // session_start();

    $current_url = $_SERVER['PHP_SELF'] . '?page=list';

    if(!isset($_SESSION['user_id'])) {
        header('Location: ../login.php');
        exit;
    }

    include_once '../../config/Database.php';
    include_once '../../classes/Candidate.php';

    $database = new Database();
    $db = $database->connect();
    $candidate = new Candidate($db);
    


    
    $total_candidates = $candidate->getTotalCandidates();

    $candidates_per_page = 10;
    $current_page = isset($_GET['current_page']) ? max(1, intval($_GET['current_page'])) : 1;
    $offset = ($current_page - 1) * $candidates_per_page;

    if (isset($_GET['search_text'])) {
        $search_string = $_GET['search_text'];
        $candidates = $candidate->findCandidatesByMultipleParameters($search_string, $candidates_per_page, $offset);
        $total_candidates = $candidate->getTotalCandidatesForSearch($search_string);
    } else {
        $candidates = $candidate->read($candidates_per_page, $offset);
        $total_candidates = $candidate->getTotalCandidates();
    }

    $total_pages = max(1, ceil($total_candidates / $candidates_per_page));
    $current_page = min($current_page, $total_pages); // Ensure page doesn't exceed total pages

    // Determine the base URL
    $base_url = 'index.php?page=list';

?>
<!-- Topbar Search -->
<head>
  <style>
        .table-hover tbody tr {
            cursor: pointer;
        }
        .action-buttons {
            opacity: 0;
            transition: opacity 0.3s;
        }
        tr:hover .action-buttons {
            opacity: 1;
        }
  </style>
</head>
<div class="container-fluid"> <div class="row">
        <div class="col-12"> 
            <form class="navbar-search border border-primary rounded" method='get' action="index.php">
                <input type="hidden" name="page" value="list">
                <div class="input-group">
                    <input type="text" id="search" name="search_text" class="form-control bg-light border-0 small" 
                          placeholder="Search for candidate by first name, last name, country, job title, or level" 
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
        <th scope="col">First Name</th>
        <th scope="col">Last Name</th>
        <th scope="col">Country</th>
        <th scope="col">State</th>
        <th scope="col">Job Title</th>
        <th scope="col">Level</th>
        <th scope="col">Resume</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $candidates->fetch(PDO::FETCH_ASSOC)): ?>
        <tr data-id="<?php echo $row['id']; ?>" class="candidate-row cursor-pointer hover:bg-gray-100">
          <td><?php echo htmlspecialchars($row['first_name']); ?></td>
          <td><?php echo htmlspecialchars($row['last_name']); ?></td>
          <td><?php echo htmlspecialchars($row['country']); ?></td>
          <td><?php echo htmlspecialchars($row['state']); ?></td>
          <td><?php echo htmlspecialchars($row['job_title']); ?></td>
          <td><?php echo htmlspecialchars($row['level']); ?></td>
          <td>
              <a href="../../uploads/<?php echo $row['resume']; ?>" target="_blank" class="btn btn-outline-primary btn-sm">View</a>
          </td>
          <td>
              <div class="d-flex flex-column flex-sm-row">
                  <form method="get" class="mb-2 mb-sm-0 me-sm-2">
                      <input type="hidden" name="candidate_action" value="update">
                      <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                      <input type="hidden" name="existing_resume" value="<?php echo htmlspecialchars($row['resume']); ?>">
                      <button type="submit" class="btn btn-outline-info btn-sm w-100">
                          <i class="bi bi-pencil-fill"></i> Update
                      </button>
                  </form>
                  <form method="get">
                      <input type="hidden" name="candidate_action" value="delete">
                      <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                      <input type="hidden" name="existing_resume" value="<?php echo htmlspecialchars($row['resume']); ?>">
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

<script>
  document.addEventListener('DOMContentLoaded', function() {
      const rows = document.querySelectorAll('.candidate-row');
      rows.forEach(row => {
          row.addEventListener('click', function(e) {
              // Prevent the click event from triggering on buttons
              if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || e.target.closest('button') || e.target.closest('a')) {
                  return;
              }
              
              const id = this.getAttribute('data-id');
              window.location.href = `?candidate_action=view&id=${id}`;
          });
      });
  });
</script>