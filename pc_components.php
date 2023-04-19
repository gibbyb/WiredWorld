<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // User has selected a store, update the session variable
    $_SESSION['selected_store_id'] = $_POST['selected_store_id'];
    $selectedStoreId = $_SESSION['selected_store_id'];
    $productId = $_POST['product_id'];
    header("Location: update_inventory.php?store_id=$selectedStoreId&product_id=$productId");
    exit;
}

$selectedStoreId = $_SESSION['selected_store_id'] ?? 0;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wired World - Products</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
<main id="products-main" style="margin-top: 100px;">
    <section class="all-products">
        <div class="product-container">
            <?php
            $sql = "SELECT * FROM products WHERE category_id BETWEEN 5 AND 9";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($products) > 0) {
                echo '<div class="product-row">';

                $productCounter = 0;
                foreach ($products as $product) {
                    if ($productCounter % 4 == 0 && $productCounter > 0) {
                        echo '</div><div class="product-row">';
                    }
                    echo '<div class="product-card">';
                    echo '<img src="' . $product['thumbnail_path'] . '" alt="' . $product['name'] . '">';
                    echo '<h3>' . $product['brand'] . ' ' . $product['name'] . '</h3>';
                    echo '<p>' . $product['description'] . '</p>';
                    echo '<p class="product-price">$' . number_format($product['price'], 2) . '</p>';
                    $storeId = $selectedStoreId == 0 ? 1 : $selectedStoreId;
                    $productId = $product['product_id'];
                    $sql = "SELECT * FROM inventory WHERE store_id = $storeId AND product_id = $productId";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute();
                    $inventory = $stmt->fetch(PDO::FETCH_ASSOC);
                    if ($inventory) {
                        echo '<p class="product-stock">In stock: ' . $inventory['quantity'] . '</p>';
                    } else {
                        echo '<p class="product-stock">Out of stock</p>';
                    }
                    echo '<form action="add_to_cart.php" method="post">';
                    echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
                    echo '<input type="number" name="quantity" value="1" min="1" max="99" style="width: 50px;">';
                    echo '<input type="submit" value="Add to Cart">';
                    echo '</form>';
                    echo '</div>';
                    $productCounter++;
                }

                echo '</div>';
            }
            ?>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>