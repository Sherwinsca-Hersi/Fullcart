<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>
    <?php 
    require_once '../api/header.php';
    ?>
     <!--iziToast-->
     <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
</head>
<body>
<?php 
    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php';
        ?>
    </div>
    <div class="addCustomer_rightbar container">

    <?php
            if(isset($_GET['customerid'])){
                echo  "<h2>Update Customer</h2>";
            }
            else{
                echo "<h2>Add  Customer</h2>";
            }
        ?>
        <?php 
			if(isset($_GET['customerid']))
			{
                $data = $mysqli->query("SELECT  `id`, `name`, `mobile`, `email_id`, `password`, `whatsapp`,`wallet` FROM `e_user_details`  WHERE 
                                         id=".$_GET['customerid']." AND cos_id = '$cos_id' ")->fetch_assoc();

                $cust_address=$mysqli->query("SELECT  `id`, `user_id`, `area`,`pincode`, `address_line_1`,`landmark`,  
                                        `name`, `mobile`, `city`, `state`, `address_line_2`,  `country` FROM `e_address_details` 
                                         WHERE user_id=".$_GET['customerid']." AND `mobile` = '{$data['mobile']}' 
                                        AND `name` = '{$data['name']}' AND `active` = '1' AND cos_id = '$cos_id' 
                                        GROUP BY `name`, `mobile` ORDER BY `created_ts` LIMIT 1")->fetch_assoc();    
			?>
            <form method="post" action="com_ins_upd.php" autocomplete="off" id="myForm" onsubmit="return validateForm()">
            <!-- <div class="c_pic">
                <div class="file_upload">
                    <i class="fa-4x fa fa-user" aria-hidden="true"></i>
                    <span>Search Image to Upload</span>
                    <input type="file" id="prod_img" class="img_upload">
                </div>
                <span>Choose Image</span>
            </div> -->
            <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="c_name" class="form-label">Customer Name</label>
                            <div>
                                <input type="text" name="c_name"  class="input_style"  value="<?php echo $data['name'] ?? ''; ?>" placeholder=" Enter Customer Name" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_phone" class="form-label">Phone Number</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="c_phone"  class="input_style"   value="<?php echo $data['mobile'] ?? ''; ?>" placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        
                        <div class="form-div">
                            <label for="c_whatsapp" class="form-label">Whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="c_whatsapp"  class="input_style"  value="<?php echo $data['whatsapp'] ?? ''; ?>" placeholder="Enter Whatsapp No" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_email" class="form-label">Email</label>
                            <div>
                                <input type="email" name="c_email" class="input_style"   value="<?php echo $data['email_id'] ?? ''; ?>" placeholder="Enter Email" maxlength="50">
                            </div>
                        </div>
                        
                        <div class="form-div">
                            <label for="c_password" class="form-label">Password</label>
                            <div class="pass_input">
                                <input type="password"  class="input_style"  name="c_password" placeholder="Enter Password"  value="<?php echo $data['password'] ?? ''; ?>" maxlength="20" autocomplete="new-password" id="c_password">
                                <span class="eye_icon" onclick="togglePassword()"><i class="fa fa-solid fa-eye-slash"></i></span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="c_doorno" class="input_style"    value="<?php echo $cust_address['address_line_1'] ?? ''; ?>"  placeholder="Enter Door No" maxlength="20">
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="c_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="c_street" id="c_street" class="input_style" value="<?php echo $cust_address['landmark'] ?? ''; ?>" placeholder="Enter Street" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="c_area" class="input_style" value="<?php echo $cust_address['area'] ?? ''; ?>" placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>

                        <div class="form-div">
                            <label for="c_pincode" class="form-label">Pincode<span class="star">*</span></label>
                            <div>
                                <input type="number"  name="c_pincode" id="pincode" oninput="fetchLocationData()" value="<?php echo $cust_address['pincode'] ?? ''; ?>"  class="input_style" placeholder="Enter Pincode" maxlength="6" required>
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="c_city" id="city" class="input_style" placeholder="Enter City" value="<?php echo $cust_address['city'] ?? ''; ?>" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="c_state" class="input_style"  id="state" value="<?php echo $cust_address['state'] ?? ''; ?>" placeholder="Enter State" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="c_country" class="input_style" id="country" value="<?php echo $cust_address['country'] ?? ''; ?>" placeholder="Enter Country" maxlength="50">
                            </div>
                        </div>
                        
                    </div>
            </div>
            <div class="add_btnDiv">
                <input type="hidden" name="customerid" value="<?php echo isset($_GET['customerid']) ? htmlspecialchars($_GET['customerid']) : ''; ?>">
                <button class="add_btn" name="cust_update">Update Customer</button>
            </div>
        </form>
        <?php
        }
        else{
        ?>
            <form method="post" action="com_ins_upd.php" autocomplete="off" id="myForm" onsubmit="return validateForm()">
            <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="c_name" class="form-label">Customer Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="c_name"  class="input_style" placeholder=" Enter Customer Name" maxlength="50" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_phone" class="form-label">Phone Number<span class="star">*</span></label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" oninput="this.value=this.value.slice(0,10)" name="c_phone"  class="input_style" placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_whatsapp" class="form-label">Whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" oninput="this.value=this.value.slice(0,10)" name="c_whatsapp"  class="input_style" placeholder="Enter Whatsapp No" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_email" class="form-label">Email</label>
                            <div>
                                <input type="email" name="c_email" class="input_style" placeholder="Enter Email" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_password" class="form-label">Customer Password</label>
                            <div class="pass_input">
                                <input type="password"  class="input_style"  name="c_password" placeholder="Enter Password" id="c_password" maxlength="20" autocomplete="new-password">
                                <span class="eye_icon" onclick="togglePassword()"><i class="fa fa-solid fa-eye-slash"></i></span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="c_doorno" class="input_style" placeholder="Enter Door No" maxlength="50">
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="c_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="c_street"  class="input_style" placeholder="Enter Street" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="c_area" class="input_style" placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_pincode" class="form-label">Pincode<span class="star">*</span></label>
                            <div>
                                <input type="number"  name="c_pincode" id="pincode" oninput="fetchLocationData()" class="input_style" placeholder="Enter Pincode" maxlength="6" required>
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="c_city" id="city" class="input_style" placeholder="Enter City" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="c_state" class="input_style"  id="state" placeholder="Enter State" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="c_country" class="input_style" id="country" placeholder="Enter Country" maxlength="50">
                            </div>
                        </div>
                    </div>
            </div>
            <div class="add_btnDiv">
                <button class="add_btn" name="cust_add">Add Customer</button>
            </div>
        </form>
        <?php } ?>
       </div>
    </div>
    <div>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>
    <div class="popup" id="popup">
        <h4>All unsaved changes will be lost.</h4>
        <div class="popup_btns">
            <button class="price_btn">Price</button>
            <button class="stock_btn">Stock</button>
            <button class="popup_cancel" id="cancel_btn">Cancel</button>
        </div>
    </div>
    <?php
        if(isset($_SESSION['error_message'])): 
        ?>
        <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
        <script>
    iziToast.error({
        title: 'Error',
        message: '<?php echo addslashes($_SESSION['error_message']); ?>',
        position: 'bottomCenter'
    });
</script>
        <?php
        unset($_SESSION['error_message']);
        endif;
    ?>
<script>
function togglePassword() {
  var passwordField = document.getElementById('c_password');
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
</script>
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/pinaddressGen.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>