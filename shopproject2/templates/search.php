<?php addToCart(); ?>
<!-- search  -->
<h2>Search results for: <?php echo '"' . $searchQuery . '"'; ?></h2>
<?php if (!empty($searchResults)): ?>
<div class="product-cards">
    <?php foreach ($searchResults as $product): ?>
        <div class="product-card">
            <h2><a href="<?php echo $sitePath; ?>/?page=product&id=<?php echo $product['ProductID']; ?>"><?php echo $product['ProductName']; ?></a></h2>
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
<?php else: ?>
    <p>No products found for: <?php echo $searchQuery; ?></p>
<?php endif; ?>