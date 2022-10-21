<?php

function settings($data, $value){
    $str = file_get_contents('../functions/settings.json');
    $json = json_decode($str, true);
    return $json[$data][$value];
}

function sign_in_admin($conn){
    if (isset($_POST['SignIn'])) {
        $Username = $_POST['Username'];
        $Password = $_POST['Password'];
        $sql = "SELECT * FROM `users` WHERE `Username`='$Username' AND `Password`='$Password' AND `admin` = '1'";
        $result = $conn->query($sql);
        if (mysqli_num_rows($result)> 0) {
            if ($row = $result-> fetch_assoc()) {
                $_SESSION['ID_ADMIN'] = hash('sha256', $row['ID']);
                header("Location: index.php?sign-in-success=true");
                exit();
            }
        }else {
            echo '<script> alert("Username or Password is incorrect");</script>';
        }
    }
}


function authenticate_admin(){
    session_start();
    if (isset($_SESSION['ID_ADMIN'])){
        header("Location: dashboard.php?user-already-signed-in=true&hash=".hash('sha512', $_SESSION['ID_ADMIN']));
    }
}

function authenticate_admin_dashboard(){
    session_start();
    if (!isset($_SESSION['ID_ADMIN'])){
        header("Location: index.php?user-not-signed-in=true&hash=".hash('sha512', 'sign-out'));
        exit();
    }

}

function sign_out_admin(){
    session_start();
    session_destroy();
    header("Location:index.php?user-signed-out=true&hash=".hash('sha512', 'sign-out'));
    exit();
}


function sign_out_button(){
    echo '<form method="post">
        <button type="submit" name="sign-out" class="btn btn-primary" /> Sign Out </button>
        </form>';
    if (isset($_POST['sign-out'])) {
        sign_out_admin();
    }
}








function sign_in_form_admin(){
    $conn = mysqli_connect(settings('database', 'host'),settings('database', 'user'),settings('database', 'password'),settings('database', 'database'));
    printf('
        <div class="global-container">
        <div class="card login-form">
        <div class="card-body">
            <h3 class="card-title text-center">Sign in to %s</h3>
            <div class="card-text">
                <form method="post" action="'.sign_in_admin($conn).'">
                    <div class="form-group">
                        <label for="exampleInputEmail1">Username</label>
                        <input type="text" class="form-control form-control-sm" name="Username">
                    </div>
                    <div class="form-group">
                        <label for="exampleInputPassword1">Password</label>
                        <input type="password" class="form-control form-control-sm" name="Password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block" name="SignIn">Sign in</button>
                </form>
            </div>
        </div>
    </div>
    </div>
    ', settings('shop-settings','name'));
}