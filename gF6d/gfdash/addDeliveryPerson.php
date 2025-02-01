<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Delivery Person</title>
    <?php 
    require_once '../api/header.php';
    ?>
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
    <div class="addemployee_rightbar container">

    <?php
            if(isset($_GET['deliveryid'])){
                echo  "<h2>Update Delivery Person</h2>";
            }
            else{
                echo "<h2>Add Delivery Person</h2>";
            }
        ?>
        <?php 
			if(isset($_GET['deliveryid']))
			{
				$data = $mysqli->query("SELECT id,s_name,s_mobile,whatsapp,email,password,joining_date,salary,bonus,s_address FROM e_salesman_details WHERE id=".$_GET['deliveryid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
                <form method="post" action="com_ins_upd.php" autocomplete="off" id="myForm" onsubmit="return validateForm()">
            <!-- <div class="d_pic">
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
                            <label for="ename" class="form-label">Name</label>
                            <div>
                                <input type="text" name="d_name"  class="input_style" value="<?php  if(!($data['s_name']==NULL || '')){ echo $data['s_name'];}?>"  placeholder=" Enter  Name" maxlength="50" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_phone" class="form-label">Phone Number</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="d_phone" id="d_phone" class="input_style" value="<?php if(!($data['s_mobile'] == NULL || '')){ echo $data['s_mobile'];}?>" placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_whatsapp" class="form-label">Whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="d_whatsapp" id="d_whatsapp" class="input_style"  placeholder="Enter Whatsapp" value="<?php if(!($data['whatsapp'] == NULL || '')){ echo $data['whatsapp'];}?>" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_email" class="form-label">Email</label>
                            <div>
                                <input type="email" name="d_email" class="input_style" value="<?php if(!($data['email'] == NULL || '')){  echo $data['email'];}?>" placeholder="Enter Email" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_pass" class="form-label"> Password</label>
                            <div class="pass_input">
                                <input type="password"  class="input_style"  name="d_password" style="width:100%;" value="<?php  echo $data['password'];?>" id="d_password" placeholder="Enter Password"  maxlength="20" autocomplete="new-password" required>
                                <span class="eye_icon" onclick="togglePassword()"><i class="fa fa-solid fa-eye-slash"></i></span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="join_date" class="form-label">Joining Date</label>
                            <div>
                                <input type="date" name="d_join_date" id="join_date" class="input_style"  max="<?php echo date('Y-m-d'); ?>" value="<?php if(!($data['joining_date'] == NULL || '')){ echo $data['joining_date'];}?>" placeholder="Enter Joining Date" required>
                            </div>
                        </div>
                        
                        <div class="form-div">
                            <label for="d_salary" class="form-label">Salary</label>
                            <div>
                                <input type="number" name="d_salary" id="d_salary" class="input_style" value="<?php if(!($data['salary'] == NULL || '')){ echo $data['salary']; }?>" placeholder="Enter Salary" maxlength="10" required>
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_bonus" class="form-label">Bonus</label>
                            <div>
                                <input type="number" name="d_bonus" id="d_bonus" class="input_style" value="<?php if(!($data['bonus'] == NULL || '')){ echo $data['bonus'];} ?>" placeholder="Enter Bonus" maxlength="10">
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="d_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="d_doorno" class="input_style" 
                                value="<?php 
                                if(!($data['s_address'] == NULL || '')){
                                    if(!($data['s_address'] == NULL || '')){
                                        $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 1) {
                                            $doorno = $address_parts[0];
                                            echo $doorno;
                                        } 
                                    }
                                }?>" placeholder="Enter Door No" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="d_street" id="d_street" class="input_style" 
                                value="<?php 
                                if(!($data['s_address'] == NULL || '')){
                                    $address = $data['s_address'];
                                    $address_parts = explode(',', $address);
                                    if (count($address_parts) >= 2) {
                                        $street = $address_parts[1];
                                        echo $street;
                                    } 
                                }?>" placeholder="Enter Street">
                            </div>
                        </div>
                    <div class="form-div">
                            <label for="d_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="d_area" class="input_style" 
                                value="<?php 
                                if(!($data['s_address'] == NULL || '')){
                                    $address = $data['s_address'];
                                    $address_parts = explode(',', $address);
                                    if (count($address_parts) >= 3) {
                                        $area = $address_parts[2];
                                        echo $area;
                                    } 
                                }?>"placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <label for="d_city" class="form-label">City</label>
                            <div>
                                <select name="d_city" id="d_city" class="input_style" >
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option value='<?php echo $city;?>' class="option_style" <?php 
                                        if(!($data['s_address'] == NULL || '')){
                                        $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 4) {
                                            $city_text = trim($address_parts[3]);
                                            if($city==$city_text){echo "selected";}
                                        }
                                    }?>><?php echo $city;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_state" class="form-label">State</label>
                            <div>
                                <select name="d_state" id="d_state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu" <?php echo 'selected';?>>TamilNadu</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_country" class="form-label">Country</label>
                            <div>
                                <select name="d_country" id="d_country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India" <?php echo 'selected';?>>India</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number" 
                                  value="<?php 
                                  if(!($data['s_address'] == NULL || '')){
                                        $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        // echo $address_parts;
                                        if (count($address_parts) >= 5) {
                                            $pincode_set = trim($address_parts[5]);
                                            $pincode_only = explode('-', $pincode_set);
                                            if (count($pincode_only) >= 2) {
                                                $pincode=trim($pincode_only[1]);
                                                echo $pincode;
                                            }
                                        } 
                                    }?>" name="d_pincode"  class="input_style"   placeholder="Enter Pincode" maxlength="6">
                            </div>
                        </div> -->
                        <div class="form-div">
                            <label for="d_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="d_pincode" id="pincode" class="input_style"  placeholder="Enter Pincode"value="<?php 
                                    if(!($data['s_address']== NULL || '')){
                                        $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        // echo $address_parts;
                                        if (count($address_parts) >= 5) {
                                            $pincode_set = trim($address_parts[5]);
                                            $pincode_only = explode('-', $pincode_set);
                                            if (count($pincode_only) >= 2) {
                                                $pincode=trim($pincode_only[1]);
                                                echo $pincode;
                                            }
                                        }
                                    } ?>" maxlength="6">

                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="d_city" id="city" class="input_style" placeholder="Enter City" value="<?php 
                                        if(!($data['s_address']== NULL || '')){
                                        $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 4) {
                                            $city_text = trim($address_parts[3]);
                                            echo $city_text;
                                        }
                                    }?>" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="d_state" class="input_style"  id="state" placeholder="Enter State" value="<?php 
                                        if(!($data['s_address']== NULL || '')){
                                        $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 5) {
                                            $state_text = trim($address_parts[4]);
                                            echo "$state_text";
                                        }
                                    }?>" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="d_country" class="input_style" id="country" placeholder="Enter Country" value="<?php 
                                        if(!($data['s_address']== NULL || '')){
                                        $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 6) {
                                            $country_text = trim($address_parts[5]);
                                            $country_only = explode('-', $country_text);
                                            if (count($country_only) >= 2) {
                                                $country=trim($country_only[0]);
                                                echo $country;
                                            }
                                        }
                                    }?>" maxlength="50">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="deliveryid" value="<?php echo isset($_GET['deliveryid']) ? htmlspecialchars($_GET['deliveryid']) : ''; ?>">
                    <button class="add_btn" name="delivery_update">Update Delivery person</button>
                </div>
        </form>
            <?php 
            }
            else{
            ?>
        <form method="post" action="com_ins_upd.php" autocomplete="off" id="myForm" onsubmit="return validateForm()">
            <!-- <div class="d_pic">
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
                            <label for="dname" class="form-label">Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="d_name"  class="input_style" placeholder=" Enter Name" maxlength="50" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_phone" class="form-label">Phone Number<span class="star">*</span></label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="d_phone" id="d_phone" class="input_style" placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_whatsapp" class="form-label">Whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="d_whatsapp" id="d_whatsapp" class="input_style"  placeholder="Enter Whatsapp" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_email" class="form-label">Email</label>
                            <div>
                                <input type="email" name="d_email" class="input_style" placeholder="Enter Email" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_pass" class="form-label"> Password<span class="star">*</span></label>
                            <div class="pass_input">
                                <input type="password"  class="input_style"  name="d_password" style="width:100%;" id="d_password" placeholder="Enter Password"  maxlength="20" autocomplete="new-password" required>
                                <span class="eye_icon" onclick="togglePassword()"><i class="fa fa-solid fa-eye-slash"></i></span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="join_date" class="form-label">Joining Date<span class="star">*</span></label>
                            <div>
                                <input type="date" name="d_join_date" id="join_date" class="input_style"  max="<?php echo date('Y-m-d'); ?>" placeholder="Enter Joining Date" required>
                            </div>
                        </div>
                        
                        <div class="form-div">
                            <label for="d_salary" class="form-label">Salary<span class="star">*</span></label>
                            <div>
                                <input type="number" name="d_salary" id="d_salary" class="input_style" placeholder="Enter Salary" maxlength="10" required>
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_bonus" class="form-label">Bonus</label>
                            <div>
                                <input type="number" name="d_bonus" id="d_bonus" class="input_style" value="" placeholder="Enter Bonus" maxlength="10">
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="d_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="d_doorno" class="input_style" placeholder="Enter Door No" maxlength="20">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="d_street" id="d_street" class="input_style" placeholder="Enter Street" maxlength="50">
                            </div>
                        </div>
                        
                        <div class="form-div">
                            <label for="d_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="d_area" class="input_style" placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <label for="d_city" class="form-label">City</label>
                            <div>
                                <select name="d_city" id="d_city" class="input_style">
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option value='<?php echo $city;?>' class="option_style"><?php echo $city;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_state" class="form-label">State</label>
                            <div>
                                <select name="d_state" id="d_state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu"  class="option_style">TamilNadu</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_country" class="form-label">Country</label>
                            <div>
                                <select name="d_country" id="d_country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India"  class="option_style">India</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,6)" name="d_pincode"  class="input_style" placeholder="Enter Pincode"  maxlength="6">
                            </div>
                        </div> -->
                        <div class="form-div">
                            <label for="d_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="d_pincode" id="pincode" class="input_style"  placeholder="Enter Pincode" maxlength="6">
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="d_city" id="city" class="input_style" placeholder="Enter City" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="d_state" class="input_style"  id="state" placeholder="Enter State" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="d_country" class="input_style" id="country" placeholder="Enter Country" maxlength="50">
                            </div>
                        </div>
                    </div>
       </div>
       <div class="add_btnDiv">
                <button class="add_btn" name="delivery_add">Add Delivery Person</button>
        </div>
        </form>
        <?php } ?>
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
    <div>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>
<script>
function togglePassword() {
  var passwordField = document.getElementById('d_password');
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