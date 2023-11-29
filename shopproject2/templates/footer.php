</main>

<footer>
    <!-- footer navigation -->
            <ul>
                <li><a href="<?php echo $sitePath; ?>/?page=home">Home</a></li>
                <li><a href="<?php echo $sitePath; ?>/?page=cart" >Cart</a></li>
                <li><a href="<?php echo $sitePath; ?>/?page=profile" >UserProfile</a></li>
            </ul>
    <!-- footer copyright -->
        <p>&copy; <?php echo date('Y'); ?> <?php echo sanitizeInput($siteName); ?>. All rights reserved.</p> 
        
</footer>
</body>
</html>