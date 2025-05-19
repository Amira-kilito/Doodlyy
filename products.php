<?php
include('config.php'); 

// SHOW PRODUCTS FROM DB
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Dashboard</title>
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
                <a href="dmin_dashboard.php" 
                    class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">
                    Dashboard
                </a>
                <a href="products.php" 
                    class="block w-full text-left py-2 px-4 rounded bg-purple-100 text-purple-700">
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

    <!-- ADD PRODUCTS-->
        <main class="flex-1 p-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Products</h2>
                <a href="add_products.php" 
                    class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-2 rounded-lg transition-colors">
                    Add New Product
                </a>
            </div>



      <!-- PRODUCTS TABLE-->
  

  <div class="bg-white rounded-lg shadow-sm overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
      <thead class="bg-gray-50">
        <tr>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
          <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-200">
        <?php if ($result->num_rows > 0): ?>
          <?php while($product = $result->fetch_assoc()): ?>
            <tr>
              <td class="px-6 py-4">
                <div class="flex items-center">
                  <div class="h-10 w-10 flex-shrink-0">
                    <img class="h-10 w-10 rounded-full object-cover"
                      src="uploads/<?php echo htmlspecialchars($product['image']); ?>"
                      alt="<?php echo htmlspecialchars($product['name']); ?>">
                  </div>
                  <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($product['name']); ?></div>
                  </div>
                </div>
              </td>
              <td class="px-6 py-4">$<?php echo number_format($product['price'], 2); ?></td>
              <td class="px-6 py-4">
                <?php if ($product['stock'] > 0): ?>
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                    In Stock (<?php echo intval($product['stock']); ?>)
                  </span>
                <?php else: ?>
                  <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                    Out of Stock
                  </span>
                <?php endif; ?>
              </td>
              <td class="px-6 py-4 text-sm font-medium">
                <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                <a href="delete_product.php?id=<?php echo $product['id']; ?>" class="text-red-600 hover:text-red-900" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
              </td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr>
            <td colspan="4" class="text-center py-4 text-gray-500">No products found.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>

        </main>
    </div>
</body>
</html>