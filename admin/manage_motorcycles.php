<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Initialize error message variable
$error = "";

// Handle form submissions for adding/editing motorcycles
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $model = $_POST['model'];
    $brand = $_POST['brand'];
    $price = $_POST['price'];
    $warna = $_POST['warna'];
    $stock = $_POST['stock'];
    $motorcycle_id = isset($_POST['motorcycle_id']) ? $_POST['motorcycle_id'] : null;

    if ($motorcycle_id) {
        // Update existing motorcycle
        $sql = "UPDATE motorcycles SET model=?, brand=?, price=?, warna=?, stock=? WHERE motorcycle_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsii", $model, $brand, $price, $warna, $stock, $motorcycle_id);
    } else {
        // Add new motorcycle
        $sql = "INSERT INTO motorcycles (model, brand, price, warna, stock, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii", $model, $brand, $price, $warna, $stock);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Motorcycle saved successfully!";
        header("Location: manage_motorcycles.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error saving motorcycle: " . $stmt->error;
    }
}

// Handle deletion of motorcycles
if (isset($_GET['delete'])) {
    $motorcycle_id = $_GET['delete'];
    $sql = "DELETE FROM motorcycles WHERE motorcycle_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $motorcycle_id);
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Motorcycle deleted successfully!";
        header("Location: manage_motorcycles.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error deleting motorcycle: " . $stmt->error;
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
    <?php include 'template/header.php'; ?>
</head>

<body>
    <div class="wrapper">
        <?php include 'template/sidebar.php'; ?>
        <main id="main" class="main">
            <?php include 'template/nav.php'; ?>

            <div class="pagetitle">
                <h1 class="mb-2">Data Motor</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">Motor</li>
                    </ol>
                </nav>
            </div>

            <section class="section dashboard">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="card">
                                <div class="card-body">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary mb-3 mt-3" data-bs-toggle="modal" data-bs-target="#motorcycleModal">Tambah Data</button>
                                    </div>

                                    <div class="overflow-x-auto">
                                        <table class="table table-bordered display" id="motorcycleTable">
                                            <thead>
                                                <tr class="text-center">
                                                    <th>NO</th>
                                                    <th>ID</th>
                                                    <th>Model</th>
                                                    <th>Brand</th>
                                                    <th>Price</th>
                                                    <th>Stock</th>
                                                    <th>Warna</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php while ($row = $result->fetch_assoc()) : ?>
                                                    <tr class="text-center">
                                                        <td class="text-center"><?php echo $no++; ?></td>
                                                        <td><?php echo htmlspecialchars($row['motorcycle_id']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['model']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['brand']); ?></td>
                                                        <td class="price"><?php echo htmlspecialchars($row['price']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                                        <td><?php echo htmlspecialchars($row['warna']); ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['motorcycle_id']; ?>" data-model="<?php echo htmlspecialchars($row['model']); ?>" data-brand="<?php echo htmlspecialchars($row['brand']); ?>" data-price="<?php echo htmlspecialchars($row['price']); ?>" data-stock="<?php echo htmlspecialchars($row['stock']); ?>" data-warna="<?php echo htmlspecialchars($row['warna']); ?>" data-bs-toggle="modal" data-bs-target="#motorcycleModal">Edit</button>
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['motorcycle_id']; ?>">Delete</button>
                                                        </td>
                                                    </tr>
                                                <?php endwhile; ?>
                                            </tbody>
                                        </table>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Add/Edit Motorcycle Modal -->
            <div class="modal fade" id="motorcycleModal" tabindex="-1" aria-labelledby="motorcycleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <form method="POST" action="manage_motorcycles.php">
                            <input type="hidden" name="motorcycle_id" id="motorcycle_id">
                            <div class="modal-header">
                                <h5 class="modal-title" id="motorcycleModalLabel">Data Motor</h5>
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
                                    <input type="text" class="form-control" name="price" id="price" required>
                                </div>
                                <div class="mb-3">
                                    <label for="stock" class="form-label">Stock</label>
                                    <input type="number" class="form-control" name="stock" id="stock" required>
                                </div>
                                <div class="mb-3">
                                    <label for="warna" class="form-label">Warna</label>
                                    <input type="text" class="form-control" name="warna" id="warna" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </main>
    </div>

    <?php include 'template/footer.php'; ?>

    <script>
        $(document).ready(function() {
            const sidebarToggle = document.querySelector("#sidebar-toggle");
            sidebarToggle.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("collapsed");
            });

            $('#motorcycleTable').DataTable();

            <?php if (isset($_SESSION['success_message'])) : ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: '<?php echo $_SESSION['success_message']; ?>'
                });
                <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['error_message'])) : ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '<?php echo $_SESSION['error_message']; ?>'
                });
                <?php unset($_SESSION['error_message']); ?>
            <?php endif; ?>

            // Handle edit button click
            $('.edit-btn').click(function() {
                $('#motorcycle_id').val($(this).data('id'));
                $('#model').val($(this).data('model'));
                $('#brand').val($(this).data('brand'));
                $('#price').val($(this).data('price'));
                $('#stock').val($(this).data('stock'));
                $('#warna').val($(this).data('warna'));
            });

            // Clear modal fields when opening for a new motorcycle
            $('#motorcycleModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                if (!button.hasClass('edit-btn')) {
                    $('#motorcycle_id').val('');
                    $('#model').val('');
                    $('#brand').val('');
                    $('#price').val('');
                    $('#stock').val('');
                    $('#warna').val('');
                }
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                var motorcycle_id = $(this).data('id');
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'manage_motorcycles.php?delete=' + motorcycle_id;
                    }
                });
            });

            // Format prices in IDR
            const priceFormatter = new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            });

            $('.price').each(function() {
                const price = parseFloat($(this).text().replace(/[^0-9.-]+/g, ""));
                $(this).text(priceFormatter.format(price));
            });
        });
    </script>

</body>

</html>