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
    <title>Add <?php echo $vendor;?></title>
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
            if(isset($_GET['vendorid'])){
                echo  "<h2>Update ".$vendor;"</h2>";
            }
            else{
                echo  "<h2>Add ".$vendor;"</h2>";
            }
        ?>
        <?php 
			if(isset($_GET['vendorid']))
			{
                $data = $mysqli->query("select * from e_vendor_details where cos_id = '$cos_id' and v_id=".$_GET['vendorid']."")->fetch_assoc();
			?>
            <form method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()">
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
                            <label for="v_name" class="form-label"><?php echo $vendor; ?> Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="v_name"  class="input_style" value="<?php if(!($data['v_name']==NULL || '')){echo $data['v_name'];}?>" placeholder=" Enter <?php echo $vendor; ?> Name" maxlength="60" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="business_name" class="form-label">Business Name</label>
                            <div>
                                <input type="text" name="business_name"  class="input_style" value="<?php if(!($data['business_name']==NULL || '')){echo $data['business_name'];}?>" placeholder=" Enter Business Name" maxlength="60">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="contact_person" class="form-label">Contact Person</label>
                            <div>
                                <input type="text" name="contact_person"  class="input_style" value="<?php if(!($data['contact_person']==NULL || '')){echo $data['contact_person'];}?>" placeholder=" Enter Contact Person" maxlength="60">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_mobile" class="form-label">Phone Number</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="v_mobile"  class="input_style"  value="<?php if(!($data['v_mobile']==NULL || '')){ echo $data['v_mobile']; }?>" placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_whatsapp" class="form-label">whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="v_whatsapp"  class="input_style"  value="<?php if(!($data['v_whatsapp']==NULL || '')){ echo $data['v_whatsapp']; }?>" placeholder="Enter Whatsapp" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="gst_no" class="form-label">GSTIN</label>
                            <div>
                                <input type="text" name="gst_no"  class="input_style"  value="<?php if(!($data['gst_no']==NULL || '')){echo $data['gst_no'];} ?>" placeholder="Enter GSTIN" maxlength="15">
                            </div>
                        </div>
                        
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="v_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="v_doorno" class="input_style"   value="<?php 
                                    if(!($data['v_address']==NULL || '')){
                                        $address = $data['v_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 1) {
                                            $doorno = $address_parts[0];
                                            echo $doorno;
                                        } 
                                    }?>" placeholder="Enter Door No" maxlength="20">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="v_street" id="v_street" class="input_style"  value="<?php 
                                 if(!($data['v_address']==NULL || '')){
                                    $address = $data['v_address'];
                                    $address_parts = explode(',', $address);
                                    if (count($address_parts) >= 2) {
                                        $street = $address_parts[1];
                                        echo $street;
                                    } 
                                }?>" placeholder="Enter Street" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="v_area" class="input_style" value="<?php 
                                 if(!($data['v_address']==NULL || '')){
                                    $address = $data['v_address'];
                                    $address_parts = explode(',', $address);
                                    if (count($address_parts) >= 3) {
                                        $area = $address_parts[2];
                                        echo $area;
                                    } 
                                }?>" placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="v_pincode" id="pincode" class="input_style"  placeholder="Enter Pincode" maxlength="6">
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="v_city" id="city" class="input_style" placeholder="Enter City" value="<?php 
                                         if(!($data['v_address']==NULL || '')){
                                        $address = $data['v_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 4) {
                                            $city_text = trim($address_parts[3]);
                                            echo $city_text;
                                        }
                                    }?>" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="v_state" class="input_style"  id="state" placeholder="Enter State" value="<?php 
                                         if(!($data['v_address']==NULL || '')){
                                        $address = $data['v_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 5) {
                                            $state_text = trim($address_parts[4]);
                                            echo $state_text;
                                        }
                                    }?>" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="v_country" class="input_style" id="country" placeholder="Enter Country" value="<?php 
                                        if(!($data['v_address']== NULL || '')){
                                        $address = $data['v_address'];
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
                            <label for="v_city" class="form-label">City</label>
                            <div>
                            
                                <select name="v_city" id="v_city" class="input_style">
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option value='<?php echo $city;?>' class="option_style" <?php 
                                         if(!($data['v_address']==NULL || '')){
                                        $address = $data['v_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 4) {
                                            $city_text = trim($address_parts[3]);
                                            if($city==$city_text){echo "selected";}
                                        }
                                    }?>><?php echo $city;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="form-div">
                            <label for="v_state" class="form-label">State</label>
                            <div>
                                <select name="v_state" id="v_state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu"  class="option_style" <?php 
                                         if(!($data['v_address']==NULL || '')){
                                        $address = $data['v_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 5) {
                                            $state_text = trim($address_parts[4]);
                                            if($state==$state_text){echo "selected";}
                                        }
                                    }?>>TamilNadu</option>
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="form-div">
                            <label for="v_country" class="form-label">Country</label>
                            <div>
                                <select name="v_country" id="v_country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India"  class="option_style" <?php echo "selected";?>>India</option>
                                </select>
                            </div>
                        </div> -->
                        <!-- <div class="form-div">
                            <label for="v_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,6)" name="v_pincode"  class="input_style" value="<?php 
                                    if(!($data['v_address']==NULL || '')){
                                        $address = $data['v_address'];
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
                <input type="hidden" name="vendorid" value="<?php echo isset($_GET['vendorid']) ? htmlspecialchars($_GET['vendorid']) : ''; ?>">
                <button class="add_btn" name="vendor_update">Update <?php echo $vendor; ?></button>
            </div>
        </form>
        <?php
        }
        else{
        ?>
             <form method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()">
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
                            <label for="v_name" class="form-label"><?php echo $vendor; ?> Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="v_name"  class="input_style"  placeholder=" Enter <?php echo $vendor; ?> Name" maxlength="60" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="business_name" class="form-label">Business Name</label>
                            <div>
                                <input type="text" name="business_name"  class="input_style" placeholder=" Enter Business Name" maxlength="60">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="contact_person" class="form-label">Contact Person<span class="star">*</span></label>
                            <div>
                                <input type="text" name="contact_person"  class="input_style"  placeholder=" Enter Contact Person" maxlength="60">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_mobile" class="form-label">Phone Number<span class="star">*</span></label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="v_mobile"  class="input_style"  placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_whatsapp" class="form-label">whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="v_whatsapp"  class="input_style" placeholder="Enter Whatsapp" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="gst_no" class="form-label">GSTIN</label>
                            <div>
                                <input type="text" name="gst_no"  class="input_style"  placeholder="Enter GSTIN" maxlength="15">
                            </div>
                        </div>
                        
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="v_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="v_doorno" class="input_style"  placeholder="Enter Door No" maxlength="20">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="v_street" id="v_street" class="input_style"  placeholder="Enter Street" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="v_area" class="input_style" placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <label for="v_city" class="form-label">City</label>
                            <div>
                            
                                <select name="v_city" id="v_city" class="input_style">
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option class="option_style"><?php echo $city;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_state" class="form-label">State</label>
                            <div>
                                <select name="v_state" id="v_state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu"  class="option_style">TamilNadu</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_country" class="form-label">Country</label>
                            <div>
                                <select name="v_country" id="v_country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India"  class="option_style">India</option>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-div">
                            <label for="v_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="v_pincode" id="pincode" class="input_style"  placeholder="Enter Pincode" maxlength="6">
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="v_city" id="city" class="input_style" placeholder="Enter City" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="v_state" class="input_style"  id="state" placeholder="Enter State" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="v_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="v_country" class="input_style" id="country" placeholder="Enter Country" maxlength="50">
                            </div>
                        </div>
                    </div>
            </div>
            <div class="add_btnDiv">
                <button class="add_btn" name="vendor_add">Add <?php echo $vendor; ?></button>
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