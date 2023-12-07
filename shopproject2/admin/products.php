<?php
require 'admin-header.php';

// Fetching all products from the database
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
} 
if (isset($_GET['delete_product'])) {
    // Removing a product
    $productId = $_GET['delete_product'];

    // Prepare the delete statement
    $deleteStatement = $pdo->prepare("DELETE FROM products WHERE ProductID=?");
    $deleteStatement->execute([$productId]);

    // Redirect to prevent form resubmission
    header("Location: products.php");
    exit();
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
                <form method="get" action="products.php">
                    <input type="hidden" name="delete_product" value="<?php echo $product['ProductID']; ?>">
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
