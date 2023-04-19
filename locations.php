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
    <title>Wired World - Locations</title>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="img/wired-world-logo.png" type="image/png">
</head>
<body>
<?php include 'header.php'; ?>
<main id="locations-main" style="margin-top: 100px;">
    <section class="all-locations">
        <div class="location-container">
            <?php
            $sql = "SELECT * FROM stores";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $stores = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if (count($stores) > 0) {
                echo '<form action="locations.php" method="post">';
                echo '<div class="location-row">';

                foreach ($stores as $store) {
                    if ($store['store_id'] == 1) {
                        // Skip the first store, it should not be manually selectable
                        continue;
                    }

                    echo '<div class="location-card">';
                    echo '<input type="radio" name="selected_store_id" value="' . $store['store_id'] . '"' . ($selectedStoreId == $store['store_id'] ? ' checked' : '') . '>';
                    echo '<h3><u><b>' . $store['name'] . '</b></u></h3>';
                    echo '<p>' . $store['address'] . '</p>';
                    echo '<p>' . $store['city'] . ', ' . $store['state'] . ' ' . $store['zip_code'] . '</p>';
                    echo '<p>' . $store['phone'] . '</p>';
                    echo '</div>';
                }

                echo '</div>';
                echo '<input type="hidden" name="product_id" value="' . $product['product_id'] . '">';
                echo '<input type="submit" value="Select Store">';
                echo '</form>';
            }
            ?>
        </div>
    </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
