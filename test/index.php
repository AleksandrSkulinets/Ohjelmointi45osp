<?php
// include header
require 'header.php';
?>

<!-- index   -->
<main>
<h1>Welcome to shop</h1>
<p>Some greeting message</p>
<?php require 'config.php'; ?>
<?php
    // All products
    // $statment = $pdo->query("SELECT ProductID, ProductName, Description, Price, ImageURL FROM products"); // All products
    // $products = $statment->fetchAll(PDO::FETCH_ASSOC); 
?>
<?php
        $statment = $pdo->query("SELECT ProductID, ProductName, Description, Price, ImageURL FROM products ORDER BY RAND() LIMIT 5"); //5 random products
        $products = $statment->fetchAll(PDO::FETCH_ASSOC);
?>

<div class="product-cards">
        <?php foreach ($products as $product): ?>
            <div class="product-card">
                <h2><a href="product.php?id=<?php echo $product['ProductID']; ?>"><?php echo $product['ProductName']; ?></a></h2>
                <img src="<?php echo $product['ImageURL']; ?>" alt="<?php echo $product['ProductName']; ?>">
                <p><?php echo $product['Description']; ?></p>
                <p><b>Price: € <?php echo $product['Price']; ?></b></p>
                <form method="post" class="add-to-cart-form">
                    <input type="hidden" name="add_to_cart" value="<?php echo $product['ProductID']; ?>">
                    <input type="number" name="quantity" class="quantity-input" value="1" min="1">
                    <input type="submit" class="add-to-cart-button" value="Add to Cart">
                </form>
            </div>
        <?php endforeach; ?>

</div>
</main>

<?php
// include footer
require 'footer.php';
?>
</body>
</html>