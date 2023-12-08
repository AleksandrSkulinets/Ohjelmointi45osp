<?php
require 'admin-header.php';

// Fetching all products from database
$statement = $pdo->query("SELECT * FROM products");
$products = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['edit_product'])) {
        // Handle editing product
        $productId = $_POST['product_id'];
        $productName = $_POST['product_name'];
        $description = $_POST['description'];
        $price = $_POST['price'];
        $imageURL = $_POST['image_url'];

        $updateStatement = $pdo->prepare("UPDATE products SET ProductName=?, Description=?, Price=?, ImageURL=? WHERE ProductID=?");
        $updateStatement->execute([$productName, $description, $price, $imageURL, $productId]);

        // Prevent form resubmission
        header("Location: products.php");
        exit();
    }
    elseif(isset($_POST['delete_product']) && isset($_POST['delete_product_id'])) {
        // Removing a product
        $productId = $_POST['delete_product_id'];
        try {
           
            $deleteStatement = $pdo->prepare("DELETE FROM Products WHERE ProductID=?");
            $deleteStatement->execute([$productId]);

            
            $rowCount = $deleteStatement->rowCount();

            if ($rowCount > 0) {
                // Product successfully deleted
                header("Location: products.php");
                exit();
            } else {
                // Product not found or not deleted
                echo "Product not found or could not be deleted.";
            }
        } catch (PDOException $e) {
            // Handle errors
            echo "Error: " . $e->getMessage();
        }
    }
}

?>

<main>
<h1>Product Management</h1>

<!-- Display products -->
<table>
    <tr>
        <th>Product ID</th>
        <th>Product Name</th>
        <th>Description</th>
        <th>Price</th>
        <th>Image URL</th>
        <th>Edit</th>
        <th>Remove</th>
    </tr>
    <?php foreach ($products as $product) : ?>
        <tr>
            <td><?php echo $product['ProductID']; ?></td>
            <td><?php echo $product['ProductName']; ?></td>
            <td><?php echo $product['Description']; ?></td>
            <td><?php echo $product['Price']; ?></td>
            <td>
            <img src="<?php echo filter_var($product['ImageURL'], FILTER_VALIDATE_URL) ? $product['ImageURL'] : '../' . $product['ImageURL']; ?>"><!-- validation if prod img is url or path -->
            </td>
            <td>
                <form method="post" action="edit-product.php?id=<?php echo $product['ProductID']; ?>">
                    <button type="submit" name="edit_product">Edit</button>
                </form>
            </td>
            <td>
            <form method="post" action="products.php">
            <input type="hidden" name="delete_product_id" value="<?php echo $product['ProductID']; ?>">
            <button type="submit" name="delete_product">Delete Product</button>
            </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<!-- Add product -->
<h3><a href="add-product.php">Add Product</a></h3>
</main>
</body>
</html>
