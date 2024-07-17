<?php
require_once __DIR__ . '/../../config.php';

function seed($pdo) {
    $sql = "INSERT INTO users (nama, username, password, role) VALUES
            ('Admin One', 'admin1', :password1, 'admin'),
            ('Admin Two', 'admin2', :password2, 'admin'),
            ('Sales One', 'sales1', :password3, 'sales');
            ('Sales Two', 'sales2', :password2, 'sales');";
    
    try {
        // Hash passwords before inserting
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':password1' => password_hash('password1', PASSWORD_DEFAULT),
            ':password2' => password_hash('password2', PASSWORD_DEFAULT),
            ':password3' => password_hash('password3', PASSWORD_DEFAULT),
        ]);
        echo "Users seeded successfully.\n";
    } catch (PDOException $e) {
        echo "Error seeding users: " . $e->getMessage() . "\n";
    }
}

seed($pdo);
?>
