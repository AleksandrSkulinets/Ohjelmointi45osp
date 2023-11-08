<?php
// include header
require 'header.php';
?>
<?php
include 'config.php';
?>
<?php
// Initialize the selected product as null
$selectedProduct = null;

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id'])) {
    $productID = $_GET['id'];

    // Create a prepared statement to retrieve the product with the given ProductID
    $statment = $pdo->prepare("SELECT ProductID, ProductName, Description, Price, ImageURL FROM products WHERE ProductID = :productID");
    $statment->bindParam(':productID', $productID, PDO::PARAM_INT);
    $statment->execute();

    // Fetch the product data
    $selectedProduct = $statment->fetch(PDO::FETCH_ASSOC);

    // Display product details if a product was found
    if ($selectedProduct !== false) {
        echo "<div class='product-details'>";
        echo "<h1>" . $selectedProduct['ProductName'] . "</h1>";
        echo "<div class='product-image'><img src='{$selectedProduct['ImageURL']}' alt='{$selectedProduct['ProductName']}'></div>";
        echo "<p class='product-description'>" . $selectedProduct['Description'] . "</p>";
        echo "<p class='product-price'>Price: €" . $selectedProduct['Price'] . "</p>";

        // Add to cart form
        echo '<form method="post" class="add-to-cart-form">';
        echo '<input type="hidden" name="add_to_cart" value="' . $selectedProduct['ProductID'] . '">';
        echo '<input type="number" name="quantity" class="quantity-input" value="1" min="1">';
        echo '<input type="submit" class="add-to-cart-button" value="Add to Cart">';
        echo '</form>';
        echo "</div>";
    } else {
        echo "Product not found.";
    }
} else {
    echo "No product selected.";
}

?>

<?php
// include footer
require 'footer.php';
?>
