<?php
// include header
require 'header.php';
?>

<?php
// Database :)
include('notadatabase.php');
if (isset($_GET['search'])) {
    $search = $_GET['search'];
    $results = array(); // Initialize the $results array

    // Search for product names match
    foreach ($PDOproducts as $product) {
        //stripos is a case-insensitive search for a substring 
        if ((stripos($product['ProductName'], $search) !== false) || (stripos($product['ProductDescription'], $search) !== false)) {
            $results[] = $product;
        }
    }

    // Display results
    if (count($results) > 0) {
        echo "<h1>Results for '$search'</h1>";
        echo "<div class='product-cards'>";
        foreach ($results as $product) {
            echo "<div class='product-card'>";
            echo "<h2><a href='product.php?product_id={$product['ProductID']}'>{$product['ProductName']}</a></h2>";
            echo "<img src='{$product['ProductImage']}' alt='{$product['ProductName']}'>";
            echo "<p>" . $product['ProductDescription'] . "</p>";
            echo "<p>Price: €" . $product['ProductPrice'] . "</p>";
            echo "</div>";
        }
        echo "</div>";
    } else {
        echo "<h1>No results for '$search'</h1>";
    }
} else {
    echo "Please enter a product name.";
}

?>
<?php
// include footer
require 'footer.php';
?>