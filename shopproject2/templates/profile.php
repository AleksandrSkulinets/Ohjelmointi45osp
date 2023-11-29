<?php
if (!isset($_SESSION['user'])) {
    header("Location: ?page=login");
    exit();
}

$user = $_SESSION['user'];
$error = updateUserProfile($pdo, $user);

?>
<!-- profile  -->
<main>
    <h2>User Profile</h2>
    <?php if (isset($error)) { echo "<p>" . sanitizeInput($error) . "</p>"; } ?>
    <div class="custom-form">
        <form method="post" action="profile.php" class="my-form">
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
