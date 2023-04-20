<?php
session_start();
require_once 'config.php';

// If user isn't logged in, redirect to login page
if (!isset($_SESSION['customer_id']))
{
    header("Location: login.php");
    exit;
}

// If user selected an item to add to the cart
if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    // Values needed to "add to cart"
    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    // Prepare & execute SQL
    $sql = "SELECT * FROM cart_items WHERE customer_id = :customer_id AND product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    // if the item is already in the cart, add to the quantity
    if ($item)
    {
        $new_quantity = $item['quantity'] + $quantity;
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE customer_id = :customer_id AND product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':quantity', $new_quantity);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    }
    // if the item isnt in the cart, add it.
    else
    {
        $sql = "INSERT INTO cart_items (customer_id, product_id, quantity) VALUES (:customer_id, :product_id, :quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
    }
}
// Redirect to page after adding to cart.
header("Location: products.php");
?>
