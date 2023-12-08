<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve rder <form>
    $name = $_POST["name"];
    $address = $_POST["address"];
    $city = $_POST["city"];
    $postcode = $_POST["postcode"];

    // example confirmation message
    $confirmationMessage = "Thanks for your order, $name! Delivery to the following address: $address, $city, $postcode";

    // Store the confirmation message in a session variable
    $_SESSION["confirmationMessage"] = $confirmationMessage;

    // Redirect to cart.php or any other page
    header("Location: cart.php");
    exit;
}
?>
