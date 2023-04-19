<?php
session_start();
require_once 'config.php';

  $customer_id = $_SESSION["customer_id"];
  $cart_items_query = "SELECT c.cart_item_id, c.product_id, c.quantity, p.name, p.price, p.thumbnail_path FROM cart_items c INNER JOIN products p ON c.product_id = p.product_id WHERE c.customer_id = $customer_id";
  $cart_items_result = mysqli_query($conn, $cart_items_query);

  $total_price = 0;

  if (isset($_POST["submit"])) {
    $cc_number = $_POST["cc_number"];
    $exp_date = $_POST["exp_date"];
    $cvv_number = $_POST["cvv_number"];
    $account_number = $_POST["account_number"];
    $routing_number = $_POST["routing_number"];

    // perform payment processing here

    // redirect to thank you page
    header("Location: thank_you.php");
    exit();
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wired World - Cart</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
    <form method="post">
  <label for="cc_number">Credit Card Number:</label>
  <input type="text" name="cc_number" required><br>

  <label for="exp_date">Expiration Date:</label>
  <input type="text" name="exp_date" placeholder="MM/YY" required><br>

  <label for="cvv_number">CVV Number:</label>
  <input type="text" name="cvv_number" required><br>

  <label for="account_number">Account Number:</label>
  <input type="text" name="account_number"><br>

  <label for="routing_number">Routing Number:</label>
  <input type="text" name="routing_number"><br>

  <h2>Cart Items</h2>

  <table>
    <thead>
      <tr>
        <th>Product Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Subtotal</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($cart_items_result)) { ?>
        <tr>
          <td><?php echo $row["name"]; ?></td>
          <td><?php echo $row["price"]; ?></td>
          <td><?php echo $row["quantity"]; ?></td>
          <td><?php echo $row["price"] * $row["quantity"]; ?></td>
        </tr>
        <?php $total_price += $row["price"] * $row["quantity"]; ?>
      <?php } ?>
    </tbody>
  </table>

  <h2>Total Price: <?php echo $total_price; ?></h2>

  <input type="submit" name="submit" value="Place Order">
</form>

</head>
<body>
<?php include 'header.php'; ?>


<?php include 'footer.php'; ?>
</body>
</html>
