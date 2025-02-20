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
    <title>Add Bank</title>
</head>
<body>
<?php 
if(isset($_SESSION['old_bank'])){
    $old_bank = $_SESSION['old_bank'] ?? [];
    unset($_SESSION['form_data']);
}

    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php';
        ?>
    </div>
    <div class="addCustomer_rightbar container">
    <?php
            if(isset($_GET['bankid'])){
                echo  "<h2>Update Bank</h2>";
            }
            else{
                echo  "<h2>Add Bank</h2>";
            }
        ?>
        <?php 
			if(isset($_GET['bankid']))
			{
                $data = $mysqli->query("SELECT id,bank_name,account_holder,account_no,ifsc_code,upi_id,app_status FROM `e_bank_details` WHERE id=".$_GET['bankid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
            <form method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" autocomplete="off">
            <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <div>
                                <input type="text" name="bank_name"  class="input_style" value="<?php echo $data['bank_name'];?>" placeholder=" Enter Bank Name" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="account_holder" class="form-label">Account Holder Name</label>
                            <div>
                                <input type="text" name="account_holder"  class="input_style" value="<?php echo $data['account_holder'];?>" placeholder=" Enter Account Holder Name" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="account_no" class="form-label">Account Number</label>
                            <div>
                                <input type="text" name="account_no"  class="input_style" value="<?php echo $data['account_no'];?>" oninput="this.value = this.value.toUpperCase();" placeholder=" Enter Account No" maxlength="18">
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="ifsc_code" class="form-label">IFSC Code</label>
                            <div>
                                <input type="text" name="ifsc_code"  class="input_style" value="<?php echo $data['ifsc_code'];?>" placeholder=" Enter IFSC Code" maxlength="11" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="upi_id" class="form-label">UPI ID</label>
                            <div>
                                <input type="text" name="upi_id"  class="input_style" value="<?php echo $data['upi_id'];?>" placeholder=" Enter UPI ID" maxlength="35" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="app_status" class="form-label">Active Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="app_status" id="app_status" class="input_style" value="1" <?php if($data['app_status']==1){echo "checked";}?>>Active
                                <input type="radio" name="app_status" id="app_status" class="input_style" value="0" <?php if($data['app_status']==0){echo "checked";}?>>In Active
                            </div>
                        </div>
                    </div>
            </div>
            <div class="add_btnDiv">
                <input type="hidden" name="bankid" value="<?php echo isset($_GET['bankid']) ? htmlspecialchars($_GET['bankid']) : ''; ?>">
                <button class="add_btn" name="bank_update">Update Bank Details</button>
            </div>
        </form>
        <?php
        }
        else{
        ?>
            <form method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" autocomplete="off">
            <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="bank_name" class="form-label">Bank Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="bank_name"  class="input_style" placeholder=" Enter Bank Name" value="<?= htmlspecialchars($old_bank['bank_name'] ?? '') ?>" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="account_holder" class="form-label">Account Holder Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="account_holder"  class="input_style"  placeholder=" Enter Account Holder Name"  value="<?= htmlspecialchars($old_bank['account_holder'] ?? '') ?>" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="account_no" class="form-label">Account Number<span class="star">*</span></label>
                            <div>
                                <input type="text" name="account_no"  class="input_style"  placeholder=" Enter Account No" oninput="this.value = this.value.toUpperCase();" value="<?= htmlspecialchars($old_bank['account_no'] ?? '') ?>" maxlength="18" required>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                    <div class="form-div">
                            <label for="ifsc_code" class="form-label">IFSC Code<span class="star">*</span></label>
                            <div>
                                <input type="text" name="ifsc_code" value="<?= htmlspecialchars($old_bank['ifsc_code'] ?? '') ?>"  class="input_style"  placeholder=" Enter IFSC Code" maxlength="11" required>
                            </div>
                        </div>
                    <div class="form-div">
                            <label for="upi_id" class="form-label">UPI ID<span class="star">*</span></label>
                            <div>
                                <input type="text" name="upi_id"  class="input_style" value="<?= htmlspecialchars($old_bank['upi_id'] ?? '') ?>" placeholder=" Enter UPI ID" maxlength="35" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="app_status" class="form-label">Active Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="app_status" id="app_status" class="input_style" value="1"   <?= isset($old_bank['app_status']) && $old_bank['app_status'] == '1' ? 'checked' : '' ?>>Active
                                <input type="radio" name="app_status" id="app_status" class="input_style" value="0"  <?= isset($old_bank['app_status']) && $old_bank['app_status'] == '0' ? 'checked' : '' ?>>In-Active
                            </div>
                        </div>
                    </div>
            </div>
            <div class="add_btnDiv">
                <input type="submit" value="Add Bank Details" class="add_btn" name="bank_add">
            </div>
        </form>
        <?php } ?>
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
            position: 'topRight'
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
</body>
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</html>