<?php
session_start();
include('config.php');
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-lg p-6">
            <div class="flex items-center mb-8">
                <h1 class="text-2xl font-bold text-purple-600">Admin Dashboard</h1>
            </div>
            <nav class="space-y-2">
                <a href="admin_dashboard.php" 
                    class="block w-full text-left py-2 px-4 rounded bg-purple-100 text-purple-700">
                    Dashboard
                </a>
                <a href="products.php" 
                    class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">
                    Products
                </a>
                <a href="orders.php" 
                    class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">
                    Orders
                </a>
                <a href="users.php" 
                    class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">
                    Users
                </a>
               <a href="logout.php"
   class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100 text-red-600"
   onclick="return confirm('Are you sure you want to log out?');">
   Logout
</a>

            </nav>
        </aside>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-4">Dashboard Overview</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-gray-500 text-sm font-medium">Total Users</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2">
<?php  $total_users = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
echo $total_users; ?>


                    </p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-gray-500 text-sm font-medium">Total Products</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2"> <?php
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
echo $total_products;?></p> 
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-gray-500 text-sm font-medium">Total Orders</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php $total_orders = $conn->query("SELECT COUNT(*) as count FROM orders")->fetch_assoc()['count'];
echo $total_orders?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <h3 class="text-gray-500 text-sm font-medium">Total Sales</h3>
                    <p class="text-3xl font-bold text-gray-900 mt-2"><?php $total_sales = $conn->query("SELECT SUM(total) as sum FROM orders WHERE status='delivered'")->fetch_assoc()['sum'] ?? 0;
echo $total_sales?></p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>