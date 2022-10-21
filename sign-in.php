<?php
include 'functions/functions.php';
authenticate();
?>
<!doctype html>
<html lang="en">
    <head>
        <title><?php echo settings('shop-settings','name') ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <?php header_html(); ?>
    </head>
    <body>

        <?php sign_in_form(); ?>

        <?php script_js(); ?>
    </body>
</html>