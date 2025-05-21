<?php
include('config.php'); 

//UPDATE STATUS
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'], $_POST['status'])) {
    $orderId = $_POST['order_id'];
    $newStatus = $conn->real_escape_string($_POST['status']);
    $allowedStatuses = ['pending', 'shipped', 'delivered', 'cancelled'];
    if (in_array($newStatus, $allowedStatuses)) {
        $updateSql = "UPDATE orders SET status = '$newStatus' WHERE id = $orderId";
        $conn->query($updateSql);}
    header("Location: orders.php");
    exit;
}

$editOrderId = isset($_GET['edit']) ? $_GET['edit'] : null;

$sql = "SELECT orders.id, users.name AS customer_name, orders.status, orders.total, orders.order_date, orders.payment_method
        FROM orders 
        JOIN users ON orders.user_id = users.id
        ORDER BY orders.order_date DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Orders - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex">
        <aside class="w-64 bg-white shadow-lg p-6">
            <div class="flex items-center mb-8">
                <h1 class="text-2xl font-bold text-purple-600">Admin Dashboard</h1>
            </div>
            <nav class="space-y-2">
                <a href="admin_dashboard.php" class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">Dashboard</a>
                <a href="products.php" class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">Products</a>
                <a href="orders.php" class="block w-full text-left py-2 px-4 rounded bg-purple-100 text-purple-700">Orders</a>
                <a href="users.php" class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100">Users</a>
                <a href="logout.php" class="block w-full text-left py-2 px-4 rounded hover:bg-gray-100 text-red-600" onclick="return confirm('Are you sure you want to log out?');">Logout</a>
            </nav>
        </aside>








        <main class="flex-1 p-6">
            <h2 class="text-2xl font-bold mb-4">Orders</h2>
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                        <tr>
                            <th class=>Order ID </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php while ($row = $result->fetch_assoc()):
                            $status = $row['status'];
                            $statusClasses = 'px-2 inline-flex text-xs leading-5 font-semibold rounded-full ';

                            switch ($status) {
                                case 'delivered': $statusClasses .= 'bg-green-100 text-green-800'; break;
                                case 'cancelled': $statusClasses .= 'bg-red-100 text-red-800'; break;
                                case 'pending': $statusClasses .= 'bg-orange-100 text-orange-800'; break;
                                case 'shipped': $statusClasses .= 'bg-yellow-100 text-yellow-800'; break;
                                default: $statusClasses .= 'bg-gray-100 text-gray-800';
                            }
                        ?>
                        <tr>
                            <td class="px-6 py-4">#<?php echo $row['id']; ?></td>
                            <td class="px-6 py-4"><?php echo ($row['customer_name']); ?></td>
                            <td class="px-6 py-4"><?php echo ($row['order_date']); ?></td>
                            <td class="px-6 py-4"><?php echo ($row['payment_method']); ?></td>
                            <td class="px-6 py-4">
                                <?php if ($editOrderId === $row['id']): ?>
                                    <form method="POST" class="inline-flex items-center space-x-2">
                                        <input type="hidden" name="order_id" value="<?php echo $row['id']; ?>">
                                        <select name="status" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-purple-500">
                                            <?php
                                            $statuses = ['pending', 'shipped', 'delivered', 'cancelled'];
                                            foreach ($statuses as $s):
                                                $selected = ($s === $status) ? 'selected' : '';
                                            ?>
                                     <option value="<?php echo $s; ?>" <?php echo $selected; ?>><?php echo ($s); ?></option>
                                    <?php endforeach; ?>
                                        </select>
                                        <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700 transition">Save</button>
                                        <a href="orders.php" class="text-red-600 hover:underline text-sm">Cancel</a>
                                    </form>
                                <?php else: ?>
                            <span class="<?php echo $statusClasses; ?>"><?php echo ($status); ?></span>
                               <a href="orders.php?edit=<?php echo $row['id']; ?>" class="ml-2 text-blue-600 hover:underline text-sm">Edit</a>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4">$<?php echo number_format($row['total'], 2); ?></td>
                            <td class="px-6 py-4">
                                <a href="view_orders.php?id=<?php echo $row['id']; ?>" class="text-purple-600 hover:underline">View</a>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</body>
</html>
