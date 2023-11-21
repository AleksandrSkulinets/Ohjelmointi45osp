<?php
// include header
require 'header.php';
?>
<main>
<?php
// Include database config and create a PDO 
include('config.php');

if (isset($_GET['search'])) {
    $search = trim($_GET['search']); 

    // Check if the search is at least 3 char lnth
    if (strlen($search) >= 3) {
        // PDO statement to search for products
        $statment = $pdo->prepare("SELECT ProductID, ProductName, Description, Price, ImageURL FROM products WHERE ProductName LIKE :search OR Description LIKE :search");
        $statment->execute(['search' => '%' . $search . '%']);
        $results = $statment->fetchAll();

        // Display results
        if (count($results) > 0) {
            echo "<h1>Results for '$search'</h1>";
            echo "<div class='product-cards'>";
            foreach ($results as $product) {
                echo "<div class='product-card'>";
                echo "<h2><a href='product.php?product_id={$product['ProductID']}'>{$product['ProductName']}</a></h2>";
                echo "<img src='{$product['ImageURL']}' alt='{$product['ProductName']}'>";
                echo "<p>" . $product['Description'] . "</p>";
                echo "<p>Price: €" . $product['Price'] . "</p>";
                echo "</div>";
            }
            echo "</div>";
        } else {
            echo "<h1>No results for" . htmlentities($search, ENT_QUOTES, 'UTF-8') . "</h1>"; // convert to htmlentities
        }
    } else {
        echo "<h1>Search must be at least 3 characters long.</h1>";
    }
} else {
    echo "Please enter a product name.";
}
?>
</main>
<?php
// include footer
require 'footer.php';
?>
</body>
</html>