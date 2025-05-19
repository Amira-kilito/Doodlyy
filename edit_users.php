<?php
session_start();
include('config.php');

// Get user ID from URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    echo "Invalid user ID.";
    exit;
}

$userId = intval($_GET['id']);

// Fetch user data
$stmt = $conn->prepare("SELECT name, email, role FROM users WHERE id = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "User not found.";
    exit;
}

$user = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newName = $_POST['name'];
    $newEmail = $_POST['email'];
    $newRole = $_POST['role'];

    $updateStmt = $conn->prepare("UPDATE users SET name = ?, email = ?, role = ? WHERE id = ?");
    $updateStmt->bind_param("sssi", $newName, $newEmail, $newRole, $userId);

    if ($updateStmt->execute()) {
        $success = "User updated successfully!";
        // Refresh user data
        $user['name'] = $newName;
        $user['email'] = $newEmail;
        $user['role'] = $newRole;
    } else {
        $error = "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen p-6">

 <div class="max-w-md mx-auto">
        <a href="users.php" class="inline-block mb-4 text-purple-600 hover:text-purple-800 font-medium">
            ← Go back to users
        </a> </div>

    <div class="max-w-md mx-auto bg-white p-6 rounded shadow-md">
        <h2 class="text-xl font-bold mb-4">Edit User</h2>

        <?php if (isset($success)): ?>
            <div class="mb-4 p-3 bg-green-100 text-green-700 rounded"><?php echo $success; ?></div>
        <?php endif; ?>
        <?php if (isset($error)): ?>
            <div class="mb-4 p-3 bg-red-100 text-red-700 rounded"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="post">
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="name">Name</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="text" name="name" id="name" value="<?php echo htmlspecialchars($user['name']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="email">Email</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded" type="email" name="email" id="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 mb-1" for="role">Role</label>
                <select class="w-full px-3 py-2 border border-gray-300 rounded" name="role" id="role" required>
                    <option value="admin" <?php if ($user['role'] === 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="customer" <?php if ($user['role'] === 'customer') echo 'selected'; ?>>customer</option>
                </select>
            </div>
            <button class="bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700" type="submit">Update User</button>
            <a href="users.php" class="ml-2 text-gray-600 hover:underline">Cancel</a>
        </form>
    </div>
</body>
</html>
