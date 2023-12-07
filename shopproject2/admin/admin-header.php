<?php
include('../functions/config.php');
session_start();
?>
<?php

if (!isset($_SESSION['admin'])) {
    header("Location: admin-login.php"); // Redirect if not logged in
    exit();
}
if (isset($_POST['logout'])) {
    unset($_SESSION['admin']);
    header("Location: admin-login.php"); // Redirect after logout
    exit();
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../templates/assets/css/style2.css">
    <title>Admin panel</title>
</head>
<body>
<header>
    <nav>
        <ul>
            <li><a href="../admin/index.php">Admin panel</a></li>
                
            <li><a href="../admin/users.php">Users</a></li>
                
            <li><a href="../admin/orders.php">Orders</a></li>
                
            <li><a href="../admin/products.php">Products</a></li>
            
            <li><form method="post" action="logout.php"><button type="submit" name="logout">Logout</button></form></li>
               
            
        </ul>
    </nav>
</header>
