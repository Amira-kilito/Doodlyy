<?php 
include('config.php');
session_start();

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Pagination 
$products_per_page = 12;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($current_page - 1) * $products_per_page;

//search and filter 
$search_query = isset($_GET['query']) ? $conn->real_escape_string($_GET['query']) : '';
$max_price = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 100;
$sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : 'default';

// categories selected
$categories = [];
if(isset($_GET['Planners'])) $categories[] = 'Planners';
if(isset($_GET['Journalsandnotebooks'])) $categories[] = 'Journalsandnotebooks';
if(isset($_GET['Sketchbooks'])) $categories[] = 'Sketchbooks';

//QUERY 
$query = "SELECT * FROM products WHERE 1=1";

if (!empty($search_query)) {
    $query .= " AND (name LIKE '%$search_query%' OR description LIKE '%$search_query%')";
}

if (!empty($categories)) {
    $category_conditions = "";
    foreach ($categories as $index => $category) {
        $category = $conn->real_escape_string($category);
        if ($index > 0) {
            $category_conditions .= " OR ";
        }
        $category_conditions .= "category = '$category'";
    }
    $query .= " AND (" . $category_conditions . ")";
}

$query .= " AND price <= $max_price";


switch ($sort_by) {
    case 'price_asc':
        $query .= " ORDER BY price ASC";
        break;
    case 'price_desc':
        $query .= " ORDER BY price DESC";
        break;
    default:
        $query .= " ORDER BY id DESC";
}

//TOTAL PRODUCTS -> how many pages
$count_query = preg_replace('/SELECT \*/', 'SELECT COUNT(*) as total', $query);
$count_query = preg_replace('/ORDER BY.*$/i', '', $count_query);
$count_result = $conn->query($count_query);
$total_products = $count_result->fetch_assoc()['total'];
$total_pages = ceil($total_products / $products_per_page);

//pagination limit
$query .= " LIMIT $offset, $products_per_page";


$result = $conn->query($query);
$cart_count = count($_SESSION['cart']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doodly - Stationery Shop</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
  
        <header class="bg-gray-100 w-full h-[70px] flex items-center justify-between px-4">
        <div>
            <a href="user_homepage.php">
            <img src="Doodly images/DOODLY.png" alt="Doodly Logo" class="w-24"> </a>
                </div>
                
                <div class="flex items-center space-x-6">
                    <!--SEARCH BAR -->
                    <form action="homepage.php" method="GET" class="relative hidden md:block">
                        <input type="text"  name="query" value="<?php echo($search_query); ?>" placeholder="Search products..." 
                               class="w-72 pl-4 pr-10 py-2 rounded-full border border-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                        <button type="submit" class="absolute right-3 top-2">
                            <img src="Doodly images/search.png" alt="Search" class="w-5 h-5">
                        </button>
                    </form>
                    
                    <div class="relative group">
                        <button class="text-gray-600 hover:text-purple-600 focus:outline-none">
                            <img src="Doodly images/user.png" alt="User Account" class="w-6 h-6">
                        </button>
                        <div class="absolute right-0 mt-2 w-40 bg-white border rounded-lg shadow-lg opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition z-10">
                            <a href="userprofile.php" class="block px-4 py-2 hover:bg-purple-100">Profile</a>
                            <a href="logout.php" class="block px-4 py-2 hover:bg-purple-100">Logout</a>
                        </div>
                    </div>
               <a href="cart.php" class="relative text-gray-600 hover:text-purple-600">
                        <img src="Doodly images/cart.png" alt="Shopping Cart" class="w-6 h-6">
                        <span class="absolute -top-2 -right-2 bg-purple-600 text-white text-xs px-1.5 py-0.5 rounded-full">
                            <?php echo $cart_count; ?>
                        </span>
                    </a>
                </div>
            </div>
        
    </header>

    
    <main class="flex-1">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <?php if (!empty($search_query)): ?>
            <div class="mb-6 ">
                <p class="text-purple-700">
                    <?php echo $total_products; ?> item<?php echo $total_products != 1 ? 's' : ''; ?> Found  </p>
        </div>
            <?php endif; ?>

            <div class="lg:grid lg:grid-cols-4 lg:gap-8">
                <!-- Filters -->
                <div class="lg:col-span-1">
                    <div class="bg-white rounded-xl shadow-sm p-6 sticky top-4">
                        <h2 class="text-lg font-bold text-gray-800 mb-4">Filter Products</h2>
                        
                        <form action="homepage.php" method="GET" id="filter-form">
                            <?php if (!empty($search_query)): ?>
                            <input type="hidden" name="query" value="<?php echo($search_query); ?>">
                            <?php endif; ?>
                            
                            <!-- Categories -->
<div class="mb-6">
    <h3 class="font-semibold text-gray-700 mb-2">Categories</h3>
    <div class="space-y-2">
        <label class="flex items-center">
            <input type="checkbox" name="Planners" 
                   <?php echo isset($_GET['Planners']) ? 'checked' : ''; ?>
                   class="h-4 w-4 text-purple-600 focus:ring-purple-500">
            <span class="ml-2 text-gray-700">Planners</span>
        </label>
        <label class="flex items-center">
            <input type="checkbox" name="Journalsandnotebooks" 
                   <?php echo isset($_GET['Journalsandnotebooks']) ? 'checked' : ''; ?>
                   class="h-4 w-4 text-purple-600 focus:ring-purple-500">
            <span class="ml-2 text-gray-700">Journals & Notebooks</span>
        </label>
        <label class="flex items-center">
            <input type="checkbox" name="Sketchbooks" 
                   <?php echo isset($_GET['Sketchbooks']) ? 'checked' : ''; ?>
                   class="h-4 w-4 text-purple-600 focus:ring-purple-500">
            <span class="ml-2 text-gray-700">Sketchbooks</span>
        </label>
    </div>
</div>


                            <!-- Price Range -->
                            <div class="mb-6">
                                <h3 class="font-semibold text-gray-700 mb-2">Max Price</h3>
                                <div class="flex items-center space-x-2">
                                    <input type="range" name="max_price" min="5" max="100" 
                                           value="<?php echo $max_price; ?>" 
                                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer"
                                           id="priceRange">
                                    <span class="text-gray-700 min-w-[50px]">$<span id="priceValue"><?php echo $max_price; ?></span></span>
                                </div>
                            </div>
                            
                           <!-- Sort By -->
<div class="mb-6">
    <h3 class="font-semibold text-gray-700 mb-2">Sort By</h3>
    <select name="sort_by" 
            class="w-full p-2 border border-gray-300 rounded bg-white focus:ring-purple-500 focus:border-purple-500">
        <option value="default" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'default' ? 'selected' : ''; ?>>Default (Newest)</option>
        <option value="price_asc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
        <option value="price_desc" <?php echo isset($_GET['sort_by']) && $_GET['sort_by'] == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
    </select>
</div>

                            
                            <!-- Filter Buttons -->
                            <div class="flex space-x-2">
                                <button type="submit" 
                                        class="flex-1 bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-md transition">
                                    Apply
                                </button>
                                <a href="homepage.php" 
                                   class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 py-2 px-4 rounded-md text-center transition">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <div class="lg:col-span-3 mt-8 lg:mt-0">
                    <?php if ($result->num_rows == 0): ?>
                        <div class="bg-white rounded-xl shadow-sm p-8 text-center">
                        <img src="images/icons/no-results.png" alt="No Results" class="w-16 h-16 mx-auto mb-4">
                        <h3 class="text-xl font-semibold text-gray-800 mb-2">No products found</h3>
                        <p class="text-gray-600 mb-4">Try adjusting your search or filter criteria</p>
                        <a href="homepage.php" class="inline-block bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-md transition">
                            View all products
                        </a>
                    </div>
                    <?php else: ?>

                    <!-- Products Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="bg-white rounded-xl shadow-sm overflow-hidden hover:shadow-md transition-shadow">
                            <div class="overflow-hidden">
                                <a href="product.php?id=<?php echo $row['id']; ?>">
                                    <img src="<?php echo($row['image']); ?>" 
                                         alt="<?php echo($row['name']); ?>" 
                                         class="w-full h-48 object-cover transform hover:scale-105 transition-transform duration-300">
                                </a>
                            </div>
                            
                            <div class="p-5">
                                <h2 class="text-xl font-semibold text-gray-800 mb-1"><?php echo($row['name']); ?></h2>
                                <p class="text-sm text-gray-500 mb-2"><?php echo($row['category']); ?></p>
                                <p class="text-purple-600 font-bold text-lg mb-4"><?php echo $row['price']; ?></p>
                                
                                <form action="add_to_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                                    <input type="hidden" name="return_url" value="<?php echo $_SERVER['REQUEST_URI']; ?>">
                                    <button type="submit" 
                                            class="w-full bg-purple-600 hover:bg-purple-700 text-white py-2 px-4 rounded-md text-sm font-medium transition">
                                        Add to Cart
                                    </button>
                                </form>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                    




                    <!-- Pagination -->
                    <?php if ($total_pages > 1): ?>
                    <div class="mt-10 flex justify-center">
                        <nav class="inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
        <!-- Previous Page -->
<?php if ($current_page > 1): ?>
<a href="?page=<?php echo $current_page - 1; ?>" 
   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
    <span class="sr-only">Previous</span>
    <img src="Doodly images/left-arrow.png" alt="Previous" class="w-5 h-5">
</a>
<?php else: ?>
<span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
    <span class="sr-only">Previous</span>
    <img src="Doodly images/left-arrow.png" alt="Previous" class="w-5 h-5 opacity-50">
</span>
<?php endif; ?>
                            
       <!-- Page Numbers -->                  
          <?php
            $range = 2;
            for ($i = max(1, $current_page - $range); $i <= min($total_pages, $current_page + $range); $i++): ?>
                                <?php if ($i == $current_page): ?>
                                <span class="relative inline-flex items-center px-4 py-2 border border-purple-500 bg-purple-50 text-sm font-medium text-purple-700">
                                    <?php echo $i; ?>
                                </span>
                                <?php else: ?>
                                <a href="?page=<?php echo $i; ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">
                                    <?php echo $i; ?>
                                </a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                    

<!-- Next Page -->
<?php if ($current_page < $total_pages): ?>
<a href="?page=<?php echo $current_page + 1; ?>" 
   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
    <span class="sr-only">Next</span>
    <img src="Doodly images/right-arrow.png" alt="Next" class="w-5 h-5">
</a>
<?php else: ?>
<span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
    <span class="sr-only">Next</span>
    <img src="Doodly images/right-arrow.png" alt="Next" class="w-5 h-5 opacity-50">
</span>
<?php endif; ?>


                        </nav>
                    </div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    
    <footer class="bg-white border-t border-gray-200 mt-12">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-gray-500 text-sm">
                &copy; <?php echo date('Y'); ?> Doodly. All rights reserved.
            </p>
        </div>
    </footer>



    
    <script>
        const range = document.getElementById('priceRange');
        const value = document.getElementById('priceValue');
        
        range.addEventListener('input', () => {
            value.textContent = range.value;
        });
    </script>
</body>
</html>