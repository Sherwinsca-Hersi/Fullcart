<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Unit</title>
    <?php 
    require_once '../api/header.php';
    ?>
</head>
<body>
<?php 
    if(isset($_SESSION['old_unit'])){
        $old_unit = $_SESSION['old_unit'] ?? [];  
        unset($_SESSION['old_unit']);
    
    }
    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php'; 
        ?>
    </div>
    <div class="addBanner_rightbar container">
        <?php
            if(isset($_GET['unitid'])){
                echo  "<h2>Update Unit</h2>";
            }
            else{
                echo "<h2>Add Unit</h2>";
            }
        ?>
        <div class="product_form">
        <?php 
			if(isset($_GET['unitid']))
			{
				$data = $mysqli->query("select * from `e_unit_details` where cos_id = '$cos_id' and id=".$_GET['unitid']."")->fetch_assoc();
			?>
            <form class="addcategory_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="unit" class="form-label">Product Unit</label>
                            <div>
                                <input type="text" name="unit" class="input_style"  value="<?php  echo $data['unit'];?>" maxlength="50" placeholder="Enter Unit">
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="unit_status" class="form-label">Unit Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="unit_status" class="input_style" value="1" <?php if($data['active']==1){echo "checked";}?>>Published
                                <input type="radio" name="unit_status" class="input_style" value="0" <?php if($data['active']==0){echo "checked";}?>>Unpublished
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="unitid" value="<?php echo isset($_GET['unitid']) ? htmlspecialchars($_GET['unitid']) : ''; ?>">
                    <input type="submit" value="Update Unit" class="add_btn" name="unit_update">
                </div>
            </form>
            <?php }else{?>
            <form class="addcategory_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="unit" class="form-label">Product Unit<span class="star">*</span></label>
                            <div>
                                <input type="text" name="unit" value="<?= htmlspecialchars($old_unit['unit'] ?? '') ?>" class="input_style" placeholder="Enter Unit" maxlength="50" autofocus required>
                            </div>
                        </div>
                        
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="unit_status" class="form-label">Unit Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="unit_status" class="input_style" value="1" <?= isset($old_unit['unit_status']) && $old_unit['unit_status'] == '1' ? 'checked' : '' ?>>Published
                                <input type="radio" name="unit_status" class="input_style" value="0" <?= isset($old_unit['unit_status']) && $old_unit['unit_status'] == '0' ? 'checked' : '' ?>>Unpublished
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add Unit" class="add_btn" name="unit_add">
                </div>
            </form>
            <?php }?>
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
</body>
</html>