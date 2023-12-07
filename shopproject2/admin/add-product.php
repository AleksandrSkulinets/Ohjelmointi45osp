<?php
require 'admin-header.php';

// Example: Fetching all products from the database
$statement = $pdo->query("SELECT * FROM products");
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

// Handling form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_product'])) {
        // Handle adding a new product
        $productName = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $imageURL = $_POST['image_url'];

        $maxIdStatement = $pdo->query("SELECT MAX(ProductID) FROM products");
        $maxId = $maxIdStatement->fetchColumn();

        // Increment the maximum ProductID by 1 for the new product
        $newProductId = $maxId + 1;

        // Prepare the INSERT statement
        $insertStatement = $pdo->prepare("INSERT INTO products (ProductID, ProductName, Description, Price, ImageURL) VALUES (?, ?, ?, ?, ?)");

        // Execute the INSERT statement with the new ProductID
        $insertStatement->execute([$newProductId, $productName, $description, $price, $imageURL]);
        // Redirect to prevent form resubmission on refresh
        header("Location: products.php");
        exit();

        }
    }
    ?>
    <!-- add new product form -->
    <main>
    <h2>Add New Product</h2>
    <div class="custom-form">
    <form method="post" action="" class="my-form">
    <label for="product_name">Product Name:</label>
    <input type="text" name="product_name" required>
    <label for="description">Description:</label>
    <textarea name="description" required></textarea>
    
    <label for="price">Price:</label>
    <input type="number" name="price" step="0.01" required>
   
    <label for="image_url">Image URL:</label>
    <input type="text" name="image_url" required>
   
    <button type="submit" name="add_product">Add Product</button>
    </form>
    </div>
    </main>
</body>
</html>