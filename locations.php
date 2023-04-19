<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Wired World - Locations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
<main id="products-main" style="margin-top: 100px;">
    <section class="all-products">
        <div class="product-container">
            <h2>Select a Store</h2>
            <form action="locations.php" method="post">
                <?php
                $sql = "SELECT * FROM stores WHERE store_id != 1";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($stores as $store) {
                    echo '<div class="store-row">';
                    echo '<label><input type="radio" name="store_id" value="' . $store['store_id'] . '"';
                    if ($_SESSION['store_id'] == $store['store_id']) {
                        echo ' checked';
                    }
                    echo '> ' . $store['name'] . '</label>';
                    echo '</div>';
                }
                ?>
                <input type="submit" name="submit" value="Select Store">
            </form>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
