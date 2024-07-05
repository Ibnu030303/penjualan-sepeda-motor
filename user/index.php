<?php
session_start();

// Check if the user is logged in and has the admin role
if (!isset($_SESSION['username']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'sales') {
    header("Location: ../login.php"); // Redirect to login page if not logged in or not admin
    exit();
}

$username = $_SESSION['username'];
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Admin Dashboard</title>

  <!-- Bootstrap -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" />
  <!-- Lni Icons -->
  <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
  <!-- Style -->
  <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
  <div class="wrapper">
    <?php  include 'template/sidebar.php' ?>
    <main id="main" class="main">
        <nav class="navbar navbar-expand border-bottom">
            <button class="btn" id="sidebar-toggle" type="button">
            <i class="lni lni-menu navbar-toggler-icon"></i>
            </button>
        </nav>

      <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
          <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.html">Home</a></li>
            <li class="breadcrumb-item active">Dashboard</li>
          </ol>
        </nav>
      </div>
      <!-- End Page Title -->

      <section class="section dashboard">
        <div class="row">
          <!-- Left side columns -->
          <div class="col-lg-12">
            <div class="row">
              <!-- Sales Card -->
              <div class="col-xxl-4 col-md-4">
                <div class="card info-card sales-card">
                  <div class="card-body">
                    <h5 class="card-title">Sales</h5>

                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="lni lni-cart"></i>
                      </div>
                      <div class="ps-3">
                        <h6>145</h6>
                        <span class="text-success small pt-1 fw-bold">12%</span>
                        <span class="text-muted small pt-2 ps-1">increase</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Sales Card -->

              <!-- Revenue Card -->
              <div class="col-xxl-4 col-md-4">
                <div class="card info-card revenue-card">
                  <div class="card-body">
                    <h5 class="card-title">Revenue</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="lni lni-dollar"></i>
                      </div>
                      <div class="ps-3">
                        <h6>$3,264</h6>
                        <span class="text-success small pt-1 fw-bold">8%</span>
                        <span class="text-muted small pt-2 ps-1">increase</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Revenue Card -->

              <!-- Customers Card -->
              <div class="col-xxl-4 col-md-4">
                <div class="card info-card customers-card">
                  <div class="card-body">
                    <h5 class="card-title">Customers</h5>
                    <div class="d-flex align-items-center">
                      <div class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                        <i class="lni lni-users"></i>
                      </div>
                      <div class="ps-3">
                        <h6>1244</h6>
                        <span class="text-danger small pt-1 fw-bold">12%</span>
                        <span class="text-muted small pt-2 ps-1">decrease</span>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- End Customers Card -->

              <!-- Reports -->
              <div class="col-12">
                <div class="card">
                  <div class="card-body">
                    <h5 class="card-title">Reports</h5>

                    <!-- Line Chart -->
                    <div id="reportsChart"></div>

                    <!-- End Line Chart -->
                  </div>
                </div>
              </div>
              <!-- End Reports -->
            </div>
          </div>
          <!-- End Left side columns -->
        </div>
      </section>
    </main>
    <!-- End #main -->
  </div>

  <!-- Bootstrap -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Script -->
  <script src="script.js"></script>
</body>

</html>
