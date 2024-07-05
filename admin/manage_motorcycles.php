<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

$error = "";  // Initialize error message variable

// Handle form submissions for adding/editing motorcycles
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $motorcycle_id = isset($_POST['motorcycle_id']) ? $_POST['motorcycle_id'] : null;

    if ($motorcycle_id) {
        // Update existing motorcycle
        $sql = "UPDATE motorcycles SET model=?, brand=?, price=?, stock=? WHERE motorcycle_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $model, $brand, $price, $stock, $motorcycle_id);
    } else {
        // Add new motorcycle
        $sql = "INSERT INTO motorcycles (model, brand, price, stock) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdi", $model, $brand, $price, $stock);
    }

    if ($stmt->execute()) {
        header("Location: manage_motorcycles.php");
        exit();
    } else {
        $error = "Error saving motorcycle: " . $stmt->error;
    }
}

// Handle deletion of motorcycles
if (isset($_GET['delete'])) {
    $motorcycle_id = $_GET['delete'];
    $sql = "DELETE FROM motorcycles WHERE motorcycle_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $motorcycle_id);
    if ($stmt->execute()) {
        header("Location: manage_motorcycles.php");
        exit();
    } else {
        $error = "Error deleting motorcycle: " . $stmt->error;
    }
}

// Fetch motorcycles from the database
$sql = "SELECT * FROM motorcycles";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Motorcycles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Lni Icons -->
    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <!-- Style -->
    <link rel="stylesheet" href="assets/css/style.css" />
</head>

<body>
    <div class="wrapper">
        <?php include 'template/sidebar.php'; ?>
        <main id="main" class="main">
            <nav class="navbar navbar-expand border-bottom">
                <button class="btn" id="sidebar-toggle" type="button">
                    <i class="lni lni-menu navbar-toggler-icon"></i>
                </button>
            </nav>

            <div class="pagetitle">
                <h1>Manage Motorcycles</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Manage Motorcycles</li>
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
                                        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#motorcycleModal">Add Motorcycle</button>
                                        <h5 class="card-title">Motorcycles</h5>
                                    </div>

                                    <?php if ($error) : ?>
                                        <div class="alert alert-danger"><?php echo $error; ?></div>
                                    <?php endif; ?>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Model</th>
                                                <th>Brand</th>
                                                <th>Price</th>
                                                <th>Stock</th>
                                                <th>Actions</th>
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
                                                    <td>
                                                        <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['motorcycle_id']; ?>" data-model="<?php echo htmlspecialchars($row['model']); ?>" data-brand="<?php echo htmlspecialchars($row['brand']); ?>" data-price="<?php echo htmlspecialchars($row['price']); ?>" data-stock="<?php echo htmlspecialchars($row['stock']); ?>" data-bs-toggle="modal" data-bs-target="#motorcycleModal">Edit</button>
                                                        <a href="manage_motorcycles.php?delete=<?php echo $row['motorcycle_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this motorcycle?')">Delete</a>
                                                    </td>
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

            <!-- Add/Edit Motorcycle Modal -->
            <div class="modal fade" id="motorcycleModal" tabindex="-1" aria-labelledby="motorcycleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="manage_motorcycles.php">
                            <input type="hidden" name="motorcycle_id" id="motorcycle_id">
                            <div class="modal-header">
                                <h5 class="modal-title" id="motorcycleModalLabel">Motorcycle</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label for="model" class="form-label">Model</label>
                                    <input type="text" class="form-control" name="model" id="model" required>
                                </div>
                                <div class="mb-3">
                                    <label for="brand" class="form-label">Brand</label>
                                    <input type="text" class="form-control" name="brand" id="brand" required>
                                </div>
                                <div class="mb-3">
                                    <label for="price" class="form-label">Price</label>
                                    <input type="number" step="0.01" class="form-control" name="price" id="price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" name="stock" id="stock" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
        <!-- End #main -->
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    document.getElementById('motorcycle_id').value = this.dataset.id;
                    document.getElementById('model').value = this.dataset.model;
                    document.getElementById('brand').value = this.dataset.brand;
                    document.getElementById('price').value = this.dataset.price;
                    document.getElementById('stock').value = this.dataset.stock;
                });
            });

            // Clear modal fields when opening the modal for adding a new motorcycle
            const motorcycleModal = document.getElementById('motorcycleModal');
            motorcycleModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                if (!button.classList.contains('edit-btn')) {
                    document.getElementById('motorcycle_id').value = '';
                    document.getElementById('model').value = '';
                    document.getElementById('brand').value = '';
                    document.getElementById('price').value = '';
                    document.getElementById('stock').value = '';
                }
            });

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
        });
    </script>

</body>

</html>
