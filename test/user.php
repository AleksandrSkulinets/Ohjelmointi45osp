<?php
require 'header.php'; 
require 'config.php'; 

// Check if the user is logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php"); // Redirect to the login page if not logged in
    exit();
}

$user = $_SESSION['user']; // Retrieve user data from session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $user['UserID'];
    // htmlspecialchars helping prevent user input from XSS
    $first_name = htmlspecialchars($_POST['first_name']);
    $last_name = htmlspecialchars($_POST['last_name']);
    $email = htmlspecialchars($_POST['email']);
    $address = htmlspecialchars($_POST['address']);
    $password = htmlspecialchars($_POST['password']);
     
    //password hashing
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $statement = $pdo->prepare("UPDATE users SET FirstName = :first_name, LastName = :last_name, Email = :email, Address = :address, Password = :password WHERE UserID = :user_id");
    $statement->bindParam(':first_name', $first_name, PDO::PARAM_STR);
    $statement->bindParam(':last_name', $last_name, PDO::PARAM_STR);
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->bindParam(':address', $address, PDO::PARAM_STR);
    $statement->bindParam(':password', $hashedPassword, PDO::PARAM_STR); //hashed pass store
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        // Update the session data
        $_SESSION['user']['FirstName'] = $first_name;
        $_SESSION['user']['LastName'] = $last_name;
        $_SESSION['user']['Email'] = $email;
        $_SESSION['user']['Address'] = $address;

        header("Location: user.php");
        exit();
    } else {
        // Error update operation
        $error = "Failed to update user info.";
    }
}

?>

    <main>
        <h1>User Profile</h1>
        <?php if (isset($error)) { echo "<p>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</p>";} ?>        
        <div class="custom-form">
            <form method="post" action="user.php" class="my-form">
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

                <button type="submit">Update</button>
            </form>
        </div>
        
        
    </main>

    <?php require 'footer.php'; ?>
</body>
</html>
