<?php 
// opcache_reset();
header("Cache-Control: no-cache, must-revalidate"); 
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
error_reporting(E_ALL); 
ini_set('display_errors', 1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FullComm-Sign Up Page</title>
    <?php
        require '../api/header.php';  
    ?>
</head>
<body>
    <div class="signup_container">
        <div class="signup_img">
            <img src="../assets/images/login_img.png" class="sign_img_style">
        </div>
        <div class="signup_sect">
            <form class="signup_form">
                <h1>Welcome to <span>FullComm</span></h1>
                <div class="signup_input">
                    <div>
                        <div class="form-div">
                            <label for="username" class="form-label">First Name</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="password" class="form-label">Username</label>
                            <div>
                                <input type="password" name="password" id="password" class="input_style">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="username" class="form-label">Street</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="password" class="form-label">City</label>
                            <div>
                                <input type="password" name="password" id="password" class="input_style">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="username" class="form-label">Country</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="password" class="form-label">Password</label>
                            <div>
                                <input type="password" name="password" id="password" class="input_style">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="username" class="form-label">Phone No</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="form-div">
                            <label for="username" class="form-label">Last Name</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="password" class="form-label">Door No</label>
                            <div>
                                <input type="password" name="password" id="password" class="input_style">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="username" class="form-label">Area</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="password" class="form-label">State</label>
                            <div>
                                <input type="password" name="password" id="password" class="input_style">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="username" class="form-label">Pincode</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="password" class="form-label">Confirm Password</label>
                            <div>
                                <input type="password" name="password" id="password" class="input_style">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="username" class="form-label">Email</label>
                            <div>
                                <input type="text" name="username" class="input_style" id="username">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="signup_btn_div">
                    <input type="submit" class="signup_btn" value="Sign Up">
                </div>
            </form>
            <h5>Already Have an account?<a href="login.php">Login</a></h5>
        </div>
    </div>
</body>
</html>