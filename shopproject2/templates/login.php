<!-- login  -->
<h2>Login</h2>
<?php if (isset($error)) { echo "<p>" . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . "</p>";} ?>       
<div class="custom-form">
    <form method="post" action="" class="my-form">
        <label for="email" class="form-label">Email:</label>
        <input type="text" id="email" name="email" required>
        <label for="password" class="form-label">Password:</label>
        <input type="password" id="password" name="password" required>
        <button type="submit">Login</button>
    </form>
</div>
<p>If you don't have an account, please <a href="<?php echo isset($sitePath) ? $sitePath : ''; ?>/?page=register">register</a>.</p>