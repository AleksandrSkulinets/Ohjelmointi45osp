<?php
require 'admin-header.php';

// Check if the user ID is provided in the URL
if (isset($_GET['id'])) {
    $userId = $_GET['id'];

    // Fetch user details from the database
    $userStatement = $pdo->prepare("SELECT * FROM users WHERE UserID = ?");
    $userStatement->execute([$userId]);
    $user = $userStatement->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // Handle the case where the user with the given ID is not found
        echo "<center><p>User not found.</p></center>";
        exit();
    }

    // Check if submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
        // Validate and update user details in the database
        $updatedFirstName = $_POST['first_name'];
        $updatedLastName = $_POST['last_name'];
        $updatedEmail = $_POST['email'];
        $updatedUserType = $_POST['user_type'];
        $updatedAddress = $_POST['address'];

        // Update user details in the database (excluding password)
        $updateStatement = $pdo->prepare("UPDATE users SET 
            FirstName = ?, 
            LastName = ?, 
            Email = ?, 
            UserType = ?, 
            Address = ? 
            WHERE UserID = ?");
        $updateStatement->execute([$updatedFirstName, $updatedLastName, $updatedEmail, $updatedUserType, $updatedAddress, $userId]);

        // Redirect after updating to prevent form resubmission
        header("Location: users.php");
        exit();
    }
} else {
    // Handle the case where no user ID is provided
    echo "Invalid request.";
    exit();
}
?>

<!-- edit user form -->
<main>
    <h1>Edit User</h1>
    <div class="custom-form">
        <form action="" method="post" class="my-form">
            <input type="hidden" name="user_id" value="<?php echo $user['UserID']; ?>">
            <label for="first_name">First Name:</label>
            <input type="text" id="first_name" name="first_name" value="<?php echo $user['FirstName']; ?>" required>
            <label for="last_name">Last Name:</label>
            <input type="text" id="last_name" name="last_name" value="<?php echo $user['LastName']; ?>" required>
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo $user['Email']; ?>" required>
            <label for="user_type">User Type:</label>
            <select name="user_type">
                <option value="Customer" <?php echo ($user['UserType'] == 'Customer') ? 'selected' : ''; ?>>Customer</option>
                <option value="Admin" <?php echo ($user['UserType'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
            </select>
            <label for="address">Address:</label>
            <input type="text" id="address" name="address" value="<?php echo $user['Address']; ?>" required>
            <button type="submit" name="update_user">Update</button>
        </form>
    </div>
</main>
</body>
</html>
