# hash-flow

## Quickstart - Hashflow Setup
Note must configure the setting.json first

          {

          "shop-settings" : {
            "name": "My Store",
            "currency-sign": "₱"
          },


          "site-config" : {
            "product_link" : "product.php",
            "shopping_cart_link" : "shopping-cart.php",
            "order_status" : "orders-status.php",
            "sign-in-failed" : "sign-in.php",
            "sign-in-success" : "index.php",
            "head" : [
              "<script src=\"https://code.jquery.com/jquery-3.5.0.js\"></script>"
            ],
            "css" : [
              "assets/css/hash-flow_v2.css",
              "assets/css/sign-in.css",
              "https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
            ],
            "js" : [
              "<script src=\"https://code.jquery.com/jquery-3.3.1.slim.min.js\" integrity=\"sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo\" crossorigin=\"anonymous\"></script>",
              "<script src=\"https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js\" integrity=\"sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1\" crossorigin=\"anonymous\"></script>",
              "<script src=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js\" integrity=\"sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM\" crossorigin=\"anonymous\"></script>",
              "<script> $(document).ready(function(){\n\nvar quantitiy=0;\n   $('.quantity-right-plus').click(function(e){\n        \n        // Stop acting like a button\n        e.preventDefault();\n        // Get the field name\n        var quantity = parseInt($('#quantity').val());\n        \n        // If is not undefined\n            \n            $('#quantity').val(quantity + 1);\n\n          \n            // Increment\n        \n    });\n\n     $('.quantity-left-minus').click(function(e){\n        // Stop acting like a button\n        e.preventDefault();\n        // Get the field name\n        var quantity = parseInt($('#quantity').val());\n        \n        // If is not undefined\n      \n            // Increment\n            if(quantity>0){\n            $('#quantity').val(quantity - 1);\n            }\n    });\n    \n});</script>"
            ]
          },

          "database" : {
            "host": "localhost",
            "user": "root",
            "password": "",
            "database": "my_shop"
          }

        }

## Commands - Hashflow 

Note Hashflow must have access to settings.json and database configuration while running these commands.

    authenticate();
authenticate(); - check if the user is sign-in and prevent them to resign-in

    authenticator();
authenticator(); - true or false check if the user is sign-in

    sign_in_form();
sign_in_form(); - generate a simple sign in form

    style_css();
style_css(); - add all css file needed for the site

    product_list();
product_list(); - generate a list of products

    product();
product(); - show product details

## Product List - Hashflow
    
#### Include Functions   
    <?php
    include 'functions/functions.php';
    ?>
    
#### Style CSS  
    <?php style_css(); ?>
    
#### Display Products   
    <?php product_list(); ?>

```php
<?php
include 'functions/functions.php';
?>
<!doctype html>
<html lang="en">
    <head>
        <title><?php echo settings('shop-settings','name') ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
       <?php style_css(); ?>
    </head>
    <body>

         <?php product_list(); ?>

        <?php script_js(); ?>
    </body>
</html>
```

## Product - Hashflow

#### Include Functions   
    <?php
    include 'functions/functions.php';
    ?>
    
#### Style CSS  
    <?php style_css(); ?>
    
#### Display Product   
    <?php product($_GET['product'], $_GET['hash']); ?>

```php
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
```
