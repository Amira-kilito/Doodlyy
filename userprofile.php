<?php
include('config.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php"); 
    exit;
}

// users information
$user_id = $_SESSION['user_id'];
$user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
$user = $user_query->fetch_assoc();

// EDITING PROFILE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $phone = $conn->real_escape_string($_POST['phone']);

    $conn->query("UPDATE users SET name='$name', email='$email', address='$address', phone='$phone' WHERE id=$user_id");
    $user_query = $conn->query("SELECT * FROM users WHERE id = $user_id");
    $user = $user_query->fetch_assoc();
}

//ORDERS HISTORY

$order_query = $conn->query("SELECT o.id AS order_id, o.order_date, o.total, o.status, oi.product_id, oi.quantity, p.name AS product_name, p.image AS product_image 
                              FROM orders o JOIN order_items oi ON o.id = oi.order_id 
                                            JOIN products p ON oi.product_id = p.id WHERE o.user_id = $user_id 
                                             ORDER BY o.order_date DESC");

//DELETE ACCOUNT
if (isset($_POST['delete_account'])) {
    $conn->query("DELETE FROM users WHERE id = $user_id");
    session_destroy();
    header("Location: goodbye.php"); 
    exit;
}

//PANIER
$cart_count = isset($_SESSION['cart']) ? count($_SESSION['cart']) : 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Profile - Doodly</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
 <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto">
     <div class="flex items-center justify-between px-4  sm:px-6 lg:px-8">
<div>
    <a href="homepage.php">
            <img src="Doodly images/DOODLY.png" alt="Doodly Logo" class="w-24"> </a>
        </div>  

         <div class="flex items-center space-x-6">
                    
         
         
         
                                                        <!-- MENU -->
                    <div class="relative group">

                      <a href="cart.php" class="relative text-gray-600 hover:text-purple-600">
                        <img src="Doodly images/cart.png" alt="Shopping Cart" class="w-6 h-6">
                        <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs px-1.5 py-0.5 rounded-full">
                            <?php echo $cart_count; ?>
                        </span>
                        <button class="text-gray-600 hover:text-purple-600 text-xl focus:outline-none ml-4">
                            <i class="ph ph-user"></i>
                        </button>
                        <div class="relative group">
    
                    <div class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg opacity-0  group-hover:opacity-100 group-hover:pointer-events-auto transition z-10">
                        <a href="userprofile.php" class="block px-4 py-2 hover:bg-purple-100">Profile</a>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-purple-100">Logout</a>
                    </div>
                </div>
                    </div>
                
                    
                </div>
            </div>
        </div>
    </header>

    






    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <h2 class="text-2xl font-semibold mb-6">Your Profile</h2>
            <div class="bg-white rounded-lg shadow-md p-6">
                
        <!-- formulaire user informations !-->    
<form method="POST">
                    <div class="mb-4">
                        <label for="name" class="block text-gray-700">Name</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($user['name']) ?>" required class="border rounded w-full p-2"></div>
                    <div class="mb-4">
                        <label for="email" class="block text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']) ?>" required class="border rounded w-full p-2"></div>
                    <div class="mb-4">
                        <label for="address" class="block text-gray-700">Address</label>
                        <input type="text" name="address" id="address" value="<?= htmlspecialchars($user['address']) ?>" class="border rounded w-full p-2"></div>
                    <div class="mb-4">
                        <label for="phone" class="block text-gray-700">Phone</label>
                        <input type="text" name="phone" id="phone" value="<?= htmlspecialchars($user['phone']) ?>" pattern="^\+?[0-9]{10,15}$" title="Phone number must be between 10 to 15 digits and can start with a +." class="border rounded w-full p-2" required>
                    </div>
                    <button type="submit" name="update_profile" class="bg-purple-600 text-white rounded p-2">Update Profile</button>
</form>

                <form method="POST" class="mt-4">
                    <button type="submit" name="delete_account" class="bg-red-600 text-white rounded p-2">Delete Account</button>
                </form>
            </div>

 <h3 class="text-2xl font-bold mt-10 mb-4 text-gray-800">Order History</h3>
<div class="bg-white rounded-xl shadow p-6">
    <?php if ($order_query->num_rows > 0): ?>
        <ul class="space-y-8">
            <?php 
            $current_order_id = null;
            while ($order = $order_query->fetch_assoc()): 
                if ($current_order_id !== $order['order_id']): 
                    if ($current_order_id !== null) echo '</ul></li>'; 
            ?>
                <li class="border-b pb-6">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div>
                            <p class="text-base text-gray-700"><span class="font-medium">Order number:</span> <?= htmlspecialchars($order['order_id']) ?></p>
                            <p class="text-sm text-gray-500"><span class="font-medium">Date:</span> <?= htmlspecialchars($order['order_date']) ?></p>
                        </div>
                        <div class="text-right mt-2 md:mt-0">
                            <p class="text-lg font-semibold text-gray-900">Total: $<?= htmlspecialchars($order['total']) ?></p>
                            <span class="
                                inline-block mt-1 px-3 py-1 rounded-full text-white text-sm font-medium 
                                <?php
                                    $status = $order['status'];
                                    if ($status === 'delivered') echo 'bg-green-500';
                                    elseif ($status === 'pending') echo 'bg-orange-500';
                                    elseif ($status === 'shipped') echo 'bg-yellow-500 text-black';
                                    elseif ($status === 'cancelled') echo 'bg-red-500';
                                    else echo 'bg-gray-500';
                                ?>
                            ">
                                <?= htmlspecialchars($order['status']) ?>
                            </span>
                        </div>
                    </div>
                    <ul class="space-y-4">
            <?php 
                    $current_order_id = $order['order_id']; 
                endif; 
            ?>
                <li class="flex items-center gap-4">
                    <img src="<?= htmlspecialchars($order['product_image']) ?>" alt="<?= htmlspecialchars($order['product_name']) ?>" class="w-20 h-20 object-cover rounded border">
                    <div class="text-gray-700">
                        <p><span class="font-medium">Product:</span> <?= htmlspecialchars($order['product_name']) ?></p>
                        <p><span class="font-medium">Quantity:</span> <?= htmlspecialchars($order['quantity']) ?></p>
                    </div>
                </li>
            <?php endwhile; ?>
            </ul></li>
        </ul>
    <?php else: ?>
        <p class="text-gray-600">No orders found.</p>
    <?php endif; ?>
</div>
