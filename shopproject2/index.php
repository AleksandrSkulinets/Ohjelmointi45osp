<?php
session_start();

 require 'functions/config.php';
 require 'functions/functions.php';
  
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

include 'templates/header.php';

switch ($page) {
    case 'home':
        $products = getAllProducts();
        include 'templates/home.php';
        break;
    case 'product':

        $selectedProduct = getProductDetails($pdo);
        if ($selectedProduct !== null) {
            include 'templates/single-product.php';
        } else {
            echo "<h2>No product selected or not found.</h2>";
        }
        break;

    case 'cart':
        include 'templates/cart.php';
        break;

    case 'profile':
            // Check if the user is loggedin
        if (isset($_SESSION['user'])) {
            include 'templates/profile.php';
        } else {
                // Redirect to login page if not loggedin
                header("Location: ?page=login");
                exit();
            }
            break;

    case 'login':
            $error = handleLogin($pdo);
            include 'templates/login.php';
            break;
    case 'logout':
        if (isset($_SESSION['user'])) {
            // Unset user session
            unset($_SESSION['user']);
            header("Location: ?page=home");
        exit();     
        }

    case 'register':
            // Check if user is already loggedin
            if (isset($_SESSION['user'])) {
                header("Location: ?page=profile");
                exit();
            }
    
            $registrationError = handleRegistration($pdo);
            include 'templates/register.php';
            break;

            case 'search':
                if (isset($_GET['query'])) {
                    $searchQuery = sanitizeInput($_GET['query']);
            
                    if (strlen($searchQuery) >= 3) {
                        $searchResults = searchProducts($pdo, $searchQuery);
            
                        include 'templates/search.php';
                    } else {
                        echo "<h2>Search query must be at least 3 characters long.</h2>";
                    }
                } else {
                    echo "<h2>No search specified.</h2>";
                }
                break;

    default:
        // 404 error
        include 'templates/404.php';
        break;
}


include 'templates/footer.php';

?>