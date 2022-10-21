# hash-flow

## Quickstart - Hashflow Setup
Note must configure the setting.json first
    {

      "shop-settings" : {
        "name": "Store name",
        "currency": "USD",
        "address": "Store address",
        "city": "none",
        "state": "none",
        "zip": "none",
        "phone": "none",
        "email": "email@email.com",
        "product_link" : "product.php",
        "shopping_cart_link" : "shopping-cart.php",
        "order_status" : "orders-status.php"
      },

      "style-config" : {
        "css" : [
          "assets/css/hash-flow_v2.css",
          "assets/css/sign-in.css"
        ]
      },

      "site-config" : {
        "sign-in-failed" : "sign-in.php",
        "sign-in-success" : "index.php"
      },

      "database" : {
        "host": "localhost",
        "user": "root",
        "password": "",
        "database": "my_store"
      }

    }

## Commands - Hashflow 

Note Hashflow must have access to settings.json and database configuration while running these commands.

    authenticate();
    authenticator();
    sign_in_form();
    style_css();
    product_list();
    product();
