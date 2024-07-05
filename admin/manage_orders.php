<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Handle form submissions for updating order status
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sale_id = $_POST['sale_id'];
    $payment_status = $_POST['payment_status'];

    $sql = "UPDATE sales SET payment_status=? WHERE sale_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $payment_status, $sale_id);

    if ($stmt->execute()) {
        header("Location: manage_orders.php");
        exit();
    } else {
        $error = "Error updating order.";
    }
}

// Handle deletion of orders
if (isset($_GET['delete'])) {
    $sale_id = $_GET['delete'];
    $sql = "DELETE FROM sales WHERE sale_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $sale_id);
    if ($stmt->execute()) {
        header("Location: manage_orders.php");
        exit();
    } else {
        $error = "Error deleting order.";
    }
}

// Fetch orders from the database
$sql = "
SELECT sales.sale_id, customers.name AS customer_name, motorcycles.model AS motorcycle_model, motorcycles.brand AS motorcycle_brand,
       sales.sale_date, sales.total_price, sales.payment_status, sales.payment_type, sales.down_payment, sales.monthly_installment
FROM sales
JOIN customers ON sales.customer_id = customers.customer_id
JOIN motorcycles ON sales.motorcycle_id = motorcycles.motorcycle_id
";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders</title>
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Customer</th>
                                        <th>Motorcycle</th>
                                        <th>Sale Date</th>
                                        <th>Total Price</th>
                                        <th>Payment Type</th>
                                        <th>Down Payment</th>
                                        <th>Monthly Installment</th>
                                        <th>Payment Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()) : ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($row['sale_id']); ?></td>
                                            <td><?php echo htmlspecialchars($row['customer_name']); ?></td>
                                            <td><?php echo htmlspecialchars($row['motorcycle_brand'] . ' ' . $row['motorcycle_model']); ?></td>
                                            <td><?php echo htmlspecialchars($row['sale_date']); ?></td>
                                            <td><?php echo htmlspecialchars($row['total_price']); ?></td>
                                            <td><?php echo htmlspecialchars($row['payment_type']); ?></td>
                                            <td><?php echo htmlspecialchars($row['down_payment']); ?></td>
                                            <td><?php echo htmlspecialchars($row['monthly_installment']); ?></td>
                                            <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                            <td>
                                                <a href="javascript:void(0);" class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['sale_id']; ?>" data-status="<?php echo $row['payment_status']; ?>">Edit</a>
                                                <a href="manage_orders.php?delete=<?php echo $row['sale_id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this order?')">Delete</a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- End Left side columns -->
                </div>
            </section>

            <!-- Edit Order Modal -->
            <div class="modal fade" id="editOrderModal" tabindex="-1" aria-labelledby="editOrderModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form method="POST" action="manage_orders.php">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editOrderModalLabel">Edit Order</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="sale_id" id="edit_sale_id">
                                <div class="mb-3">
                                    <label for="payment_status" class="form-label">Payment Status</label>
                                    <select class="form-select" name="payment_status" id="edit_payment_status" required>
                                        <option value="Pending">Pending</option>
                                        <option value="Completed">Completed</option>
                                        <option value="Canceled">Canceled</option>
                                    </select>
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
        </main>
        <!-- End #main -->
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.edit-btn').forEach(function(button) {
                button.addEventListener('click', function() {
                    const saleId = this.dataset.id;
                    const status = this.dataset.status;

                    document.getElementById('edit_sale_id').value = saleId;
                    document.getElementById('edit_payment_status').value = status;

                    const editOrderModal = new bootstrap.Modal(document.getElementById('editOrderModal'));
                    editOrderModal.show();
                });
            });
        });
    </script>

</body>

</html>
<?php
$conn->close();
?>
