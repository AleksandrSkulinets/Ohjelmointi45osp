<?php
// include header
require 'header.php';
?>

<!-- indexpage   -->
<main>
<h1>Welcome to shop</h1>
<p>It's a test that uses array as a database</p>
<?php include 'notadatabase.php'; ?>
<div class="product-cards">
    <?php foreach ($PDOproducts as $product): ?>
        <div class="product-card">
            <h2><a href="product.php?product_id=<?php echo $product['ProductID']; ?>"><?php echo $product['ProductName']; ?></a></h2>
            <img src="<?php echo $product['ProductImage']; ?>" alt="<?php echo $product['ProductName']; ?>">
            <p><?php echo $product['ProductDescription']; ?></p>
            <p><b>Price: € <?php echo $product['ProductPrice']; ?></b></p>
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