<?php
session_start();

// Check if the user is logged in and is a sales user
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'sales') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Fetch customers data
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

// Initialize variables for error/success messages
$error_message = '';
$success_message = '';

// Example for updating customer information
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['customer_id'])) {
    $customer_id = $_POST['customer_id'];
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $address = $conn->real_escape_string($_POST['address']);

    // Update customer query
    $update_sql = "UPDATE customers SET name = ?, email = ?, phone = ?, address = ? WHERE customer_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);

    if ($stmt->execute()) {
        $success_message = "Customer information updated successfully.";
    } else {
        $error_message = "Failed to update customer information.";
    }
    $stmt->close();
}

// Close database connection
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

                            <div class="card">
                                <?php if (!empty($error_message)) : ?>
                                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                <?php endif; ?>

                                <?php if (!empty($success_message)) : ?>
                                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title">Reports</h5>
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Customer ID</th>
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
                                                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $row['customer_id']; ?>">
                                                            Edit
                                                        </button>
                                                    </td>
                                                </tr>

                                                <!-- Edit Customer Modal -->
                                                <div class="modal fade" id="editModal<?php echo $row['customer_id']; ?>" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <form action="" method="POST">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="editModalLabel">Edit Customer</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <input type="hidden" name="customer_id" value="<?php echo $row['customer_id']; ?>">
                                                                    <div class="mb-3">
                                                                        <label for="name" class="form-label">Name</label>
                                                                        <input type="text" class="form-control" name="name" id="name" value="<?php echo $row['name']; ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="email" class="form-label">Email</label>
                                                                        <input type="email" class="form-control" name="email" id="email" value="<?php echo $row['email']; ?>" required>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="phone" class="form-label">Phone</label>
                                                                        <input type="text" class="form-control" name="phone" id="phone" value="<?php echo $row['phone']; ?>">
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label for="address" class="form-label">Address</label>
                                                                        <textarea class="form-control" name="address" id="address" rows="3"><?php echo $row['address']; ?></textarea>
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
</body>

</html>
