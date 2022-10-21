<?php

function sign_in($conn){
    if (isset($_POST['SignIn'])) {
        $Username = $_POST['Username'];
        $Password = $_POST['Password'];
        $sql = "SELECT * FROM `users` WHERE `Username`='$Username' AND `Password`='$Password'";
        $result = $conn->query($sql);
        if (mysqli_num_rows($result)> 0) {
            if ($row = $result-> fetch_assoc()) {
                    $_SESSION['ID'] = hash('sha256', $row['ID']);
                $location_success = settings('site-config','sign-in-success');
                header("Location: $location_success?sign-in-success=true");
                exit();
            }
        }else {
            $location_failed = settings('site-config','sign-in-failed');
            header("Location: $location_failed?sign-in-failed=true");
            exit();
        }
    }
}

function sign_up($conn){
    if (isset($_POST['SignUp'])) {
        $Username = $_POST['Username'];
        $Password = $_POST['Password'];
        $FB = $_POST['FB'];
            $sql = "INSERT INTO `users`(`Username`,`Password`) VALUES ('$Username','$Password')";
            $conn->query($sql);
            echo "<script type='text/javascript'>location.href = 'index.php';</script>";
            header("Location:index.php?SignupSuccessful");
            exit();
    }
}

function authenticate(){
    session_start();
    if (isset($_SESSION['ID'])){
        header("Location: index.php?user-already-signed-in=true&hash=".hash('sha512', $_SESSION['ID']));
        exit();
    }
}

function authenticator(){
    if (!isset($_SESSION['ID'])){
        return false;
    } else{
        return true;
    }
}

function sign_out(){
        session_start();
        session_destroy();
        header("Location:index.php?user-signed-out=true&hash=".hash('sha512', 'sign-out'));
        exit();
}

function settings($data, $value){
    $str = file_get_contents('functions/settings.json');
    $json = json_decode($str, true);
    return $json[$data][$value];
}

function style_css(){
    foreach (settings('style-config','css') as $css) {
        echo '<link href="'.$css.'" rel="stylesheet">';
    }
}



function product_list(){
    $conn = mysqli_connect(settings('database', 'host'),settings('database', 'user'),settings('database', 'password'),settings('database', 'database'));
    $sql = "SELECT * FROM `products`";
    $result = $conn->query($sql);

    echo '  <div class="p-5">
  <div class="reflow-product-list ref-cards">
    <div class="ref-products">';

//        display products
    if (mysqli_num_rows($result)> 0) {
        while ($row = $result-> fetch_assoc()) {
            printf('<a class="ref-product" href="%s?product=%s&hash='.hash('sha512',$row['product_id']).'">',
                settings('shop-settings','product_link'),base64_encode(base64_encode($row['product_id'])));
            printf('
            <div class="ref-media">
                <img class="ref-image"
                <img class="ref-image"
                  src="https://cdn.reflowhq.com/media/627668095/888175706/62a5cceb5e695082689cef77f4fa5747_md.jpg"
                  loading="lazy"/>
              </div>
              
              <div class="ref-product-data">
                <div class="ref-product-info">
                  <h5 class="ref-name">%s</h5>
                  <p class="ref-excerpt">%s</p>
                </div>
                <strong class="ref-price">
                ₱%s</strong>
              </div>
              <div class="ref-addons"></div
            ></a>
            ', $row['product_name'], $row['product_descriptions'], $row['product_price']);
        }
    }

    echo '
    </div>
    <div class="ref-product-preview">
      <div class="ref-product-preview-header">
        <div class="ref-title"></div>
        <div class="ref-close-button">×</div>
      </div>
      <div class="ref-product-preview-content"></div>
    </div>
  </div>
</div>';
}

function product($product_id, $hash){
    if (    $hash == hash('sha512', base64_decode(base64_decode($product_id)))){
        $conn = mysqli_connect(settings('database', 'host'),settings('database', 'user'),settings('database', 'password'),settings('database', 'database'));
        $sql = "SELECT * FROM `products` WHERE `product_id`='".base64_decode(base64_decode($product_id))."'";
        $result = $conn->query($sql);

        if (mysqli_num_rows($result)> 0) {
            while ($row = $result-> fetch_assoc()) {
                printf('
            <div class="p-5">
              <div class="reflow-product">
                <div class="ref-media">
                  <div class="ref-preview">
                    <img
                      class="ref-image active"
                      src="https://cdn.reflowhq.com/media/627668095/888175706/62a5cceb5e695082689cef77f4fa5747_md.jpg"
                      data-reflow-preview-type="image"
                    />
                  </div>
                
                </div>
                <div class="ref-product-data">
                  <h2 class="ref-name">%s</h2>
                  <div class="ref-categories">
                    <span class="ref-category">%s</span>
                  </div>
                  <strong class="ref-price">₱%s</strong>
                  <span class="ref-qty-available">%s left in stock</span>
                  <span>
                    <div class="reflow-add-to-cart ref-product-controls">
                      <span>
                        <div class="ref-quantity-widget">
                          <div class="ref-decrease"><span></span></div>
                          <input type="text" value="1" />
                          <div class="ref-increase"><span></span></div>
                        </div> </span
                      ><a href="#" class="ref-button">Add to Cart</a>
                    </div></span>
                  <div class="ref-description"><p>%s</p></div>
                </div>
              </div>
            </div>
            ', $row['product_name'], $row['product_category'], $row['product_price'], $row['product_quantity'], $row['product_descriptions']);
            }
        }

    }else{
        echo 'Invalid Product <br>';
    }
}

function sign_in_form(){
    $conn = mysqli_connect(settings('database', 'host'),settings('database', 'user'),settings('database', 'password'),settings('database', 'database'));
    printf('
        <div class="global-container">
        <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center">Sign in to %s</h3>
            <div class="card-text">
                <form method="post" action="'.sign_in($conn).'">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input type="text" class="form-control form-control-sm" name="Username">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <a href="#" style="float:right;font-size:12px;">Forgot password?</a>
                        <input type="password" class="form-control form-control-sm" name="Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="SignIn">Sign in</button>
                    
                    <div class="sign-up">
                        Dont have an account? <a href="#">Create One</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    </div>
    ', settings('shop-settings','name'));
}