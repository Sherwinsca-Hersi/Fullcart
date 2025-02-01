<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>
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
        <h2>Add Stock</h2><br><br>
        <?php
            if(isset($_GET['productid']))
			{
				$data = $mysqli->query("SELECT pd.p_title,ps.s_mrp,ps.supplier_id,pd.reorder_level,pd.emergency_level,pp.qty_left FROM `e_product_details` as pd join `e_product_stock` as ps join `e_product_price` as pp on pd.cos_id='$cos_id' and pd.id=".$_GET['productid']." and ps.s_batch_no=pp.batch_no and pd.active=1 WHERE pd.cos_id = ps.cos_id and ps.cos_id = pp.cos_id and pd.active=ps.active and ps.active=pp.active and pd.id=ps.s_product_id and ps.s_product_id=pp.product_id ORDER BY ps.updated_ts LIMIT 1")->fetch_assoc();
			?>
            
            <div class="product_details">
                <?php 
                $product_name=$mysqli->query("SELECT p_title FROM `e_product_details` WHERE cos_id='$cos_id' AND active=1 AND id=".$_GET['productid']." GROUP BY id")->fetch_assoc();
                ?>
                <h2><?php echo $product_name['p_title'];?></h2>
                <h2 class="product_stock">Existing Stock:<div class="exist_stock">
                    <?php echo $data['qty_left'];?>
                </div></h2>
                
            </div>
        <div class="product_form">
            <form class="addproduct_form" method="post" action="com_ins_upd.php"  enctype="multipart/form-data">
                
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="batch_no" class="form-label">Batch No<sup>*</sup></label>
                            <div>
                                <input type="text" name="batch_no" class="input_style" placeholder="Enter Batch No" maxlength="10" autofocus required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="tstock" class="form-label">Stock Count<sup>*</sup></label>
                            <div>
                                <input type="number" name="tstock" id="tstock" class="input_style" placeholder="Enter Stock Count" required>
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <label for="exp_date" class="form-label">Expiry Date</label>
                            <div>
                                <input type="date" name="expiry_date" id="exp_date" class="input_style" placeholder="Enter Expiry Date" required>
                            </div>
                        </div> -->
                        
                        <div class="form-div">
                            <label for="supplier_id" class="form-label"><?php echo $vendor;?> Name<sup>*</sup></label>
                            <div>
                            <select name="supplier_id" class="input_style">
                                    <option value=""  class="option_style" disabled selected>Select <?php echo $vendor;?></option>
                                    <?php
                                     $vendor = $mysqli->query("select * from e_vendor_details where cos_id = '$cos_id' and active=1");
                                    while($row = $vendor->fetch_assoc())
                                    {
	                                ?>
                                        <option value="<?php echo $row['v_id'];?>" <?php if($row['v_id']==$data['supplier_id']){
                                            echo "selected";
                                        } ?>><?php echo $row['v_name'];?></option>
	                                <?php 
                                    }	
									?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <h3 class="product_stock">Spread:<div class="exist_stock" id="spread"></div></h3>
                        </div>
                        <div class="form-div">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <div>
                                <input type="text" name="invoice_no" class="input_style" placeholder="Enter Invoive No">
                                <!-- <span class="rupees_symbol">₹</span> -->
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="mrp" class="form-label">MRP</label>
                            <div>
                                <input type="number" name="mrp" id="mrp" class="input_style" placeholder="Enter MRP" value="<?php echo $data['s_mrp'];?>">
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="inprice" class="form-label">In-Price</label>
                            <div>
                                <input type="number" name="in_price" class="input_style" placeholder="Enter In-Price"  id="inprice" value="<?php echo $data['in_price'];?>">
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="outprice" class="form-label">Price</label>
                            <div>
                                <input type="number" name="out_price" class="input_style" placeholder="Enter Price" value="<?php echo $data['s_out_price'];?>" id="outprice">
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Upload Bill Image</span>
                                <input type="file" id="stock_bill" class="img_upload" name="stock_bill">
                            </div>
                            <div>
                                <img id="previewImage"  src="" width="100px"/>
                            </div>
                        </div>
                        <script>
                            document.getElementById('stock_bill').addEventListener('change', function(event){
                                const file = event.target.files[0];
                                const reader = new FileReader();
                                reader.onload = function(event){
                                    document.getElementById('previewImage').src = event.target.result;
                                };
                                reader.readAsDataURL(file);
                            });
                        </script>
                       
                    </div>
                    <div class="add_btnDiv">
                        <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                        <input type="submit" value="Add Stock" class="add_btn" name="stock_add">
                    </div>
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
    <script>
        function updateSpread() {
        let inPrice = parseFloat(document.getElementById('inprice').value);
        let outPrice = parseFloat(document.getElementById('outprice').value);
        if (isNaN(inPrice)) inPrice = 0;
        if (isNaN(outPrice)) outPrice = 0;
        let spread = ((outPrice - inPrice) * 100 / inPrice).toFixed();
        if(isNaN(spread)){
            document.getElementById('spread').innerText = 0;
        }else{
            document.getElementById('spread').innerText = spread;
        }
    }
    document.getElementById('inprice').addEventListener('input', updateSpread);
    document.getElementById('outprice').addEventListener('input', updateSpread);
    updateSpread();
    </script>
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
</body>
</html>