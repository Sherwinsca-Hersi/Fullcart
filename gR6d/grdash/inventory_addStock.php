<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Stock</title>
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
    <div class="addproduct_rightbar container">
    <?php 
        if(isset($_GET['productid']) && isset($_GET['batch_id'])){
            ?>
            <h2>Adjust Stock</h2><br><br>
    <?php
        }
        else{
        ?>
            <h2>Add Stock</h2><br><br>
        <?php
        }
    ?>
        <?php
        if(isset($_GET['productid']) && isset($_GET['batch_id'])){
            $stock_data = $mysqli->query("SELECT pd.p_title,pd.p_variation,pd.unit,ps.s_batch_no,ps.qty,ps.s_expiry_date,ps.supplier_id,
                                        ps.s_mrp,ps.in_price,ps.s_out_price,ps.invoice_no,ps.stock_bill,pp.per_g
                                        FROM e_product_details pd,e_product_stock ps,e_product_price pp
                                        WHERE pd.id = ps.s_product_id AND ps.s_product_id = pp.product_id AND pp.product_id=pd.id 
                                        AND pp.batch_no=ps.s_batch_no AND pp.cos_id=ps.cos_id AND pd.active=1
                                        AND pd.cos_id='$cos_id' AND ps.active=1 AND  pd.id='".$_GET['productid']."' AND ps.s_batch_no='".$_GET['batch_id']."'")->fetch_assoc();
        ?>
       <div class="product_details">
        <?php $product_details=$mysqli->query("SELECT p_title,id,p_variation,unit,is_loose FROM `e_product_details` WHERE cos_id='$cos_id' AND active=1 AND id=".$_GET['productid']." GROUP BY id")->fetch_assoc(); ?>
            <h2><?php echo $product_details['p_title']." ".$product_details['p_variation']." ".$product_details['unit'];?></h2>
            <h2 class="product_stock">Existing Stock:<div class="exist_stock">
                <?php echo $data['qty_left'] ?? 'N/A'; ?>
            </div></h2>
            
        </div>
    <div class="product_form">
        <form class="addproduct_form" method="post" action="com_ins_upd.php"  enctype="multipart/form-data" autocomplete="off"  id="myForm" onsubmit="return validateForm()">
            <div class="grid_col">
                <div class="grid-col-1">
                <div class="form-div">
                            <label for="batch_no" class="form-label">Batch No<span class="star">*</span></label>
                            <div>
                                <div class="batch_div">
                                    <input type="text" name="batch_no" class="input_style" placeholder="Enter Batch No" id="batch_no" value="<?php echo $stock_data['s_batch_no'];?>" maxlength="6" required>
                                    <button type="button" class="barcode_btn" id="generateBatchNo"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        <script>
    function generateBatchNumber() {
        var length = 6;
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var batchNumber = '';
        for (var i = 0; i < length; i++) {
            var randomIndex = Math.floor(Math.random() * characters.length);
            batchNumber += characters[randomIndex];
        }
        return batchNumber;
    }

    document.getElementById("generateBatchNo").addEventListener("click", function() {
        var batchNumber = generateBatchNumber();
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "checkBatchNo.php?batchNo=" + batchNumber, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText === "exists") {
                    console.log("Batch number already exists. Generating a new one...");
                    document.getElementById("batchNo").value = "";
                    document.getElementById("generateBatchNo").click();
                } else {
                    console.log("Batch number doesn't exist. Proceeding...");
                    document.getElementById("batch_no").value = batchNumber;
                }
            }
        };
        xhr.send();
    });
</script>
                    </div>
                    
                    <div class="form-div">
                        <label for="tstock" class="form-label">Stock Count <?php $product_details['is_loose'] == 1 ? '(in Kg)' : '' ?></label>
                        <div>
                            <input type="number" name="tstock" id="tstock" class="input_style" placeholder="Enter Stock Count"  value="<?php echo $stock_data['qty'];?>" oninput="this.value = this.value.slice(0, 7);" required>
                        </div>
                    </div>
                    <?php 
                        if($product_details['is_loose'] ==1 ){
                            $p_perkg= $stock_data['per_g']*1000;
                            ?>
                            <div class="form-div">
                                <label for="p_perkg" class="form-label">Price For (1 kg)</label>
                                <div>
                                    <input  type="text" oninput="validateDecimal(this)" maxlength="10"  name="p_perkg" id="p_perkg" class="input_style" placeholder="Enter Price Per kg" value="<?php echo $p_perkg;?>"  required>
                                </div>
                            </div>
                        <?php 
                        }
                    ?>
                    <div class="form-div">
                        <label for="exp_date" class="form-label">Expiry Date</label>
                        <div>
                            <input type="date" name="expiry_date" id="exp_date" class="input_style" placeholder="Enter Expiry Date" value="<?php echo $stock_data['s_expiry_date'];?>" required>
                        </div>
                    </div>
                    
                    <div class="form-div">
                        <label for="supplier_id" class="form-label">Supplier Name</label>
                        <div>
                        <select name="supplier_id" class="input_style" required>
                                <option value=""  class="option_style" disabled selected>Select Supplier</option>
                                <?php
                                 $vendor = $mysqli->query("select `v_id`, `cos_id`, `v_name`, `business_name`, `contact_person`,
                                  `v_mobile`, `v_whatsapp`, `gst_no`, `v_address`, `active`  from e_vendor_details where cos_id = '$cos_id' and active=1");
                                while($row = $vendor->fetch_assoc())
                                {
                                ?>
                                    <option value="<?php echo $row['v_id'];?>" <?php if($row['v_id']==$stock_data['supplier_id']){
                                        echo "selected";
                                    } else{
                                        echo "";
                                    }?>><?php echo $row['v_name'];?></option>
                                <?php 
                                }	
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="grid-col-2">
                    <div class="form-div">
                        <label for="mrp" class="form-label" id="mrp_level">MRP(Per Product)</label>
                        <div>
                            <input type="number" name="mrp" id="mrp" class="input_style" placeholder="Enter MRP" value="<?php echo $stock_data['s_mrp'];?>" required>
                            <span class="rupees_symbol">₹</span>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="inprice" class="form-label">In-Price</label>
                        <div>
                            <input type="number" name="in_price" class="input_style" placeholder="Enter In-Price"  id="inprice" value="<?php echo $stock_data['in_price'];?>" required>
                            <span class="rupees_symbol">₹</span>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="outprice" class="form-label">Out-Price</label>
                        <div>
                            <input type="number" name="out_price" class="input_style" placeholder="Enter Out-Price" value="<?php echo $stock_data['s_out_price'];?>" id="outprice" required>
                            <span class="rupees_symbol">₹</span>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="invoice_no" class="form-label">Invoice No</label>
                        <div>
                            <input type="text" name="invoice_no" class="input_style" placeholder="Enter Invoive No" value="<?php echo $stock_data['invoice_no'];?>">
                        </div>
                    </div>
                    <div class="img_input">
                        <div class="file_upload">
                            <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                            <span>Search Image to Upload</span>
                            <input type="file" id="stock_bill" class="img_upload" name="stock_bill">
                        </div>
                        <div>
                            <img id="previewImage" src="../<?php echo $stock_data['stock_bill'];?>"  width="100px"/>
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
                
            </div>
            <div class="add_btnDiv">
                    <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                    <input type="hidden" name="stock_batch_id" value="<?php echo isset($_GET['batch_id']) ? htmlspecialchars($_GET['batch_id']) : ''; ?>">
                    <input type="hidden" name="stock_purchase_id" value="<?php echo isset($_GET['purchase_id']) ? htmlspecialchars($_GET['purchase_id']) : ''; ?>">
                    <input type="submit" value="Update Stock" class="add_btn" name="stock_update">
                </div>
        </form>
        <?php
        }
            else if(isset($_GET['productid']))
			{
				$data = $mysqli->query("SELECT pd.p_title,pd.p_variation,pd.unit,is_loose,ps.in_price,ps.s_out_price,ps.s_mrp,ps.supplier_id,pd.reorder_level,pd.emergency_level,pp.qty_left FROM `e_product_details` as pd join `e_product_stock` as ps join `e_product_price` as pp on pd.cos_id='$cos_id' and pd.id=".$_GET['productid']." and ps.s_batch_no=pp.batch_no and pd.active=1 WHERE pd.cos_id = ps.cos_id and ps.cos_id = pp.cos_id and pd.active=ps.active and ps.active=pp.active and pd.id=ps.s_product_id and ps.s_product_id=pp.product_id ORDER BY ps.updated_ts LIMIT 1;")->fetch_assoc();
			?>
            
            <div class="product_details">
            <?php $product_details=$mysqli->query("SELECT p_title,id,p_variation,unit,is_loose FROM `e_product_details` WHERE cos_id='$cos_id' AND active=1 AND id=".$_GET['productid']." GROUP BY id")->fetch_assoc(); ?>
                <h2><?php echo $product_details['p_title']." ".$product_details['p_variation']." ".$product_details['unit'];?></h2>
                <h2 class="product_stock">Existing Stock:<div class="exist_stock">
                    <?php echo $data['qty_left']??'N/A';?>
                </div></h2>
                
            </div>
        <div class="product_form">
            <form class="addproduct_form" method="post" action="com_ins_upd.php"  enctype="multipart/form-data" autocomplete="off"  id="myForm" onsubmit="return validateForm()">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="batch_no" class="form-label">Batch No<span class="star">*</span></label>
                            <div>
                                <div class="batch_div">
                                    <input type="text" name="batch_no" class="input_style" placeholder="Enter Batch No" id="batch_no"  required>
                                    <button type="button" class="barcode_btn" id="generateBatchNo"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        <script>
    function generateBatchNumber() {
        // Function to generate a random alphanumeric string of specified length
        var length = 6; // Define the length of the batch number
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        var batchNumber = '';
        for (var i = 0; i < length; i++) {
            var randomIndex = Math.floor(Math.random() * characters.length);
            batchNumber += characters[randomIndex];
        }
        return batchNumber;
    }

    document.getElementById("generateBatchNo").addEventListener("click", function() {
        var batchNumber = generateBatchNumber();
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "checkBatchNo.php?batchNo=" + batchNumber, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText === "exists") {
                    console.log("Batch number already exists. Generating a new one...");
                    document.getElementById("batchNo").value = "";
                    document.getElementById("generateBatchNo").click(); 
                } else {
                    console.log("Batch number doesn't exist. Proceeding...");
                    document.getElementById("batch_no").value = batchNumber;
                }
            }
        };
        xhr.send();
    });
</script>
                        </div>
                        <div class="form-div">
                            <label for="tstock" class="form-label">Stock Count<?php echo $product_details['is_loose'] == 1 ?"(in Kg)" : ""; ?></label>
                            <div>
                                <input type="number" name="tstock" id="tstock" class="input_style" placeholder="Enter Stock Count"  oninput="this.value = this.value.slice(0, 7);" required>
                            </div>
                        </div>
                        <?php 
                        if($product_details['is_loose'] ==1 ){
                            ?>
                            <div class="form-div">
                                <label for="p_perkg" class="form-label">Price For(1 kg)</label>
                                <div>
                                    <input type="text" oninput="validateDecimal(this)" maxlength="10" name="p_perkg" id="p_perkg" class="input_style" placeholder="Enter Price Per kg" required>
                                </div>
                            </div>
                        <?php 
                        }
                        ?>
                        <div class="form-div">
                            <label for="exp_date" class="form-label">Expiry Date</label>
                            <div>
                                <input type="date" name="expiry_date" id="exp_date" class="input_style" placeholder="Enter Expiry Date" required>
                            </div>
                        </div>
                        
                        <div class="form-div">
                            <label for="supplier_id" class="form-label">Supplier Name</label>
                            <div>
                            <select name="supplier_id" class="input_style" required>
                                <option value=""  class="option_style" disabled selected>Select Supplier</option>
                                
                                <?php
                                 $vendor = $mysqli->query("select `v_id`, `cos_id`, `v_name`, `business_name`, `contact_person`, `v_mobile`, `v_whatsapp`, `gst_no`, `v_address`, `active`  from e_vendor_details where cos_id = '$cos_id' and active=1");
                                while($row = $vendor->fetch_assoc())
                                {
                                    if($data['supplier_id']==NULL || ''){
                                        ?>
                                        <option value="<?php echo $row['v_id'];?>"><?php echo $row['v_name'];?></option>
                                    <?php
                                    }else{
                                        ?>
                                        <option value="<?php echo $row['v_id'];?>" <?php 
                                    
                                        if($row['v_id']==$data['supplier_id']){
                                            echo "selected";
                                        } else{
                                            echo "";
                                        }?>><?php echo $row['v_name'];?></option>
                                    <?php
                                    }
                                }
                                ?>
                            </select>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="mrp" class="form-label" id="mrp_level">MRP(Per Product)</label>
                            <div>
                                <input type="number" name="mrp" id="mrp" class="input_style" placeholder="Enter MRP" value="<?php echo $data['s_mrp'];?>" required>
                                <span class="rupees_symbol">₹</span>
                                <small id="mrp-error">MRP Cannot be 0</small>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="inprice" class="form-label">In-Price</label>
                            <div>
                                <input type="number" name="in_price" class="input_style" placeholder="Enter In-Price"  id="inprice" value="<?php echo $data['in_price'];?>" required>
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="outprice" class="form-label">Out-Price</label>
                            <div>
                                <input type="number" name="out_price" class="input_style" placeholder="Enter Out-Price" value="<?php echo $data['s_out_price'];?>" id="outprice" required>
                                <span class="rupees_symbol">₹</span>
                                <small id="price-error">Out-Price must be less than or equal to MRP</small>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <div>
                                <input type="text" name="invoice_no" class="input_style" placeholder="Enter Invoive No">
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="stock_bill" class="img_upload" name="stock_bill">
                            </div>
                            <div>
                                <img id="previewImage"   width="100px"/>
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
                    
                </div>
                <div class="add_btnDiv">
                        <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                        <input type="submit" value="Add Stock" class="add_btn" name="stock_add">
                    </div>
            </form>
            <?php 
            }
            else{
                ?>
                <form class="addproduct_form" method="post" action="com_ins_upd.php"  enctype="multipart/form-data" autocomplete="off"  id="myForm" onsubmit="return validateForm()">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <!-- <div class="form-div">
                            <label for="pname" class="form-label">Product Name</label>
                            <div>
                                <input type="text" name="p_title" class="input_style"  placeholder=" Enter Product Name" value="<?php echo $data['p_title'];?>" disabled>
                            </div>
                        </div> -->
                        <!-- <div class="form-div">
                            <label for="pname" class="form-label">Product Name</label>
                            <div>
                                <select name="pname" class="input_style" placeholder="Enter Product Name" id="product" onchange="updateURL()" required>
                                    <option value=""  class="option_style" disabled selected>Select Product Name</option>
                                    <?php
                                     $product = $mysqli->query("select id,p_title,p_variation,unit FROM `e_product_details` as pd join  `e_product_stock` as ps on ps.s_product_id=pd.id and ps.active=1 and ps.cos_id='$cos_id' where ps.cos_id = pd.cos_id and pd.active=ps.active");
                                    while($row = $product->fetch_assoc())
                                    {
	                                ?>
	                                    <option value="<?php echo $row['id'];?>"><?php echo $row['p_title']." ".$row['p_variation']." ".$row['unit'];?></option>
	                                    <?php 
                                        }	
									   ?>
                                </select>
                            </div>
                        </div> -->
                        <div class="form-div">
    <label for="pname" class="form-label">Product Name<span class="star">*</span></label>
    <!-- <div> -->
        <div class="search-container">
            <input type="text" placeholder="Search..." class="input_style search-box" name="pname" required>
            <div id="dropdown" class="dropdown">
                <!-- Suggestions will be dynamically added here -->
            </div>
            <input type="hidden" id="product-id" name="product-id">
            <!-- other form fields -->
        </div>
    <!-- </div> -->
</div>
                        <script>
        function updateURL() {
            var selectedValue = document.getElementById('product').value;
            var newURL = window.location.origin + window.location.pathname + '?productid=' + selectedValue;
            window.location.href = newURL;
        }
        function retainSelectedValue() {
            var params = new URLSearchParams(window.location.search);
            var selectedValue = params.get('productid');
            if (selectedValue) {
                document.getElementById('product').value = selectedValue;
            }
        }

        // Call retainSelectedValue on page load
        window.onload = retainSelectedValue;
    </script>
    
                        <div class="form-div">
                            <label for="batch_no" class="form-label">Batch No<span class="star">*</span></label>
                            <div>
                                <div class="batch_div">
                                    <input type="text" name="batch_no" class="input_style" placeholder="Enter Batch No" id="batch_no" maxlength="6" required>
                                    <button type="button" class="barcode_btn" id="generateBatchNo"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        <script>
    function generateBatchNumber() {
        // Function to generate a random alphanumeric string of length 6 with uppercase letters and numbers
        var length = 6; // Define the length of the batch number
        var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Uppercase letters and numbers only
        var batchNumber = '';
        for (var i = 0; i < length; i++) {
            var randomIndex = Math.floor(Math.random() * characters.length);
            batchNumber += characters[randomIndex];
        }
        return batchNumber;
    }

    document.getElementById("generateBatchNo").addEventListener("click", function() {
        var batchNumber = generateBatchNumber();
        var xhr = new XMLHttpRequest();
        xhr.open("GET", "checkBatchNo.php?batchNo=" + batchNumber, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText === "exists") {
                    console.log("Batch number already exists. Generating a new one...");
                    document.getElementById("batchNo").value = "";
                    document.getElementById("generateBatchNo").click();
                } else {
                    console.log("Batch number doesn't exist. Proceeding...");
                    document.getElementById("batch_no").value = batchNumber;
                }
            }
        };
        xhr.send();
    });
</script>
                    </div>
                        <div class="form-div">
                            <label for="mrp" class="form-label" id="mrp_level">MRP(Per Product)<span class="star">*</span></label>
                            <div>
                                <input type="number" name="mrp" id="mrp" class="input_style" placeholder="Enter MRP" required>
                                <span class="rupees_symbol">₹</span>
                                <small id="mrp-error">MRP Cannot be 0</small>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="inprice" class="form-label">In-Price<span class="star">*</span></label>
                            <div>
                                <input type="number" name="in_price" class="input_style" placeholder="Enter In-Price"  id="inprice"  required>
                                <span class="rupees_symbol">₹</span>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="outprice" class="form-label">Out-Price<span class="star">*</span></label>
                            <div>
                                <input type="number" name="out_price" class="input_style" placeholder="Enter Out-Price" id="outprice" required>
                                <span class="rupees_symbol">₹</span>
                                <small id="price-error">Out-Price must be less than or equal to MRP</small>
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <h3 class="product_stock">Spread:<div class="exist_stock" id="spread"></div></h3>
                        </div> -->
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="tstock" class="form-label">Stock Count<span class="star">*</span></label>
                            <div>
                                <input type="number" name="tstock" id="tstock" class="input_style" placeholder="Enter Stock Count" oninput="this.value = this.value.slice(0, 7);" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="exp_date" class="form-label">Expiry Date<span class="star">*</span></label>
                            <div>
                                <input type="date" name="expiry_date" id="exp_date" class="input_style" placeholder="Enter Expiry Date" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="supplier_id" class="form-label no-sup">Supplier Name</label>
                            <div>
                            <select name="supplier_id" class="input_style" required>
                                    <option value=""  class="option_style" disabled selected>Select Supplier</option>
                                    <?php
                                     $vendor = $mysqli->query("select `v_id`, `cos_id`, `v_name`, `business_name`, `contact_person`, `v_mobile`, `v_whatsapp`, `gst_no`, `v_address`, `active`  from e_vendor_details where cos_id = '$cos_id' and active=1");
                                    while($row = $vendor->fetch_assoc())
                                    {
	                                ?>
                                        <option value="<?php echo $row['v_id'];?>"><?php echo $row['v_name'];?></option>
	                                <?php 
                                    }	
									?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="invoice_no" class="form-label no-sup">Invoice No</label>
                            <div>
                                <input type="text" name="invoice_no" class="input_style" placeholder="Enter Invoive No">
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="stock_bill" class="img_upload" name="stock_bill">
                            </div>
                            <div>
                                <img id="previewImage" width="100px"/>
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
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                    <input type="submit" value="Add Stock" class="add_btn" name="stock_add">
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

    <?php
        if(isset($_SESSION['success'])): 
        ?>
        <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
        <script>
            iziToast.success({
                title: 'Success',
                message: '<?php echo $_SESSION['success']; ?>',
                position: 'bottomCenter',
                timeout: 5000
            });
        </script>
        <?php
        unset($_SESSION['success']);
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
    <!-- <script>
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
    </script> -->


<?php
$product_name=[];
$product = $mysqli->query("SELECT `id`,`p_title`, `p_variation`,`unit` FROM `e_product_details` WHERE cos_id='$cos_id' AND active=1 GROUP BY id");
while($row = $product->fetch_assoc()){
    $product_name[]=$row; 
}
?>

    <script>
    var products = <?php echo json_encode($product_name); ?>;
    console.log(products);
    var currentFocus = -1;

    function updateURL(productId) {
        var newURL = window.location.origin + window.location.pathname + '?productid=' + productId;
        window.history.replaceState({}, '', newURL);
        window.location.reload(); // Reload the page after updating URL
    }

    function searchProducts(searchTerm) {
    // Filter products based on search term
    // var filteredProducts = products.filter(function(product) {
    //     return product.p_title.toLowerCase().includes(searchTerm.toLowerCase());
    // });

    const filteredProducts = products.filter(product => {
        const title = product.p_title ? product.p_title.toLowerCase() : '';
        const variation = product.p_variation ? product.p_variation.toLowerCase() : '';
        const unit = product.unit ? product.unit.toLowerCase() : '';

        return title.includes(searchTerm.toLowerCase()) ||
                variation.includes(searchTerm.toLowerCase()) ||
                unit.includes(searchTerm.toLowerCase());
    });

    // Display filtered products in dropdown
    var dropdown = document.getElementById('dropdown');
    dropdown.innerHTML = '';

    if (filteredProducts.length === 0) {
        // Display 'No products found' if there are no matching products
        var noResults = document.createElement('div');
        noResults.textContent = 'No products found';
        noResults.classList.add('dropdown-item', 'no-results');
        dropdown.appendChild(noResults);
    } else {
        // If matching products are found, display them in the dropdown
        filteredProducts.forEach(function(product, index) {
            var variation = product.p_variation ? product.p_variation : '';
            var unit = product.unit ? product.unit : '';
            var option = document.createElement('div');
            option.textContent = product.p_title + " " + variation + " " + unit;
            option.classList.add('dropdown-item');
            option.setAttribute('data-index', index);
            option.addEventListener('click', function() {
                document.querySelector('.search-box').value = product.p_title + " " + variation + " " + unit;
                document.getElementById('product-id').value = product.id;
                updateURL(product.id); // Update URL and reload page
                dropdown.innerHTML = ''; // Clear dropdown after selection
                dropdown.style.display = 'none'; // Hide dropdown after selection
            });
            dropdown.appendChild(option);
        });
    }

    // Show dropdown after the search results are appended
    dropdown.style.display = 'block';
}

function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (items.length - 1);
        items[currentFocus].classList.add('active');

        // Scroll the active item into view
        items[currentFocus].scrollIntoView({
            behavior: 'smooth',
            block: 'nearest',
        });
    }

    function removeActive(items) {
        for (var i = 0; i < items.length; i++) {
            items[i].classList.remove('active');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        var searchBox = document.querySelector('.search-box');
        searchBox.addEventListener('input', function() {
            var searchTerm = this.value.trim();
            if (searchTerm.length >= 2) { // Adjust minimum characters for search
                searchProducts(searchTerm);
            } else {
                var dropdown = document.getElementById('dropdown');
                dropdown.innerHTML = ''; // Clear dropdown if search term is less than 2 characters
                dropdown.style.display = 'none'; // Hide dropdown if no search term
            }
        });

        searchBox.addEventListener('keydown', function(e) {
            var dropdown = document.getElementById('dropdown');
            var items = dropdown.getElementsByClassName('dropdown-item');
            if (e.key === 'ArrowDown') {
                currentFocus++;
                addActive(items);
            } else if (e.key === 'ArrowUp') {
                currentFocus--;
                addActive(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentFocus > -1) {
                    if (items) items[currentFocus].click();
                }
            }
        });

        // Hide dropdown when clicking outside of it
        document.addEventListener('click', function(event) {
            if (!document.getElementById('dropdown').contains(event.target)) {
                document.getElementById('dropdown').style.display = 'none';
            }
        });

        // Retain selected product ID on page load if present in URL
        var params = new URLSearchParams(window.location.search);
        var productId = params.get('productid');
        if (productId) {
            // Fetch product details based on productId if needed
            document.getElementById('product-id').value = productId;
            // Implement logic to show selected product name in search box if needed
        }
    });
    // document.addEventListener("DOMContentLoaded", function () {
    // const stockInput = document.getElementById("tstock");
    // const outPriceInput = document.getElementById("outprice");
    // const pPerKgInput = document.getElementById("p_perkg");

    // function calculatePricePerKg() {
    //     const StockValue = parseFloat(stockInput.value) || 0;
    //     const outPriceValue = parseFloat(outPriceInput.value) || 0; 
    //     const pricePerKg = outPriceValue / StockValue;
        
    //     // Update the p_perkg field
    //     pPerKgInput.value = pricePerKg > 0 ? pricePerKg.toFixed(2) : ''; 
    // }

    // // Trigger calculation on input change
    // outPriceInput.addEventListener("input", calculatePricePerKg);
    // stockInput.addEventListener("input", calculatePricePerKg);

    // // Trigger calculation on page load if values already exist
    // calculatePricePerKg();
    // });


    document.addEventListener("DOMContentLoaded", function () {
    const stockInput = document.getElementById("tstock");
    const outPriceInput = document.getElementById("outprice");
    const pPerKgInput = document.getElementById("p_perkg");
     const mrplabel = document.getElementById("mrp_level");
    


    function calculateOutprice() {
        const StockValue = parseFloat(stockInput.value) || 0;
        const pricePerKg = parseFloat(pPerKgInput.value) || 0;
        const outPriceValue = StockValue * pricePerKg; 

        outPriceInput.readOnly="true";
        outPriceInput.style.backgroundColor = '#D3D3D3';
        outPriceInput.value = outPriceValue; 
        mrplabel.innerText =`MRP For (${StockValue} Kg)`;
    }

    // Trigger calculation on input change
    pPerKgInput.addEventListener("input", calculateOutprice);
    stockInput.addEventListener("input", calculateOutprice);

    // Trigger calculation on page load if values already exist
    calculateOutprice();
    });

</script>

<!-- Outprice & MRP Validation -->

<script>
function validatePrice() {
    var mrp = parseFloat(document.getElementById('mrp').value) || 0;
    var outPrice = parseFloat(document.getElementById('outprice').value) || 0;
    var errorMrp = document.getElementById('mrp-error');
    var errorPrice = document.getElementById('price-error');

    errorMrp.style.display = 'none';
    errorMrp.textContent = '';
    document.getElementById('mrp').setCustomValidity('');

    errorPrice.style.display = 'none';
    errorPrice.textContent = '';
    document.getElementById('outprice').setCustomValidity('');

    if (mrp === 0) {
        errorMrp.style.display = 'block';
        errorMrp.textContent = 'MRP cannot be 0';
        document.getElementById('mrp').setCustomValidity('MRP cannot be 0');
        return false;
    }


    if (outPrice > mrp) {
        errorPrice.style.display = 'block';
        errorPrice.textContent = 'Out-Price must be less than or equal to MRP';
        document.getElementById('outprice').setCustomValidity('Out-Price must be less than or equal to MRP');
        return false;
    }

    return true;
}

document.getElementById('mrp').addEventListener('input', validatePrice);
document.getElementById('outprice').addEventListener('input', validatePrice);

document.getElementById('myForm').addEventListener('submit', function (e) {
    if (!validatePrice()) {
        e.preventDefault();
    }
});
</script>
<script>

function validateDecimal(input) {
    let value = input.value;

    value = value.replace(/[^0-9.]/g, '');

    const parts = value.split('.');
    if (parts.length > 2) {
        value = parts[0] + '.' + parts[1];
    }

    if (value.startsWith('.')) {
        value = '0' + value;
    }
    value = value.replace(/^0+(?!\.)/, '');

    value = value.slice(0, 10);
    input.value = value;
}

</script>
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>