<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo sanitizeInput($siteName); ?></title>
    <link rel="stylesheet" href="<?php echo $sitePath; ?>/templates/assets/css/style2.css">
</head>
<body>
    <header>
        <h1><?php echo sanitizeInput($siteName); ?></h1>
        
    <!-- search form -->

    <div class="search-container">
    <form class="search-form" method="get" action="<?php echo $sitePath; ?>?page=search">
    <input type="hidden" name="page" value="search">
    <input type="text" id="search" name="query" placeholder="Search...">
    <input type="submit" value="Search">
</form>
</div>

    <!-- navigation -->

        <nav>
            <ul>
                <li><a href="<?php echo $sitePath; ?>/?page=home">Home</a></li>
                <li><?php echo getCartImgHtml(); ?></li>
                <li><?php echo getUserImgHtml(); ?></li>
            </ul>
        </nav>

    </header>
<main>
