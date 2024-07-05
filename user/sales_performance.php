<?php
session_start();

// Check if the user is logged in and is a sales user
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'sales') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Fetch sales performance data for the logged-in sales user
$username = $_SESSION['username'];

// Example query to retrieve sales performance data (you can adjust this query based on your actual database schema)
$sql = "SELECT SUM(total_price) AS total_sales, COUNT(sale_id) AS total_orders
        FROM sales s
        JOIN users u ON s.user_id = u.user_id
        WHERE u.username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $total_sales = $row['total_sales'];
    $total_orders = $row['total_orders'];
} else {
    $total_sales = 0;
    $total_orders = 0;
}

$stmt->close();
$conn->close();
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
        <?php include 'template/sidebar.php' ?>
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
                            <div class="card mt-4" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Total Sales</h5>
                                    <p class="card-text">$ <?php echo number_format($total_sales, 2); ?></p>
                                </div>
                            </div>

                            <div class="card mt-4" style="width: 18rem;">
                                <div class="card-body">
                                    <h5 class="card-title">Total Orders</h5>
                                    <p class="card-text"><?php echo $total_orders; ?></p>
                                </div>
                            </div>
                           
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
