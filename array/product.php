<?php
// include header
require 'header.php';
?>

<?php
//Database :)
include 'notadatabase.php';

// check if product_id URL set
if (isset($_GET['product_id'])) {
    $productID = $_GET['product_id'];

    // product with ProductID
    $selectedProduct = null;
    foreach ($PDOproducts as $product) {
        if ($product['ProductID'] == $productID) {
            $selectedProduct = $product;
            break;
        }
    }

    // Display products details
    if ($selectedProduct !== null) {
        echo "<div class='product-details'>";
        echo "<h1>" . $selectedProduct['ProductName'] . "</h1>";
        echo "<img src='{$selectedProduct['ProductImage']}' alt='{$selectedProduct['ProductName']}'>";
        echo "<p>" . $selectedProduct['ProductDescription'] . "</p>";
        echo "<p>Price: €" . $selectedProduct['ProductPrice'] . "</p>";
        echo '<form method="post" class="add-to-cart-form">';
        echo '<input type="hidden" name="add_to_cart" value="' . $selectedProduct['ProductID'] . '">';
        echo '<input type="number" name="quantity" class="quantity-input" value="1" min="1">';
        echo '<input type="submit" class="add-to-cart-button" value="Add to Cart">';
        echo '</form>';
        echo "</div>";

    } else {
        echo "product not found.";
    }
} else {
    echo "no product selected.";
}
?>


<?php
// include footer
require 'footer.php';
?>
