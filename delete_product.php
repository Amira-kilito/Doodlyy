<?php
include('config.php');

$success = false;
$error = false;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);

    $sql = "DELETE FROM products WHERE id = $id";
    if (mysqli_query($conn, $sql)) {
        $success = true;
    } else {
        $error = "Error deleting product: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Delete Product</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
  <div class="bg-white shadow-lg rounded-lg p-6 max-w-md w-full text-center">

    <?php if ($success): ?>
      <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        ✅ Product deleted successfully.
      </div>
      <a href="admin_dashboard.php" class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition">
        Back to Products
      </a>
    <?php else: ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
         <?php echo htmlspecialchars($error); ?>
      </div>
      <a href="admin_dashboard.php" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 transition">
        Back to Dashboard
      </a>
    <?php endif; ?>

  </div>
</body>
</html>
