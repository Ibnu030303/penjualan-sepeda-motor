<?php
$host = 'viaduct.proxy.rlwy.net';
$db = 'railway';
$user = 'root';
$pass = 'NffllskjJyRdpUivsWrLvenIvRQdLYYs';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;port=31143;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connection successful!\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
?>
