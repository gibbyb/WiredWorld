<?php
require_once('config.php');

// check if user is logged in
if (!isset($_SESSION['customer_id']))
{
    header('Location: login.php');
    exit();
}
$customer_id = $_SESSION['customer_id'];

$query = "SELECT * FROM customers WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$customer_id]);
$customer_data = $stmt->fetch(PDO::FETCH_ASSOC);

$query = "SELECT * FROM cart_items WHERE customer_id = ?";
$stmt = $conn->prepare($query);
$stmt->execute([$customer_id]);
$cart_items = $stmt->fetch(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wired World - Products</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
    <script src="scripts/use_card_ending.js"></script>
</head>
<body>
<?php include 'header.php'; ?>
<main id="check-out-main" style="margin-top: 100px;">
    <h1>Checkout</h1><br>
    <form method="POST" action="check_out.php">

        <input type="radio" name="prev_card" id="prev_card">
        <label for="prev_card">Use Card ending in <?php echo substr($customer_data['cc_number'], -4); ?></label><br><br>


        <label for="cc_number">Credit Card Number:</label>
        <input type="text" name="cc_number" id="cc_number"><br>

        <label for="exp_date">Expiration Date (MM/YY):</label>
        <input type="text" name="exp_date" id="exp_date"><br>

        <label for="cvv_number">CVV Number:</label>
        <input type="text" name="cvv_number" id="cvv_number"><br><br>

        <label for="account_number">Business Account Number:</label>
        <input type="text" name="account_number" id="account_number"><br><br>

       <input type="radio" name="prev_address" id="prev_address">
        <label for="prev_address">Use <?php echo $customer_data['address']; ?> </label><br><br>

        <label for="address">Street Address</label>
        <input type="text" name="address" id="address"><br>

       <label for="zip_code">Zip Code</label>
        <input type="text" name="zip_code" id="zip_code"><br>

        <label for="state">State</label>
        <select>
            <option value="--">Select your state</option>
            <option value="AL">Alabama</option>
            <option value="AK">Alaska</option>
            <option value="AZ">Arizona</option>
            <option value="AR">Arkansas</option>
            <option value="CA">California</option>
            <option value="CO">Colorado</option>
            <option value="CT">Connecticut</option>
            <option value="DE">Delaware</option>
            <option value="DC">District Of Columbia</option>
            <option value="FL">Florida</option>
            <option value="GA">Georgia</option>
            <option value="HI">Hawaii</option>
            <option value="ID">Idaho</option>
            <option value="IL">Illinois</option>
            <option value="IN">Indiana</option>
            <option value="IA">Iowa</option>
            <option value="KS">Kansas</option>
            <option value="KY">Kentucky</option>
            <option value="LA">Louisiana</option>
            <option value="ME">Maine</option>
            <option value="MD">Maryland</option>
            <option value="MA">Massachusetts</option>
            <option value="MI">Michigan</option>
            <option value="MN">Minnesota</option>
            <option value="MS">Mississippi</option>
            <option value="MO">Missouri</option>
            <option value="MT">Montana</option>
            <option value="NE">Nebraska</option>
            <option value="NV">Nevada</option>
            <option value="NH">New Hampshire</option>
            <option value="NJ">New Jersey</option>
            <option value="NM">New Mexico</option>
            <option value="NY">New York</option>
            <option value="NC">North Carolina</option>
            <option value="ND">North Dakota</option>
            <option value="OH">Ohio</option>
            <option value="OK">Oklahoma</option>
            <option value="OR">Oregon</option>
            <option value="PA">Pennsylvania</option>
            <option value="RI">Rhode Island</option>
            <option value="SC">South Carolina</option>
            <option value="SD">South Dakota</option>
            <option value="TN">Tennessee</option>
            <option value="TX">Texas</option>
            <option value="UT">Utah</option>
            <option value="VT">Vermont</option>
            <option value="VA">Virginia</option>
            <option value="WA">Washington</option>
            <option value="WV">West Virginia</option>
            <option value="WI">Wisconsin</option>
            <option value="WY">Wyoming</option>
        </select><br><br>
        <input type="submit" value="Place Order">
    </form>

</main>
<?php include 'footer.php'; ?>
</body>
</html>
