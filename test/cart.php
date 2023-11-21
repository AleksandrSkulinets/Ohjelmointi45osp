<?php
// Include header
require 'header.php';
?>

<!-- Cart Page -->
<main>
    <h1>Shopping Cart</h1>
    
    <?php
    if (empty($_SESSION['cart'])) {
        echo "Your shopping cart is empty ";
        echo "<br>";
    } else {
        // Include database 
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
  
            echo '<div class="cart-itm"><p class="cart-count">Total Items in Cart: ' . array_sum($_SESSION['cart']) . '</p>';
            echo '<p class="cart-count">Total Price: €' . $totalPrice . '</p>';
            echo "<form class='cart-form' method='post' action='cart.php'>";
            echo "<input type='submit' name='empty_cart' value='Remove all items from cart'>";
            echo "</form></div>";
            if (isset($_POST['empty_cart'])) { //if empty_cart is submitted
    unset($_SESSION['cart']);      //unset cart key 
    header("Location: cart.php");  //redirect to cart.php
    exit;
}
        } else {
            //echo "No products available.";
        }
    }

    //if user logged in and cart is not emty
    if (isset($_SESSION['user']) && !empty($_SESSION['cart'])) {
    if (isset($_POST['make_order'])) {
        // Find the maximum OrderID from the db
        $maxOrderIDQuery = $pdo->query("SELECT MAX(OrderID) AS max_order_id FROM orders");
        $maxOrderIDRow = $maxOrderIDQuery->fetch(PDO::FETCH_ASSOC);

        //  new OrderID
        if ($maxOrderIDRow) {
            $newOrderID = (int)$maxOrderIDRow['max_order_id'] + 1;
        } else {
            //case when there is no existing orders
            $newOrderID = 1;
        }

        // Create a new order in 'orders'
        $userID = $_SESSION['user']['UserID'];
        $orderDate = date('Y-m-d H:i:s'); 
        $status = 'Pending'; // Set Pending as default status 

        $insertO = $pdo->prepare("INSERT INTO orders (OrderID, UserID, OrderDate, Status, TotalPrice) VALUES (?, ?, ?, ?, ?)");
        $insertO->execute([$newOrderID, $userID, $orderDate, $status, $totalPrice]);

        // Find maximum OrderItemID from db
        $maxOrderItemIDQuery = $pdo->query("SELECT MAX(OrderItemID) AS max_order_item_id FROM orderitems");
        $maxOrderItemIDRow = $maxOrderItemIDQuery->fetch(PDO::FETCH_ASSOC);

        // new OrderItemID
        if ($maxOrderItemIDRow) {
            $newOrderItemID = (int)$maxOrderItemIDRow['max_order_item_id'] + 1;
        } else {
            // when no existing 'orderitems'
            $newOrderItemID = 1;
        }

        // Create and prepare the $insertOI
        $insertOI = $pdo->prepare("INSERT INTO orderitems (OrderItemID, OrderID, ProductID, Quantity, Subtotal) VALUES (?, ?, ?, ?, ?)");

        foreach ($_SESSION['cart'] as $productID => $quantity) {
            $productDetails = null;
            foreach ($products as $product) {
                if ($product['ProductID'] == $productID) {
                    $productDetails = $product;
                    break;
                }
            }
            // subtota count
            $subtotal = $quantity * $productDetails['Price'];

            // Insert order items with OrderItemID
            $insertOI->execute([$newOrderItemID, $newOrderID, $productID, $quantity, $subtotal]);

            // Increment OrderItemID
            $newOrderItemID++;
        }
    
        // Remove  items from cart
        unset($_SESSION['cart']);

        // message after order
        echo '<p>Thank you for your order, ' . $_SESSION['user']['FirstName'] . '.</p>';
        echo '<p>Your order id is ' . $newOrderID . ' order price is €' . $totalPrice . '.</p>';

        // print links
        echo '<p><a href="cart.php">Back to Cart</a></p>'. "or" . '<p><a href="index.php">home page</a></p>';
    }

    // Display the "Make Order" button
    echo '<div class="cart-itm">';
    echo "<form class='cart-form' method='post' action='cart.php'>";
    echo "<input type='submit' name='make_order' value='Make an order'>";
    echo "</form></div>";
    
} elseif (!isset($_SESSION['user']) && !empty($_SESSION['cart'])) {
    // If user is not logged but cart not emty
    // Or i can put here form like register.php 
    // but it will look a little bit strange, 
    // cuz user have anyway to register with it
    // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //     $firstName = $_POST['first_name'];
    //     $lastName = $_POST['last_name'];
    //     $email = $_POST['email'];
    //     $password = $_POST['password'];
    //     $address = $_POST['address']; 
    //     $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    //     $maxUserIDQuery = $pdo->query("SELECT MAX(UserID) AS max_user_id FROM users");
    //     $maxUserIDRow = $maxUserIDQuery->fetch(PDO::FETCH_ASSOC);
    //     $nextUserID = $maxUserIDRow ? ($maxUserIDRow['max_user_id'] + 1) : 1;
    //     $userType = 'Customer';
    //     $insertUserStatement = $pdo->prepare("INSERT INTO users (UserID, FirstName, LastName, Password, Email, Address, UserType) VALUES (?, ?, ?, ?, ?, ?, ?)");
    //     $insertSuccess = $insertUserStatement->execute([$nextUserID, $firstName, $lastName, $hashedPassword, $email, $address, $userType]);
    //     if ($insertSuccess) {
    //         $maxOrderIDQuery = $pdo->query("SELECT MAX(OrderID) AS max_order_id FROM orders");
    //         $maxOrderIDRow = $maxOrderIDQuery->fetch(PDO::FETCH_ASSOC);
    //         if ($maxOrderIDRow) {
    //             $newOrderID = (int)$maxOrderIDRow['max_order_id'] + 1;
    //         } else {
    //             $newOrderID = 1;
    //         }
    //         $userID = $nextUserID; 
    //         $orderDate = date('Y-m-d H:i:s'); 
    //         $status = 'Pending'; 
    //         $insertO = $pdo->prepare("INSERT INTO orders (OrderID, UserID, OrderDate, Status, TotalPrice) VALUES (?, ?, ?, ?, ?)");
    //         $insertO->execute([$newOrderID, $userID, $orderDate, $status, $totalPrice]);
    //         $maxOrderItemIDQuery = $pdo->query("SELECT MAX(OrderItemID) AS max_order_item_id FROM orderitems");
    //         $maxOrderItemIDRow = $maxOrderItemIDQuery->fetch(PDO::FETCH_ASSOC);
    //         if ($maxOrderItemIDRow) {
    //             $newOrderItemID = (int)$maxOrderItemIDRow['max_order_item_id'] + 1;
    //         } else
    //             $newOrderItemID = 1;
    //         }
    //         $insertOI = $pdo->prepare("INSERT INTO orderitems (OrderItemID, OrderID, ProductID, Quantity, Subtotal) VALUES (?, ?, ?, ?, ?)");
    
    //         foreach ($_SESSION['cart'] as $productID => $quantity) {
    //             $productDetails = null;
    //             foreach ($products as $product) {
    //                 if ($product['ProductID'] == $productID) {
    //                     $productDetails = $product;
    //                     break;
    //                 }
    //             }
    //             $subtotal = $quantity * $productDetails['Price'];
    //             $insertOI->execute([$newOrderItemID, $newOrderID, $productID, $quantity, $subtotal]);
    //             $newOrderItemID++;
    //         }
    //         unset($_SESSION['cart']);
    //         header("Location: cart.php");
    //         exit();
    //     } 
    // }
    // 
    //    <div class="custom-form">
    //         <form action="cart.php" method="post" class="my-form">
    //             <label for="first_name">First Name:</label>
    //             <input type="text" id="first_name" name="first_name" required>
    
    //             <label for="last_name">Last Name:</label>
    //             <input type="text" id="last_name" name="last_name" required>
    
    //             <label for="email">Email:</label>
    //             <input type="email" id="email" name="email" required>
                
    //             <label for="address">Address:</label>
    //             <input type="text" id="address" name="address" required>
    
    //             <label for="password">Password:</label>
    //             <input type="password" id="password" name="password" required>
    
    //             <button type="submit" name='make_order'>Register and make order</button>
    
    //         </form>
    //     </div> 

    echo '<a href="login.php">Please log in to complete the order.</a>';  
}  
?>
</main>
<?php
// Include footer
require 'footer.php';
?>
</body>
</html>