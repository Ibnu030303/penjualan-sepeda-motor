<?php
require_once __DIR__ . '/../../config.php';

function up($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS customers (
        customer_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nik VARCHAR(25) COLLATE utf8mb4_general_ci NOT NULL,
        no_kk VARCHAR(25) COLLATE utf8mb4_general_ci NOT NULL,
        name VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
        email VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
        phone VARCHAR(20) COLLATE utf8mb4_general_ci,
        address TEXT COLLATE utf8mb4_general_ci,
        ktp VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
        kk VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY email (email)
    ) ENGINE=INNODB;";

    try {
        $pdo->exec($sql);
        echo "Table 'customers' created successfully.\n";
    } catch (PDOException $e) {
        echo "Error creating table: " . $e->getMessage() . "\n";
    }
}

function down($pdo) {
    $sql = "DROP TABLE IF EXISTS customers;";

    try {
        $pdo->exec($sql);
        echo "Table 'customers' dropped successfully.\n";
    } catch (PDOException $e) {
        echo "Error dropping table: " . $e->getMessage() . "\n";
    }
}
?>
