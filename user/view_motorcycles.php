<?php
session_start();

// Check if the user is logged in and is a sales user
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'sales') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Fetch motorcycles data
$sql = "SELECT * FROM motorcycles";
$result = $conn->query($sql);
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
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Reports</h5>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Model</th>
                                                <th>Brand</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?php echo htmlspecialchars($row['motorcycle_id']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['model']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['brand']); ?></td>
                                                    <td class="price"><?php echo htmlspecialchars($row['price']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['stock']); ?></td>
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
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script -->
    <script src="script.js"></script>

    <script>
        // Format prices in IDR
        const priceFormatter = new Intl.NumberFormat('id-ID', {
            style: 'currency',
            currency: 'IDR',
            minimumFractionDigits: '0'
        });

        document.querySelectorAll('.price').forEach(function(cell) {
            const price = parseFloat(cell.textContent.replace(/[^0-9.-]+/g, ""));
            cell.textContent = priceFormatter.format(price);
        });
    </script>
</body>

</html>

<?php
$conn->close();
?>