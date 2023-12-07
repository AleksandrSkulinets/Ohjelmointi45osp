</main>

<footer>
<!-- footer navigation -->
        <ul>
            <li><a href="<?php echo $sitePath; ?>/?page=home">Home</a></li>
            <li><a href="<?php echo $sitePath; ?>/?page=cart" >Cart</a></li>
            <li><a href="<?php echo $sitePath; ?>/?page=profile" >UserProfile</a></li>
        </ul>

<!-- payments -->
    <div class="payment-logos">
        <img src="<?php echo $sitePath; ?>/templates/assets/images/visa.svg" alt="Visa">
        <img src="<?php echo $sitePath; ?>/templates/assets/images/mastercard.svg" alt="Mastercard">
        <img src="<?php echo $sitePath; ?>/templates/assets/images/Klarna.svg" alt="Klarna">
    </div>
<!-- footer copyright -->
        <p>&copy; <?php echo date('Y'); ?> <?php echo sanitizeInput($siteName); ?>. All rights reserved.</p> 
        
</footer>
</body>
</html>