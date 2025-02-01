<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reorder/Low Stock Level</title>
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
    <div class="addproduct_rightbar container">
        <?php
            if(isset($_GET['productid'])){
                echo  "<h2>Update Reorder/Low Stock Level</h2><br><br>";
            }
            // else{
            //     echo "<h2>Add Product</h2>";
            // }
        ?>
        <div class="product_form">
        <?php 
			if(isset($_GET['productid']))
			{
				$data = $mysqli->query("SELECT *
                FROM `e_product_details`
                WHERE cos_id = '$cos_id'  and active!=2  and id=".$_GET['productid']."")->fetch_assoc();
			?>
            
            <form class="addproduct_form" method="post" action="com_ins_upd.php"  enctype="multipart/form-data" autocomplete="off">

                <div class="product_details">
                    <?php $product_details=$mysqli->query("SELECT p_title,id,p_variation,unit FROM `e_product_details` WHERE cos_id='$cos_id' AND active!=2 AND id=".$_GET['productid']." GROUP BY id")->fetch_assoc(); ?>
                    <h2><?php echo $product_details['p_title']." ".$product_details['p_variation']." ".$product_details['unit'];?></h2>
                </div>
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="emergency_level" class="form-label">Low Stock Level</label>
                            <div>
                                <input type="number" name="emergency_level" id="emergency_level" class="input_style" value="<?php if(!($data['emergency_level']==NULL || '')){echo $data['emergency_level'];}?>" placeholder="Enter Emergency Level" maxlength="10" autofocus>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="reorder_level" class="form-label">Reorder Level</label>
                            <div>
                                <input type="number" name="reorder_level" id="reorder_level" class="input_style" value="<?php if(!($data['reorder_level']==NULL || '')){echo $data['reorder_level'];}?>" placeholder="Enter Reorder Level" maxlength="10">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="productid" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                    <input type="submit" value="Update Reorder/Low Stock Level" class="add_btn" name="level_update">
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
    <script>
function redirect(row, productId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'editLevel.php?productid=' + productId;
    }
}
</script>
    <!-- <div class="popup" id="popup">
        <h4>All unsaved changes will be lost.</h4>
        <div class="popup_btns">
            <button class="price_btn">Price</button>
            <button class="stock_btn">Stock</button>
            <button class="popup_cancel" id="cancel_btn">Cancel</button>
        </div>
    </div> -->
    <!-- <script>
        function updateSpread() {
        let inPrice = parseFloat(document.getElementById('inprice').value);
        let outPrice = parseFloat(document.getElementById('outprice').value);
        if (isNaN(inPrice)) inPrice = 0;
        if (isNaN(outPrice)) outPrice = 0;
        let spread = ((outPrice - inPrice) * 100 / inPrice).toFixed();
        document.getElementById('spread').value = spread;
    }
    document.getElementById('inprice').addEventListener('input', updateSpread);
    document.getElementById('outprice').addEventListener('input', updateSpread);
    updateSpread();
    </script> -->
    <script src="../assets/js/session_check.js"></script>
</body>
</html>