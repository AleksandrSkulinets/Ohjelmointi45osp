<?php
require 'header.php'; // Start the session in header
require 'config.php'; // Include the database connection

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$user = $_SESSION['user']; // Retrieve user data from the session

// Handle form submission to update user information
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $user['UserID'];
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $password = htmlspecialchars($_POST['password']); // add  password hashing

    $statement = $pdo->prepare("UPDATE users SET FirstName = :first_name, LastName = :last_name, Email = :email, Address = :address, Password = :password WHERE UserID = :user_id");
    $statement->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $statement->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':address', $address, PDO::PARAM_STR);
    $statement->bindParam(':password', $password, PDO::PARAM_STR);
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        // Update the session data with the new information
        $_SESSION['user']['FirstName'] = $first_name;
        $_SESSION['user']['LastName'] = $last_name;
        $_SESSION['user']['Email'] = $email;
        $_SESSION['user']['Address'] = $address;

        // Display a success message
        $success = "User information updated successfully.";
    } else {
        // Error handling for the update operation
        $error = "Failed to update user info.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
</head>
<body>
    <main>
        <h1>User Profile</h1>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <?php if (isset($success)) { echo "<p>$success</p>"; header("Location: user.php"); } ?>
        
        <div class="loginform">
            <form method="post" action="user.php" class="custom-form">
                <label for="first_name">First Name:</label>
                <input type="text" id="first_name" name="first_name" value="<?php echo $user['FirstName']; ?>" required>
                
                <label for="last_name">Last Name:</label>
                <input type="text" id="last_name" name="last_name" value="<?php echo $user['LastName']; ?>" required>
                
                <label for="email">Email:</label>
                <input type="text" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
                
                <label for="address">Address:</label>
                <input type="text" id="address" name="address" value="<?php echo $user['Address']; ?>" required>

                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>

                <button type="submit">Update Information</button>
            </form>
        </div>
        
        
    </main>

    <?php require 'footer.php'; ?>
</body>
</html>
