<main>
    <h2>User Registration</h2>
    <?php
        if (!empty($registrationError)) {
            echo "<p>" . sanitizeInput($registrationError) . "</p>";
        }
    ?>
    <!-- register  -->
    <div class="custom-form">
        <form action="?page=register" method="post" class="my-form">
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