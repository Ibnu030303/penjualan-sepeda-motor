<?php
require_once __DIR__ . '/../../config.php';

function up($pdo) {
    $sql = "CREATE TABLE IF NOT EXISTS sale (
        sale_id INT(11) AUTO_INCREMENT PRIMARY KEY,
        customer_id INT(11) NOT NULL,
        motorcycle_id INT(11) NOT NULL,
        user_id INT(11) NOT NULL,
        sale_date DATE NOT NULL,
        total_price VARCHAR(20) COLLATE utf8mb4_general_ci NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        payment_type VARCHAR(10) COLLATE utf8mb4_general_ci NOT NULL,
        INDEX (customer_id),
        INDEX (motorcycle_id),
        INDEX (user_id),
        FOREIGN KEY (customer_id) REFERENCES customers(customer_id),
        FOREIGN KEY (motorcycle_id) REFERENCES motorcycles(motorcycle_id),
        FOREIGN KEY (user_id) REFERENCES users(user_id)
    ) ENGINE=INNODB;";

    try {
        $pdo->exec($sql);
        echo "Table 'sale' created successfully.\n";
    } catch (PDOException $e) {
        echo "Error creating table: " . $e->getMessage() . "\n";
    }
}

function down($pdo) {
    $sql = "DROP TABLE IF EXISTS sale;";

    try {
        $pdo->exec($sql);
        echo "Table 'sale' dropped successfully.\n";
    } catch (PDOException $e) {
        echo "Error dropping table: " . $e->getMessage() . "\n";
    }
}
?>
