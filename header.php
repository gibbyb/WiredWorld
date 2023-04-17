<?php
require_once 'config.php';
session_start();

$first_name = "";

if (isset($_SESSION['customer_id'])) {
    $stmt = $conn->prepare("SELECT first_name FROM customer WHERE customer_id = ?");
    $stmt->bind_param("i", $_SESSION['customer_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $first_name = $user['first_name'];
    $stmt->close();
}
?>

<header>
    <a href="index.php"><img src="/img/wired-world-logo.png" alt="Wired World Logo"></a>
    <nav>
        <div class="dropdown">
            <button class="dropbtn">Products &#9662;</button>
            <div class="dropdown-content">
                <a href="products.php">All Products</a>
                <a href="#">Phones</a>
                <a href="#">Tablets</a>
                <a href="#">Laptops</a>
                <a href="#">Desktops</a>
                <a href="#">PC Components</a>
            </div>
        </div>
        <a href="locations.php">Store Locations</a>
        <a href="contact.php">Contact Us</a>
        <?php if (isset($_SESSION['customer_id'])):?>
            <span>Welcome, <?php echo $first_name; ?></span>
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