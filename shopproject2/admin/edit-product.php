<?php
require 'admin-header.php';



// Check  productID provided in the URL
if (isset($_GET['id'])) {
    $productId = $_GET['id'];

    // Fetch product details 
    $productStatement = $pdo->prepare("SELECT * FROM products WHERE ProductID = ?");
    $productStatement->execute([$productId]);
    $product = $productStatement->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        //case where the product with ID is not found
        echo "<center><p>Product not found.</p></center>";
        exit();
    }

    // Check form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_product'])) {
        // Validate and update product details in the database
        $updatedProductName = $_POST['product_name'];
        $updatedDescription = $_POST['description'];
        $updatedPrice = $_POST['price'];
        $updatedImageURL = $_POST['image_url'];

        // update product details 
        $updateStatement = $pdo->prepare("UPDATE products SET 
            ProductName = ?, 
            Description = ?, 
            Price = ?, 
            ImageURL = ? 
            WHERE ProductID = ?");
        $updateStatement->execute([$updatedProductName, $updatedDescription, $updatedPrice, $updatedImageURL, $productId]);

        // Redirect to prevent resubmission
        header("Location: products.php");
        exit();
    }
} else {
    // If no product ID provided
    echo "Invalid request.";
    exit();
}
?>

<!--edit product form -->
<main>
    <h1>Edit Product</h1>
    <div class="custom-form">
    <form action="" method="post" class="my-form">
        <input type="hidden" name="product_id" value="<?php echo $product['ProductID']; ?>">
        <label for="product_name">Product Name:</label>
        <input type="text" id="product_name" name="product_name" value="<?php echo $product['ProductName']; ?>" required>
        <label for="description">Description:</label>
        <textarea id="description" name="description" required><?php echo $product['Description']; ?></textarea>
        <label for="price">Price:</label>
        <input type="text" id="price" name="price" value="<?php echo $product['Price']; ?>" required>
        <label for="image_url">Image URL:</label>
        <input type="text" id="image_url" name="image_url" value="<?php echo $product['ImageURL']; ?>" required>
        <button type="submit" name="update_product">Update</button>
    </form>
</div>
</main>
</body>
</html>
