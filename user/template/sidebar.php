<aside id="sidebar" class="js-sidebar">
    <div class="h-100">
        <div class="sidebar-logo">
            <a href="#">Sales Dashboard</a>
        </div>
        <ul class="sidebar-nav">
            <li class="sidebar-item">
                <a href="http://localhost/Penjualan/user/index.php" class="sidebar-link">
                    <i class="lni lni-grid-alt"></i> Dashboard
                </a>
            </li>
            <li class="sidebar-item">
                <a href="http://localhost/Penjualan/user/view_motorcycles.php" class="sidebar-link">
                    <i class="lni lni-webhooks"></i> Motor
                </a>
            </li>
            <li class="sidebar-item">
                <a href="http://localhost/Penjualan/user/manage_customers.php" class="sidebar-link">
                    <i class="lni lni-customer"></i> Customer
                </a>
            </li>
            <li class="sidebar-item">
                <a href="http://localhost/Penjualan/user/manage_orders.php" class="sidebar-link">
                    <i class="lni lni-dollar"></i> Sale
                </a>
            </li>
            <li class="sidebar-item">
                <a href="http://localhost/Penjualan/user/sales_performance.php" class="sidebar-link">
                    <i class="lni lni-stats-up"></i> Sales Report
                </a>
            </li>
        </ul>
        <div class="sidebar-footer">
            <a href="#" class="sidebar-link" onclick="confirmLogout();">
                <i class="lni lni-exit"></i> Logout
            </a>
        </div>
    </div>
</aside>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function confirmLogout() {
    Swal.fire({
        title: 'Are you sure?',
        text: "You will be logged out!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, logout!',
        cancelButtonText: 'No, stay logged in!'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = "http://localhost/Penjualan/logout.php";
        }
    });
}
</script>
