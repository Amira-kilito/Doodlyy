<?php
session_start();
include('config.php');

if (!isset($_SESSION['cart'])) 
{ $_SESSION['cart'] = [];}


if (isset($_GET['action']) && $_GET['action'] === 'checkout') {
    if (isset($_SESSION['user_id'])) {
        header("Location: checkout.php");
        exit; } 
        else { $_SESSION['redirect_after_login'] = "cart.php?action=checkout";
        header("Location: login.php");
        exit;}
}

// DELETE FROM CART
if (isset($_POST['remove_item']) && isset($_POST['item_index'])) {
    $index = (int)$_POST['item_index'];
    if (isset($_SESSION['cart'][$index])) 
    {unset($_SESSION['cart'][$index]);
}
    header("Location: cart.php");
    exit;}

//UPDATE CART QUANTITY
if (isset($_POST['update_quantity']) && isset($_POST['item_index']) && isset($_POST['quantity'])) {
    $index = $_POST['item_index'];
    $quantity = $_POST['quantity'];

    if (isset($_SESSION['cart'][$index])) {
        $product_id = $_SESSION['cart'][$index]['id'];
        $query = "SELECT stock FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $query);

        if ($result && mysqli_num_rows($result) > 0) {
            $product = mysqli_fetch_assoc($result);
            if ($quantity > 0 && $quantity <= $product['stock'])
             {$_SESSION['cart'][$index]['quantity'] = $quantity; }
        }
    }
              header("Location: cart.php");
             exit;
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shopping Cart - Doodly</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 flex justify-between">
            <div>
            <a href="user_homepage.php">
            <img src="Doodly images/DOODLY.png" alt="Doodly Logo" class="w-24"> </a>
                </div>
            <a href="homepage.php" class="text-gray-600 hover:text-purple-600 pt-8 pb-2">Continue Shopping -> </a>
        </div>
    </header>

    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 py-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-8">Your Shopping Cart</h1>

            <?php if (empty($_SESSION['cart'])): ?>
                <div class="bg-white p-8 rounded-xl shadow text-center">
                    <p class="text-xl font-semibold mb-2">Your cart is empty</p>
                    <a href="homepage.php" class="bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-md">Start Shopping</a> </div>
            <?php else: ?>
                <div class="bg-white rounded-xl shadow overflow-hidden">
             <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50"> <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase ">Quantity</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                          </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($_SESSION['cart'] as $index => $item): ?>
                                <tr>
                                    <td class="px-6 py-4">
                                  <div class="flex items-center">
                                    <img class="h-16 w-16 rounded-md object-cover" src="<?=($item['image']) ?>" alt="">
                                    <div class="ml-4 text-sm font-medium text-gray-900"><?= ($item['name']) ?></div>  </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900"><?=$item['price']?> $</td>
                                 <td class="px-6 py-4">
    <form action="cart.php" method="POST" class="flex items-center space-x-2 update-form">
        <input type="hidden" name="item_index" value="<?= $index ?>">
        <input  type="number" name="quantity" value="<?= $item['quantity'] ?>" min="1" max="<?= $item['stock'] ?>" 
            class="w-16 border rounded px-2 py-1 text-sm quantity-input" >
        <input type="hidden" name="update_quantity" value="1">
    </form>
</td>

  <td class="px-6 py-4 text-sm text-gray-900"><?= $item['price'] * $item['quantity'] ?> $</td>
 <td class="px-6 py-4">
 <form action="cart.php" method="POST">
 <input type="hidden" name="item_index" value="<?= $index ?>">
 <button type="submit" name="remove_item" class="text-red-600 hover:text-red-800 text-sm">Remove</button>
</form></td>
    </tr>
                      <?php endforeach; ?>
                        </tbody>
                         </table>
                    </div>

                <div class="mt-6 flex justify-between items-center">
                  <div class="text-lg font-semibold">Total: <?=$total ?> $</div>
                <a href="cart.php?action=checkout" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md">
                 Proceed to Checkout
             </a> </div>
            <?php endif; ?> </div>
            </main>




<script>
document.querySelectorAll('.quantity-input').forEach(input => {input.addEventListener('change', function () {
        this.closest('form').submit();
    });
});
</script>


</body>
</html>
