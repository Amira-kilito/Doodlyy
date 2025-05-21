<?php
include('config.php');

$errors = [];
$success = false;

// Get product ID from query string, e.g. edit_product.php?id=123
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid product ID.");
}

$product_id = intval($_GET['id']);

// Fetch existing product data
$sql = "SELECT * FROM products WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Product not found.");
}

$product = $result->fetch_assoc();

// On form submit - update product
if (isset($_POST['edit_product'])) {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_name = $_POST['category'];

    // Default to current image
    $image = $product['image'];

    // Handle new image upload if any
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image_tmp = $_FILES['image']['tmp_name'];
        $image = $_FILES['image']['name'];
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        if (!move_uploaded_file($image_tmp, $upload_dir . $image)) {
            $errors[] = "Failed to upload new image.";
        }
    }

    // If no errors, update database
    if (empty($errors)) {
        $sql = "UPDATE products SET name=?, description=?, price=?, image=?, stock=?, category=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsisi", $name, $description, $price, $image, $stock, $category_name, $product_id);
        // Note: Fixed spacing issue, should be "ssdsisi"
        $stmt->bind_param("ssdsisi", $name, $description, $price, $image, $stock, $category_name, $product_id);
        $stmt->execute();

        if ($stmt->error) {
            $errors[] = "Update error: " . $stmt->error;
        } else {
            $success = true;
            // Refresh product data for display
            $product['name'] = $name;
            $product['description'] = $description;
            $product['price'] = $price;
            $product['image'] = $image;
            $product['stock'] = $stock;
            $product['category'] = $category_name;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Edit Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Edit Product</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
        <p>Product updated successfully!</p>
        <a href="admin_dashboard.php" 
           class="inline-block mt-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-200">
           Go to Products Page
        </a>
      </div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <?php foreach ($errors as $error): ?>
          <p><?php echo htmlspecialchars($error); ?></p>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data" class="space-y-4" <?php if ($success) echo 'style="display:none;"'; ?>>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
        <input type="text" name="name" required
               value="<?php echo htmlspecialchars($product['name']); ?>"
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" required rows="3"
                  class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500"><?php echo htmlspecialchars($product['description']); ?></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
        <input type="number" step="0.01" name="price" required
               value="<?php echo htmlspecialchars($product['price']); ?>"
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Current Image</label>
        <img src="uploads/<?php echo htmlspecialchars($product['image']); ?>" alt="Product Image" class="mb-2 max-h-40 object-contain" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Upload New Image (optional)</label>
        <input type="file" name="image" accept="image/*"
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
        <input type="number" name="stock" required
               value="<?php echo htmlspecialchars($product['stock']); ?>"
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <input type="text" name="category" required
               value="<?php echo htmlspecialchars($product['category']); ?>"
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <button type="submit" name="edit_product"
              class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition duration-200">
        Update Product
      </button>
    </form>

  </div>

</body>
</html>
