<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee/Role</title>
    <?php 
    require_once '../api/header.php';
    ?>
    <!--iziToast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
</head>
<body>
<?php 
if(isset($_SESSION['old_employee'])){
    $old_employee = $_SESSION['old_employee'] ?? [];

    unset($_SESSION['old_employee']);
}
    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php';
        ?>
    </div>
    <div class="addemployee_rightbar container">
    <?php
            if(isset($_GET['employeeid'])){
                echo  "<h2>Update Employee/Role</h2>";
            }
            else{
                echo "<h2>Add Employee/Role</h2>";
            }
        ?>
        
        <?php 
			if(isset($_GET['employeeid']))
			{
				$data = $mysqli->query("SELECT id,s_name,s_mobile,whatsapp,email,password,joining_date,role,other_roles,salary,bonus,s_address FROM e_salesman_details WHERE  id=".$_GET['employeeid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
                <form method="post" action="com_ins_upd.php" autocomplete="off" id="myForm" onsubmit="return validateForm()">
            <!-- <div class="emp_pic">
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
                            <label for="ename" class="form-label">Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="emp_name"  class="input_style" value="<?php echo $data['s_name'];?>"  placeholder=" Enter  Name" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_phone" class="form-label">Phone Number<span class="star">*</span></label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="emp_phone" id="emp_phone" class="input_style" value="<?php  echo $data['s_mobile'];?>" placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_whatsapp" class="form-label">Whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="emp_whatsapp" id="emp_whatsapp" class="input_style"  placeholder="Enter Whatsapp Number" value="<?php  echo $data['whatsapp'];?>">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_email" class="form-label">Email</label>
                            <div>
                                <input type="email" name="emp_email" class="input_style" value="<?php echo $data['email'];?>" placeholder="Enter Email" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_password" class="form-label"> Password<span class="star">*</span></label>
                            <div class="pass_input">
                                <input type="password"  class="input_style"  name="emp_password" value="<?php  echo $data['password'];?>" style="width:100%;" id="emp_password" placeholder="Enter Password"  maxlength="20" autocomplete="new-password" required>
                                <span class="eye_icon" onclick="togglePassword()"><i class="fa fa-solid fa-eye-slash"></i></span>
                                <input type="password" name="dummy" style="display:none" autocomplete="new-password">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="join_date" class="form-label">Joining Date<span class="star">*</span></label>
                            <div>
                                <input type="date" name="emp_join_date" id="join_date" class="input_style" value="<?php  echo $data['joining_date'];?>"  max="<?php echo date('Y-m-d'); ?>" placeholder="Enter Joining Date" required>
                            </div>
                        </div>
                        <?php
                            $role_query = "SELECT id, role_title FROM `e_salesman_role` WHERE active != 2 AND cos_id = '$cos_id' ORDER BY role_title ASC";
                            $result = $mysqli->query($role_query);

                            $roles = [];
                            while ($row = $result->fetch_assoc()) {
                                $roles[] = $row;
                            }

                            $main_role = $data['role'] ?? '';


                            $selected_roles = !empty($data['other_roles']) ? explode(',', $data['other_roles']) : [];
                    ?>
                    <div class="form-div">
                        <label for="emp_role" class="form-label">Position/Role<span class="star">*</span></label>
                        <div>
                            <select name="emp_role" class="input_style" required>
                                <option value="" class="option_style" disabled selected>Position</option>
                                <?php
                                    foreach ($roles as $role) {
                                        $selected = ($role['id'] == $main_role) ? 'selected' : '';
                                        echo '<option value="' . $role['id'] . '" ' . $selected . '>' . htmlspecialchars($role['role_title']) . '</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="role" class="form-label">Other Roles</label>
                        <div class="checkbox_div">
                            <div class="role_checkbox">
                            <?php
                                foreach ($roles as $role) {
                                    $checked = in_array($role['id'], $selected_roles) ? 'checked' : '';
                                    echo '<input type="checkbox" name="role[]" class="input_style" value="' . $role['id'] . '" ' . $checked . '>';
                                    echo htmlspecialchars($role['role_title']) . '<br>';
                                }
                            ?>
                            </div>
                        </div>
                    </div>
                </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="emp_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="emp_doorno" class="input_style" 
                                value="<?php 
                                 if(!($data['s_address']== NULL || '')){
                                    $address = $data['s_address'];
                                        $address_parts = explode(',', $address);
                                        if (count($address_parts) >= 1) {
                                            $doorno = $address_parts[0];
                                            echo $doorno;
                                        }
                                 }
                                ?>" placeholder="Enter Door No" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="emp_street" id="emp_street" class="input_style"
                                value="<?php 
                                if(!($data['s_address']== NULL || '')){
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
                            <label for="emp_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="emp_area" class="input_style" 
                                value="<?php 
                                if(!($data['s_address']== NULL || '')){
                                    $address = $data['s_address'];
                                    $address_parts = explode(',', $address);
                                    if (count($address_parts) >= 3) {
                                        $area = $address_parts[2];
                                        echo $area;
                                    } 
                                }?>"placeholder="Enter Area" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="emp_pincode" id="pincode" class="input_style"  placeholder="Enter Pincode"value="<?php 
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
                            <label for="emp_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="emp_city" id="city" class="input_style" placeholder="Enter City" value="<?php 
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
                            <label for="emp_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="emp_state" class="input_style"  id="state" placeholder="Enter State" value="<?php 
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
                            <label for="emp_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="emp_country" class="input_style" id="country" placeholder="Enter Country" value="<?php 
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
                        
                        <!-- <div class="form-div">
                            <label for="emp_city" class="form-label">City</label>
                            <div>
                                <select name="emp_city" id="emp_city" class="input_style">
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option value='<?php echo $city;?>' class="option_style" <?php 
                                        if(!($data['s_address']== NULL || '')){
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
                            <label for="emp_state" class="form-label">State</label>
                            <div>
                                <select name="emp_state" id="emp_state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu" <?php echo 'selected';?>>TamilNadu</option>
                                </select>
                            </div>
                        </div> 
                        <div class="form-div">
                            <label for="emp_country" class="form-label">Country</label>
                            <div>
                                <select name="emp_country" id="emp_country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India" <?php echo 'selected';?>>India</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,6)" name="emp_pincode"  class="input_style"  value="<?php 
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
                                    } ?>" placeholder="Enter Pincode" maxlength="6">
                            </div>
                        </div> -->
                        <div class="form-div">
                            <label for="emp_salary" class="form-label">Salary <span class="star">*</span></label>
                            <div>
                                <input type="number" name="emp_salary" id="emp_salary" class="input_style" value="<?php if(!($data['salary']== NULL || '')){ echo $data['salary'];} ?>" placeholder="Enter Salary" required>
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_bonus" class="form-label">Bonus</label>
                            <div>
                                <input type="number" name="emp_bonus" id="emp_bonus" class="input_style" value="<?php if(!($data['bonus']== NULL || '')){ echo $data['bonus'];} ?>" placeholder="Enter Bonus">
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                    </div>
                </div>
            <div class="add_btnDiv">
                <input type="hidden" name="employeeid" value="<?php echo isset($_GET['employeeid']) ? htmlspecialchars($_GET['employeeid']) : ''; ?>">
                <button class="add_btn" name="employee_update">Update Employee/Role</button>
            </div>
        </form>
            <?php 
            }
            else{
            ?>
        <form method="post" action="com_ins_upd.php" autocomplete="off" id="myForm" onsubmit="return validateForm()">
            <!-- <div class="emp_pic">
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
                            <label for="ename" class="form-label">Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="emp_name" value="<?= htmlspecialchars($old_employee['emp_name'] ?? '') ?>" class="input_style" placeholder=" Enter  Name" maxlength="100" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_phone" class="form-label">Phone Number<span class="star">*</span></label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" value="<?= htmlspecialchars($old_employee['emp_phone'] ?? '') ?>"  name="emp_phone" id="emp_phone" class="input_style" placeholder="Enter Phone Number" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_whatsapp" class="form-label">Whatsapp</label>
                            <div>
                                <input type="number" oninput="this.value=this.value.slice(0,10)" name="emp_whatsapp"  value="<?= htmlspecialchars($old_employee['emp_whatsapp'] ?? '') ?>" id="emp_whatsapp" class="input_style"  placeholder="Enter Whatsapp Number" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_email" class="form-label">Email</label>
                            <div>
                                <input type="email" name="emp_email" class="input_style"  value="<?= htmlspecialchars($old_employee['emp_email'] ?? '') ?>" placeholder="Enter Email" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_password" class="form-label"> Password<span class="star">*</span></label>
                            <div class="pass_input">
                                <input type="password"  class="input_style"  name="emp_password"   value="<?= htmlspecialchars($old_employee['emp_password'] ?? '') ?>" id="emp_password" placeholder="Enter Password" style="width:100%;"  maxlength="20" autocomplete="new-password" required>
                                <span class="eye_icon" onclick="togglePassword()"><i class="fa fa-solid fa-eye-slash"></i></span>
                            </div>
                           
                        </div>
                        <div class="form-div">
                            <label for="join_date" class="form-label">Joining Date<span class="star">*</span></label>
                            <div>
                                <input type="date" name="emp_join_date"  value="<?= htmlspecialchars($old_employee['emp_join_date'] ?? '') ?>" id="join_date" class="input_style"  max="<?php echo date('Y-m-d'); ?>" placeholder="Enter Joining Date" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_role" class="form-label">Position/Role<span class="star">*</span></label>
                            <div>
                                <?php
                                    $role_query="SELECT id,role_title FROM `e_salesman_role`
                                        WHERE active!=2 AND cos_id = '$cos_id' ORDER BY role_title ASC";
                                    $roles = $mysqli->query($role_query);
                                ?>
                                   <select name="emp_role" class="input_style" required>
    <option value="" class="option_style" disabled selected>Position</option>
    <?php
        if ($roles->num_rows > 0) {
            // Retrieve the previously selected role from the session
            $selected_role = htmlspecialchars($old_employee['emp_role'] ?? '');

            while ($row = $roles->fetch_assoc()) {
                $role_id = $row['id'];
                $role_title = htmlspecialchars($row['role_title']);

                // Check if the role is the selected one
                $isSelected = ($role_id == $selected_role) ? 'selected' : '';

                // Output the option element
                echo '<option value="' . $role_id . '" ' . $isSelected . '>' . $role_title . '</option>';
            }
        } 
        else {
            echo '<option value="" disabled>No roles available</option>';
        }
    ?>
</select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="role" class="form-label">Other Roles</label>
                            <div class="checkbox_div">
                                <div class="role_checkbox">
                                    <?php

                                     $role_query="SELECT id,role_title FROM `e_salesman_role`
                                         WHERE active!=2 AND cos_id = '$cos_id' ORDER BY role_title ASC";

                                     $roles = $mysqli->query($role_query);

                                     // Retrieve selected roles from session, ensuring it's an array
                                     $selected_roles = isset($old_employee['role']) ? (array) $old_employee['role'] : [];
                                 
                                     while ($row = $roles->fetch_assoc()) {
                                         $role_id = $row['id'];
                                         $role_title = htmlspecialchars($row['role_title']);
                                         
                                         // Check if the current role ID exists in the session's selected roles
                                         $isChecked = in_array($role_id, $selected_roles) ? 'checked' : '';
                                 
                                         echo '<input type="checkbox" name="role[]" class="input_style" value="' . $role_id . '" ' . $isChecked . '> ' . $role_title . '<br>';
                                    }
                                 ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="emp_doorno" class="form-label">Door No</label>
                            <div>
                                <input type="text" name="emp_doorno" class="input_style" value="<?= htmlspecialchars($old_employee['emp_doorno'] ?? '') ?>" placeholder="Enter Door No" maxlength="40">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_street" class="form-label">Street</label>
                            <div>
                                <input type="text" name="emp_street" id="emp_street" class="input_style" value="<?= htmlspecialchars($old_employee['emp_street'] ?? '') ?>" placeholder="Enter Street" maxlength="40">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_area" class="form-label">Area</label>
                            <div>
                                <input type="text" name="emp_area"  value="<?= htmlspecialchars($old_employee['emp_area'] ?? '') ?>" class="input_style" placeholder="Enter Area" maxlength="40">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_pincode" class="form-label">Pincode</label>
                            <div>
                                <input type="number"   oninput="fetchLocationData()"  name="emp_pincode"  value="<?= htmlspecialchars($old_employee['emp_pincode'] ?? '') ?>" id="pincode" class="input_style"  placeholder="Enter Pincode" maxlength="6">
                                <h6 class="price-error" id="suggestion_box"></h6>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_city" class="form-label">City</label>
                            <div>
                                <input type="text" name="emp_city"  value="<?= htmlspecialchars($old_employee['emp_city'] ?? '') ?>" id="city" class="input_style" placeholder="Enter City" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_state" class="form-label">State</label>
                            <div>
                                <input type="text" name="emp_state"  value="<?= htmlspecialchars($old_employee['emp_state'] ?? '') ?>" class="input_style"  id="state" placeholder="Enter State" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_country" class="form-label">Country</label>
                            <div>
                                <input type="text" name="emp_country" class="input_style" id="country"  value="<?= htmlspecialchars($old_employee['emp_country'] ?? '') ?>" placeholder="Enter Country" maxlength="50">
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <label for="emp_city" class="form-label">City</label>
                            <div>
                                <select name="emp_city" id="emp_city" class="input_style">
                                    <option value=""  class="option_style" disabled selected>City</option>
                                    <?php
                                    foreach($cities as $city):?>
									    <option value='<?php echo $city;?>' class="option_style"><?php echo $city;?></option>
                                    <?php endforeach;?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_state" class="form-label">State</label>
                            <div>
                                <select name="emp_state" id="emp_state" class="input_style">
                                    <option value=""  class="option_style" disabled selected>State</option>
									<option value="TamilNadu"  class="option_style">TamilNadu</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_country" class="form-label">Country</label>
                            <div>
                                <select name="emp_country" id="emp_country" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Country</option>
									<option value="India"  class="option_style">India</option>
                                </select>
                            </div>
                        </div> -->
                        
                        <div class="form-div">
                            <label for="emp_salary" class="form-label">Salary<span class="star">*</span></label>
                            <div>
                                <input type="number" name="emp_salary"  value="<?= htmlspecialchars($old_employee['emp_salary'] ?? '') ?>" id="emp_salary" class="input_style" placeholder="Enter Salary" maxlength="10" required>
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emp_bonus" class="form-label">Bonus</label>
                            <div>
                                <input type="number" name="emp_bonus"  value="<?= htmlspecialchars($old_employee['emp_bonus'] ?? '') ?>" id="emp_bonus" class="input_style"  placeholder="Enter Bonus">
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                    </div>
           
       </div>
       <div class="add_btnDiv">
            <button class="add_btn" name="employee_add">Add Employee/Role</button>
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
    <!-- <div class="popup" id="popup">
        <h4>All unsaved changes will be lost.</h4>
        <div class="popup_btns">
            <button class="price_btn">Price</button>
            <button class="stock_btn">Stock</button>
            <button class="popup_cancel" id="cancel_btn">Cancel</button>
        </div>
    </div> -->
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
  var passwordField = document.getElementById('emp_password');
  console.log(emp_password);
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
</body>
</html>