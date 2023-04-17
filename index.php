<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wired World</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
<main>
    <section class="featured-products">
        <div class="product-container">
            <h1 class="featured-products-header">Featured Products</h1>
            <?php
            $categories = [
                ['name' => 'Phones & Tablets', 'ids' => [1, 2]],
                ['name' => 'Laptops', 'ids' => [3]],
                ['name' => 'Desktops', 'ids' => [4]],
                ['name' => 'PC Components', 'ids' => [5, 6, 7, 8, 9]]
            ];

            foreach ($categories as $category) {
                $category_ids = implode(",", $category['ids']);

                $sql = "SELECT * FROM products WHERE featured = 1 AND category_id IN ($category_ids) LIMIT 4";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($products) > 0) {
                    echo '<h2 class="featured-products-row-header">' . $category['name'] . '</h2>';
                    echo '<div class="product-row">';

                    foreach ($products as $product) {
                        echo '<div class="product-card">';
                        echo '<img src="' . $product['thumbnail_path'] . '" alt="' . $product['name'] . '">';
                        echo '<h3>' . $product['brand'] . ' ' . $product['name'] . '</h3>';
                        echo '<p>' . $product['description'] . '</p>';
                        echo '<p class="product-price">$' . number_format($product['price'], 2) . '</p>';
                        echo '<button>Add to Cart</button>';
                        echo '</div>';
                    }

                    echo '</div>';
                }
            }
            ?>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>

