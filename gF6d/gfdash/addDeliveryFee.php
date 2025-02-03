<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Fee Details</title>
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
            if(isset($_GET['deliveryFeeid'])){
                echo  "<h2>Update Delivery Fee Details</h2>";
            }
            else{
                echo  "<h2>Add Delivery Fee Details</h2>";
            }
        ?>
        <div class="banner_form">
        <?php 
			if(isset($_GET['deliveryFeeid']))
			{
				$data = $mysqli->query("SELECT id,title,c_img,d_charge,min_amt,disc_alert_msg  FROM `e_data_city`  WHERE  id=".$_GET['deliveryFeeid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
            <form class="addcoupon_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="city_title" class="form-label">City</label>
                            <div>
                                <input type="text" name="city_title"  class="input_style" value="<?php if(!($data['title']==NULL || '')){ echo $data['title'];}?>" placeholder="Enter City" maxlength="50" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_charge" class="form-label">Delivery Charge</label>
                            <div>
                                <input type="text" name="d_charge"  class="input_style"  value="<?php if(!($data['d_charge']==NULL || '')){echo $data['d_charge'];}?>" placeholder="Enter Delivery Charge" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_message" class="form-label">Message</label>
                            <div>
                                <textarea rows="6" cols="30" name="d_message"  class="input_style"  placeholder="Enter alert Message" maxlength="100"><?php if(!($data['disc_alert_msg']==NULL || '')){echo $data['disc_alert_msg'];}?></textarea> 
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="min_ord_amount" class="form-label">Min Order Amount</label>
                            <div>
                                <input type="text" name="min_ord_amount" class="input_style" value="<?php if(!($data['min_amt']==NULL || '')){echo $data['min_amt'];}?>" maxlength="10" placeholder="Enter  Min Order  Amount" required>
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Upload City Image</span>
                                <input type="file" id="city_img" class="img_upload" name="city_img">
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
                            document.getElementById('city_img').addEventListener('change', function(event){
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
                    <input type="hidden" name="deliveryFeeid" value="<?php echo isset($_GET['deliveryFeeid']) ? htmlspecialchars($_GET['deliveryFeeid']) : ''; ?>">
                    <input type="submit" value="Update Details" class="add_btn" name="city_delivery_update">
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
                            <label for="city_title" class="form-label">City</label>
                            <div>
                                <input type="text" name="city_title"  class="input_style"  placeholder="Enter City" maxlength="50" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_charge" class="form-label">Delivery Charge</label>
                            <div>
                                <input type="text" name="d_charge"  class="input_style"  placeholder="Enter Delivery Charge" maxlength="10" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="d_message" class="form-label">Message</label>
                            <div>
                                <textarea rows="6" cols="30" name="d_message"  class="input_style"  placeholder="Enter alert Message" maxlength="100"><?php if(!($data['disc_alert_msg']==NULL || '')){echo $data['disc_alert_msg'];}?></textarea> 
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="min_ord_amount" class="form-label">Min Order Amount</label>
                            <div>
                                <input type="text" name="min_ord_amount" class="input_style"  maxlength="10" placeholder="Enter  Min Order  Amount" required>
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Upload City Image</span>
                                <input type="file" id="city_img" class="img_upload" name="city_img">
                            </div>
                            <div>
                            <?php
                            if(!($data['c_img']==NULL || '')){
                            ?>
                                <img id="previewImage"  width="100px"/>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                        <script>
                            document.getElementById('city_img').addEventListener('change', function(event){
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
                    <input type="submit" value="Add Details" class="add_btn" name="city_delivery_add">
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
    <div class="popup" id="popup">
        <h4>All unsaved changes will be lost.</h4>
        <div class="popup_btns">
            <button class="price_btn">Price</button>
            <button class="stock_btn">Stock</button>
            <button class="popup_cancel" id="cancel_btn">Cancel</button>
        </div>
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
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>