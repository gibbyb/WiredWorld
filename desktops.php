<?php
require_once 'config.php';
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
            $sql = "SELECT * FROM products WHERE category_id = 4";
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