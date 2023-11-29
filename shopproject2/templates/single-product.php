<?php addToCart(); ?>
        <div class='product-details'>
            <h2><?php echo $selectedProduct['ProductName']; ?></h2>
            <div class='product-image'><img src='<?php echo $selectedProduct['ImageURL']; ?>' alt='<?php echo $selectedProduct['ProductName']; ?>'></div>
            <p class='product-description'><?php echo $selectedProduct['Description']; ?></p>
            <p class='product-price'>Price: €<?php echo $selectedProduct['Price']; ?></p>

            <!-- Add to cart form -->
            <form method="post" class="add-to-cart-form">
                <input type="hidden" name="add_to_cart" value="<?php echo $selectedProduct['ProductID']; ?>">
                <input type="number" name="quantity" class="quantity-input" value="1" min="1">
                <input type="submit" class="add-to-cart-button" value="Add to Cart">
            </form>
        </div>
  