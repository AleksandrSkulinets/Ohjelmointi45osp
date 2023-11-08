<?php
// Include header
require 'header.php';
?>

<!-- Cart Page -->
<main>
    <h1>Shopping Cart</h1>

    <?php
    if (empty($_SESSION['cart'])) {
        echo "Your shopping cart is empty.";
    } else {
        // Include database configuration to establish a connection
        require 'config.php';

        // Fetch products from the database
        $statment = $pdo->query("SELECT ProductID, ProductName, Price FROM products");
        $products = $statment->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($products)) {
            echo "<table>";//table start

            echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>";

            $totalPrice = 0;

            foreach ($_SESSION['cart'] as $productId => $quantity) {
                // product details from $products
                $productDetails = null;
                foreach ($products as $product) {
                    if ($product['ProductID'] == $productId) {
                        $productDetails = $product;
                    }
                }

                // Calculate subtotal
                $subtotal = $quantity * $productDetails['Price'];
                $totalPrice += $subtotal;

                echo "<tr>";
                echo "<td>{$productDetails['ProductName']}</td>";
                echo "<td>$quantity</td>";
                echo "<td>€{$productDetails['Price']}</td>";
                echo "<td>€$subtotal</td>";
                echo "</tr>";
            }

            echo "</table>"; // table end

            echo '<p class="cart-count">Total Items in Cart: ' . array_sum($_SESSION['cart']) . '</p>';
            echo '<p class="cart-count">Total Price: €' . $totalPrice . '</p>';
            echo "<form class='cart-form' method='post' action='cart.php'>";
            echo "<input type='submit' name='empty_cart' value='Remove all items from cart'>";
            echo "</form>";
            if (isset($_POST['empty_cart'])) { //if empty_cart is submited
                unset($_SESSION['cart']);      //unset cart key 
                header("Location: cart.php");  //redirect to cart.php
                exit;
        } else {
            //echo "No products available.";
        }
    }
}
    ?>
</main>

<?php
// Include footer
require 'footer.php';
?>
