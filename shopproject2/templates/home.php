<!-- index  -->
<?php addToCart(); 

$sort = isset($_GET['sort']) ? $_GET['sort'] : 'price_asc';
$products = sortProducts($sort);

?>
<h2>Products catalog</h2>

<!-- sorting form -->
<form action="<?php echo $sitePath; ?>" method="get">
    <label for="sort">Sort products:</label>
    <select name="sort" id="sort" onchange="this.form.submit()">
        <option value="price_asc" <?php echo ($sort === 'price_asc') ? 'selected' : ''; ?>>Price Ascending</option>
        <option value="price_desc" <?php echo ($sort === 'price_desc') ? 'selected' : ''; ?>>Price Descending</option>
        <option value="name_asc" <?php echo ($sort === 'name_asc') ? 'selected' : ''; ?>>Name A-Z</option>
        <option value="name_desc" <?php echo ($sort === 'name_desc') ? 'selected' : ''; ?>>Name Z-A</option>
    </select>
</form>

<div class="product-cards">
        <?php foreach ($products as $product): ?>
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
