<?php
require 'header.php';

// Check if the user is already loggedin, and if so, redirect to the user.php
if (isset($_SESSION['user'])) {
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
    // pass hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Calculate next UserID
    $statment = $pdo->prepare("SELECT MAX(UserID) AS maxUserID FROM users");
    $statment->execute();
    $result = $statment->fetch();
    $nextUserID = $result['maxUserID'] + 1;

    // Set UserType to new user Customer by default
    $userType = 'Customer';

    // Insert into database
    $statment = $pdo->prepare("INSERT INTO users (UserID, FirstName, LastName, Password, Email, Address, UserType) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $insertSuccess = $statment->execute([$nextUserID, $firstName, $lastName, $hashedPassword, $email, $address, $userType]);

    if ($insertSuccess) {
        // Registration successful redirect to user page
        header("Location: user.php");
        exit();
    } else {
        $registrationError = "Registration failed.";
    }
}
?>
<main>
    <h1>User Registration</h1>
    <?php
if (!empty($registrationError)) {
    echo "<p>" . htmlspecialchars($registrationError, ENT_QUOTES, 'UTF-8') . "</p>";
}
?>
    <div class="custom-form">
        <form action="register.php" method="post" class="my-form">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" required>

            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" required>

            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>

            <button type="submit">Register</button>

        </form>
    </div>
</main>

<?php
// Include the footer
require 'footer.php';
?>
</body>
</html>
