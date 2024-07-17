<?php
require_once __DIR__ . '/../../config.php';

function getRandomElement($array) {
    return $array[array_rand($array)];
}

$brands = ['Honda', 'Yamaha', 'Suzuki', 'Kawasaki', 'Vespa'];
$models = [
    'Honda' => ['Beat', 'Vario', 'PCX', 'Scoopy', 'CBR'],
    'Yamaha' => ['Nmax', 'Aerox', 'Mio', 'R15', 'MT-15'],
    'Suzuki' => ['GSX-R150', 'Satria F150', 'Nex II', 'Address', 'Smash'],
    'Kawasaki' => ['Ninja 250', 'KLX 150', 'W175', 'Versys-X', 'Z250'],
    'Vespa' => ['Primavera', 'Sprint', 'GTS', 'S', '946']
];
$colors = ['Hitam', 'Putih', 'Merah', 'Biru', 'Abu-abu'];

for ($i = 0; $i < 20; $i++) {
    $brand = getRandomElement($brands);
    $model = getRandomElement($models[$brand]);
    $price = rand(15000000, 60000000);
    $color = getRandomElement($colors);
    $stock = rand(1, 10);

    $sql = "INSERT INTO motorcycles (model, brand, price, warna, stock, created_at) VALUES (?, ?, ?, ?, ?, current_timestamp())";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(1, $model);
    $stmt->bindParam(2, $brand);
    $stmt->bindParam(3, $price);
    $stmt->bindParam(4, $color);
    $stmt->bindParam(5, $stock);
    $stmt->execute();
}

echo "20 records inserted successfully.";
?>
