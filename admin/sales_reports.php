<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Handle form submission for filtering sales reports
$whereClauses = [];
$params = [];
$types = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!empty($_POST['start_date'])) {
        $whereClauses[] = 'sale_date >= ?';
        $params[] = $_POST['start_date'];
        $types .= 's';
    }
    if (!empty($_POST['end_date'])) {
        $whereClauses[] = 'sale_date <= ?';
        $params[] = $_POST['end_date'];
        $types .= 's';
    }
}

$whereSql = '';
if (count($whereClauses) > 0) {
    $whereSql = 'WHERE ' . implode(' AND ', $whereClauses);
}

$sql = "SELECT sales.sale_id, customers.name AS customer_name, motorcycles.model AS motorcycle_model, sales.sale_date, sales.total_price, sales.payment_status 
        FROM sales 
        JOIN customers ON sales.customer_id = customers.customer_id 
        JOIN motorcycles ON sales.motorcycle_id = motorcycles.motorcycle_id 
        $whereSql 
        ORDER BY sales.sale_date DESC";

$stmt = $conn->prepare($sql);
if ($types) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
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
                            <div class="card">

                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center">

                                        <h5 class="card-title">Reports</h5>
                                    </div>

                                    <form method="POST" action="sales_reports.php" class="row g-3 mb-4">
                                        <div class="col-md-3">
                                            <label for="start_date" class="form-label">Start Date</label>
                                            <input type="date" class="form-control" name="start_date" id="start_date" value="<?php echo htmlspecialchars($_POST['start_date'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-3">
                                            <label for="end_date" class="form-label">End Date</label>
                                            <input type="date" class="form-control" name="end_date" id="end_date" value="<?php echo htmlspecialchars($_POST['end_date'] ?? ''); ?>">
                                        </div>
                                        <div class="col-md-3 align-self-end">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                        </div>
                                    </form>


                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sale ID</th>
                                                <th>Customer Name</th>
                                                <th>Motorcycle Model</th>
                                                <th>Sale Date</th>
                                                <th>Total Price</th>
                                                <th>Payment Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?php echo $row['sale_id']; ?></td>
                                                    <td><?php echo $row['customer_name']; ?></td>
                                                    <td><?php echo $row['motorcycle_model']; ?></td>
                                                    <td><?php echo $row['sale_date']; ?></td>
                                                    <td><?php echo $row['total_price']; ?></td>
                                                    <td><?php echo $row['payment_status']; ?></td>
                                                </tr>
                                            <?php endwhile; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Left side columns -->
                </div>
            </section>

        </main>
        <!-- End #main -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>
<?php
$stmt->close();
$conn->close();
?>