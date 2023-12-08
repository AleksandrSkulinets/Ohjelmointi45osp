<!-- index  -->
<?php addToCart(); 


$sortOption = isset($_SESSION['sortOption']) ? $_SESSION['sortOption'] : 'default';

// update the sort option if submitted form
if (isset($_POST['sort'])) {
    $sortOption = $_POST['sort'];
    $_SESSION['sortOption'] = $sortOption;
}

$sortedProducts = productSort($products, $sortOption);
?>


<h2>Products catalog</h2>

<!-- sorting form -->

<form method="post" class="sort-form">
    <label for="sort">Sort by:</label>
    <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="price_asc" <?php echo ($sortOption === 'price_asc') ? 'selected' : ''; ?>>Price (Low to High)</option>
        <option value="price_desc" <?php echo ($sortOption === 'price_desc') ? 'selected' : ''; ?>>Price (High to Low)</option>
        <option value="name_asc" <?php echo ($sortOption === 'name_asc') ? 'selected' : ''; ?>>Name (A to Z)</option>
        <option value="name_desc" <?php echo ($sortOption === 'name_desc') ? 'selected' : ''; ?>>Name (Z to A)</option>
    </select>
</form>

<!-- product cards -->
<div class="product-cards">
    <?php foreach ($sortedProducts as $product): ?>
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
