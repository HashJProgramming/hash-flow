<?php
include 'functions/functions.php';
?>

<!doctype html>
<html lang="en">
    <head>
        <title><?php echo settings('shop-settings','name') ?></title>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php style_css(); ?>
    </head>
    <body>

        <?php product($_GET['product'], $_GET['hash']); ?>

        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <?php script_js(); ?>
    </body>
</html>