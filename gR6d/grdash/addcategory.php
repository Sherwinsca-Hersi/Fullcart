<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <?php 
        require_once '../api/header.php';
    ?>
    <!--iziToast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
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
    <button onclick="window.history.back()" class="back_arrow"><i class="fa fa-arrow-left" aria-hidden="true"></i></button>
        <?php
            if(isset($_GET['categoryid'])){
                echo  "<h2>Update Category</h2>";
            }
            else{
                echo "<h2>Add Category</h2>";
            }
        ?>
        <div class="product_form">
        <?php 
		if(isset($_GET['categoryid'])){
				$data = $mysqli->query("SELECT id,title,c_img,active FROM `e_category_details` WHERE id=".$_GET['categoryid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
            <form class="addcategory_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="pcategory" class="form-label">Product Category</label>
                            <div>
                                <input type="text" name="pcategory" class="input_style"  value="<?php if(!($data['title'])==NULL || ''){ echo $data['title'];}?>" maxlength="50" placeholder="Enter Category Name">
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="cat_img" class="img_upload" name="cat_img" accept="image/*">
                            </div>
                            <div>
                                <?php 
                                    if(!($data['c_img']==NULL || '')){
                                    ?>
                                        <img id="previewImage" src="../../<?php echo $data['c_img'];?>" width="100px"/>
                                    <?php
                                    }
                                ?>
                            </div>
                        </div>
                
                            <script>
                                document.getElementById('cat_img').addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    document.getElementById('previewImage').src = event.target.result;
                                };
                                reader.readAsDataURL(file);
                                });
                            </script>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="cat_status" class="form-label">Category Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="cat_status" class="input_style" value="1" <?php if($data['active']==1){echo "checked";}?>>Published
                                <input type="radio" name="cat_status" class="input_style" value="2" <?php if($data['active']==0){echo "checked";}?>>Unpublished
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="categoryid" value="<?php echo isset($_GET['categoryid']) ? htmlspecialchars($_GET['categoryid']) : ''; ?>">
                    <input type="submit" value="Update Category" class="add_btn" name="category_update">
                </div>
            </form>
            <?php 
        }
        else{?>
            <form class="addcategory_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="pcategory" class="form-label">Product Category<span class="star">*</span></label>
                            <div>
                                <input type="text" name="pcategory" class="input_style" placeholder="Enter Category Name" maxlength="50" required autofocus>
                            </div>
                        </div>
                        <div class="img_input">
                    <div class="file_upload">
                        <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                        <span>Search Image to Upload</span>
                        <input type="file" id="cat_img" class="img_upload" name="cat_img" accept="image/*" required>
                    </div>
                    <div>
                        <img id="previewImage"  width="100px"/>
                    </div>
                </div>
                
                <script>
                    document.getElementById('cat_img').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            document.getElementById('previewImage').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                </script>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="cat_status" class="form-label">Category Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="cat_status" class="input_style" value="1">Published
                                <input type="radio" name="cat_status" class="input_style" value="0">Unpublished
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add Category" class="add_btn" name="category_add">
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
                message: '<?php echo $_SESSION['error_message']; ?>',
                position: 'bottomCenter',
                timeout: 5000
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
    <script src="../assets/js/session_check.js"></script>
</body>
</html>