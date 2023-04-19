<?php
require_once 'config.php';

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];

// Fetch orders
$query = "SELECT * FROM orders WHERE customer_id = :customer_id ORDER BY order_date DESC";
$stmt = $conn->prepare($query);
$stmt->bindParam(':customer_id', $customer_id, PDO::PARAM_INT);
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wired World - Orders</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
<main id="orders-container" style="margin-top: 100px;">
    <h2>Your Orders</h2>
    <?php foreach ($orders as $order): ?>
        <div class="order">
            <h3>Order #<?php echo $order['order_id']; ?> - <?php echo $order['order_date']; ?></h3>
            <table>
                <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
                </thead>
                <tbody>
                <?php
                // Fetch order items
                $query = "SELECT oi.*, p.name FROM order_items oi
                              JOIN products p ON p.product_id = oi.product_id
                              WHERE oi.order_id = :order_id";
                $stmt = $conn->prepare($query);
                $stmt->bindParam(':order_id', $order['order_id'], PDO::PARAM_INT);
                $stmt->execute();
                $order_items = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $total_price = 0;
                ?>
                <?php foreach ($order_items as $item): ?>
                    <tr>
                        <td><?php echo $item['name']; ?></td>
                        <td><?php echo $item['quantity']; ?></td>
                        <td>$<?php echo $item['price']; ?></td>
                        <td>$<?php echo $item['price'] * $item['quantity']; ?></td>
                    </tr>
                    <?php $total_price += $item['price'] * $item['quantity']; ?>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                <tr>
                    <td colspan="3">Total Price</td>
                    <td>$<?php echo $total_price; ?></td>
                </tr>
                </tfoot>
            </table>
        </div>
    <?php endforeach; ?>
</main>
<?php include 'footer.php'; ?>
</body>
</html>

