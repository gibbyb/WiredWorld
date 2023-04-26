<?php
require_once 'config.php';
$search_query = "";
if (isset($_GET['search_query']))
{
    $search_query = $_GET['search_query'];
}
$stmt = $conn->prepare("SELECT * FROM products WHERE name LIKE ? OR brand LIKE ? OR description LIKE ?");
$search_term = '%' . $search_query . '%';
$stmt->bindParam(1, $search_term);
$stmt->bindParam(2, $search_term);
$stmt->bindParam(3, $search_term);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
    // User has selected a store, update the session variable
    $_SESSION['selected_store_id'] = $_POST['selected_store_id'];
    $selectedStoreId = $_SESSION['selected_store_id'];
    $productId = $_POST['product_id'];
    header("Location: update_inventory.php?store_id=$selectedStoreId&product_id=$productId");
    exit;
}
$customer_id = $_SESSION['customer_id'];
if (isset($_SESSION['selected_store_id']))
{
    $selectedStoreId = $_SESSION['selected_store_id'];
}
else
{
    $query = "SELECT store_id FROM customers WHERE customer_id = :customer_id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':customer_id', $customer_id);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $selectedStoreId = $result['store_id'] ?? 1;
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
<main id="products-main" style="margin-top: 100px;">
    <section class="all-products">
        <div class="product-container">
            <?php
            echo '<h1 class="featured-products-header">Search Results for ' . htmlspecialchars($search_query) . '</h1>';
            if (count($products) > 0)
            {
                echo '<div class="product-row">';
                $productCounter = 0;
                foreach ($products as $product)
                {
                    if ($productCounter % 4 == 0 && $productCounter > 0)
                    {
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
                    if ($inventory)
                    {
                        echo '<p class="product-stock">In stock: ' . $inventory['quantity'] . '</p>';
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
            else
            {
                echo '<p>No results found for "' . htmlspecialchars($search_query) . '"</p>';
            }
            ?>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>