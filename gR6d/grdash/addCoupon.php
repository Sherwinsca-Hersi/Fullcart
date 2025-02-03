<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Coupon</title>
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
    <div class="addCoupon_rightbar container">

    <?php
            if(isset($_GET['couponid'])){
                echo  "<h2>Update Coupon</h2>";
            }
            else{
                echo "<h2>Add Coupon</h2>";
            }
        ?>
        <div class="banner_form">
        <?php 
			if(isset($_GET['couponid']))
			{
				$data = $mysqli->query("SELECT id,c_title,c_code,c_value,c_date,active,min_amt,c_desc,c_img FROM `e_data_coupon`  WHERE  id=".$_GET['couponid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
            <form class="addcoupon_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="c_title" class="form-label">Coupon Title</label>
                            <div>
                                <input type="text" name="coup_title"  class="input_style" value="<?php if(!($data['c_title']==NULL || '')){ echo $data['c_title'];}?>" placeholder="Enter Coupon Title" maxlength="50" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_code" class="form-label">Coupon Code</label>
                            <div>
                                <input type="text" name="coup_code"  class="input_style"  value="<?php if(!($data['c_code']==NULL || '')){echo $data['c_code'];}?>" placeholder="Enter Coupon Code" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_min_amount" class="form-label">Coupon Min Order Amount</label>
                            <div>
                                <input type="text" name="coup_min_amount" id="coup_min_amount" class="input_style" value="<?php if(!($data['min_amt']==NULL || '')){echo $data['min_amt'];}?>" maxlength="10" placeholder="Enter Coupon Min Amount" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_value" class="form-label">Coupon Value</label>
                            <div>
                                <input type="text" name="coup_value" id="coup_value" class="input_style" value="<?php  if(!($data['c_value']==NULL || '')){ echo $data['c_value'];}?>" placeholder="Enter Coupon Value" maxlength="10" required>
                                <small id="price-error">Coupon Value must be less than Minimum OrderAmount</small>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="publish" class="form-label">Coupon Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="coup_status"  class="input_style" value="1" <?php if($data['active'] == 1){echo 'checked';}?>>Publish
                                <input type="radio" name=coup_status  class="input_style" value="0" <?php if($data['active'] == 0){echo 'checked';}?>>Unpublish
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="cexp_date" class="form-label">Expiry Date</label>
                            <div>
                                <input type="date" class="input_style" name="coup_exp_date"  value="<?php  if(!($data['c_date']==NULL || '')){ echo $data['c_date'];}?>" placeholder="Enter Coupon Expiry Date" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_desc" class="form-label">Coupon Description</label>
                            <div>
                                <textarea rows="6" cols="30" name="coup_desc"  class="input_style"  placeholder="Enter Coupon Description" maxlength="100"><?php if(!($data['c_desc']==NULL || '')){echo $data['c_desc'];}?></textarea> 
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Upload Coupon Image</span>
                                <input type="file" id="coup_img" class="img_upload" name="coup_img">
                            </div>
                            <div>
                            <?php
                            if(!($data['c_img']==NULL || '')){
                            ?>
                                <img id="previewImage"  src="../../<?php echo $data['c_img']?>"  width="100px"/>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                        <script>
                            document.getElementById('coup_img').addEventListener('change', function(event){
                        const file = event.target.files[0];
                        const reader = new FileReader();
                        reader.onload = function(event){
                            document.getElementById('previewImage').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                </script>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="couponid" value="<?php echo isset($_GET['couponid']) ? htmlspecialchars($_GET['couponid']) : ''; ?>">
                    <input type="submit" value="Update Coupon" class="add_btn" name="coupon_update">
                </div>
            </form>
            <?php
            }
            else {
            ?>
            <form class="addcoupon_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        
                        <div class="form-div">
                            <label for="c_title" class="form-label">Coupon Title<span class="star">*</span></label>
                            <div>
                                <input type="text" name="coup_title"  class="input_style" placeholder="Enter Coupon Title" maxlength="50" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_code" class="form-label">Coupon Code<span class="star">*</span></label>
                            <div>
                                <input type="text" name="coup_code"  class="input_style" placeholder="Enter Coupon Code" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_min_amount" class="form-label">Coupon Min Order Amount<span class="star">*</span></label>
                            <div>
                                <input type="text" name="coup_min_amount" id="coup_min_amount" class="input_style" placeholder="Enter Coupon Min Amount" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_value" class="form-label">Coupon Value<span class="star">*</span></label>
                            <div>
                                <input type="text" name="coup_value"  class="input_style" placeholder="Enter Coupon Value" maxlength="10" id="coup_value" required>
                                <small id="price-error">Coupon Value must be less than Minimum OrderAmount</small>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="publish" class="form-label">Coupon Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="coup_status"  class="input_style" value="1">Publish
                                <input type="radio" name=coup_status  class="input_style" value="0">Unpublish
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="cexp_date" class="form-label">Expiry Date<span class="star">*</span></label>
                            <div>
                                <input type="date" class="input_style" name="coup_exp_date" placeholder="Enter Coupon Expiry Date" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="c_desc" class="form-label">Coupon Description</label>
                            <div>
                                <textarea rows="6" cols="30" name="coup_desc"  class="input_style" placeholder="Enter Coupon Description" maxlength="100"></textarea> 
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Upload Coupon Image</span>
                                <input type="file" id="coup_img" class="img_upload" name="coup_img">
                            </div>
                            <div>
                                <img id="previewImage" width="100px"/>
                            </div>
                        </div>
                        <script>
                            document.getElementById('coup_img').addEventListener('change', function(event){
                        const file = event.target.files[0];
                        const reader = new FileReader();
                        reader.onload = function(event){
                            document.getElementById('previewImage').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                </script>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add Coupon" class="add_btn" name="coupon_add">
                </div>
            </form>
            <?php
            }
            ?>
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
    <div>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>
    <script>

    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
    const currentDate = `${year}-${month}-${day}`;


    const dateInputs = document.querySelectorAll('input[type="date"]');

    dateInputs.forEach(input => {
        if (!input.value) {
            input.value = currentDate;
        }
    });
</script>
<!-- Coupon MinAmount Validation -->
<script>
document.getElementById('coup_value').addEventListener('input', function() {
    var minAmount = parseFloat(document.getElementById('coup_min_amount').value);
    var coupValue = parseFloat(this.value);
    
    if (coupValue >= minAmount) {
        document.getElementById('price-error').style.display = 'block';
        this.setCustomValidity('Coupon value must be less than Minimum Order Amount');
    } else {
        document.getElementById('price-error').style.display = 'none';
        this.setCustomValidity('');
    }
});
</script>
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>