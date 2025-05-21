<?php
session_start();
include('config.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: homepage.php");
    exit;
}

$product = $result->fetch_assoc();
$stmt->close();

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($quantity < 1) { $quantity = 1;}
    if ($quantity > $product['stock']) {
        $quantity = $product['stock']; }
    
    
    $found = false;
    foreach ($_SESSION['cart'] as $key => $item) {
        if ($item['id'] == $product_id) {
            $new_quantity = $item['quantity'] + $quantity;
            if ($new_quantity > $product['stock']) {
                $new_quantity = $product['stock'];
            }
            $_SESSION['cart'][$key]['quantity'] = $new_quantity;
            $found = true;
            break;
        }
    }
    
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'image' => $product['image'],
            'quantity' => $quantity,
            'stock' => $product['stock']
        ];
    }
    
    header("Location: product.php?id=" . $product_id);
    exit;
    
    exit;
}

// Get cart count
$cart_count = 0;
foreach ($_SESSION['cart'] as $item) {
    $cart_count += $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($product['name']) ?> - Doodly</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
                <a href="homepage.php" class="text-2xl font-bold text-purple-700">Doodly</a>
                <div class="flex items-center space-x-4">
                    <a href="cart.php" class="relative text-gray-600 hover:text-purple-600">
                        <i class="ph ph-shopping-cart text-xl"></i>
                        <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs px-1.5 py-0.5 rounded-full"><?= $cart_count ?></span>
                    </a>
                </div>
            </div>
        </div>
    </header>





    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 py-8">
            <div class="flex flex-col md:flex-row gap-8">
                <!-- Product Image -->
                <div class="md:w-1/2">
                    <img src="<?= htmlspecialchars($product['image']) ?>" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         class="w-full h-auto rounded-lg shadow-lg">
                </div>

                <!-- Product Details -->
                <div class="md:w-1/2">
                    <h1 class="text-3xl font-bold text-gray-900 mb-4"><?= htmlspecialchars($product['name']) ?></h1>
                    <p class="text-2xl text-purple-600 font-semibold mb-4">$<?= number_format($product['price'], 2) ?></p>
                    
                    <?php if ($product['stock'] > 0): ?>
                        <p class="text-green-600 mb-6">
                            <i class="ph ph-check-circle"></i> 
                            In Stock (<?= $product['stock'] ?> available)
                        </p>
                    <?php else: ?>
                        <p class="text-red-600 mb-6">
                            <i class="ph ph-x-circle"></i>
                            Out of Stock
                        </p>
                    <?php endif; ?>

                    <p class="text-gray-600 mb-8"><?= htmlspecialchars($product['description']) ?></p>

                    <form method="POST" class="space-y-6">
                        <div>
                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                            <input type="number" 
                                   id="quantity" 
                                   name="quantity" 
                                   value="1" 
                                   min="1" 
                                   max="<?= $product['stock'] ?>" 
                                   class="w-20 rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500"
                                   <?= $product['stock'] === 0 ? 'disabled' : '' ?>>
                        </div>

                        <button type="submit" 
                                name="add_to_cart" 
                                class="w-full bg-purple-600 text-white py-3 px-6 rounded-lg font-medium hover:bg-purple-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
                                <?= $product['stock'] === 0 ? 'disabled' : '' ?>>
                            <?= $product['stock'] === 0 ? 'Out of Stock' : 'Add to Cart' ?>
                        </button>
                    </form>
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





































    <footer class="bg-gray-100 h-[200px] flex items-center relative">
        <div class="container mx-auto px-8 md:px-16 w-full flex justify-between items-center">
            
            <!-- Left: Logo -->
            <div>
                <img src="Doodly images/DOODLY.png" alt="Doodly Logo" class="w-24">
            </div>
    
            <!-- Center: Navigation & Contact -->
            <div class="flex space-x-16">
                <!-- Navigation Links -->
                <div class="flex flex-col text-sm text-gray-700 space-y-1">
                    <a href="#" class="hover:text-gray-500">Home</a>
                    <a href="#" class="hover:text-gray-500">About Us</a>
                    <a href="#" class="hover:text-gray-500">Contact Us</a>
                    <a href="#" class="hover:text-gray-500">Privacy Policy | Terms of Service</a>
                </div>
    
                <!-- Contact Info -->
                <div class="flex flex-col text-sm text-gray-700 space-y-1">
                    <a href="#" > doodly@gmail.com </a>
                    <p>+212 97 58 32 47</p>
                    <p>20 Rue Biyara Boukroune</p>
                    <a href="#" >Return & Refund Policy</a>
                </div>
            </div>
    
            <!-- Right: Social Media -->
            <div class="flex flex-col items-center space-y-1">
                <p class="text-sm font-semibold">Follow Us:</p>
                <div class="flex space-x-2">
                    <a href="#"><img src="Doodly images/fcb.png" alt="facebook" class="w-5"></a>
                    <a href="#"><img src="Doodly images/instagram.png" alt="Instagram" class="w-5"></a>
                </div>
            </div>
        </div>
    
        <!-- Bottom Right: Copyright -->
        <div class="absolute bottom-4 right-6 text-sm text-gray-700">
            © 2025 Doodly. All Rights Reserved
        </div>
    </footer>






  
  </body>
</html>