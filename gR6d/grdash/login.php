<?php 
session_start();
error_reporting(E_ALL); 
ini_set('display_errors', 1);

require '../api/header.php';  

$auth_id = 0;
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $login_query = "SELECT id FROM `e_dat_admin` WHERE cos_id = '$cos_id' AND username = '$username' AND password = '$password' AND active = 1";
    $login_result = $mysqli->query($login_query);
    
    if ($login_result && $login_result->num_rows > 0) {
        $result = $login_result->fetch_assoc();
        $auth_id = $result['id'];
        ?>
    <?php
    } else {
        $auth_id = 0;
    }
    ?>
    <?php
    if ($auth_id != 0) {
        $_SESSION['username'] = $username;
        $_SESSION['password'] = $password;
        $_SESSION['login_success'] = true;
        if (isset($_SESSION['username'])) {
            ?>
           <script>
                window.location.href="dashboard.php";
           </script>
           <?php
            exit();
        }
        else{
        }
        
    }
    else{
            ?>
            <script>
                document.addEventListener("DOMContentLoaded", function() {
                var x = document.getElementById("snackbar_fail");
                x.className = "show";
                setTimeout(function() { 
                    x.className = x.className.replace("show", ""); 
                }, 3000);
            });
            </script>
        <?php
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullComm-Login Page</title>
</head>
<body>
    <div class="login_container">
        <div class="login_img">
            <img src="../assets/images/login_img.jpg">
        </div>
        <div class="login_sect">
            <form class="login_form" method="POST">
                <h1>Welcome to <span> FullComm </span></h1>
                <div class="login_input">
                    <div class="form-div">
                        <label for="username" class="form-label">Username</label>
                        <div>
                            <input type="text" name="username" class="input_style" id="username" required>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="password" class="form-label">Password</label>
                        <div>
                            <input type="password" name="password" id="password" class="input_style" required>
                        </div>
                    </div>
                    <div class="form-div">
                        <input type="submit" class="login_btn" value="Login" name="login" onclick="myfunction();">
                    </div>
                </div>
            </form>
        </div>
        <div id="snackbar_fail">Sorry! Login Failed...</div>
    </div>
</body>

</html>
