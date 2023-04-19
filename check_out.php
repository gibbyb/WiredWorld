<?php
require_once('config.php');

// check if user is logged in
if (!isset($_SESSION['customer_id'])) {
  header('Location: login.php');
  exit();
}

// get user info
$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare('SELECT * FROM customers WHERE customer_id = :customer_id');
$stmt->execute(['user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// get cart items
$stmt = $conn->prepare('SELECT * FROM cart_items WHERE customer_id = :customer_id');
$stmt->execute(['user_id' => $user_id]);
$cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

// calculate total price
$total_price = 0;
foreach ($cart_items as $item) {
  $total_price += $item['quantity'] * $item['price'];
}

// handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  // validate form fields
  $errors = [];
  if (empty($_POST['cc_number'])) {
    $errors[] = 'Please enter your credit card number.';
  } elseif (!preg_match('/^\d{16}$/', $_POST['cc_number'])) {
    $errors[] = 'Please enter a valid 16-digit credit card number.';
  }

  if (empty($_POST['exp_date'])) {
    $errors[] = 'Please enter your credit card expiration date.';
  } elseif (!preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $_POST['exp_date'])) {
    $errors[] = 'Please enter a valid expiration date in the format MM/YY.';
  }

  if (empty($_POST['cvv_number'])) {
    $errors[] = 'Please enter your credit card CVV number.';
  } elseif (!preg_match('/^\d{3}$/', $_POST['cvv_number'])) {
    $errors[] = 'Please enter a valid 3-digit CVV number.';
  }

  if (empty($_POST['account_number'])) {
    $errors[] = 'Please enter your bank account number.';
  } elseif (!preg_match('/^\d+$/', $_POST['account_number'])) {
    $errors[] = 'Please enter a valid bank account number.';
  }



  if (empty($errors)) {
    // insert order into orders table
    $stmt = $conn->prepare('INSERT INTO orders (customer_id, order_date, total_price) VALUES (:customer_id, :order_date, :total_price)');
    $stmt->execute(['customer_id' => $user_id, 'order_date' => date('order_date'), 'total_price' => $total_price]);
    $order_id = $conn->lastInsertId();
// insert items into order_items table
    foreach ($cart_items as $item) {
      $stmt = $conn->prepare('INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (:order_id, :product_id, :quantity, :price)');
      $stmt->execute(['order_id' => $order_id, 'product_id' => $item['product_id'], 'quantity' => $item['quantity'], 'price' => $item['price']]);
    }

    // clear cart
    $_SESSION['cart'] = [];

    // redirect to order confirmation page
    header('Location: confirmation.php?id=' . $order_id);
  } else {
    // redirect back to check out page with errors
    $_SESSION['checkout_errors'] = $errors;
    header('Location: checkout.php');
  }
  exit;
}

<!DOCTYPE html>
<html>
  <head>
    <title>Checkout</title>
  </head>
  <body>
    <h1>Checkout</h1>

    <form method="POST" action="checkout.php">
      <label for="cc_number">Credit Card Number:</label>
      <input type="text" name="cc_number" id="cc_number"><br>

      <label for="exp_date">Expiration Date (MM/YY):</label>
      <input type="text" name="exp_date" id="exp_date"><br>

      <label for="cvv_number">CVV Number:</label>
      <input type="text" name="cvv_number" id="cvv_number"><br>

      <label for="account_number">Bank Account Number:</label>
      <input type="text" name="account_number" id="account_number"><br>

      <input type="submit" value="Place Order">
    </form>
  </body>
</html>

