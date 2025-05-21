<?php
session_start();
include('config.php');

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];}

if (isset($_POST['product_id'])) {
    $product_id = (int)$_POST['product_id'];
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($quantity < 1) {$quantity = 1;}

 $stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
 $stmt->bind_param("i", $product_id);
 $stmt->execute();
  $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
    $product = $result->fetch_assoc();
            if ($product['stock'] > 0) {
            $found = false;
            foreach ($_SESSION['cart'] as $key => $item) {
             if ($item['id'] == $product_id) { $new_quantity = $item['quantity'] + $quantity;
                if ($new_quantity > $product['stock']) { $new_quantity = $product['stock'];}
                 $_SESSION['cart'][$key]['quantity'] = $new_quantity;
                    $found = true;break;}}
            if (!$found) {
                if ($quantity > $product['stock'])
                 {$quantity = $product['stock'];}
                
                $_SESSION['cart'][] = [ 'id' => $product['id'], 'name' => $product['name'],
                    'price' => $product['price'],      'image' => $product['image'],
                    'quantity' => $quantity,           'stock' => $product['stock']];
                 }
        }
       }

    $stmt->close();}

header("Location: homepage.php");
exit;