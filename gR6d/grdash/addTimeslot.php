<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Timeslot</title>
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
    <div class="addBanner_rightbar container">

        <?php
            if(isset($_GET['timeslotid'])){
                echo  "<h2>Update Timeslot</h2>";
            }
            else{
                echo "<h2>Add Timeslot</h2>";
            }
        ?>
        <div class="product_form">
        <?php 
			if(isset($_GET['timeslotid']))
			{
				$data = $mysqli->query("select id,max_time,min_time,slot_limit,active from `e_dat_timeslot` where cos_id = '$cos_id' and id=".$_GET['timeslotid']."")->fetch_assoc();
			?>
            <form class="addtimeslot_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data" id="myForm" onsubmit="return validateForm()">
            <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="from_time" class="form-label">From</label>
                            <div>
                                <input type="time" id="from_time" name="from_time" value="<?php if(!($data['min_time']==NULL || '')){echo date('H:i', strtotime($data['min_time'])); }?>" onclick="this.showPicker()" required>   
                            </div>      
                        </div>
                        <div class="form-div">
                            <label for="slot_limit" class="form-label">Slot Limit</label>
                            <div>
                                <input type="number" id="slot_limit" name="slot_limit" value="<?php  if(!($data['slot_limit']==NULL || '')){echo $data['slot_limit'];}?>" maxlength="10" required> 
                            </div>        
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="to_time" class="form-label">To</label>
                            <div>
                                <input type="time" id="to_time" name="to_time" value="<?php if(!($data['max_time']==NULL || '')){echo date('H:i', strtotime($data['max_time'])); }?>" onclick="this.showPicker()" required> 
                            </div>        
                        </div>
                        <div class="form-div">
                            <label for="cat_status" class="form-label">Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="timeslot_status" class="input_style" value="1" <?php if($data['active']==1){echo "checked";}?>>Publish
                                <input type="radio" name="timeslot_status" class="input_style" value="0" <?php if($data['active']==0){echo "checked";}?>>Unpublish
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                <input type="hidden" name="timeslotid" value="<?php echo isset($_GET['timeslotid']) ? htmlspecialchars($_GET['timeslotid']) : ''; ?>">
                    <input type="submit" value="Update Timeslot" class="add_btn" name="timeslot_update">
                </div>
            </form>
            
            <?php }else{?>
            <form class="addtimeslot_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data" id="myForm" onsubmit="return validateForm()">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="from_time" class="form-label">From<span class="star">*</span></label>
                            <div>
                                <input type="time" id="from_time" name="from_time" onclick="this.showPicker()" required>   
                            </div>      
                        </div>
                        <div class="form-div">
                            <label for="slot_limit" class="form-label">Slot Limit</label>
                            <div>
                                <input type="number" id="slot_limit" name="slot_limit" required> 
                            </div>        
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="to_time" class="form-label">To<span class="star">*</span></label>
                            <div>
                                <input type="time" id="to_time" name="to_time" onclick="this.showPicker()" required> 
                            </div>        
                        </div>
                        <div class="form-div">
                            <label for="cat_status" class="form-label">Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="timeslot_status" class="input_style" value="1">Publish
                                <input type="radio" name="timeslot_status" class="input_style" value="0">Unpublish
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add Timeslot" class="add_btn" name="timeslot_add">
                </div>
            </form>
            <?php }?>
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
    <script src="../assets/js/validateForm.js"></script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>