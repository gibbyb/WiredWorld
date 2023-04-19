<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $customer_id = $_SESSION['customer_id'];
    $product_id = $_POST['product_id'];
    $quantity = $_POST['quantity'];

    $sql = "SELECT * FROM cart_items WHERE customer_id = :customer_id AND product_id = :product_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->bindParam(':product_id', $product_id);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        $new_quantity = $item['quantity'] + $quantity;
        $sql = "UPDATE cart_items SET quantity = :quantity WHERE customer_id = :customer_id AND product_id = :product_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':quantity', $new_quantity);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->execute();
    } else {
        $sql = "INSERT INTO cart_items (customer_id, product_id, quantity) VALUES (:customer_id, :product_id, :quantity)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':customer_id', $customer_id);
        $stmt->bindParam(':product_id', $product_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->execute();
    }
}

header("Location: products.php");
?>
