<?php
session_start();
include('config.php');


$users = [];
    $result = $conn->query("SELECT id, name, email, date_created, role FROM users");
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }

?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - Admin Dashboard</title>
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
                    class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">
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
                    class="block w-full text-left py-2 px-4 rounded bg-purple-100 text-purple-700">
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
            <h2 class="text-2xl font-bold mb-4">Users</h2>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
    <?php foreach ($users as $user): ?>
        <tr>
            <td class="px-6 py-4"><?php echo htmlspecialchars($user['name']); ?></td>
            <td class="px-6 py-4"><?php echo htmlspecialchars($user['email']); ?></td>
            <td class="px-6 py-4">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                    <?php echo $user['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800'; ?>">
                    <?php echo htmlspecialchars(ucfirst($user['role'])); ?>
                </span>
            </td>
            <td class="px-6 py-4 text-sm font-medium">
                <a href="edit_users.php?id=<?php echo $user['id']; ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
              <form method="POST" action="delete_users.php" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline;">
    <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
    <button type="submit" class="text-red-600 hover:text-red-900 bg-transparent border-none p-0 m-0 cursor-pointer">
        Delete
    </button>
</form>
            </td>
        </tr>
    <?php endforeach; ?>
</tbody>

                </table>
            </div>
        </main>
    </div>
</body>
</html>