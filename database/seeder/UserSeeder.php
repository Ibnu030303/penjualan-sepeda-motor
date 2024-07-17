<?php
require_once __DIR__ . '/../../config.php';

function UserSeeder($pdo) {
    $users = [
        ['nama' => 'Admin User', 'username' => 'admin', 'password' => password_hash('admin123', PASSWORD_BCRYPT), 'role' => 'admin'],
        ['nama' => 'Sales User 1', 'username' => 'sales1', 'password' => password_hash('sales123', PASSWORD_BCRYPT), 'role' => 'sales'],
        ['nama' => 'Sales User 2', 'username' => 'sales2', 'password' => password_hash('sales123', PASSWORD_BCRYPT), 'role' => 'sales']
    ];

    foreach ($users as $user) {
        $sql = "INSERT INTO users (nama, username, password, role, created_at) VALUES (:nama, :username, :password, :role, CURRENT_TIMESTAMP)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($user);
    }

    echo "Users seeded successfully.\n";
}
?>
