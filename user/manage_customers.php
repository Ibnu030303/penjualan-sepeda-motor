<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'sales') {
    header("Location: login.php");
    exit();
}

require_once '../config.php';

// Handle form submissions for adding/editing customers
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $customer_id = $_POST['customer_id'] ?? null;
    $nik = $_POST['nik'];
    $no_kk = $_POST['no_kk'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Default values for KTP and KK
    $ktp_upload_path = null;
    $kk_upload_path = null;

    // Handle KTP upload if a new file is provided
    if (!empty($_FILES['ktp']['name'])) {
        $ktp = $_FILES['ktp']['name'];
        $ktp_tmp = $_FILES['ktp']['tmp_name'];
        $ktp_upload_path = 'assets/uploads/' . basename($ktp);
        move_uploaded_file($ktp_tmp, $ktp_upload_path);
    }

    // Handle KK upload if a new file is provided
    if (!empty($_FILES['kk']['name'])) {
        $kk = $_FILES['kk']['name'];
        $kk_tmp = $_FILES['kk']['tmp_name'];
        $kk_upload_path = 'assets/uploads/' . basename($kk);
        move_uploaded_file($kk_tmp, $kk_upload_path);
    }

    if ($customer_id) {
        // Update existing customer
        $sql = "SELECT ktp, kk FROM customers WHERE customer_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $customer_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $customer = $result->fetch_assoc();

        // Use existing paths if no new files are uploaded
        if (!$ktp_upload_path) {
            $ktp_upload_path = $customer['ktp'];
        }
        if (!$kk_upload_path) {
            $kk_upload_path = $customer['kk'];
        }

        $sql = "UPDATE customers SET nik=?, no_kk=?, name=?, email=?, phone=?, address=?, ktp=?, kk=? WHERE customer_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $nik, $no_kk, $name, $email, $phone, $address, $ktp_upload_path, $kk_upload_path, $customer_id);
    } else {
        // Add new customer
        $sql = "INSERT INTO customers (nik, no_kk, name, email, phone, address, ktp, kk) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssss", $nik, $no_kk, $name, $email, $phone, $address, $ktp_upload_path, $kk_upload_path);
    }

    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Customer saved successfully.";
        header("Location: manage_customers.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error saving customer: " . $stmt->error;
    }
}

// Handle deletion of customers
if (isset($_GET['delete'])) {
    $customer_id = $_GET['delete'];
    $sql = "DELETE FROM customers WHERE customer_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $customer_id);
    $stmt->execute();
    $_SESSION['success_message'] = "Customer deleted successfully.";
    header("Location: manage_customers.php");
    exit();
}

// Fetch customers from the database
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

if (!$result) {
    $_SESSION['error_message'] = "Error fetching customers: " . $conn->error;
    $result = []; // Ensure $result is defined as an empty array
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Dashboard</title>
    <?php include 'template/header.php'; ?>
</head>

<body>
    <div class="wrapper">
        <?php include 'template/sidebar.php'; ?>
        <main id="main" class="main">
            <?php include 'template/nav.php'; ?>

            <div class="pagetitle">
                <h1 class="mb-2">Data Customer</h1>
                <nav>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item active">Customers</li>
                    </ol>
                </nav>
            </div>

            <section class="section dashboard">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="row">
                            <div class="card shadow-lg">
                                <div class="card-body">
                                    <div class="d-flex justify-content-end">
                                        <button type="button" class="btn btn-primary mb-3 mt-3" data-bs-toggle="modal" data-bs-target="#CustomerModal">Tambah Data</button>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="table table-bordered display overflow-scroll" id="customerTable">
                                            <thead>
                                                <tr>
                                                    <th>NO</th>
                                                    <th>NIK</th>
                                                    <th>NO KK</th>
                                                    <th>Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $no = 1; ?>
                                                <?php while ($row = $result->fetch_assoc()) : ?>
                                                    <tr>
                                                        <td class="text-center"><?php echo $no++; ?></td>
                                                        <td><?php echo $row['nik']; ?></td>
                                                        <td><?php echo $row['no_kk']; ?></td>
                                                        <td><?php echo $row['name']; ?></td>
                                                        <td><?php echo $row['email']; ?></td>
                                                        <td><?php echo $row['phone']; ?></td>
                                                        <td><?php echo $row['address']; ?></td>
                                                        <td>
                                                            <button type="button" class="btn btn-info btn-sm view-btn" data-id="<?php echo $row['customer_id']; ?>">View</button>
                                                            <button type="button" class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['customer_id']; ?>" data-nik="<?php echo $row['nik']; ?>" data-no_kk="<?php echo $row['no_kk']; ?>" data-name="<?php echo $row['name']; ?>" data-email="<?php echo $row['email']; ?>" data-phone="<?php echo $row['phone']; ?>" data-address="<?php echo $row['address']; ?>" data-ktp="<?php echo $row['ktp']; ?>" data-kk="<?php echo $row['kk']; ?>" data-bs-toggle="modal" data-bs-target="#CustomerModal">Edit</button>
                                                            <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="<?php echo $row['customer_id']; ?>">Delete</button>
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

                <div class="modal fade" id="CustomerModal" tabindex="-1" aria-labelledby="CustomerModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <form method="POST" action="manage_customers.php" enctype="multipart/form-data">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="CustomerModalLabel">Customer</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <input type="hidden" name="customer_id" id="customer_id">
                                    <div class="mb-3">
                                        <label for="nik" class="form-label">NIK</label>
                                        <input type="number" class="form-control" name="nik" id="nik" placeholder="Enter NIK" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="no_kk" class="form-label">NO KK</label>
                                        <input type="number" class="form-control" name="no_kk" id="no_kk" placeholder="Enter NO KK" required>
                                    </div>
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
                                    <div class="mb-3">
                                        <label for="ktp" class="form-label">KTP (Upload Image)</label>
                                        <input type="file" class="form-control" name="ktp" id="ktp" accept="image/*">
                                    </div>
                                    <div class="mb-3">
                                        <label for="kk" class="form-label">KK (Upload Image)</label>
                                        <input type="file" class="form-control" name="kk" id="kk" accept="image/*">
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
    </div>

    <?php include 'template/footer.php'; ?>

    <script>
        $(document).ready(function() {
            const sidebarToggle = document.querySelector("#sidebar-toggle");
            sidebarToggle.addEventListener("click", function() {
                document.querySelector("#sidebar").classList.toggle("collapsed");
            });

            $('#customerTable').DataTable();

            $('.view-btn').click(function() {
                const customerId = $(this).data('id');
                window.location.href = 'view_customer.php?id=' + customerId;
            });

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
                $('#customer_id').val($(this).data('id'));
                $('#nik').val($(this).data('nik'));
                $('#no_kk').val($(this).data('no_kk'));
                $('#name').val($(this).data('name'));
                $('#email').val($(this).data('email'));
                $('#phone').val($(this).data('phone'));
                $('#address').val($(this).data('address'));
                $('#CustomerModal').modal('show');
            });

            // Clear modal fields when opening the modal for adding a new customer
            $('#CustomerModal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget);
                if (!button.hasClass('edit-btn')) {
                    $('#customer_id').val('');
                    $('#nik').val('');
                    $('#no_kk').val('');
                    $('#name').val('');
                    $('#email').val('');
                    $('#phone').val('');
                    $('#address').val('');
                    $('#ktp').val('');
                    $('#kk').val('');
                }
            });

            // Handle delete button click
            $('.delete-btn').click(function() {
                var customerId = $(this).data('id');
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
                        window.location.href = 'manage_customers.php?delete=' + customerId;
                    }
                });
            });

        });
    </script>

</body>

</html>