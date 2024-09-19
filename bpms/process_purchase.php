<?php
// Connect to database
$host = '127.0.0.1';
$db = 'products_db';
$user = 'root';
$pass = '';  // Your DB password here
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Get the posted data
$data = json_decode(file_get_contents('php://input'), true);

if (isset($data['name'], $data['address'], $data['cart'])) {
    $name = $data['name'];
    $address = $data['address'];
    $cart = $data['cart'];

    // Calculate total price
    $total_price = 0;
    foreach ($cart as $item) {
        $total_price += $item['price'];
    }

    // Insert order into `orders` table
    $stmt = $pdo->prepare('INSERT INTO orders (user_name, address, total_price) VALUES (?, ?, ?)');
    $stmt->execute([$name, $address, $total_price]);

    // Get the last inserted order ID
    $order_id = $pdo->lastInsertId();

    // Insert each cart item into `order_items` table
    foreach ($cart as $item) {
        $stmt = $pdo->prepare('INSERT INTO order_items (order_id, product_name, quantity, price) VALUES (?, ?, ?, ?)');
        $stmt->execute([$order_id, $item['name'], 1, $item['price']]); // Assuming quantity = 1
    }

    // Return success response
    echo json_encode(['success' => true]);
} else {
    // Return failure response
    echo json_encode(['success' => false]);
}
