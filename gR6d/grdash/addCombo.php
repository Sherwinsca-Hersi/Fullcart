<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    require_once '../api/header.php';
    ?>
    <title>Add <?php echo $combo;?></title>
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
            if(isset($_GET['comboid'])){
                echo  "<h2> Update $combo</h2>";
            }
            else{
                echo "<h2> Add $combo</h2>";
            }
        ?>
        <div class="combo_form">
        <?php 
			if(isset($_GET['comboid']))
			{
				$data = $mysqli->query("SELECT  dc.`id`, dc.`cos_id`, dc.`sku_id`,
                dc.`p_id`, dc.`offer_amt`,dc.`title`, dc.`c_img`, dc.`from_qty`, 
                dc.`to_qty`, dc.`bulk_price`,dc.`active`, pc.`id`, pc.`cos_id`,
                pc.`c_id`, pc.`prod_id`, pc.`offer_amt`, pc.`qty`,pc.`active` FROM 
                e_data_collection dc JOIN e_product_collection_map pc ON dc.id = pc.c_id 
                AND dc.cos_id = '$cos_id' AND pc.cos_id = dc.cos_id WHERE dc.id = {$_GET['comboid']} 
                AND dc.active = 1 AND pc.active = 1 ORDER BY dc.id DESC")->fetch_assoc();
			?>
            <form class="combo_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()"  enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="combo_name" class="form-label"><?php echo $combo;?> Product Name</label>
                            <div>
                                <input type="text" name="combo_name" class="input_style" value="<?php if(!($data['title']==NULL || '')) {echo $data['title']; }?>" placeholder=" Enter <?php echo $combo;?> Product Name"  maxlength="60" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="sku_id" class="form-label">Model No</label>
                            <div>
                                <input type="text" name="sku_id"  class="input_style" placeholder="Enter Model No"  value="<?php if(!($data['sku_id']==NULL || '')) {echo $data['sku_id']; }?>"  maxlength="10" required>
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Upload Image</span>
                                <input type="file" id="combo_img" class="img_upload" name="combo_img">
                            </div>
                            <div>
                                <?php
                                    if(!($data['c_img']==NULL || '')) {
                                ?>
                                    <img id="previewImage"  src="../<?php echo $data['c_img'];?>"  width="100px"/>
                                <?php
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="addSub_head">
                        <div class="combo_head_flex">
                            <h3><?php echo $combo;?> Bulk Price</h3>
                        </div>
                        <div class="bulk_price_div">
    <div id="bulk_price">
        <?php
        if ($data['from_qty']=='') {
            ?>
            <div class="grid-col-3" id="cloneBulkPrice">
                <div class="form-div">
                    <label for="f_quant" class="form-label">From Qty</label>
                    <div>
                        <input type="number" name="f_quant[]" id="f_quant" class="input_style" placeholder="Enter Quantity" maxlength="10">
                    </div>
                </div>
                <div class="form-div">
                    <label for="t_quant" class="form-label">To Qty</label>
                    <div>
                        <input type="number" name="t_quant[]" id="t_quant" class="input_style" placeholder="Enter Quantity" maxlength="10">
                    </div>
                </div>
                <div class="form-div">
                    <label for="price" class="form-label">Price</label>
                    <div>
                        <input type="number" name="price[]" id="Outprice" class="input_style" placeholder="Enter Price" maxlength="15">
                    </div>
                </div>
                <div class="form-div">
                    <div class="del_button">
                        <img src="../assets/images/delete_icon.png"  alt="delete-icon-img" class="deleteButton">
                    </div>
                </div>
            </div>
            <?php
        } else {
                $from_qtys = explode(",", $data['from_qty']); 
                $to_qtys = explode(",", $data['to_qty']);     
                $prices = explode(",", $data['bulk_price']);       
            foreach ($from_qtys as $index => $fqty):
                $to_qty = isset($to_qtys[$index]) ? $to_qtys[$index] : '';
                $bulk_price = isset($prices[$index]) ? $prices[$index] : '';
                ?>
                <div class="grid-col-3" id="cloneBulkPrice">
                    <div class="form-div">
                        <label for="f_quant" class="form-label">From Qty</label>
                        <div>
                            <input type="number" name="f_quant[]" value="<?php if(!($fqty==NULL || '')) { echo $fqty; } ?>" id="f_quant" class="input_style" placeholder="Enter Quantity" maxlength="10">
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="t_quant" class="form-label">To Qty</label>
                        <div>
                            <input type="number" name="t_quant[]" value="<?php if(!($to_qty==NULL || '')) { echo $to_qty;} ?>" id="t_quant" class="input_style" placeholder="Enter Quantity" maxlength="10">
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="price" class="form-label">Price</label>
                        <div>
                            <input type="number" name="price[]" value="<?php if(!($bulk_price==NULL || '')) { echo $bulk_price;} ?>" id="Outprice" class="input_style" placeholder="Enter Price" maxlength="15">
                        </div>
                    </div>
                    <div class="form-div">
                        <div class="del_button">
                            <img src="../assets/images/delete_icon.png" alt="delete-icon-img" class="deleteButton">
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        }
        ?>
    </div>
    <div>
        <button type="button" class="add_btn" id="addBulkPrice"><i class="fa fa-solid fa-plus"></i>&emsp;Add Bulk Pricing</button>
    </div>
</div>
                    </div>
                    <div class="addSub_head2">
                        <div class="combo_head_flex">
                            <h3><?php echo $combo;?> Products</h3>
                            <p>Total Price of Products in the <?php echo $combo;?><span id="totalOutprice" class="tot_outprice"></span></p>
                        </div>
                        <div class="combo_product_div">
    <div class="combo_product" id="combo_product">
        <?php
        $combo_product_query = $mysqli->query(
            "SELECT id, prod_id, qty, offer_amt, c_id 
             FROM `e_product_collection_map` 
             WHERE active=1 AND c_id=" . $_GET['comboid'] . " AND cos_id='$cos_id'"
        );
        $combo_product = [];
        
        while ($combo_product_table = $combo_product_query->fetch_assoc()) {
            $combo_product[] = $combo_product_table;
        }
        if (empty($combo_product)) {

            ?>
            <div class="grid-col-4" id="cloneComboProduct">
                <div class="form-div">
                    <label for="pname" class="form-label">Product Name</label>
                    <div class="search-container">
                        <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" required>
                        <div id="dropdown" class="dropdown">
                            <!-- Suggestions will be dynamically added here -->
                        </div>
                        <input type="hidden" id="product-id" name="product-id[]" class="product-id">
                        <!-- other form fields -->
                    </div>
                </div>
                <div class="form-div">
                    <label for="quantity" class="form-label">Quantity</label>
                    <div>
                        <input type="number" name="quantity[]" id="quantity" class="input_style" placeholder="Enter Quantity" value="" maxlength="10" required>
                    </div>
                </div>
                <div class="form-div">
                    <label for="Outprice" class="form-label">Price</label>
                    <div>
                        <input type="number" name="Outprice[]" id="Outprice" class="input_style" placeholder="Enter Price" value="" maxlength="15" required>
                    </div>
                </div>
                <div class="form-div">
                    <div class="del_button">
                        <img src="../assets/images/delete_icon.png" alt="delete-icon-img" class="deleteButton">
                    </div>
                </div>
                <div class="form-div">
                    <div>
                        <input type="hidden" name="cp_id[]" id="cp_id" value="">
                    </div>
                </div>
            </div>
            <?php
        } else {
            foreach ($combo_product as $combo_prod) {
                ?>
                <div class="grid-col-4" id="cloneComboProduct">
                    <?php
                    $product = $mysqli->query(
                                    "SELECT pd.id, pd.p_title, pd.p_variation, pd.unit
                                        FROM e_product_details pd
                                        JOIN e_product_stock ps ON ps.s_product_id = pd.id AND ps.active = 1 
                                        AND pd.active = 1 AND pd.cos_id = '$cos_id' AND ps.cos_id = pd.cos_id
                                        WHERE pd.id = {$combo_prod['prod_id']} ORDER BY pd.id DESC;")->fetch_assoc();
                                ?>
                    <div class="form-div">
                        <label for="pname" class="form-label">Product Name</label>
                        <div class="search-container">
                            <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" required value="<?php if(!(($product['p_title'] || $product['p_variation'] || $product['unit'])==NULL || '')){ echo htmlspecialchars($product['p_title'])." ".htmlspecialchars($product['p_variation'])." ".htmlspecialchars($product['unit']); }?>">
                            <div id="dropdown" class="dropdown">
                                <!-- Suggestions will be dynamically added here -->
                            </div>
                            <input type="hidden" id="product-id" name="product-id[]" class="product-id" value="<?php if(!($product['id']==NULL || '')){echo htmlspecialchars($product['id']);} ?>">
                            <!-- other form fields -->
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="quantity" class="form-label">Quantity</label>
                        <div>
                            <input type="number" name="quantity[]" id="quantity" class="input_style" value="<?php if(!($combo_prod['qty']==NULL || '')){ echo $combo_prod['qty']; }?>" maxlength="10" required>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="Outprice" class="form-label">Price</label>
                        <div>
                            <input type="number" name="Outprice[]" id="Outprice" class="input_style  outprice" value="<?php if(!($combo_prod['offer_amt']==NULL || '')){  echo $combo_prod['offer_amt']; }?>" maxlength="15" required>
                        </div>
                    </div>
                    <div class="form-div">
                        <div class="del_button">
                            <img src="../assets/images/delete_icon.png" alt="combo-img" class="deleteButton" data-prod-id="<?php echo $combo_prod['prod_id']; ?>" data-combo-id="<?php echo $_GET['comboid']; ?>" alt="delete-icon-img">
                        </div>
                    </div>
                    <div class="form-div">
                        <div>
                            <input type="hidden" name="cp_id[]" id="cp_id" value="<?php if(!($combo_prod['id']==NULL || '')){echo $combo_prod['id'];} ?>">
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>
                        <div class="add_btnDiv">
                            <button  type="button" class="add_btn combo_add_btn"  id="addProduct"><i class="fa fa-solid fa-plus"></i>&emsp;Add Product</button>
                        </div>
                </div>
                </div>
                <?php 
                    $combo_asproduct_query=$mysqli->query("SELECT id FROM `e_product_details` 
                    WHERE p_title='".$data['title']."' AND  type='2' AND sku_id='".$data['sku_id']."'
                    AND cos_id='$cos_id'")->fetch_assoc();
                    $comboProdId=$combo_asproduct_query['id']??'';
                ?>  
                <div class="add_btnDiv">
                    <input type="hidden" name="combo_prod_id" value="<?php echo $comboProdId; ?>">
                    <input type="hidden" name="comboid" value="<?php echo isset($_GET['comboid']) ? htmlspecialchars($_GET['comboid']) : ''; ?>">
                    <input type="submit" value="Update <?php echo $combo;?>" class="add_btn" name="combo_update">
                </div>
            </form>
            <?php } else{?>
            <form class="combo_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="combo_name" class="form-label"><?php echo $combo;?> Product Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="combo_name" class="input_style" placeholder=" Enter <?php echo $combo;?> Product Name"  maxlength="60" autofocus required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="sku_id" class="form-label">Model No<span class="star">*</span></label>
                            <div>
                                <input type="text" name="sku_id"  class="input_style" placeholder="Enter Model No"  maxlength="10" required>
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Upload Image</span>
                                <input type="file" id="combo_img" class="img_upload" name="combo_img" required>
                            </div>
                            <div>
                                <img id="previewImage"  width="100px"/>
                            </div>
                        </div>
                    </div>
                    <div class="addSub_head">
                        <h3><?php echo $combo;?> Bulk Price</h3>
                        <div class="bulk_price_div">
                            <div  id="bulk_price">
                                <div class="grid-col-3" id="cloneBulkPrice">
                                    <div class="form-div">
                                        <label for="f_quant" class="form-label">From Qty<span class="star">*</span></label>
                                        <div>
                                            <input type="number" name="f_quant[0]" id="f_quant" class="input_style" placeholder="Enter Quantity" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="form-div">
                                        <label for="t_quant" class="form-label">To Qty<span class="star">*</span></label>
                                        <div>
                                            <input type="number" name="t_quant[0]" id="t_quant" class="input_style" placeholder="Enter Quantity" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="form-div">
                                        <label for="price" class="form-label">Price<span class="star">*</span></label>
                                        <div>
                                            <input type="number" name="price[0]" id="Outprice" class="input_style" placeholder="Enter Price" maxlength="15" required>
                                        </div>
                                    </div>
                                    <div class="form-div">
                                        <div class="del_button">
                                            <img src="../assets/images/delete_icon.png" alt="delete-icon" class="deleteButton">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <button type="button" class="add_btn" id="addBulkPrice"><i class="fa fa-solid fa-plus"></i>&emsp;Add Bulk Pricing</button>
                            </div>
                        </div>
                    </div>
                    <div class="addSub_head2">
                        <div class="combo_head_flex">
                            <h3><?php echo $combo;?> Products</h3>
                            <p>Total Price of Products in the <?php echo $combo;?><span id="totalOutprice" class="tot_outprice">₹ 0.00</span></p>
                        </div>
                        <div class="combo_product_div">
                            <div class="combo_product" id="combo_product">
                                <div class="grid-col-4" id="cloneComboProduct">
                                    <div class="form-div">
                                        <label for="pname" class="form-label">Product Name <span class="star">*</span></label>
                                        <div class="search-container">
                                            <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" required>
                                            <div id="dropdown" class="dropdown">
                                                <!-- Suggestions will be dynamically added here -->
                                            </div>
                                            <input type="hidden" id="product-id" name="product-id[]" class="product-id">
                                                <!-- other form fields -->
                                        </div>
                                    </div>
                                    <div class="form-div">
                                        <label for="quantity" class="form-label">Quantity<span class="star">*</span></label>
                                        <div>
                                            <input type="number" name="quantity[0]" id="quantity" class="input_style" placeholder="Enter Quantity" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="form-div">
                                        <label for="Outprice" class="form-label">Price<span class="star">*</span></label>
                                        <div>
                                            <input type="number" name="Outprice[0]" id="Outprice" class="input_style outprice" placeholder="Enter Price" maxlength="10" required>
                                        </div>
                                    </div>
                                    <div class="form-div">
                                        <div class="del_button">
                                            <img src="../assets/images/delete_icon.png" alt="delete-icon-img" class="deleteButton">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>   
                        <div class="add_btnDiv">
                            <button  type="button" class="add_btn combo_add_btn"  id="addProduct"><i class="fa fa-solid fa-plus"></i>&emsp;Add Product</button>
                        </div>
                </div>
                    
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add <?php echo $combo;?>" class="add_btn" name="combo_add">
                </div>
            </form>
            <?php } ?>
        </div>
    </div>
</div>
    <div>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>
<?php
$product_name=[];
$product = $mysqli->query("SELECT `id`,`p_title`, `p_variation`,`unit` FROM `e_product_details` WHERE active=1 and type!=2 and cos_id='$cos_id' ORDER BY p_title");
while($row = $product->fetch_assoc()){
    $product_name[]=$row; 
}
?>
<script>
    document.getElementById('combo_img').addEventListener('change', function(event){
        const file = event.target.files[0];
        const reader = new FileReader();
        reader.onload = function(event){
            document.getElementById('previewImage').src = event.target.result;
        };
        reader.readAsDataURL(file);
    });
                    
    // Search Dropdown

    var products = <?php echo json_encode($product_name); ?>;
    var currentFocus = -1;

    document.addEventListener('DOMContentLoaded', function () {
        initializeSearchBoxes();

        const addBulkPrice = document.getElementById('addBulkPrice');
        const containerBulkPrice = document.getElementById('bulk_price');
        const comboProductContainer = document.getElementById('combo_product');
        const cloneComboProduct = document.getElementById('cloneComboProduct');
        const addProduct = document.getElementById('addProduct');

        addBulkPrice.addEventListener('click', function () {
            const clonedItem = cloneBulkPriceItem();
            containerBulkPrice.appendChild(clonedItem);
        });

        addProduct.addEventListener('click', function () {
            const lastProduct = comboProductContainer.lastElementChild;
            const searchBox = lastProduct.querySelector('.search-box');
            const quantityInput = lastProduct.querySelector('input[name^="quantity"]');
            const priceInput = lastProduct.querySelector('input[name^="Outprice"]');

            if (!validateProductFields(searchBox, quantityInput, priceInput)) {
                alert('Please fill in all fields before adding a new product.');
                return;
            }

            const clonedProduct = cloneProductItem(comboProductContainer.children.length);
            comboProductContainer.appendChild(clonedProduct);
            calculateTotalOutprice();
        });

        calculateTotalOutprice();

        document.querySelectorAll('.outprice').forEach(function (input) {
            input.addEventListener('input', calculateTotalOutprice);
        });

        function initializeSearchBoxes() {
            document.querySelectorAll('.search-box').forEach(function (searchBox) {
                initializeSearchBox(searchBox);
            });
        }

        function initializeSearchBox(searchBox) {
            const dropdown = createDropdownForSearchBox(searchBox);

            searchBox.addEventListener('focus', function () {
                displayDropdownItems(dropdown, products, searchBox);
            });

            searchBox.addEventListener('input', function () {
                const searchTerm = this.value.trim();
                if (searchTerm.length >= 2) {
                    searchProducts(searchTerm, dropdown, searchBox);
                } else {
                    dropdown.innerHTML = '';
                    dropdown.style.display = 'none';
                }
            });

            searchBox.addEventListener('keydown', function (e) {
                const items = dropdown.getElementsByClassName('dropdown-item');
                handleDropdownNavigation(e, items, dropdown, searchBox);
            });

            document.addEventListener('click', function (event) {
                if (!dropdown.contains(event.target) && !searchBox.contains(event.target)) {
                    dropdown.style.display = 'none';
                }
            });
        }

        function createDropdownForSearchBox(searchBox) {
            let dropdown = searchBox.nextElementSibling;
            if (!dropdown || !dropdown.classList.contains('dropdown')) {
                dropdown = document.createElement('div');
                dropdown.classList.add('dropdown');
                searchBox.parentNode.appendChild(dropdown);
            }
            return dropdown;
        }

        function displayDropdownItems(dropdown, data, searchBox) {
            dropdown.innerHTML = '';
            data.forEach(function (product) {
                const option = createDropdownOption(product);
                option.addEventListener('click', function () {
                    selectProduct(option, searchBox, product.id, dropdown);
                });
                dropdown.appendChild(option);
            });
            dropdown.style.display = 'block';
        }

        function searchProducts(searchTerm, dropdown, searchBox) {
            dropdown.innerHTML = '';

            const filteredProducts = products.filter(function (product) {
                return product.p_title.toLowerCase().includes(searchTerm.toLowerCase());
            });

            filteredProducts.forEach(function (product) {
                const option = createDropdownOption(product);
                option.addEventListener('click', function () {
                    selectProduct(option, searchBox, product.id, dropdown);
                });
                dropdown.appendChild(option);
            });

            dropdown.style.display = filteredProducts.length > 0 ? 'block' : 'none';
        }

        function createDropdownOption(product) {
            const variation = product.p_variation ? product.p_variation : '';
            const unit = product.unit ? product.unit : '';
            const option = document.createElement('div');
            option.textContent = `${product.p_title} ${variation} ${unit}`;
            option.classList.add('dropdown-item');
            return option;
        }

        function selectProduct(option, searchBox, productId, dropdown) {
    searchBox.value = option.textContent;
    searchBox.closest('.search-container').querySelector('.product-id').value = productId;

    const deleteButton = searchBox.closest('#cloneComboProduct').querySelector('.deleteButton');
    deleteButton.setAttribute('data-prod-id', productId);

    dropdown.innerHTML = '';
    dropdown.style.display = 'none';
}

        function handleDropdownNavigation(e, items, dropdown, searchBox) {
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                currentFocus++;
                if (currentFocus >= items.length) currentFocus = 0;
                addActive(items, currentFocus);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                currentFocus--;
                if (currentFocus < 0) currentFocus = items.length - 1;
                addActive(items, currentFocus);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (currentFocus > -1) {
                    items[currentFocus].click();
                }
            }
        }

        function addActive(items, currentFocus) {
            if (!items || items.length === 0) return false;
            removeActive(items);
            if (currentFocus >= items.length) currentFocus = 0;
            if (currentFocus < 0) currentFocus = items.length - 1;
            items[currentFocus].classList.add('active');
            items[currentFocus].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
        }

        function removeActive(items) {
            Array.from(items).forEach(function (item) {
                item.classList.remove('active');
            });
        }

        function cloneBulkPriceItem() {
            const clone = document.getElementById('cloneBulkPrice').cloneNode(true);
            const index = containerBulkPrice.children.length;
            const inputs = clone.querySelectorAll('input');

            inputs.forEach(function (input) {
                const name = input.getAttribute('name');
                if (name) {
                    const newName = name.replace(/\[\d+\]/, `[${index}]`);
                    input.setAttribute('name', newName);
                }
                input.value = '';  
            });

            const deleteButton = clone.querySelector('.deleteButton');
            deleteButton.addEventListener('click', function () {
                if (containerBulkPrice.children.length > 1) {
                    containerBulkPrice.removeChild(clone);
                }
            });

            return clone;
        }

        function cloneProductItem(index) {
    const clone = cloneComboProduct.cloneNode(true);
    const searchBox = clone.querySelector('.search-box');
    const inputs = clone.querySelectorAll('input');
    const deleteButton = clone.querySelector('.deleteButton');

    inputs.forEach(function (input) {
        const name = input.getAttribute('name');
        if (name) {
            const newName = name.replace(/\[\d+\]/, `[${index}]`);
            input.setAttribute('name', newName);
        }
        input.value = '';  
    });

    searchBox.value = '';
    deleteButton.removeAttribute('data-prod-id');
    initializeSearchBox(searchBox);

    deleteButton.addEventListener('click', function () {
        if (comboProductContainer.children.length > 1) {
            clone.remove();
            calculateTotalOutprice();
        }
    });

    return clone;
}

        function validateProductFields(searchBox, quantityInput, priceInput) {
            return searchBox.value !== '' && quantityInput.value !== '' && priceInput.value !== '';
        }
    });

    function calculateTotalOutprice() {
            let total = 0;
            document.querySelectorAll('.outprice').forEach(function (input) {
                const value = parseFloat(input.value);
                if (!isNaN(value)) {
                    total += value;
                }
            });
            document.getElementById('totalOutprice').textContent = `₹ ${total.toFixed(2)}`;
        }
        document.getElementById('combo_product').addEventListener('click', function (event) {

    if (event.target.classList.contains('deleteButton')) {
        const combo_prod_id = event.target.getAttribute('data-prod-id');
        const combo_id = event.target.getAttribute('data-combo-id');
        const clonedItem = event.target.closest('#cloneComboProduct');
        const clonedItems = document.querySelectorAll('#combo_product #cloneComboProduct');

    
        if (clonedItems.length > 1) {
            if (combo_prod_id && combo_id) {
               
                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'checkComboProd.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.send(`product_id=${combo_prod_id}&combo_id=${combo_id}`);

                xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4) {
                        if (xhr.status == 200) {
                            try {
                                const response = JSON.parse(xhr.responseText);
                                console.log("Server Response:", response);

                                if (response.exists) {
                                    
                                    var deleteXhr = new XMLHttpRequest();
                                    deleteXhr.open('GET', `deleteComboProd.php?product_cid=${combo_prod_id}&combo_id=${combo_id}`, true);
                                    
                                    deleteXhr.onreadystatechange = function () {
                                        if (deleteXhr.readyState === 4) {
                                            if (deleteXhr.status === 200) {
                                                try {
                                                    const deleteResponse = JSON.parse(deleteXhr.responseText);
                                                    if (deleteResponse.success) {
                                                    
                                                        location.reload();
                                                        console.log("Product deleted successfully.");
                                                    } else {
                                                        alert(`Failed to delete the product from combo: ${deleteResponse.message}`);
                                                        console.error(deleteResponse.message);
                                                    }
                                                } catch (e) {
                                                    console.error("Error parsing JSON from delete response:", e);
                                                    alert("An error occurred while trying to delete the product. Please try again.");
                                                }
                                            } else {
                                                alert("Failed to delete the product from combo. Server responded with an error.");
                                                console.error("Error status:", deleteXhr.status);
                                            }
                                        }
                                    };
                                    deleteXhr.send();
                                } else {
                                    clonedItem.remove();
                                    calculateTotalOutprice();
                                }
                            } catch (e) {
                                console.error("Error parsing JSON:", e);
                                console.error("Response received:", xhr.responseText);
                                alert("An error occurred while checking combo product. Please try again.");
                            }
                        } else {
                            console.error("Request failed with status:", xhr.status);
                            alert("Failed to check combo product. Please try again.");
                        }
                    }
                };
            } else {
                clonedItem.remove();
                calculateTotalOutprice();
            }
        } else {
            alert("At least one combo product must remain.");
        }
    }
});
</script>
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>