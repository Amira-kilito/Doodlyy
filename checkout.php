<?php
session_start();
include('config.php');

if (!isset($_SESSION['user_id'])) {
    $_SESSION['return_to'] = 'checkout.php';
    
    header('Location: login.php');
    exit;}
    
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header('Location: cart.php');
    exit;
}

//Check stock!
$total = 0;
$out_of_stock = false;
$stock_errors = [];

foreach ($_SESSION['cart'] as $index => $item) {
    $query = "SELECT stock FROM products WHERE id = " . $item['id'];
    $result = mysqli_query($conn, $query);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $product = mysqli_fetch_assoc($result);
        
        // Check if quantity exceeds current stock
        if ($item['quantity'] > $product['stock']) {
            if ($product['stock'] == 0) {
                $stock_errors[] = "'{$item['name']}' is no longer in stock.";
            } else {
                $_SESSION['cart'][$index]['quantity'] = $product['stock'];
                $stock_errors[] = "Quantity for '{$item['name']}' was adjusted to {$product['stock']} due to stock limitations.";
            }
        }
        
        // Update total
        $total += $item['price'] * $_SESSION['cart'][$index]['quantity'];
    }
}

//CHECKOUT PROCESS : recuperer les données 
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['place_order'])) {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    
    //if CREDIT CART IS SELECTED
    $card_data = [];
    if ($payment_method === 'credit_card') {
        $card_data = [ 'card_number' => mysqli_real_escape_string($conn, $_POST['card_number']),
            'card_expiry' => mysqli_real_escape_string($conn, $_POST['card_expiry']),
            'card_cvv' => mysqli_real_escape_string($conn, $_POST['card_cvv'])
        ];
    }
    
    // Create order 
    $user_id = $_SESSION['user_id'];
    $order_date = date('Y-m-d H:i:s');
    
    $query = "INSERT INTO orders (user_id, total, payment_method, shipping_address,city,postal_code, order_date, status) 
              VALUES ( '$user_id', '$total','$payment_method','$address','$city','$postal_code','$order_date','pending')";
    
    if (mysqli_query($conn, $query)) {
        $order_id = mysqli_insert_id($conn);
        
        //ADD ITEMS IN ORDERS-ITEMS TABLE
        foreach ($_SESSION['cart'] as $item) {
            $product_id = $item['id'];
            $quantity = $item['quantity'];
            $price = $item['price'];
            
            $query = "INSERT INTO order_items (order_id, product_id, quantity, unit_price) 
                      VALUES ('$order_id', '$product_id', '$quantity', '$price')";
            mysqli_query($conn, $query);
            
            //UPDATE STOCK
            $query = "UPDATE products SET stock = stock - $quantity WHERE id = $product_id";
            mysqli_query($conn, $query);
        }
        //RECUPERER ID 
        $_SESSION['last_order_id'] = $order_id;
       //VIDER PANIER
        $_SESSION['cart'] = [];
            header('Location: order_success.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Doodly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <style>
        .credit-card-form {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .credit-card-form.visible {
            max-height: 400px;
            transition: max-height 0.5s ease-in;
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="homepage.php" class="text-2xl font-bold text-purple-700">Doodly</a>
                <a href="cart.php" class="text-gray-600 hover:text-purple-600 flex items-center">
                    <i class="ph ph-arrow-left mr-1"></i> Back to Cart
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-8">Checkout</h1>
            
            <?php if (!empty($stock_errors)): ?>
                <div class="bg-amber-50 border-l-4 border-amber-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="ph ph-warning text-amber-400"></i>
                        </div>
                        <div class="ml-3">
                            <h3 class="text-sm font-medium text-amber-800">Attention needed</h3>
                            <div class="text-sm text-amber-700 mt-2">
                                <ul class="list-disc pl-5 space-y-1">
                                    <?php foreach($stock_errors as $error): ?>
                                        <li><?= $error ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Order Summary -->
                <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <h2 class="text-lg font-semibold text-gray-800">Order Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <?php foreach ($_SESSION['cart'] as $item): ?>
                                <div class="flex justify-between">
                                    <div class="flex">
                                        <img src="<?= htmlspecialchars($item['image']) ?>" alt="<?= htmlspecialchars($item['name']) ?>" class="h-14 w-14 rounded-md object-cover">
                                        <div class="ml-3">
                                            <p class="text-sm font-medium text-gray-900"><?= htmlspecialchars($item['name']) ?></p>
                                            <p class="text-sm text-gray-500">Qty: <?= $item['quantity'] ?></p>
                                        </div>
                                    </div>
                                    <p class="text-sm font-medium text-gray-900"><?= $item['price'] * $item['quantity'] ?> $</p>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        
                        <div class="border-t border-gray-200 mt-6 pt-6">
                            <div class="flex justify-between">
                                <p class="text-sm font-medium text-gray-900">Subtotal</p>
                                <p class="text-sm font-medium text-gray-900"><?= $total ?> $</p>
                            </div>
                            <div class="flex justify-between mt-2">
                                <p class="text-sm font-medium text-gray-900">Shipping</p>
                                <p class="text-sm font-medium text-gray-900">Free</p>
                            </div>
                            <div class="flex justify-between mt-4 pt-4 border-t border-gray-200">
                                <p class="text-base font-semibold text-gray-900">Total</p>
                                <p class="text-base font-semibold text-purple-700"><?= $total ?> $</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Shipping & Payment -->
                <div>
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800">Shipping Information</h2>
                        </div>
                        <div class="p-6">
                            <form id="checkout-form" method="POST">
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="first-name" class="block text-sm font-medium text-gray-700">First Name</label>
                                        <input type="text" id="first-name" name="first_name" required
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                                        <input type="text" id="last-name" name="last_name" required
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div class="col-span-2">
                                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                                        <input type="text" id="address" name="address" required
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="city" class="block text-sm font-medium text-gray-700">City</label>
                                        <input type="text" id="city" name="city" required
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div class="col-span-2 sm:col-span-1">
                                        <label for="postal-code" class="block text-sm font-medium text-gray-700">Postal Code</label>
                                        <input type="text" id="postal-code" name="postal_code" required
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div class="col-span-2">
                                        <label for="phone" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                        <input type="tel" id="phone" name="phone" required
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                </div>
                        </div>
                    </div>
                    
                    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                        <div class="px-6 py-4 border-b border-gray-200">
                            <h2 class="text-lg font-semibold text-gray-800">Payment Method</h2>
                        </div>
                        <div class="p-6">
                            <div class="space-y-4">
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="credit_card" checked
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="ml-2 text-gray-700">Credit Card</span>
                                </label>
                              
                                <label class="flex items-center">
                                    <input type="radio" name="payment_method" value="cash"
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300">
                                    <span class="ml-2 text-gray-700">Cash on Delivery</span>
                                </label>
                            </div>
                            
                            <!-- Credit Card Form - This will show/hide based on payment selection -->
                            <div id="credit-card-form" class="credit-card-form visible mt-4 border-t border-gray-200 pt-4">
                                <div class="space-y-4">
                                    <div>
                                        <label for="card-number" class="block text-sm font-medium text-gray-700">Card Number</label>
                                        <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456"
                                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div>
                                            <label for="card-expiry" class="block text-sm font-medium text-gray-700">Expiry Date</label>
                                            <input type="text" id="card-expiry" name="card_expiry" placeholder="MM/YY"
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                        </div>
                                        <div>
                                            <label for="card-cvv" class="block text-sm font-medium text-gray-700">CVV</label>
                                            <input type="text" id="card-cvv" name="card_cvv" placeholder="123"
                                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-purple-500 focus:border-purple-500">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-6">
                                <button type="submit" form="checkout-form" name="place_order"
                                        class="w-full bg-purple-700 hover:bg-purple-600 text-white py-3 px-4 rounded-lg font-medium transition-colors duration-200 flex items-center justify-center">
                                    <i class="ph ph-check-circle mr-2"></i> Place Order
                                </button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; <?= date('Y') ?> Doodly. All rights reserved.
            </p>
        </div>
    </footer>
    
    <script>
        // Toggle credit card form visibility based on payment method selection
        const paymentRadios = document.querySelectorAll('input[name="payment_method"]');
        const creditCardForm = document.getElementById('credit-card-form');
        
        // Function to toggle form visibility
        function toggleCreditCardForm() {
            const selectedPayment = document.querySelector('input[name="payment_method"]:checked').value;
            
            if (selectedPayment === 'credit_card') {
                creditCardForm.classList.add('visible');
                // Make credit card fields required
                document.getElementById('card-number').required = true;
                document.getElementById('card-expiry').required = true;
                document.getElementById('card-cvv').required = true;
            } else {
                creditCardForm.classList.remove('visible');
                // Make credit card fields not required
                document.getElementById('card-number').required = false;
                document.getElementById('card-expiry').required = false;
                document.getElementById('card-cvv').required = false;
            }
        }
        
        // Add event listeners to payment method radios
        paymentRadios.forEach(radio => {
            radio.addEventListener('change', toggleCreditCardForm);
        });
        
        // Initialize form state based on default selection
        toggleCreditCardForm();
    </script>
</body>
</html>