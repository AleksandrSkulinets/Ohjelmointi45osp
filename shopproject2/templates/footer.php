</main>

<footer>
<!-- footer navigation -->
        <ul>
            <li><a href="<?php echo $sitePath; ?>/?page=home">Home</a></li>
            <li><a href="<?php echo $sitePath; ?>/?page=cart" >Cart</a></li>
            <li><a href="<?php echo $sitePath; ?>/?page=profile" >UserProfile</a></li>
        </ul>

<!-- Logos -->
<div class="logos">
    <div class="delivery">
    <img src="<?php echo $sitePath; ?>/templates/assets/images/Posti.svg" alt="Posti">
    <div class="postnord">
    <img src="<?php echo $sitePath; ?>/templates/assets/images/PostNord.svg" alt="PostNord">
    </div>
    </div>
    <div class="payments">
        <img src="<?php echo $sitePath; ?>/templates/assets/images/visa.svg" alt="Visa">
        <img src="<?php echo $sitePath; ?>/templates/assets/images/mastercard.svg" alt="Mastercard">
        <img src="<?php echo $sitePath; ?>/templates/assets/images/Klarna.svg" alt="Klarna">
    </div>
</div>
<!-- footer copyright -->
        <p>&copy; <?php echo date('Y'); ?> <?php echo sanitizeInput($siteName); ?>. All rights reserved.</p> 
        
</footer>
</body>
</html>