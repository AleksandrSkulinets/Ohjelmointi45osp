<?php
$sitePath = "/shopproject2";

function getAllProducts() {
    global $pdo; 
    $statement = $pdo->query("SELECT ProductID, ProductName, Description, Price, ImageURL FROM products");
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}
//sort
function productSort($products, $sortOption) {
    
    switch ($sortOption) {
        case 'price_asc':
            usort($products, function($a, $b) {
                return $a['Price'] - $b['Price'];
            });
            break;
        case 'price_desc':
            usort($products, function($a, $b) {
                return $b['Price'] - $a['Price'];
            });
            break;
        case 'name_asc':
            usort($products, function($a, $b) {
                return strcmp($a['ProductName'], $b['ProductName']);
            });
            break;
        case 'name_desc':
            usort($products, function($a, $b) {
                return strcmp($b['ProductName'], $a['ProductName']);
            });
            break;
    }

    return $products;
}

function getProductDetails($pdo) {
    $selectedProduct = null;

    if (isset($_GET['id'])) {
        $productID = $_GET['id'];
        $statement = $pdo->prepare("SELECT ProductID, ProductName, Description, Price, ImageURL FROM products WHERE ProductID = :productID");
        $statement->bindParam(':productID', $productID, PDO::PARAM_INT);
        $statement->execute();
        $selectedProduct = $statement->fetch(PDO::FETCH_ASSOC);

        if ($selectedProduct !== false) {
            return $selectedProduct;
        }
    }
    return null;
}

function sanitizeInput($input) {
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function getCartItemCount() {
    if (isset($_SESSION['cart'])) {
        return  array_sum($_SESSION['cart']);
    } else {
        return 0;
    }
}

function getCartImgHtml() {
    global $sitePath;

    $cartIsEmpty = empty($_SESSION['cart']);
    $cartItemCount = getCartItemCount();

    $html = '<a href="' . $sitePath . '/?page=cart">';
    $html .= '<img src="' . $sitePath . '/templates/assets/images/';
    $html .= $cartIsEmpty ? 'cart.png" alt="Cart">' : 'cartfull.png" alt="Cart with items">';
    if (!$cartIsEmpty) {
        $html .= '<span>' . $cartItemCount . '</span>';
    }
    $html .= '</a>';

    return $html;
}

function getUserImgHtml() {
    global $sitePath;

    $userIsNotLogin = empty($_SESSION['user']);

    $html = '<a href="' . $sitePath . '/?page=profile">';
    $html .= '<img src="' . $sitePath . '/templates/assets/images/';
    $html .= $userIsNotLogin ? 'user.png" alt="User">' : 'user-login.png" alt="User is logged in">';
    $html .= '</a>';
    
    if (!$userIsNotLogin) {
        $html .= ' <span><a href="' . $sitePath . '/?page=logout">Logout</a></span>';
    }

    return $html;
}



function addToCart() {
    if (isset($_POST['add_to_cart'])) {
        $productId = $_POST['add_to_cart'];
        $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
        $quantity = max(1, $quantity);
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$productId])) {
            $_SESSION['cart'][$productId] += $quantity;
        } else {
            $_SESSION['cart'][$productId] = $quantity;
        }
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }
}

function displayCart($pdo) {
    if (empty($_SESSION['cart'])) {
        echo "<p>Your shopping cart is empty</p>";
    } else {
        $statement = $pdo->query("SELECT ProductID, ProductName, Price FROM products");
        $products = $statement->fetchAll(PDO::FETCH_ASSOC);

        if (!empty($products)) {
            echo "<table>"; // table start

            echo "<tr><th>Product</th><th>Quantity</th><th>Price</th><th>Subtotal</th></tr>";

            $totalPrice = 0;

            foreach ($_SESSION['cart'] as $productId => $quantity) {
                // product details 
                $productDetails = null;
                foreach ($products as $product) {
                    if ($product['ProductID'] == $productId) {
                        $productDetails = $product;
                        break;
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
            echo "</table>"; // end table 

            echo '<div class="cart-itm"><p class="cart-count">Total Items in Cart: ' . array_sum($_SESSION['cart']) . '</p>';
            echo '<p class="cart-count">Total Price: €' . $totalPrice . '</p>';
            echo "<form class='cart-form' method='post' action='?page=cart'>";
            echo "<input type='submit' name='empty_cart' value='Remove all items from cart'>";
            echo "</form></div>";
        }
    }
}
function emptyCart() {
    if (isset($_POST['empty_cart']) && isset($_GET['page']) && $_GET['page'] === 'cart') {
        // Unset the cart session to remove all cart items
        unset($_SESSION['cart']);

        // Redirect to the same page
        header("Location: {$_SERVER['REQUEST_URI']}");
        exit;
    }
}


function calculateTotalPrice($cart, $products) {
    $totalPrice = 0;

    foreach ($cart as $productID => $quantity) {
        $productDetails = null;
        foreach ($products as $product) {
            if ($product['ProductID'] == $productID) {
                $productDetails = $product;
                break;
            }
        }

        if ($productDetails) {
            $subtotal = $quantity * $productDetails['Price'];
            $totalPrice += $subtotal;
        }
    }

    return $totalPrice;
}


function makeOrder($pdo) {
    if (isset($_SESSION['user']) && !empty($_SESSION['cart'])) {
        if (isset($_POST['make_order'])) {
            $statement = $pdo->query("SELECT ProductID, ProductName, Price FROM products");
            $products = $statement->fetchAll(PDO::FETCH_ASSOC);
            // Calculate total price 
            $totalPrice = calculateTotalPrice($_SESSION['cart'], $products);

            // Create new order in 'orders'
            $userID = $_SESSION['user']['UserID'];
            $orderDate = date('Y-m-d H:i:s'); 
            $status = 'Pending'; //Pending as the default
            $insertO = $pdo->prepare("INSERT INTO orders (UserID, OrderDate, Status, TotalPrice) VALUES (?, ?, ?, ?)");
            $insertO->execute([$userID, $orderDate, $status, $totalPrice]);

            // Get the auto-incremented OrderID
            $newOrderID = $pdo->lastInsertId();

            // Find maximum OrderItemID 
            $maxOrderItemIDQuery = $pdo->query("SELECT MAX(OrderItemID) AS max_order_item_id FROM orderitems");
            $maxOrderItemIDRow = $maxOrderItemIDQuery->fetch(PDO::FETCH_ASSOC);
            
            // New OrderItemID
            if ($maxOrderItemIDRow) {
                $newOrderItemID = (int)$maxOrderItemIDRow['max_order_item_id'] + 1;
            } else {
                // When no existing 'orderitems'
                $newOrderItemID = 1;
            }
            
            $insertOI = $pdo->prepare("INSERT INTO orderitems (OrderItemID, OrderID, ProductID, Quantity, Subtotal) VALUES (?, ?, ?, ?, ?)");

            foreach ($_SESSION['cart'] as $productID => $quantity) {
                $productDetails = null;
                foreach ($products as $product) {
                    if ($product['ProductID'] == $productID) {
                        $productDetails = $product;
                        break;
                    }
                }
                // Subtotal count
                $subtotal = $quantity * $productDetails['Price'];
                // Insert orderitems 
                $insertOI->execute([$newOrderItemID, $newOrderID, $productID, $quantity, $subtotal]);
                // Increment OrderItemID
                $newOrderItemID++;
            }
            
            // Remove items from cart after order
            unset($_SESSION['cart']);

            echo '<p>Thank you for your order, ' . $_SESSION['user']['FirstName'] . '.</p>';
            echo '<p>Your order id is ' . $newOrderID . ' order price is €' . $totalPrice . '.</p>';
            
            // print links
            echo '<p><a href="?page=cart">Back to Cart</a></p>' . "<p>or</p>" . '<p><a href="index.php">Home Page</a></p>';
        }
        
        // Display "Make Order" button
        echo '<div class="cart-itm">';
        echo "<form class='cart-form' method='post' action='?page=cart'>";
        echo "<input type='submit' name='make_order' value='Make an order'>";
        echo "</form></div>";
    }
}



function handleLogin($pdo) {
    if (isset($_SESSION['user'])) {
        header("Location: ?page=profile"); // Redirect to profile if logged in
        exit();
    }

    $error = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $statement = $pdo->prepare("SELECT * FROM users WHERE Email = :email");
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->execute();

        if ($statement->rowCount() === 1) {
            $row = $statement->fetch(PDO::FETCH_ASSOC);
            if (password_verify($password, $row['Password'])) {
                $_SESSION['user'] = [
                    'UserID' => $row['UserID'],
                    'FirstName' => $row['FirstName'],
                    'LastName' => $row['LastName'],
                    'Email' => $row['Email'],
                    'Address' => $row['Address'],
                    'Password' => $row['Password'],
                ];
                header("Location: ?page=profile");
                exit();
            } else {
                $error = "Incorrect password";
            }
        } else {
            $error = "User not found";
        }
    }

    return $error;
}

function updateUserProfile($pdo, $user) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $user_id = $user['UserID'];
        $first_name = htmlspecialchars($_POST['first_name']);
        $last_name = htmlspecialchars($_POST['last_name']);
        $email = htmlspecialchars($_POST['email']);
        $address = htmlspecialchars($_POST['address']);
        $password = htmlspecialchars($_POST['password']);

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $statement = $pdo->prepare("UPDATE users SET FirstName = :first_name, LastName = :last_name, Email = :email, Address = :address, Password = :password WHERE UserID = :user_id");
        $statement->bindParam(':first_name', $first_name, PDO::PARAM_STR);
        $statement->bindParam(':last_name', $last_name, PDO::PARAM_STR);
        $statement->bindParam(':email', $email, PDO::PARAM_STR);
        $statement->bindParam(':address', $address, PDO::PARAM_STR);
        $statement->bindParam(':password', $hashedPassword, PDO::PARAM_STR);
        $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);

        if ($statement->execute()) {
            
            $_SESSION['user']['FirstName'] = $first_name;
            $_SESSION['user']['LastName'] = $last_name;
            $_SESSION['user']['Email'] = $email;
            $_SESSION['user']['Address'] = $address;

            header('Location: ?page=profile');
            exit();
        } else {
            
            return "Failed to update user info.";
        }
    }
    return null; 
}

function handleRegistration($pdo) {
    $registrationError = '';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $address = $_POST['address']; 
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Check if user with the same email already exists
        $existingUserStatement = $pdo->prepare("SELECT UserID FROM users WHERE Email = ?");
        $existingUserStatement->execute([$email]);
        $existingUser = $existingUserStatement->fetch();

        if ($existingUser) {
            $registrationError = "User with this email is already registered.";
        } else {
            // Calculate next UserID
            $statement = $pdo->prepare("SELECT MAX(UserID) AS maxUserID FROM users");
            $statement->execute();
            $result = $statement->fetch();
            $nextUserID = $result['maxUserID'] + 1;
            // UserType Customer by default
            $userType = 'Customer';
            // Insert into database
            $insertStatement = $pdo->prepare("INSERT INTO users (UserID, FirstName, LastName, Password, Email, Address, UserType) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $insertSuccess = $insertStatement->execute([$nextUserID, $firstName, $lastName, $hashedPassword, $email, $address, $userType]);

            if ($insertSuccess) {
                // Registration successful, redirect to profile
                header("Location: ?page=profile");
                exit();
            } else {
                $registrationError = "Registration failed.";
            }
        }
    }

    return sanitizeInput($registrationError);
}

function searchProducts($pdo, $query) {

    if (strlen($query) < 3) {    
        return [];
    }
    $query = '%' . $query . '%'; 
    $statement = $pdo->prepare("SELECT ProductID, ProductName, Description, Price, ImageURL FROM products WHERE ProductName LIKE :query OR Description LIKE :query");
    $statement->bindParam(':query', $query, PDO::PARAM_STR);
    $statement->execute();

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}


?>