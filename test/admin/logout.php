<?php
session_start(); 
if (isset($_SESSION['admin'])) {
    // Unset session key 
    unset($_SESSION['admin']);
}

// Redirect to the index 
header("Location: index.php");
exit();
?>