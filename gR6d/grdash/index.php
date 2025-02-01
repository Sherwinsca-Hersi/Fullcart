<?php 
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/', 
    'domain' => '', 
    'secure' => true, 
    'httponly' => true, 
    'samesite' => 'Lax'
]);

ini_set('session.gc_maxlifetime', 86400 * 30);

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
        $role = 5;
    } else {
        $login_query = "SELECT id, password, role FROM `e_salesman_details` WHERE cos_id = '$cos_id' AND s_name = '$username' AND password='$password' AND  active = 1";
        $login_result = $mysqli->query($login_query);
        if ($login_result && $login_result->num_rows > 0) {
            $result = $login_result->fetch_assoc();
            $auth_id = $result['id'];
            $role = $result['role'];
        }
    }

    if ($auth_id != 0) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $role;
        $_SESSION['login_success'] = true;
        $_SESSION['auth_id'] = $auth_id;
    
        echo '<script>
                localStorage.setItem("sessionActive", "true");
                localStorage.setItem("username", "' . $username . '");
                localStorage.setItem("role", "' . $role . '");
                localStorage.setItem("authId", "' . $auth_id . '");
                localStorage.setItem("activeMainMenu", "Dashboard");
                window.location.href="dashboard.php";
              </script>';
        exit();
    } else {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    var x = document.getElementById("snackbar_fail");
                    x.className = "show";
                    setTimeout(function() { 
                        x.className = x.className.replace("show", ""); 
                    }, 3000);
                });
              </script>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php require '../api/header.php'; ?>
    <title><?php echo $project_name;?> - Login Page</title>
</head>
<body>
    <div class="login_container">
        <div class="login_img">
            <img src="../assets/images/login_img.png" alt="Login page-img">
        </div>
        <div class="login_sect">
            <form class="login_form" method="POST" autocomplete="off">
                <h1>Welcome to <span> <?php echo $project_name;?> </span></h1>
                <div class="login_input">
                    <div class="form-div">
                        <label for="username" class="form-label">Username</label>
                        <div>
                            <input type="text" name="username" class="input_style" id="username" required>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="password" class="form-label">Password</label>
                        <div class="pass_input" style="width:100%">
                            <input type="password" name="password" id="password" class="input_style" required>
                            <span class="eye_icon" onclick="togglePassword()"><i class="fa fa-solid fa-eye-slash"></i></span>
                        </div>
                        <a href="forgotPassword.php" class="forgot_pass">Forgot Password?</a>
                    </div>
                    <div class="form-div">
                        <input type="submit" class="login_btn" value="Login" name="login">
                    </div>
                </div>
            </form>
        </div>
        <div id="snackbar_fail">Sorry! Login Failed...</div>
    </div>
    
    <script>
    function togglePassword() {
        var passwordField = document.getElementById('password');
        var eyeIcon = document.querySelector('.eye_icon i');
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            passwordField.type = 'password';
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    }
    

    setInterval(function() {
        fetch('loginKeepAlive.php'); 
    }, 300000); 
    </script>

    <script>
        if(localStorage.getItem('sessionActive') === 'false') {
            console.log("Session is inactive");
        }
    </script>
</body>
</html>
