<?php
include('../config.php');
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get user input email/password
    $email = $_POST['email'];
    $password = $_POST['password'];

    // query to select user based on email
    $statement = $pdo->prepare("SELECT * FROM users WHERE Email = :email");
    $statement->bindParam(':email', $email, PDO::PARAM_STR);
    $statement->execute();

    // Redirect back to login page if issue with the database query
    if ($statement->rowCount() !== 1) {
        header("Location: admin-login.php?error=User not found");
        exit();
    }

    $row = $statement->fetch(PDO::FETCH_ASSOC);

    // add hashed password
    if (password_verify($password, $row['Password'])) {
        //  verify from hash
        session_regenerate_id(true); // Regenerate session for security

        // Change session key name
        $_SESSION['admin'] = [
            'email' => $row['Email'],
            'UserType' => $row['UserType'],
        ];

        if ($_SESSION['admin']['UserType'] === 'Admin') {
            // User is admin
            header("Location: index.php"); // Redirect to admin page
            exit();
        } else {
            // User is not admin
            header("Location: admin-login.php?error=This user is not an admin");
            unset($_SESSION['admin']);
            exit();
        }
    } else {
        // Incorrect password
        header("Location: admin-login.php?error=Wrong password");
        exit();
    }
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="../style.css">
    <title>Admin login</title>
</head>
<main>
    <h1>Admin Login</h1>
    <?php if (isset($_GET['error'])) { echo "<p>" . htmlentities($_GET['error'], ENT_QUOTES, 'UTF-8') . "</p>"; } ?>
                                                <!--htmlentities to convert special characters to their HTML entities, to prevent xss -->
    <div class="custom-form">
        <form method="post" action="admin-login.php" class="my-form">
            <label for="email" class="form-label">Email:</label>
            <input type="text" id="email" name="email" required>
            <label for="password" class="form-label">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</main>
