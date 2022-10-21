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

function header_html(){

    foreach (settings('site-config','head') as $head) {
        echo $head;
    }
    foreach (settings('site-config','css') as $css) {
        echo '<link href="'.$css.'" rel="stylesheet">';
    }

}

function script_js(){

    foreach (settings('site-config','js') as $js) {
        echo $js;
    }

}



function product_list(){
    $conn = mysqli_connect(settings('database', 'host'),settings('database', 'user'),settings('database', 'password'),settings('database', 'database'));
    $sql = "SELECT * FROM `products`";
    $result = $conn->query($sql);

    echo '  <div class="p-5">
  <div class="hashflow-product-list hash-cards">
    <div class="hash-products">';

//        display products
    if (mysqli_num_rows($result)> 0) {
        while ($row = $result-> fetch_assoc()) {
            printf('<a class="hash-product" href="%s?product=%s&hash='.hash('sha512',$row['product_id']).'">',
                settings('site-config','product_link'),base64_encode(base64_encode($row['product_id'])));
            printf('
            <div class="hash-media">
                <img class="hash-image"
                  src="https://cdn.reflowhq.com/media/627668095/888175706/62a5cceb5e695082689cef77f4fa5747_md.jpg"
                  loading="lazy" alt=""/>
              </div>
              
              <div class="hash-product-data">
                <div class="hash-product-info">
                  <h5 class="hash-name">%s</h5>
                  <p class="hash-excerpt">%s</p>
                </div>
                <strong class="hash-price">
                %s%s</strong>
              </div>
              <div class="hash-addons"></div
            ></a>
            ', $row['product_name'], $row['product_descriptions'], settings('shop-settings', 'currency-sign'),$row['product_price']);
        }
    }

    echo '
    </div>
    <div class="hash-product-preview">
      <div class="hash-product-preview-header">
        <div class="hash-title"></div>
        <div class="hash-close-button">×</div>
      </div>
      <div class="hash-product-preview-content"></div>
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
              <div class="hashflow-product">
                <div class="hash-media">
                  <div class="hash-preview">
                    <img
                      class="hash-image active"
                      src="https://cdn.reflowhq.com/media/627668095/888175706/62a5cceb5e695082689cef77f4fa5747_md.jpg"
                      data-hashflow-preview-type="image"
                     alt=""/>
                  </div>
                
                </div>
                <div class="hash-product-data">
                  <h2 class="hash-name">%s</h2>
                  <div class="hash-categories">
                    <span class="hash-category">%s</span>
                  </div>
                  <strong class="hash-price">%s%s</strong>
                  <span class="hash-qty-available">%s left in stock</span>
                  <span>
                    <div class="hashflow-add-to-cart hash-product-controls">
                      <span>
                        <div class="hash-quantity-widget">
                          <div class="hash-decrease"><a type="button" data-type="minus" data-field=""><span></span></a></div>
                          <input type="text" id="quantity" name="quantity" value="1" min="1" max="99999999"/>
                          <div class="hash-increase"><a type="button" data-type="plus" data-field=""><span></span></a></div>
                        </div> </span
                      ><a href="#" class="hash-button">Add to Cart</a>
                    </div></span>
                  <div class="hash-description"><p>%s</p></div>
                </div>
              </div>
            </div>
            ', $row['product_name'], $row['product_category'], settings('shop-settings', 'currency-sign'), $row['product_price'], $row['product_quantity'], $row['product_descriptions']);
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
    ', settings('site-config','name'));
}