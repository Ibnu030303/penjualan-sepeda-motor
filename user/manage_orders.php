<?php
session_start();

// Check if the user is logged in and is a sales user
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'sales') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Fetch sales orders data
$sql = "
SELECT sales.sale_id, sales.sale_date, sales.total_price, sales.payment_status, sales.payment_type, sales.down_payment, sales.monthly_installment,
       customers.name AS customer_name, motorcycles.model AS motorcycle_model, motorcycles.brand AS motorcycle_brand
FROM sales
JOIN customers ON sales.customer_id = customers.customer_id
JOIN motorcycles ON sales.motorcycle_id = motorcycles.motorcycle_id
";
$result = $conn->query($sql);

// Update payment status
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['sale_id']) && isset($_POST['payment_status'])) {
    $sale_id = $_POST['sale_id'];
    $payment_status = $_POST['payment_status'];

    $update_sql = "UPDATE sales SET payment_status = ? WHERE sale_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("si", $payment_status, $sale_id);

    if ($stmt->execute()) {
        $success_message = "Payment status updated successfully.";
    } else {
        $error_message = "Failed to update payment status.";
    }
    $stmt->close();
}

// Fetch customer data for dropdown
$sql_customers = "SELECT customer_id, name FROM customers";
$result_customers = $conn->query($sql_customers);

// Fetch motorcycle data for dropdown
$sql_motorcycles = "SELECT motorcycle_id, brand, model, stock FROM motorcycles";
$result_motorcycles = $conn->query($sql_motorcycles);

// Handle form submission for adding new order
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'];
    $motorcycle_id = $_POST['motorcycle_id'];
    $sale_date = $_POST['sale_date'];
    $total_price = $_POST['total_price'];
    $payment_type = $_POST['payment_type']; // Added payment type
    $down_payment = isset($_POST['down_payment']) ? $_POST['down_payment'] : 0; // Added down payment, default to 0 if not set

    // Check if motorcycle stock is available
    $get_stock_sql = "SELECT stock FROM motorcycles WHERE motorcycle_id = ?";
    $stmt_get_stock = $conn->prepare($get_stock_sql);
    $stmt_get_stock->bind_param("i", $motorcycle_id);
    $stmt_get_stock->execute();
    $stmt_get_stock->bind_result($stock);
    $stmt_get_stock->fetch();
    $stmt_get_stock->close();

    // Check if stock is available
    if ($stock > 0) {
        // Proceed with order insertion
        $user_id = $_SESSION['user_id']; // Assuming you fetch this during login

        if ($payment_type == 'Credit') {
            // Calculate monthly installment
            $loan_amount = $total_price - $down_payment;
            $monthly_installment = $loan_amount / 12; // Assuming 12 months installment

            $insert_sql = "INSERT INTO sales (user_id, customer_id, motorcycle_id, sale_date, total_price, down_payment, payment_status, payment_type, monthly_installment) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $payment_status = 'Pending'; // Default payment status for new orders
            $stmt->bind_param("iiisdsdsd", $user_id, $customer_id, $motorcycle_id, $sale_date, $total_price, $down_payment, $payment_status, $payment_type, $monthly_installment);
        } else {
            // For Cash transaction
            $insert_sql = "INSERT INTO sales (user_id, customer_id, motorcycle_id, sale_date, total_price, payment_status, payment_type) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert_sql);
            $payment_status = 'Pending'; // Default payment status for new orders
            $stmt->bind_param("iiisdss", $user_id, $customer_id, $motorcycle_id, $sale_date, $total_price, $payment_status, $payment_type);
        }

        if ($stmt->execute()) {
            $success_message = "New order added successfully.";

            // Update motorcycle stock
            $update_stock_sql = "UPDATE motorcycles SET stock = stock - 1 WHERE motorcycle_id = ?";
            $stmt_update_stock = $conn->prepare($update_stock_sql);
            $stmt_update_stock->bind_param("i", $motorcycle_id);
            $stmt_update_stock->execute();
            $stmt_update_stock->close();

        } else {
            $error_message = "Failed to add new order: " . $stmt->error;
        }
        $stmt->close();
    } else {
        // Handle case where stock is 0
        $error_message = "Failed to add new order: Motorcycle is out of stock.";
    }
}
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
                                <?php if (isset($success_message)) : ?>
                                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                                <?php elseif (isset($error_message)) : ?>
                                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                                <?php endif; ?>
                                <div class="card-body">
                                    <h5 class="card-title">Manage Orders</h5>
                                    <a href="create_order.php" class="btn btn-primary">Add New Order</a>
                                    <button type="button" class="btn btn-success mb-3" data-bs-toggle="modal" data-bs-target="#addCustomerModal">Tambah Order</button>

                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Sale ID</th>
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
                                                    <td class="price"><?php echo htmlspecialchars($row['total_price']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['payment_type']); ?></td>
                                                    <td class="price"><?php echo htmlspecialchars($row['down_payment']); ?></td>
                                                    <td class="price"><?php echo htmlspecialchars($row['monthly_installment']); ?></td>
                                                    <td><?php echo htmlspecialchars($row['payment_status']); ?></td>
                                                    <td>
                                                        <form action="" method="POST" class="d-inline">
                                                            <input type="hidden" name="sale_id" value="<?php echo htmlspecialchars($row['sale_id']); ?>">
                                                            <select name="payment_status" class="form-select form-select-sm d-inline" style="width: auto;">
                                                                <option value="Pending" <?php echo $row['payment_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="Completed" <?php echo $row['payment_status'] == 'Completed' ? 'selected' : ''; ?>>Completed</option>
                                                                <option value="Canceled" <?php echo $row['payment_status'] == 'Canceled' ? 'selected' : ''; ?>>Canceled</option>
                                                            </select>
                                                            <button type="submit" class="btn btn-sm btn-primary">Update</button>
                                                        </form>
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
            <!-- Add/Edit Customer Modal -->
            <div class="modal fade" id="addCustomerModal" tabindex="-1" aria-labelledby="addCustomerModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label for="customer_id" class="form-label">Customer</label>
                                <select name="customer_id" id="customer_id" class="form-select" required>
                                    <?php while ($row = $result_customers->fetch_assoc()) : ?>
                                        <option value="<?php echo $row['customer_id']; ?>"><?php echo $row['name']; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="motorcycle_id" class="form-label">Motorcycle</label>
                                <select name="motorcycle_id" id="motorcycle_id" class="form-select" required>
                                    <?php while ($row = $result_motorcycles->fetch_assoc()) : ?>
                                        <option value="<?php echo $row['motorcycle_id']; ?>"><?php echo $row['brand'] . ' ' . $row['model'] . ' (Stock: ' . $row['stock'] . ')'; ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="sale_date" class="form-label">Sale Date</label>
                                <input type="date" name="sale_date" id="sale_date" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="total_price" class="form-label">Total Price</label>
                                <input type="number" name="total_price" id="total_price" class="form-control" step="0.01" required>
                            </div>
                            <div class="mb-3">
                                <label for="payment_type" class="form-label">Payment Type</label>
                                <select name="payment_type" id="payment_type" class="form-select" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Credit">Credit</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="down_payment" class="form-label">Down Payment (if Credit)</label>
                                <input type="number" name="down_payment" id="down_payment" class="form-control" step="0.01">
                            </div>
                            <button type="submit" class="btn btn-primary">Add Order</button>
                        </form>
                    </div>
                </div>
            </div>
        </main>
        <!-- End #main -->
    </div>

    <!-- Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Script -->
    <script src="script.js"></script>
    <script>
        function formatCurrency(amount) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR'
            }).format(amount);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const prices = document.querySelectorAll('.price');
            prices.forEach(function (price) {
                const value = parseFloat(price.innerText.replace(/,/g, ''));
                price.innerText = formatCurrency(value);
            });
        });
    </script>
</body>

</html>
<?php
$conn->close();
?>
