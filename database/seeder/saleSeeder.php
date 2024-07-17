<?php
require_once '../../config.php';

function getRandomDate($startDate, $endDate) {
    $timestamp = mt_rand(strtotime($startDate), strtotime($endDate));
    return date("Y-m-d", $timestamp);
}

function getRandomElement($array) {
    return $array[array_rand($array)];
}

// Fetch customer_ids
$customer_ids = [];
$sql_customers = "SELECT customer_id FROM customers";
$result_customers = $conn->query($sql_customers);
while ($row = $result_customers->fetch_assoc()) {
    $customer_ids[] = $row['customer_id'];
}

// Fetch motorcycle_ids
$motorcycle_ids = [];
$sql_motorcycles = "SELECT motorcycle_id FROM motorcycles";
$result_motorcycles = $conn->query($sql_motorcycles);
while ($row = $result_motorcycles->fetch_assoc()) {
    $motorcycle_ids[] = $row['motorcycle_id'];
}

// Fetch user_ids
$user_ids = [];
$sql_users = "SELECT user_id FROM users WHERE role = 'sales'";
$result_users = $conn->query($sql_users);
while ($row = $result_users->fetch_assoc()) {
    $user_ids[] = $row['user_id'];
}

$payment_types = ['Cash', 'Credit'];

for ($i = 0; $i < 20; $i++) {
    $customer_id = getRandomElement($customer_ids);
    $motorcycle_id = getRandomElement($motorcycle_ids);
    $user_id = getRandomElement($user_ids);
    $sale_date = getRandomDate('2023-01-01', '2023-12-31');

    // Fetch the price of the selected motorcycle
    $sql_price = "SELECT price FROM motorcycles WHERE motorcycle_id=?";
    $stmt_price = $conn->prepare($sql_price);
    $stmt_price->bind_param("i", $motorcycle_id);
    $stmt_price->execute();
    $stmt_price->bind_result($motorcycle_price);
    $stmt_price->fetch();
    $stmt_price->close();

    $total_price = $motorcycle_price; // Set total price to motorcycle price
    $payment_type = getRandomElement($payment_types);

    $sql = "INSERT INTO sale (customer_id, motorcycle_id, user_id, sale_date, total_price, payment_type) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiisss", $customer_id, $motorcycle_id, $user_id, $sale_date, $total_price, $payment_type);
    $stmt->execute();
}

echo "20 records inserted successfully.";
?>
