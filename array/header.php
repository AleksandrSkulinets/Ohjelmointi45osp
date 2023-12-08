<?php
session_start(); // Start the session every page
// better put this to functions.php 

function getCartItemCount() {
    if (isset($_SESSION['cart'])) {
        return  array_sum($_SESSION['cart']);
    } else {
        return 0;
    }
}
?>
<?php


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'], $_POST['quantity'])) {
    $productId = $_POST['add_to_cart'];
    $quantity = intval($_POST['quantity']);

    // Check if the quantity is a positive number
    if ($quantity > 0) {
        // Initialize the cart if it doesn't exist in the session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if the product is already in the cart
        if (array_key_exists($productId, $_SESSION['cart'])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }

        // Redirect to the current page to prevent form resubmission
        header("Location: " . $_SERVER['REQUEST_URI']);
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="style.css"/>
    <title>Shop</title>
</head>
<body>
    <header>
        <div class="header-part">
        <div class="logo">
        <a href="index.php"><img src="images/logo.png" alt="Shop"></a>
        </div>
        <div class="search">
            <form action="search.php" method="get">
                <input type="text" name="search" placeholder="Search for a product">
                <button type="submit">Search</button>
            </form>
        </div>
        
        
        
        
  
        </div>
    </div>
        <nav>
            <ul>
                <li><a href="index.php">Home</a></li>
            
            <li>  
                
            <?php
    // Check if the shopping cart is empty
    $cartIsEmpty = empty($_SESSION['cart']);

    if ($cartIsEmpty) {
        // Display image for an empty cart
        echo '<a href="cart.php"><img src="images/cart.png" alt="Cart"></a>';
    } else {
        // Display image for a cart with items and show the number of products
        echo '<a href="cart.php"><img src="images/cartfull.png" alt="Cart with items"></a>';
        echo '<span>' . getCartItemCount() . '</span>';
    }
    ?>
        </li>
        </ul>
        </nav>
    
    </header>
