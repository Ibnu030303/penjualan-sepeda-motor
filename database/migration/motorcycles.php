<?php
require_once __DIR__ . '/../../config.php';

function up_motorcycles($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS motorcycles (
        motorcycle_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        model VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
        brand VARCHAR(100) COLLATE utf8mb4_general_ci NOT NULL,
        price INT(11) NOT NULL,
        warna VARCHAR(50) COLLATE utf8mb4_general_ci NOT NULL,
        stock INT(11) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=INNODB;";
    
    try {
        $pdo->exec($sql);
        echo "Table 'motorcycles' created successfully.\n";
    } catch (PDOException $e) {
        echo "Error creating table: " . $e->getMessage() . "\n";
    }
}

function down_motorcycles($pdo) {
    $sql = "DROP TABLE IF EXISTS motorcycles;";
    
    try {
        $pdo->exec($sql);
        echo "Table 'motorcycles' dropped successfully.\n";
    } catch (PDOException $e) {
        echo "Error dropping table: " . $e->getMessage() . "\n";
    }
}
?>
