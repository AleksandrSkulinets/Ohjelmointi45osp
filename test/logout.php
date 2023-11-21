<?php
session_start(); 
if (isset($_SESSION['user'])) {
    // Unset the session user
    unset($_SESSION['user']);

}

// Redirect to the index page
header("Location: index.php");
exit();
?>