<?php

// Database
$server = "localhost";
$username = "root"; 
$password = ""; 
$database = "verkkokauppa";

try {
    
    $pdo = new PDO("mysql:host=$server;dbname=$database", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); 
    // echo "Connection ok!";
}
catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}

$siteName = "Shop project";
$sitePath = "/shopproject2";

?>