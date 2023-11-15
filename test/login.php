<?php
require 'header.php'; // Start the session in header

require 'config.php'; // Include the database connection

if (isset($_SESSION['user'])) { //if user logged in redirect to user.php
    header("Location: user.php");
    exit();
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the user input (email and password)
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare a query to select the user based on their email
    $statment = $pdo->prepare("SELECT * FROM users WHERE Email = :email");
    $statment->bindParam(':email', $email, PDO::PARAM_STR);
    $statment->execute();

    if ($statment->rowCount() === 1) {
        $row = $statment->fetch(PDO::FETCH_ASSOC);
        if ($password === $row['Password']) {
            // Password matches plaintext 
            
                $_SESSION['user'] = [
                    'UserID' => $row['UserID'],
                    'FirstName' => $row['FirstName'],
                    'LastName' => $row['LastName'],
                    'Email' => $row['Email'],
                    'Address' => $row['Address'],
                    'Password' => $row['Password'],
                
            ];
            header("Location: user.php"); // Redirect to a user page
            exit();
        } else {
            $error = "Incorrect password";
        }
    } else {
        $error = "User not found";
    }
}
?>



    <main>
        <h1>Login</h1>
        <?php if (isset($error)) { echo "<p>$error</p>"; } ?>
        <div class="loginform">
            <form method="post" action="login.php" class="login-form">
                <label for="email" class="form-label">Email:</label>
                <input type="text" id="email" name="email" required>
                <label for="password" class="form-label">Password:</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
        </div>
        <p>If you don't have an account, please <a href="register.php">register</a>.</p>
    </main>
    

    <?php require 'footer.php'; ?>


