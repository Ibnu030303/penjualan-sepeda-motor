<?php
require_once __DIR__ . '/../../config.php';

function up($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS users (
        user_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        nama CHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
        username VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
        password VARCHAR(255) COLLATE utf8mb4_general_ci NOT NULL,
        role ENUM('admin', 'sales') COLLATE utf8mb4_general_ci NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;";
    
    try {
        $pdo->exec($sql);
        echo "Table 'users' created successfully.\n";
    } catch (PDOException $e) {
        echo "Error creating table: " . $e->getMessage() . "\n";
    }
}

function down($pdo) {
    $sql = "DROP TABLE IF EXISTS users;";
    
    try {
        $pdo->exec($sql);
        echo "Table 'users' dropped successfully.\n";
    } catch (PDOException $e) {
        echo "Error dropping table: " . $e->getMessage() . "\n";
    }
}
?>
