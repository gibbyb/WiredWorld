<?php
require_once 'config.php';

$first_name = "";

if (isset($_SESSION['customer_id'])) {
    $stmt = $conn->prepare("SELECT first_name FROM customers WHERE customer_id = ?");
    $stmt->bindParam(1, $customer_id, PDO::PARAM_INT);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $user = $result[0];
    $first_name = $user['first_name'];
    $stmt = null;
}
?>

<header>
    <a href="index.php"><img src="/img/wired-world-logo.png" alt="Wired World Logo"></a>
    <nav>
        <div class="dropdown">
            <button class="dropbtn">Products &#9662;</button>
            <div class="dropdown-content">
                <a href="products.php">All Products</a>
                <a href="phones.php">Phones</a>
                <a href="tablets.php">Tablets</a>
                <a href="laptops.php">Laptops</a>
                <a href="desktops.php">Desktops</a>
                <a href="pc_components.php">PC Components</a>
            </div>
        </div>
        <a href="locations.php">Store Locations</a>
        <?php if (isset($_SESSION['customer_id'])):?>
            <a href="orders.php">Orders</a>
            <a href="logout.php">Log Out</a>
        <?php else: ?>
            <a href="login.php">Log In</a>
        <?php endif; ?>
    </nav>
    <div class="shopping-cart">
        <a href="cart.php">
            <img src="/img/shopping-cart.png" alt="Shopping Cart">
        </a>
    </div>
    <div class="search-bar">
        <input type="text" placeholder="Search Products">
        <button>Search</button>
    </div>
</header>