<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Handle form submissions for adding/editing customers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'] ?? null;
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    if ($customer_id) {
        // Update existing customer
        $sql = "UPDATE customers SET name=?, email=?, phone=?, address=? WHERE customer_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);
    } else {
        // Add new customer
        $sql = "INSERT INTO customers (name, email, phone, address) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $name, $email, $phone, $address);
    }

    if ($stmt->execute()) {
        header("Location: manage_customers.php");
        exit();
    } else {
        $error = "Error saving customer.";
    }
}

// Handle deletion of customers
if (isset($_GET['delete'])) {
    $customer_id = $_GET['delete'];
    $sql = "DELETE FROM customers WHERE customer_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    header("Location: manage_customers.php");
    exit();
}

// Fetch customers from the database
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>
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
                                        <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Add Customer</button>
                                        <h5 class="card-title">Reports</h5>
                                    </div>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php while ($row = $result->fetch_assoc()) : ?>
                                                <tr>
                                                    <td><?php echo $row['customer_id']; ?></td>
                                                    <td><?php echo $row['name']; ?></td>
                                                    <td><?php echo $row['email']; ?></td>
                                                    <td><?php echo $row['phone']; ?></td>
                                                    <td><?php echo $row['address']; ?></td>
                                                    <td>
                                                        <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['customer_id']; ?>" data-name="<?php echo $row['name']; ?>" data-email="<?php echo $row['email']; ?>" data-phone="<?php echo $row['phone']; ?>" data-address="<?php echo $row['address']; ?>">Edit</a>
                                                        <a href="manage_customers.php?delete=<?php echo $row['customer_id']; ?>" class="btn btn-danger btn-sm">Delete</a>
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
                <!-- Add/Edit Customer Modal -->
                <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="POST" action="manage_customers.php">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="addCustomerModalLabel">Add/Edit Customer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="customer_id" id="customer_id">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name</label>
                                        <input type="text" class="form-control" name="name" id="name" placeholder="Enter name" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Enter email" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone</label>
                                        <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter phone" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Address</label>
                                        <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter address" required></textarea>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Save changes</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
        <!-- End #main -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const customerId = this.dataset.id;
                    const name = this.dataset.name;
                    const email = this.dataset.email;
                    const phone = this.dataset.phone;
                    const address = this.dataset.address;

                    document.getElementById('customer_id').value = customerId;
                    document.getElementById('name').value = name;
                    document.getElementById('email').value = email;
                    document.getElementById('phone').value = phone;
                    document.getElementById('address').value = address;

                    const addCustomerModal = new bootstrap.Modal(document.getElementById('addCustomerModal'));
                    addCustomerModal.show();
                });
            });
        });
    </script>

</body>

</html>