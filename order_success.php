<?php
session_start();
include('config.php');

// Check if user came from checkout process
if (!isset($_SESSION['last_order_id'])) {
    header('Location: homepage.php');
    exit;
}

$order_id = $_SESSION['last_order_id'];

//order details
$query = "SELECT * FROM orders WHERE id = $order_id";
$order_result = mysqli_query($conn, $query);

if ($order_result && mysqli_num_rows($order_result) > 0) {
    $order = mysqli_fetch_assoc($order_result);
    
    // Get order items
    $query = "SELECT oi.*, p.name, p.image FROM order_items oi 
              JOIN products p ON oi.product_id = p.id  WHERE oi.order_id = $order_id";
    $items_result = mysqli_query($conn, $query);
    
    $order_items = [];
    if ($items_result) {
        while ($item = mysqli_fetch_assoc($items_result)) {
            $order_items[] = $item;
        }
    }
} 


 unset($_SESSION['last_order_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Confirmed - Doodly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out forwards;
        }
        
        .animate-delay-1 {
            animation-delay: 0.1s;
        }
        
        .animate-delay-2 {
            animation-delay: 0.2s;
        }
        
        .animate-delay-3 {
            animation-delay: 0.3s;
        }
        
        .animate-delay-4 {
            animation-delay: 0.4s;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="homepage.php" class="text-2xl font-bold text-purple-700">Doodly</a>
            </div>
        </div>
    </header>









    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="max-w-3xl mx-auto">
                <!-- Success Message -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 animate-fade-in">
                    <div class="bg-green-500 px-6 py-4 flex items-center">
                        <i class="ph ph-check-circle text-white text-2xl"></i>
                        <h1 class="text-xl font-bold text-white ml-3">Order Confirmed!</h1>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">Thank you for your purchase. Your order has been successfully placed and is being processed.</p>
                        
                        <div class="bg-gray-50 rounded-lg p-4 mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Order Number:</span>
                                <span class="text-sm font-medium">#<?= $order_id ?></span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-500">Date:</span>
                        <span class="text-sm font-medium"><?= date('F j, Y', strtotime($order['order_date'])) ?></span>
                            </div>
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-sm text-gray-500">Payment Method:</span>
                                <span class="text-sm font-medium"><?= ($order['payment_method']) ?></span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-sm text-gray-500">Total:</span>
                                <span class="text-sm font-bold text-purple-700"><?= $order['total'] ?> $</span>
                            </div>
                        </div>
                        
                        <p class="text-gray-600">
                            <?php if ($order['payment_method'] === 'cash'): ?>
                                Your order will be delivered to:
                            <?php else: ?>
                                Your payment has been processed successfully. Your order will be delivered to:
                            <?php endif; ?>
                            <span class="font-medium block mt-1"><?= htmlspecialchars($order['shipping_address']) ?></span>
                        </p>
                    </div>
                </div>
                
                <!-- Order Items -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8 animate-fade-in animate-delay-1">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Order Items</h2>
                    </div>
                    <div class="p-6">
                        <div class="divide-y divide-gray-200">
                            <?php foreach ($order_items as $item): ?>
                                <div class="py-4 flex justify-between">
                                    <div class="flex">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-16 w-16 rounded-md object-cover">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></p>
                                            <p class="text-sm text-gray-500">Qty: <?= $item['quantity'] ?></p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900"><?= $item['unit_price'] * $item['quantity'] ?> $</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white rounded-xl shadow-sm overflow-hidden animate-fade-in animate-delay-2">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">What's Next?</h2>
                    </div>
                    <div class="p-6">
                        <p class="text-gray-600 mb-4">We'll send you shipping confirmation and tracking information once your order is on its way. In the meantime:</p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-6">
                            <a href="homepage.php" class="flex items-center justify-center px-4 py-3 border border-purple-700 text-purple-700 rounded-lg hover:bg-purple-50 transition-colors duration-200">
                                <i class="ph ph-shopping-bag mr-2"></i>
                                Continue Shopping
                            </a>
                           
                        </div>
                    </div>
                </div>
                
                <div class="text-center mt-8 text-sm text-gray-500 animate-fade-in animate-delay-3">
                    <p>A confirmation email has been sent to your registered email address.</p>
                </div>
            </div>
        </div>
    </main>







    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; <?= date('Y') ?> Doodly. All rights reserved.
            </p>
        </div>
    </footer>
</body>
</html>