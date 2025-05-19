<?php
include('config.php');

$errors = [];
$success = false;

if (isset($_POST['add_product'])) {
    // Get form inputs safely
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = floatval($_POST['price']);
    $stock = intval($_POST['stock']);
    $category_name = $_POST['category'];

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $image_tmp = $_FILES['image']['tmp_name'];
        $upload_dir = 'uploads/';

        // Make sure upload directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move uploaded file
        if (!move_uploaded_file($image_tmp, $upload_dir . $image)) {
            $errors[] = "Failed to upload image.";
        }
    } else {
        $errors[] = "Please upload an image.";
    }

    // If no errors, insert product into database
    if (empty($errors)) {
        $sql = "INSERT INTO products (name, description, price, image, stock, category) 
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdsis", $name, $description, $price, $image, $stock, $category_name);
        $stmt->execute();

        if ($stmt->error) {
            $errors[] = "Insert error: " . $stmt->error;
        } else {
            $success = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Add Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

  <div class="bg-white shadow-lg rounded-lg p-8 max-w-md w-full">
    <h2 class="text-2xl font-semibold text-gray-800 mb-6 text-center">Add New Product</h2>

    <?php if ($success): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 text-center">
        <p>Product added successfully!</p>
        <a href="admin_dashboard.php" 
           class="inline-block mt-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-200">
           Go to Products Page
        </a>
         <a href="add_products.php" 
           class="inline-block mt-4 bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-200">
           Add another product
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
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
        <textarea name="description" required rows="3" 
                  class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500"></textarea>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Price</label>
        <input type="number" step="0.01" name="price" required 
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Image</label>
        <input type="file" name="image" accept="image/*" required 
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stock Quantity</label>
        <input type="number" name="stock" required 
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
        <input type="text" name="category" required 
               class="w-full border border-gray-300 p-2 rounded focus:outline-none focus:ring-2 focus:ring-purple-500" />
      </div>

      <button type="submit" name="add_product" 
              class="w-full bg-purple-600 text-white py-2 rounded hover:bg-purple-700 transition duration-200">
        Save Product
      </button>
    </form>
  </div>

</body>
</html>
