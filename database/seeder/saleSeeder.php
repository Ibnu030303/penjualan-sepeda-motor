<?php
require_once __DIR__ . '/../../config.php';

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
$stmt_customers = $pdo->query($sql_customers);
while ($row = $stmt_customers->fetch(PDO::FETCH_ASSOC)) {
    $customer_ids[] = $row['customer_id'];
}

// Fetch motorcycle_ids
$motorcycle_ids = [];
$sql_motorcycles = "SELECT motorcycle_id FROM motorcycles";
$stmt_motorcycles = $pdo->query($sql_motorcycles);
while ($row = $stmt_motorcycles->fetch(PDO::FETCH_ASSOC)) {
    $motorcycle_ids[] = $row['motorcycle_id'];
}

// Fetch user_ids
$user_ids = [];
$sql_users = "SELECT user_id FROM users WHERE role = 'sales'";
$stmt_users = $pdo->query($sql_users);
while ($row = $stmt_users->fetch(PDO::FETCH_ASSOC)) {
    $user_ids[] = $row['user_id'];
}

$payment_types = ['Cash', 'Credit'];

for ($i = 0; $i < 20; $i++) {
    $customer_id = getRandomElement($customer_ids);
    $motorcycle_id = getRandomElement($motorcycle_ids);
    $user_id = getRandomElement($user_ids);
    $sale_date = getRandomDate('2023-01-01', '2023-12-31');

    // Fetch the price of the selected motorcycle
    $sql_price = "SELECT price FROM motorcycles WHERE motorcycle_id = ?";
    $stmt_price = $pdo->prepare($sql_price);
    $stmt_price->execute([$motorcycle_id]);
    $motorcycle_price = $stmt_price->fetchColumn();

    $total_price = $motorcycle_price; // Set total price to motorcycle price
    $payment_type = getRandomElement($payment_types);

    $sql = "INSERT INTO sale (customer_id, motorcycle_id, user_id, sale_date, total_price, payment_type) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$customer_id, $motorcycle_id, $user_id, $sale_date, $total_price, $payment_type]);
}

echo "20 records inserted successfully.";
?>
