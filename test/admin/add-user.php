<?php
require 'admin-header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_user'])) {
    // new user data
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $email = $_POST['email'];
    $address = $_POST['address'];
    $userType = $_POST['user_type'];

    // Find max UserID
    $maxIdStatement = $pdo->query("SELECT MAX(UserID) FROM users");
    $maxId = $maxIdStatement->fetchColumn();

    // UserID will be maxId + 1
    $newUserId = $maxId + 1;

    //  database insertion statement
    $insertStatement = $pdo->prepare("INSERT INTO users (UserID, FirstName, LastName, Password, Email, Address, UserType) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertStatement->execute([$newUserId, $firstName, $lastName, $password, $email, $address, $userType]);

    // redirect back to users.php adding 
    header("Location: users.php");
    exit();
}
?>

<main>
    <h1>Add User</h1>
    <div class="custom-form">
        <form action="" method="post" class="my-form">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>

            <label for="user_type">User Type:</label>
            <select name="user_type">
                <option value="Customer">Customer</option>
                <option value="Admin">Admin</option>
            </select>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit" name="add_user">Register</button>
        </form>
    </div>
</main>
