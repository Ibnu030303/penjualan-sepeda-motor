<?php
require_once '../../config.php';

function getRandomElement($array) {
    return $array[array_rand($array)];
}

function getUniqueEmail($name, $existingEmails) {
    $domains = ['gmail.com', 'yahoo.com', 'hotmail.com'];
    do {
        $domain = getRandomElement($domains);
        $email = strtolower(str_replace(' ', '.', $name)) . rand(1, 100) . '@' . $domain; // Adding a random number to ensure uniqueness
    } while (in_array($email, $existingEmails));
    
    return $email;
}

function getRandomPhone() {
    return '08' . rand(1000000000, 9999999999);
}

function getRandomAddress() {
    $streets = ['Jalan Sudirman', 'Jalan Thamrin', 'Jalan Gajah Mada', 'Jalan Diponegoro', 'Jalan Pahlawan'];
    $cities = ['Jakarta', 'Bandung', 'Surabaya', 'Medan', 'Makassar'];
    return getRandomElement($streets) . ', ' . getRandomElement($cities);
}

$names = [
    'Ahmad Fauzi', 'Sri Wahyuni', 'Budi Santoso', 'Siti Aminah', 'Dewi Sartika', 
    'Agus Susanto', 'Nur Aini', 'Yusuf Hidayat', 'Ratna Sari', 'Indra Wijaya', 
    'Fitriani', 'Rina Susanti', 'Hendra Setiawan', 'Maria Ulfah', 'Adi Putra', 
    'Ani Purwanti', 'Joko Purwanto', 'Tina Susanti', 'Nina Mariani', 'Lukman Hakim'
];

// Fetch existing emails to avoid duplicates
$existingEmails = [];
$result = $conn->query("SELECT email FROM customers");
while ($row = $result->fetch_assoc()) {
    $existingEmails[] = $row['email'];
}

for ($i = 0; $i < 20; $i++) {
    $name = getRandomElement($names);
    $nik = rand(1000000000000000, 9999999999999999);
    $no_kk = rand(1000000000000000, 9999999999999999);
    $email = getUniqueEmail($name, $existingEmails);
    $phone = getRandomPhone();
    $address = getRandomAddress();
    $ktp = 'ktp_' . $i . '.jpg';
    $kk = 'kk_' . $i . '.jpg';

    $sql = "INSERT INTO customers (nik, no_kk, name, email, phone, address, ktp, kk, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, current_timestamp())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssssss", $nik, $no_kk, $name, $email, $phone, $address, $ktp, $kk);

    if ($stmt->execute()) {
        $existingEmails[] = $email; // Add to the list of existing emails
    } else {
        echo "Error: " . $stmt->error . "<br>";
    }
}

echo "20 records inserted successfully.";
?>
