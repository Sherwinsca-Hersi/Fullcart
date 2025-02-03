<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php 
        require_once '../api/header.php';
    ?>
    <title>Add/Edit Profile</title>
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
            if(isset($_GET['profileid'])){
                echo  "<h2>Update Profile</h2>";
            }
            else{
                echo  "<h2>Add Profile</h2>";
            }
        ?>
        <?php 
			if(isset($_GET['profileid']))
			{
                $data = $mysqli->query("select id,logo_img,business_name,mobile_1,mobile_2,email_id,gst_no,address from `e_data_profile` where cos_id = '$cos_id' and id=".$_GET['profileid']."")->fetch_assoc();
			?>
            <form method="post" action="com_ins_upd.php" enctype="multipart/form-data" id="myForm" onsubmit="return validateForm()">
                <div class="img_profile">
                    <div class="file_upload">
                        <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                        <span>Upload Logo Image</span>
                        <input type="file" id="logo_img" class="img_upload" name="logo_img">
                    </div>
                    <div>
                        <?php
                        if(!($data['logo_img']==NULL || '')){
                        ?>
                            <img id="previewImage" src="../../<?php echo $data['logo_img'];?>" width="100px"/>
                        <?php
                        }
                        ?>
                    </div>
                </div>
            <script>
                document.getElementById('logo_img').addEventListener('change', function(event){
                    const file = event.target.files[0];
                    const reader = new FileReader();
                    reader.onload = function(event){
                        document.getElementById('previewImage').src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            </script>
            <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="business_name" class="form-label">Business Name</label>
                            <div>
                                <input type="text" name="business_name"  class="input_style" value="<?php  if(!($data['business_name']==NULL || '')){ echo $data['business_name'];}?>" placeholder=" Enter Business Name" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="mobile1" class="form-label">Contact No 1</label>
                            <div>
                                <input type="text" name="mobile1"  class="input_style"  value="<?php if(!($data['mobile_1']==NULL || '')){echo $data['mobile_1']; }?>" placeholder="Enter Contact 1" maxlength="15" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="mobile2" class="form-label">Contact No 2</label>
                            <div>
                                <input type="text" name="mobile2"  class="input_style"  value="<?php if(!($data['mobile_2']==NULL || '')){echo $data['mobile_2']; }?>" placeholder="Enter Contact 2" maxlength="15">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="gst_no" class="form-label">GSTIN</label>
                            <div>
                                <input type="text" name="gst_no"  class="input_style"  value="<?php if(!($data['gst_no']==NULL || '')){echo $data['gst_no']; }?>" placeholder="Enter GSTIN" maxlength="15">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="b_email" class="form-label">Email-ID</label>
                            <div>
                                <input type="email" name="b_email"  class="input_style" value="<?php if(!($data['email_id']==NULL || '')){ echo $data['email_id']; }?>"  placeholder="Enter Email" maxlength="60">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="doorno" class="input_style"   value="<?php 
                                    if(!($data['address']==NULL || '')){ 
                                        $address = $data['address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 1) {
                                            $doorno = $address_parts[0];
                                            echo $doorno;
                                        } 
                                    }?>" placeholder="Enter Door No" maxlength="20">
                            </div>
                        </div>
                        
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="street" id="street" class="input_style"  value="<?php 
                                if(!($data['address']==NULL || '')){ 
                                    $address = $data['address'];
                                    $address_parts = explode(',', $address);
                                    if (count($address_parts) >= 2) {
                                        $street = $address_parts[1];
                                        echo $street;
                                    } 
                                }?>" placeholder="Enter Street" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="area" class="input_style" value="<?php 
                                if(!($data['address']==NULL || '')){ 
                                    $address = $data['address'];
                                    $address_parts = explode(',', $address);
                                    if (count($address_parts) >= 3) {
                                        $area = $address_parts[2];
                                        echo $area;
                                    } 
                                }?>" placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="pincode" id="pincode" value="<?php 
                                    if(!($data['address']== NULL || '')){
                                        $address = $data['address'];
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
                                    } ?>" class="input_style"  placeholder="Enter Pincode" maxlength="6">
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="city" class="form-label">City</label>
                            <div>
                                <input type="text" name="city" id="city" class="input_style" placeholder="Enter City" value="<?php 
                                        if(!($data['address']== NULL || '')){
                                        $address = $data['address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 4) {
                                            $city_text = trim($address_parts[3]);
                                            echo $city_text;
                                        }
                                    }?>"  maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="state" class="form-label">State</label>
                            <div>
                                <input type="text" name="state" class="input_style"  id="state" placeholder="Enter State" value="<?php 
                                        if(!($data['address']== NULL || '')){
                                        $address = $data['address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 5) {
                                            $state_text = trim($address_parts[4]);
                                            echo "$state_text";
                                        }
                                    }?>" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="country" class="input_style" id="country" placeholder="Enter Country" value="<?php 
                                        if(!($data['address']== NULL || '')){
                                        $address = $data['address'];
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
                        <!-- <div class="form-div">
                            <label for="city" class="form-label">City</label>
                            <div>
                            
                                <select name="city" id="city" class="input_style">
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option value='<?php echo $city;?>' class="option_style" <?php 
                                        if(!($data['address']==NULL || '')){ 
                                        $address = $data['address'];
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
                            <label for="state" class="form-label">State</label>
                            <div>
                                <select name="state" id="state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu"  class="option_style" <?php echo "selected"?>>TamilNadu</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="country" class="form-label">Country</label>
                            <div>
                                <select name="country" id="country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India"  class="option_style" <?php echo "selected";?>>India</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,6)" name="pincode"  class="input_style" value="<?php
                                    if(!($data['address']==NULL || '')){  
                                        $address = $data['address'];
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
                                    }?>" placeholder="Enter Pincode" maxlength="6">
                            </div>
                        </div> -->
                    </div>
            </div>
            <div class="add_btnDiv">
                <input type="hidden" name="profileid" value="<?php echo isset($_GET['profileid']) ? htmlspecialchars($_GET['profileid']) : ''; ?>">
                <button class="add_btn" name="profile_update">Update Profile</button>
            </div>
        </form>
        <?php
        }
        else{
        ?>
            <form method="post" action="com_ins_upd.php" autocomplete="off" enctype="multipart/form-data" id="myForm" onsubmit="return validateForm()">
            <div class="img_profile">
                <div class="file_upload">
                    <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                    <span>Upload Logo Image</span>
                    <input type="file" id="add_logo_img" class="img_upload" name="add_logo_img">
                </div>
                <div>
                    <img id="previewImage" width="100px"/>
                </div>
            </div>
            <script>
                document.getElementById('add_logo_img').addEventListener('change', function(event){
                    console.log(event.target.files[0].name);
                    const file = event.target.files[0];
                    const reader = new FileReader();
                    reader.onload = function(event){
                        document.getElementById('previewImage').src = event.target.result;
                    };
                    reader.readAsDataURL(file);
                });
            </script>
            <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="business_name" class="form-label">Business Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="business_name"  class="input_style" placeholder=" Enter Business Name" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="mobile1" class="form-label">Contact No 1<span class="star">*</span></label>
                            <div>
                                <input type="text" name="mobile1"  class="input_style"  placeholder="Enter Contact 1" maxlength="15" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="mobile2" class="form-label">Contact No 2</label>
                            <div>
                                <input type="text" name="mobile2"  class="input_style"   placeholder="Enter Contact 2" maxlength="15" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="gst_no" class="form-label">GSTIN</label>
                            <div>
                                <input type="text" name="gst_no"  class="input_style"   placeholder="Enter GSTIN" maxlength="15">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="b_email" class="form-label">Email-ID</label>
                            <div>
                                <input type="email" name="b_email"  class="input_style"   placeholder="Enter Email" maxlength="60">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="doorno" class="input_style"  placeholder="Enter Door No" maxlength="20">
                            </div>
                        </div>
                        
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="street" id="street" class="input_style" placeholder="Enter Street" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="area" class="input_style" placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <label for="city" class="form-label">City</label>
                            <div>
                            
                                <select name="city" id="city" class="input_style">
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option value='<?php echo $city;?>' class="option_style"><?php echo $city;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="state" class="form-label">State</label>
                            <div>
                                <select name="state" id="state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu"  class="option_style" <?php echo "selected"?>>TamilNadu</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="country" class="form-label">Country</label>
                            <div>
                                <select name="country" id="country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India"  class="option_style" <?php echo "selected";?>>India</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-div">
                            <label for="pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="pincode" id="pincode" class="input_style"  placeholder="Enter Pincode" maxlength="6">
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="city" class="form-label">City</label>
                            <div>
                                <input type="text" name="city" id="city" class="input_style" placeholder="Enter City" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="state" class="form-label">State</label>
                            <div>
                                <input type="text" name="state" class="input_style"  id="state" placeholder="Enter State" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="country" class="input_style" id="country" placeholder="Enter Country" maxlength="50">
                            </div>
                        </div>
                    </div>
            </div>
            <div class="add_btnDiv">
                <button class="add_btn" name="profile_add">Add Profile</button>
            </div>
        </form>
        <?php } ?>
       </div>
    </div>
    <div>
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