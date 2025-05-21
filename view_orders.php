<?php
session_start();
include('config.php');

if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid order ID.";
    exit;
}

$orderId = intval($_GET['id']);

$sql = "SELECT orders.id AS order_id, orders.order_date, orders.status, orders.total, 
               users.name, users.email, users.address
        FROM orders 
        JOIN users ON orders.user_id = users.id
        WHERE orders.id = $orderId";

$result = $conn->query($sql);

if (!$result || $result->num_rows === 0) {
    echo "Order not found.";
    exit;
}

$order = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Order Details #<?php echo htmlspecialchars($order['order_id']); ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-6">
    <div class="w-full max-w-lg bg-white rounded-lg shadow-lg p-8">
        <a href="orders.php" class="inline-block mb-6 text-indigo-600 hover:text-indigo-900 font-semibold">
            &larr; Back to Orders
        </a>
        <h1 class="text-3xl font-extrabold mb-8 text-gray-800">Order #<?php echo htmlspecialchars($order['order_id']); ?></h1>

        <div class="space-y-4 text-gray-700 text-lg">
            <div>
                <span class="font-semibold text-gray-900">Customer:</span> <?php echo htmlspecialchars($order['name']); ?>
            </div>
            <div>
                <span class="font-semibold text-gray-900">Email:</span> <?php echo htmlspecialchars($order['email']); ?>
            </div>
            <div>
                <span class="font-semibold text-gray-900">Address:</span> 
                <?php 
                echo $order['address'] ? htmlspecialchars($order['address']) : '<span class="italic text-gray-500">No address provided</span>'; 
                ?>
            </div>
            <div>
                <span class="font-semibold text-gray-900">Order Date:</span> <?php echo htmlspecialchars($order['order_date']); ?>
            </div>
            <div>
                <span class="font-semibold text-gray-900">Status:</span> 
                <span class="<?php 
                    $status = strtolower(trim($order['status']));
                    switch ($status) {
                        case 'delivered':
                            echo 'text-green-600 font-bold';
                            break;
                        case 'cancelled':
                            echo 'text-red-600 font-bold';
                            break;
                        case 'pending':
                            echo 'text-orange-400 font-bold';
                            break;
                        case 'shipped':
                            echo 'text-yellow-500 font-bold';
                            break;
                        default:
                            echo 'text-gray-600 font-bold';
                    }
                ?>">
                    <?php echo htmlspecialchars($order['status']); ?>
                </span>
            </div>
            <div>
                <span class="font-semibold text-gray-900">Total:</span> $<?php echo htmlspecialchars($order['total']); ?>
            </div>
        </div>
    </div>
</body>
</html>
