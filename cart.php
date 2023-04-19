<?php
require_once 'config.php';

$customer_id = $_SESSION['customer_id'];

if (isset($_POST['action']) && $_POST['action'] === 'update_cart') {
  $cart_item_id = $_POST['cart_item_id'];
  $quantity = $_POST['quantity'];

  if ($quantity == 0) {
    $stmt = $conn->prepare("DELETE FROM cart_items WHERE cart_item_id = ?");
    $stmt->bindParam(1, $cart_item_id);
  } else {
    $stmt = $conn->prepare("UPDATE cart_items SET quantity = ? WHERE cart_item_id = ?");
    $stmt->bindParam(1, $quantity);
    $stmt->bindParam(2, $cart_item_id);
  }
  $stmt->execute();
}

$stmt = $conn->prepare("SELECT cart_items.cart_item_id, cart_items.product_id, cart_items.quantity, products.brand, products.name, products.description, products.thumbnail_path, products.price FROM cart_items JOIN products ON cart_items.product_id = products.product_id WHERE cart_items.customer_id = ?");
$stmt->bindParam(1, $customer_id);
$stmt->execute();
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

$total = 0;
foreach ($cart_items as $item) {
  $total += $item['price'] * $item['quantity'];
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Wired World - Cart</title>
  <link rel="stylesheet" href="style.css">
  <link rel="icon" href="img/wired-world-logo.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
<main id="cart-main" style="margin-top: 100px;">

  <h1>Your Cart</h1>
  <div class="cart-container">
    <?php foreach ($cart_items as $item): ?>
      <div class="cart-item">
        <div class="cart-pic">
          <img src="<?php echo $item['thumbnail_path']; ?>" alt="<?php echo $item['name']; ?>">
        </div>
        <div class="cart-item-info">
          <h2><?php echo $item['brand'] . ' ' . $item['name']; ?></h2>
          <p><?php echo $item['description']; ?></p>
          <form action="cart.php" method="post">
            <input type="hidden" name="action" value="update_cart">
            <input type="hidden" name="cart_item_id" value="<?php echo $item['cart_item_id']; ?>">
            <label for="quantity">Quantity:</label>
            <input type="number" name="quantity" id="quantity" value="<?php echo $item['quantity']; ?>" min="0" max="99">
            <button type="submit">Update</button>
          </form>
          <p class="item-price">Price: $<?php echo number_format($item['price'], 2); ?></p>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
  <div class="cart-total">
    <h2>Total: $<?php echo number_format($total, 2); ?></h2>
    <button class="checkout-btn">Checkout</button>
  </div>

</main>
<?php include 'footer.php'; ?>
</body>
</html>