<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reconcilation</title>
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
    <div class="addBanner_rightbar container">
        <?php
            if(isset($_GET['orderid'])){
                echo  "<h2>Reconcilation</h2>";
            }
        ?>
        <div class="product_form">
        <?php 
			if(isset($_GET['orderid']))
			{
				$data = $mysqli->query("SELECT * FROM `e_normal_order_details` WHERE p_method_id=1 and cos_id='$cos_id' and active=6 and id=".$_GET['orderid']." order by id")->fetch_assoc();
			?>
            <div class="order_details">
                <?php 
                    $date = date_create($data['created_ts']);
                ?>
                <h3><?php echo "Customer Name:".$data['name'];?></h3> 
                <h3><?php echo "Order Amount:".$data['subtotal'];?></h3>
                <h3><?php echo "Order Date:".date_format($date, "d/m/Y h:i A");?></h3>
            </div>
                <form class="addcategory_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="bank_trans_id" class="form-label">Bank Transaction Id</label>
                            <div>
                                <input type="text" name="bank_trans_id" class="input_style"  placeholder="Enter Bank Transaction No"  maxlength="50" value="<?php echo $data['bank_trans_id'];?>">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="upi_id" class="form-label">UPI Id</label>
                            <div>
                                <input type="text" name="upi_id" class="input_style" placeholder="Enter UPI Id" maxlength="50" value="<?php echo $data['upi_id'];?>">
                            </div>
                        </div>
                    </div>
                <div class="grid-col-2">
                        <div class="form-div">
                            <label for="recon_status" class="form-label">Reconsilation Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="recon_status" class="input_style" value="0" <?php if($data['recon_status']==0){echo "checked";}?>>Pending
                                <input type="radio" name="recon_status" class="input_style" value="1" <?php if($data['recon_status']==1){echo "checked";}?>>Completed
                            </div>
                        </div>
                        <!-- <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="subcat_img" class="img_upload" name="subcat_img" accept="image/*">
                            </div>
                        <div>
                        <img id="previewImage" src="../<?php echo $data['c_img'];?>" width="100px"/>
                        <script>
                        document.getElementById('subcat_img').addEventListener('change', function(event) {
                        const file = event.target.files[0];
                        const reader = new FileReader();
                        reader.onload = function(event) {
                            document.getElementById('previewImage').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                    </script> -->
                </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="orderid" value="<?php echo isset($_GET['orderid']) ? htmlspecialchars($_GET['orderid']) : ''; ?>">
                    <input type="submit" value="Submit" class="add_btn" name="recon_submit">
                </div>
            </form>   
        <?php
            } 
            ?>
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
    <script src="../assets/js/session_check.js"></script>
</body>
</html>