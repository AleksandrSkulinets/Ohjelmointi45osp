<?php

// Include header
require 'header.php';

// if 'cart' session is not set , than setting session
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = array();
}
//someday ill put all functions to functions.php
function addToCart($productId, $quantity) {
    // Check if the product is already in the cart
    if (array_key_exists($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][$productId] += $quantity;
    } else {
        $_SESSION['cart'][$productId] = $quantity;
    }
}

// Check if the form was submitted to add a product to the cart
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_id'], $_POST['quantity'])) {
        $productId = $_POST['product_id'];
        $quantity = $_POST['quantity'];
        addToCart($productId, $quantity);
    }

    // Check if the "Empty Cart" button was clicked
    if (isset($_POST['empty_cart'])) {
        unset($_SESSION['cart']); // Clear the cart
    }

    // Redirect to the cart page to prevent form data resubmission
    header("Location: cart.php");
    exit; // Terminate the script after the redirect
}


echo "<h1>Shopping Cart</h1>";

if (empty($_SESSION['cart'])) {
    echo "Your shopping cart is empty.";
} else {

    echo "<table>";
    echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>";

    $totalPrice = 0;

   //Database :)
    include 'notadatabase.php';
    

    foreach ($_SESSION['cart'] as $productId => $quantity) {
        // Fetch the product details 
        $productDetails = null;
        foreach ($PDOproducts as $product) {
            if ($product['ProductID'] == $productId) {
                $productDetails = $product;
            }
        }

        // Calculate subtotal 
        $subtotal = $quantity * $productDetails['ProductPrice'];
        $totalPrice += $subtotal;

        echo "<tr>";
        echo "<td>{$productDetails['ProductName']}</td>";
        echo "<td>$quantity</td>";
        echo "<td>€{$productDetails['ProductPrice']}</td>";
        echo "<td>€$subtotal</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<p>Total Items in Cart: " . array_sum($_SESSION['cart']) . "</p>";
    echo "<p>Total Price: €$totalPrice</p>";
    echo "<form  class='cart-form' method='post' action='cart.php'>";
    echo "<input type='submit' name='empty_cart' value='Remove all items from cart'>";
    echo "</form>";
}
?>
<h1>Order form</h1>
<form action="order.php" method="post">
        <table>
            <tr>
                <th>Name</th>
                <td><input type="text" id="name" name="name" required></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><input type="text" id="address" name="address" required></td>
            </tr>
            <tr>
                <th>City</th>
                <td><input type="text" id="city" name="city" required></td>
            </tr>
            <tr>
                <th>Postcode</th>
                <td><input type="text" id="postcode" name="postcode" required></td>
            </tr>
            <tr>
                <td colspan="2">
                    <button type="submit">Submit</button>
                </td>
            </tr>
        </table>
    </form>
    <?php
    // Check if a confirmation message set in session
    if (isset($_SESSION["confirmationMessage"])) {
        $confirmationMessage = $_SESSION["confirmationMessage"];
        echo '<h1>' . $confirmationMessage . '</h1>';

        // Clear the confirmationmessage 
        unset($_SESSION["confirmationMessage"]);
        // Clear cart
        unset($_SESSION['cart']);
        

    }
    ?>
<?php
// Include footer
require 'footer.php';
?>
