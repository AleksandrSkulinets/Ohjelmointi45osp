<?php
require 'header.php';

// Check if the user is already logged in, and if so, redirect to the user profile page
if (isset($_SESSION['user_id'])) {
    header("Location: user.php");
    exit();
}

// Include database connection
require 'config.php';

$registrationError = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $address = $_POST['address']; 



    // Calculate next available UserID
    $statment = $pdo->prepare("SELECT MAX(UserID) AS maxUserID FROM users");
    $statment->execute();
    $result = $statment->fetch();
    $nextUserID = $result['maxUserID'] + 1;

    // Set UserType to new users as Customer
    $userType = 'Customer';

    // Insert user data into the database
    $statment = $pdo->prepare("INSERT INTO users (UserID, FirstName, LastName, Password, Email, Address, UserType) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertSuccess = $statment->execute([$nextUserID, $firstName, $lastName, $password, $email, $address, $userType]);

    if ($insertSuccess) {
        // Registration successful; you can optionally log the user in here
        header("Location: user.php");
        exit();
    } else {
        $registrationError = "Registration failed. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>User Registration</title>
</head>
<body>
    <h1>User Registration</h1>
    <?php if (!empty($registrationError)) {
        echo "<p>$registrationError</p>";
    } ?>
    <form action="register.php" method="post">
        <label for="first_name">First Name:</label>
        <input type="text" id="first_name" name="first_name" required><br><br>

        <label for="last_name">Last Name:</label>
        <input type="text" id="last_name" name="last_name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address"  required><br><br> 

        <button type="submit">Register</button>
    </form>
</body>
</html>

<?php
// Include the footer
require 'footer.php';
?>
