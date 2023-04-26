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

    $query = "SELECT store_id FROM customers WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $store_id = $result['store_id'];

    $query = "SELECT quantity FROM inventory WHERE product_id = :product_id AND store_id = :store_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->bindParam(":store_id", $store_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    $store_quantity = $result['quantity'];

    if ($store_quantity >= $quantity)
    {
        // Prepare & execute SQL command to see if we already have the item.
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
            $sql = "INSERT INTO cart_items (customer_id, product_id, store_id, quantity) VALUES (:customer_id, :product_id, :store_id, :quantity)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':customer_id', $customer_id);
            $stmt->bindParam(':product_id', $product_id);
            $stmt->bindParam(':store_id', $store_id);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->execute();
        }
    }
    else
    {
        header("Location: products.php?error=Quantity not available");
        exit;
    }


}
// Redirect to page after adding to cart.
header("Location: products.php");
?>
