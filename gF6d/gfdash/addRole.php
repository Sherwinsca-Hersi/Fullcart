<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Roles</title>
    <?php 
        require_once '../api/header.php';
    ?>
    <!--iziToast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
</head>
<body>
<?php 
if(isset($_SESSION['old_role'])){
    $old_role = $_SESSION['old_role'] ?? [];
    unset($_SESSION['old_role']);

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
            if(isset($_GET['roleid'])){
                echo  "<h2>Update Roles</h2>";
            }
            else{
                echo "<h2>Add Roles</h2>";
            }
        ?>
        <div class="product_form">
        <?php 
		if(isset($_GET['roleid'])){
				$data = $mysqli->query("SELECT id,role_title,role_desc,active FROM `e_salesman_role` WHERE id=".$_GET['roleid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
            <form class="addcategory_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="role_title" class="form-label">Role Name</label>
                            <div>
                                <input type="text" name="role_title" class="input_style" placeholder="Enter Role Name" maxlength="50" value="<?php if(!($data['role_title'])==NULL || ''){ echo $data['role_title'];}?>" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="role_desc" class="form-label">Description</label>
                            <div>
                                <textarea rows="6" cols="30" name="role_desc" class="input_style"   placeholder="Enter Description"><?php if(!($data['role_desc'])==NULL || ''){ echo $data['role_desc'];}?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="role_status" class="form-label">Role Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="role_status" class="input_style" value="1" <?php if($data['active']==1){echo "checked";}?>>Publish
                                <input type="radio" name="role_status" class="input_style" value="0" <?php if($data['active']==0){echo "checked";}?>>Unpublish
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="roleid" value="<?php echo isset($_GET['roleid']) ? htmlspecialchars($_GET['roleid']) : ''; ?>">
                    <input type="submit" value="Update Role" class="add_btn" name="role_update">
                </div>
            </form>
            <?php 
        }
        else{?>
            <form class="addcategory_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()"  enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="role_title" class="form-label">Role Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="role_title" value="<?= htmlspecialchars($old_role['role_title'] ?? '') ?>" class="input_style" placeholder="Enter Role Name" maxlength="50" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="role_desc" class="form-label">Description</label>
                            <div>
                                <textarea rows="6" cols="30" name="role_desc" class="input_style" placeholder="Enter Description"><?= htmlspecialchars($old_role['role_desc'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="role_status" class="form-label">Role Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="role_status" class="input_style" value="1" <?= isset($old_role['role_status']) && $old_role['role_status'] == '1' ? 'checked' : '' ?>>Publish
                                <input type="radio" name="role_status" class="input_style" value="0" <?= isset($old_role['role_status']) && $old_role['role_status'] == '0' ? 'checked' : '' ?>>Unpublish
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add Role" class="add_btn" name="role_add">
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