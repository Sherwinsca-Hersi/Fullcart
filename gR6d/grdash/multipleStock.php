<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Multiple Stock</title>
    <?php 
    require_once '../api/header.php';
    ?>
</head>
<body>
    <?php 
    if(isset($_SESSION['old_multiple_stock'])){
        $old_multiple_stock = $_SESSION['old_multiple_stock'] ?? [];
        $stock_bill=$_SESSION['old_multiple_stock']['stock_bill'];
        unset($_SESSION['old_multiple_stock']);
    }
    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php';
        ?>
    </div>
    <div class="addproduct_rightbar container">
        <h2>Add Multiple Stock</h2><br><br>
        <form class="addstock_form" method="post" action="com_ins_upd.php"  enctype="multipart/form-data" autocomplete="off" id="myForm" onsubmit="return validateForm()">
            <div id="containerStock">
            <?php 
         if (!empty($old_multiple_stock['pname'])) {// Ensure at least one row is available
        foreach ($old_multiple_stock['pname'] as $index => $pname) { 
        ?>
        <div class="grid_col" id="cloningDiv">
            <div class="grid-col-1">
                <div class="form-div">
                    <label class="form-label">Product Name<span class="star">*</span></label>
                    <div class="search-container">
                        <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" value="<?= htmlspecialchars($pname) ?>" required>
                        <input type="hidden" class="product-id" name="product-id[]" value="<?= htmlspecialchars($old_multiple_stock['product-id'][$index] ?? '') ?>">
                    </div>
                </div>
                <div class="form-div">
                    <label class="form-label">Batch No<span class="star">*</span></label>
                    <div class="batch_div">
                        <input type="text" name="batch_no[]" class="input_style batch-no" maxlength="10" placeholder="Enter Batch No" value="<?= htmlspecialchars($old_multiple_stock['batch_no'][$index] ?? '') ?>" required>
                        <button type="button" class="generate-batch-no barcode_btn"><i class="fa fa-refresh"></i></button>
                    </div>
                </div>
                <div class="form-div">
                    <label class="form-label">Stock Count<span class="star">*</span></label>
                    <div>
                        <input type="number"  name="tstock[]" class="input_style tstock" placeholder="Enter Stock Count" value="<?= htmlspecialchars($old_multiple_stock['tstock'][$index] ?? '') ?>" required>
                    </div>
                </div>
                <div class="form-div p_perkg_field" style="display:none;">
                        <label for="p_perkg" class="form-label">Price For(1 Kg)</label>
                        <div>
                            <input type="text" oninput="validateDecimal(this)" maxlength="10" name="p_perkg[]" id="p_perkg" value="<?= htmlspecialchars($old_multiple_stock['p_perkg'][$index] ?? '') ?>" class="input_style p_perkg" placeholder="Enter Price Per kg">
                        </div>
                    </div>
                <div class="form-div">
                    <label class="form-label">MRP<span class="star">*</span></label>
                    <div>
                        <input type="number" name="mrp[]" id="mrp" class="input_style mrp" placeholder="Enter MRP" value="<?= htmlspecialchars($old_multiple_stock['mrp'][$index] ?? '') ?>" required>
                        <span class="rupees_symbol">₹</span>
                        <small id="mrp-error" class="mrp-error">MRP Cannot be 0</small>
                    </div>
                </div>
                <div class="form-div">
                    <label class="form-label">In-Price<span class="star">*</span></label>
                    <div>
                        <input type="number" name="in_price[]" id="in_price" class="input_style" placeholder="Enter In-Price" value="<?= htmlspecialchars($old_multiple_stock['in_price'][$index] ?? '') ?>" required>
                        <span class="rupees_symbol">₹</span>
                    </div>
                </div>
                <div class="form-div">
                    <label class="form-label">Out-Price<span class="star">*</span></label>
                    <div>
                        <input type="number" name="out_price[]" id="out_price" class="input_style outprice" placeholder="Enter Out-Price" value="<?= htmlspecialchars($old_multiple_stock['out_price'][$index] ?? '') ?>" required>
                        <span class="rupees_symbol">₹</span>
                        <small id="price-error" class="price-error">Out-Price must be less than or equal to MRP</small>
                    </div>
                </div>
                <div class="form-div">
                    <label class="form-label">Expiry Date<span class="star">*</span></label>
                    <div>
                        <input type="date" name="expiry_date[]" class="input_style" value="<?= htmlspecialchars($old_multiple_stock['expiry_date'][$index] ?? '') ?>" required>
                    </div>
                </div>
                <div class="form-div">
                    <img src="../assets/images/delete_icon.png" class="deleteButton" alt="delete-icon-img">
                </div>
            </div>
        </div>
        <?php }
         }else{
            ?>
                <div class="grid_col" id="cloningDiv">
                <div class="grid-col-1">
                    <div class="form-div">
                        <label for="pname" class="form-label">Product Name<span class="star">*</span></label>
                            <div class="search-container">
                                <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" required>
                                <div id="dropdown" class="dropdown">
                                    <!-- Suggestions will be dynamically added here -->
                                </div>
                                <input type="hidden" id="product-id"  class="product-id" name="product-id[]">
                                    <!-- other form fields -->
                            </div>
                    </div>
                    <script>
                        function retainSelectedValue() {
                            var params = new URLSearchParams(window.location.search);
                            var selectedValue = params.get('productid');
                            if (selectedValue) {
                                document.getElementById('product').value = selectedValue;
                            }
                        }

                        window.onload = retainSelectedValue;
                    </script>
                    <div class="form-div">
                        <label for="batch_no" class="form-label">Batch No<span class="star">*</span></label>
                        <div class="batch_div">
                            <input type="text" name="batch_no[]" class="input_style batch-no" maxlength="10" placeholder="Enter Batch No" required>
                            <button type="button" class="generate-batch-no barcode_btn" aria-label="Generate Batch Number"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="tstock" class="form-label stock-label">Stock Count<span class="star">*</span></label>
                        <div>
                            <input type="number" name="tstock[]" id="tstock" class="input_style tstock" placeholder="Enter Stock Count" required>
                        </div>
                    </div>
                    <div class="form-div p_perkg_field" style="display:none;">
                        <label for="p_perkg" class="form-label">Price For(1 Kg)</label>
                        <div>
                            <input type="text" oninput="validateDecimal(this)" maxlength="10" name="p_perkg[]" id="p_perkg" class="input_style p_perkg" placeholder="Enter Price Per kg">
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="mrp" class="form-label mrp_label">MRP<span class="star">*</span></label>
                        <div>
                            <input type="number" name="mrp[]" id="mrp" class="input_style mrp" placeholder="Enter MRP" required>
                            <span class="rupees_symbol">₹</span>
                            <small id="mrp-error" class="mrp-error">MRP Cannot be 0</small>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="inprice" class="form-label">In-Price<span class="star">*</span></label>
                        <div>
                            <input type="number" name="in_price[]" class="input_style" placeholder="Enter In-Price"  id="inprice"  required>
                            <span class="rupees_symbol">₹</span>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="outprice" class="form-label">Out-Price<span class="star">*</span></label>
                        <div>
                            <input type="number" name="out_price[]" class="input_style outprice" placeholder="Enter Out-Price" id="outprice" required>
                            <span class="rupees_symbol">₹</span>
                            <small id="price-error" class="price-error">Out-Price must be less than or equal to MRP</small>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="exp_date" class="form-label">Expiry Date<span class="star">*</span></label>
                        <div>
                            <input type="date" name="expiry_date[]" id="exp_date" class="input_style" placeholder="Enter Expiry Date" required>
                        </div>
                    </div>
                    <div class="form-div">
                        <div>
                            <img src="../assets/images/delete_icon.png" class="deleteButton" alt="delete-icon-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
        }
        ?>   
            <div class="clone_btn">
                <input type="button" value="Add Another Stock" class="add_btn clone_stock_btn" name="clone_stock_btn" id="cloneStockBtn">
            </div>
            <div class="stockBillDiv"><h4>Add Stock bill Image</h4></div>
            <div class="common_fields">
                <div class="img_input">
                    <div class="file_upload">
                        <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                        <span>Search Image to Upload</span>
                        <input type="file" id="stock_bill" class="img_upload" name="stock_bill">
                    </div>
                    <div>
                        <img id="previewImage" src="../../<?= !empty($stock_bill) ? $stock_bill : '' ?>" width="100px"/>
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
                        <div class="form-div">
                            <label for="supplier_id" class="form-label">Supplier Name<span class="star">*</span></label>
                            <div>
                            <select name="supplier_id" class="input_style" required>
                                <option value="" class="option_style" disabled>Select Supplier</option>
                                 <?php
                                    // Retrieve the old supplier_id from session if available
                                    $old_supplier_id = $_SESSION['old_multiple_stock']['supplier_id'] ?? '';

                                    $vendor = $mysqli->query("SELECT * FROM e_vendor_details WHERE cos_id = '$cos_id' AND active = 1");
                                    while ($row = $vendor->fetch_assoc()) {
                                        $selected = ($row['v_id'] == $old_supplier_id) ? 'selected' : '';
                                    ?>
                                        <option value="<?php echo $row['v_id']; ?>" <?php echo $selected; ?>>
                                            <?php echo $row['v_name']; ?>
                                        </option>
                                    <?php 
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <div>
                                <input type="text" name="invoice_no" class="input_style" placeholder="Enter Invoive No" value="<?= htmlspecialchars($old_multiple_stock['invoice_no'] ?? '') ?>"  maxlength="10" id="invoiceNo">
                            </div>
                        </div>
            </div>
            <div class="add_btnDiv">
                <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                <input type="submit" value="Add Stock" class="add_btn" name="multiple_stock_add">
            </div>           
        </form>
    </div>
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
$product_name=[];
$product = $mysqli->query("SELECT `id`,`p_title`, `p_variation`,`unit` FROM `e_product_details` WHERE cos_id='$cos_id' 
AND active=1  GROUP BY id ORDER BY p_title");
while($row = $product->fetch_assoc()){
    $product_name[]=$row; 
}
?>

<script>
    var products = <?php echo json_encode($product_name); ?>;
    var currentFocus = -1;

    document.addEventListener('DOMContentLoaded', function() {
        const cloneStockBtn = document.getElementById('cloneStockBtn');
        const containerStock = document.getElementById('containerStock');

        cloneStockBtn.addEventListener('click', function() {
            const cloningDiv = document.getElementById('cloningDiv');
            const clonedItem = cloningDiv.cloneNode(true);
            const index = containerStock.children.length;

            const inputs = clonedItem.querySelectorAll('input, select, textarea');
            inputs.forEach(function(input) {
                const originalName = input.getAttribute('name');
                if (originalName) {
                    const newName = originalName.replace(/\[\d+\]/, '[' + index + ']');
                    input.setAttribute('name', newName);

                    if (input.id) {
                        const newId = input.id + '_' + index;
                        input.setAttribute('id', newId);
                    }

                    if (input.name.includes('invoice_no')) {
                        if (index > 0) {
                            const previousInvoiceNoElement = containerStock.querySelector(`input[name="invoice_no[${index - 1}]"]`);
                            if (previousInvoiceNoElement) {
                                const previousInvoiceNo = previousInvoiceNoElement.value;
                                input.value = previousInvoiceNo;
                            }
                        }
                    } else if (input.name.includes('supplier_id')) {
                        if (index > 0) {
                            const previousSupplierElement = containerStock.querySelector(`select[name="supplier_id[${index - 1}]"]`);
                            if (previousSupplierElement) {
                                const previousSupplierValue = previousSupplierElement.value;
                                const options = input.options;
                                for (let i = 0; i < options.length; i++) {
                                    if (options[i].value === previousSupplierValue) {
                                        options[i].selected = true;
                                        break;
                                    }
                                }
                            }
                        } else {
                            input.selectedIndex = 0;
                        }
                    } else {
                        if (input.type !== 'hidden') {
                            input.value = '';
                        }
                    }
                }
            });

            setupSearch(clonedItem, index);

            const pricePerKgFieldDiv = clonedItem.querySelector('.p_perkg_field');
            if (pricePerKgFieldDiv) {
                pricePerKgFieldDiv.style.display = 'none';
            }
            const stockLabel = clonedItem.querySelector('.stock-label');
            if (stockLabel) {
                stockLabel.textContent = "Stock Count";
            }
            const mrpLabel = clonedItem.querySelector('.mrp_label');
            if (mrpLabel) {
                mrpLabel.textContent = "MRP";
            }


            const deleteButton = clonedItem.querySelector('.deleteButton');
            deleteButton.addEventListener('click', function() {
                if (containerStock.children.length > 1) {
                    containerStock.removeChild(clonedItem);
                } else {
                    alert("At least one stock entry must remain.");
                }
            });

            const generateBatchNoBtn = clonedItem.querySelector('.generate-batch-no');
            generateBatchNoBtn.addEventListener('click', function() {
                const batchNoInput = clonedItem.querySelector('.batch-no');
                checkAndGenerateBatchNo(batchNoInput);
            });

            const outpriceInput = clonedItem.querySelector('.outprice');
            outpriceInput.addEventListener('input', function() {
                validateOutprice(outpriceInput, clonedItem);
            });

            const mrpInput = clonedItem.querySelector('.mrp');
            mrpInput.addEventListener('input', function() {
                validateMRP(mrpInput,outpriceInput,clonedItem);
            });

            containerStock.appendChild(clonedItem);
        });


        const originalGenerateBatchNoBtn = document.querySelector('.generate-batch-no');
        originalGenerateBatchNoBtn.addEventListener('click', function() {
            const originalBatchNoInput = document.querySelector('.batch-no');
            checkAndGenerateBatchNo(originalBatchNoInput);
        });


        setupSearch(document.getElementById('cloningDiv'), 0);
    });

    function setupSearch(item, index) {
        const searchBox = item.querySelector('.search-box');
        const dropdown = item.querySelector('.dropdown');
        if (searchBox && dropdown) {
            const uniqueSearchBoxId = 'searchBox_' + index;
            const uniqueDropdownId = 'dropdown_' + index;

            searchBox.setAttribute('id', uniqueSearchBoxId);
            dropdown.setAttribute('id', uniqueDropdownId);

            attachSearchListener(searchBox, dropdown);
        }
    }

function validateOutprice(outpriceInput, clonedItem) {
    const outpriceValue = parseFloat(outpriceInput.value);
    const comparePriceInput = clonedItem.querySelector('.mrp');
    const comparePriceValue = parseFloat(comparePriceInput.value);

    const priceError = clonedItem.querySelector('.price-error');

    priceError.style.display = 'none';
    priceError.textContent = '';

    if (isNaN(outpriceValue)) {
        priceError.textContent = "Please enter a valid number for Outprice";
        priceError.style.display = 'block';
        outpriceInput.classList.add('invalid');
        return false;
        e.preventDefault();
    } else if (outpriceValue > comparePriceValue) {
        priceError.textContent = `Outprice must not exceed the MRP (${comparePriceValue})`;
        priceError.style.display = 'block';
        outpriceInput.classList.add('invalid');
        return false;
        e.preventDefault();
    } else {
        outpriceInput.classList.remove('invalid');
        return true;
    }
}

function validateMRP(mrpInput,outpriceInput,clonedItem) {
    const mrpValue = parseFloat(mrpInput.value);
    const outpriceValue = parseFloat(outpriceInput.value);


    const mrpError = clonedItem.querySelector('.mrp-error');


    mrpError.style.display = 'none';
    mrpError.textContent = '';


    if (isNaN(mrpValue) || mrpValue === 0) {
        mrpError.textContent = "MRP must not be 0";
        mrpError.style.display = 'block';
        mrpInput.classList.add('invalid');
        return false;
        e.preventDefault();
    }
    else if(mrpValue < outpriceValue){
        mrpError.textContent = "MRP should be ≥ Outprice and non-zero.";
        mrpError.style.display = 'block';
        mrpInput.classList.add('invalid');
        return false;
        e.preventDefault();
    }
    else {
        mrpInput.classList.remove('invalid');
        return true;
    }
}

document.addEventListener("DOMContentLoaded", function () {
    const form = document.getElementById("myForm");

    form.addEventListener("submit", function (e) {
        let isValid = true;

        const clonedItems = document.querySelectorAll('.cloned-item');
        clonedItems.forEach(clonedItem => {
            const mrpInput = clonedItem.querySelector('.mrp');
            const outpriceInput = clonedItem.querySelector('.outprice');

            // Validate MRP and Outprice
            const isMRPValid = validateMRP(mrpInput,outpriceInput,clonedItem);
            const isOutpriceValid = validateOutprice(outpriceInput, clonedItem);

            if (!isMRPValid || !isOutpriceValid) {
                isValid = false;
            }
        });

        if (!isValid) {
            e.preventDefault();
            console.log("Form submission prevented due to validation errors.");
        }
    });
});


function validateClonedFields() {
    const clonedItems = document.querySelectorAll('.cloned-item');
    let isFormValid = true;

    clonedItems.forEach((clonedItem) => {
        const mrpInput = clonedItem.querySelector('.mrp');
        const outpriceInput = clonedItem.querySelector('.outprice');


        const isMRPValid = validateMRP(mrpInput,outpriceInput,clonedItem);
        const isOutpriceValid = validateOutprice(outpriceInput, clonedItem);

        if (!isMRPValid || !isOutpriceValid) {
            isFormValid = false;
        }
    });

    return isFormValid;
}

document.getElementById('myForm').addEventListener('submit', function (e) {
    if (!validateClonedFields()) {
        e.preventDefault();
    }
});

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

    function checkAndGenerateBatchNo(batchNoInput) {
        var batchNumber = generateBatchNumber();

        var xhr = new XMLHttpRequest();
        xhr.open("GET", "checkBatchNo.php?batchNo=" + batchNumber, true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText === "exists") {
                    checkAndGenerateBatchNo(batchNoInput); 
                } else {
                    batchNoInput.value = batchNumber; 
                }
            }
        };
        xhr.send();
    }

    function attachSearchListener(searchBox, dropdown) {
        searchBox.addEventListener('input', function() {
            var searchTerm = this.value.trim();
            if (searchTerm.length >= 2) {
                searchProducts(searchTerm, dropdown);
            } else {
                showAllProducts(dropdown); 
            }
        });


        searchBox.addEventListener('focus', function() {
            showAllProducts(dropdown); 
            dropdown.style.display = 'block';
        });


        searchBox.addEventListener('focusout', function() {
            setTimeout(() => {
                dropdown.style.display = 'none';
            }, 200); 
        });


        searchBox.addEventListener('keydown', function(e) {
            var items = dropdown.getElementsByClassName('dropdown-item');
            if (e.key === 'ArrowDown') {
                currentFocus++;
                addActive(items);
                dropdown.style.display = 'block'; 
                scrollIntoView(items[currentFocus]);
            } else if (e.key === 'ArrowUp') {
                currentFocus--;
                addActive(items);
                dropdown.style.display = 'block'; 
                scrollIntoView(items[currentFocus]); 
            } else if (e.key === 'Enter') {
                e.preventDefault(); 
                if (currentFocus > -1 && items[currentFocus]) {
                    selectProduct(items[currentFocus], dropdown);
                }
            }
        });
    }

    function scrollIntoView(element) {
        if (element) {
            const dropdown = element.closest('.dropdown');
            const dropdownHeight = dropdown.clientHeight;
            const elementTop = element.offsetTop;
            const elementHeight = element.clientHeight;

            if (elementTop < dropdown.scrollTop) {
                dropdown.scrollTop = elementTop;
            } else if (elementTop + elementHeight > dropdown.scrollTop + dropdownHeight) {
                dropdown.scrollTop = elementTop + elementHeight - dropdownHeight;
            }
        }
    }

    function showAllProducts(dropdown) {
        dropdown.innerHTML = '';
        products.forEach(function(product) {
            var variation = product.p_variation ? product.p_variation : '';
            var unit = product.unit ? product.unit : '';
            var option = document.createElement('div');
            option.textContent = product.p_title + " " + product.p_variation + " " + product.unit;
            option.classList.add('dropdown-item');
            option.setAttribute('data-value', product.id);
            option.addEventListener('click', function() {
                selectProduct(this, dropdown);
            });
            dropdown.appendChild(option);
        });
    }

    function searchProducts(searchTerm, dropdown) {
    // Clear the dropdown
    dropdown.innerHTML = '';

    // Loop through products and check each field for the search term
    products.forEach(function(product) {
        // Safely handle missing/null values and apply .toLowerCase() for search
        var title = product.p_title ? product.p_title.toLowerCase() : '';
        var variation = product.p_variation ? product.p_variation.toLowerCase() : '';
        var unit = product.unit ? product.unit.toLowerCase() : '';

        // Check if the search term is present in any of the fields
        if (title.includes(searchTerm.toLowerCase()) || 
            variation.includes(searchTerm.toLowerCase()) || 
            unit.includes(searchTerm.toLowerCase())) {

            // Create the option element
            var option = document.createElement('div');
            option.textContent = product.p_title + " " + product.p_variation + " " + product.unit;
            option.classList.add('dropdown-item');
            option.setAttribute('data-value', product.id);

            // Add click event listener to select product
            option.addEventListener('click', function() {
                selectProduct(this, dropdown);
            });

            // Append option to the dropdown
            dropdown.appendChild(option);
        }
    });

    // Toggle visibility of the dropdown based on whether it has any items
    dropdown.style.display = dropdown.innerHTML ? 'block' : 'none';
}

    function addActive(items) {
        if (!items) return;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = items.length - 1;
        items[currentFocus].classList.add('active');
    }

    function removeActive(items) {
        for (var i = 0; i < items.length; i++) {
            items[i].classList.remove('active');
        }
    }

function selectProduct(selectedItem, dropdown) {
    var productId = selectedItem.getAttribute('data-value');
    var searchBox = dropdown.previousElementSibling;
    searchBox.value = selectedItem.textContent;
    dropdown.style.display = 'none';
    var hiddenInput = searchBox.closest('.search-container').querySelector('.product-id');
    
    if (hiddenInput) {
        hiddenInput.value = productId;
        console.log("Hidden input found, product ID set to:", productId);

        fetch('check_loose_product.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.is_loose) {
                console.log("This is a loose product.");
                var container = searchBox.closest('#cloningDiv');
                var stockLabel = container.querySelector('.stock-label');
                var stockInput = container.querySelector('.tstock');
                var pPerKgInput = container.querySelector('.p_perkg');
                var pricePerKgField = container.querySelector('.p_perkg_field');
                var outPriceInput = container.querySelector('.outprice');
                var mrpLabel = container.querySelector('.mrp_label');

                
                if (stockLabel && pricePerKgField) {
                    stockLabel.textContent = "Stock Count (in Kg)";
                    pricePerKgField.style.display = 'block';


                    function calculateOutprice() {


        const stockValue = parseFloat(stockInput.value) || 0;
        const pricePerKg = parseFloat(pPerKgInput.value) || 0;
        const outPriceValue = stockValue * pricePerKg;

        outPriceInput.value = outPriceValue.toFixed(2);
        outPriceInput.readOnly="true";
        outPriceInput.style.backgroundColor = '#D3D3D3';

        mrpLabel.innerText = `MRP For (${stockValue} Kg)`;
    }
    stockInput.addEventListener("input", () => calculateOutprice());
    pPerKgInput.addEventListener("input", () => calculateOutprice());
                }

            } else {
                console.log("This is not a loose product.");
                var container = searchBox.closest('.cloningDiv')
                var stockLabel = container.querySelector('.stock_label');
                var pricePerKgField = container.querySelector('.p_perkg');
                
                if (stockLabel && pricePerKgField) {
                    stockLabel.textContent = "Stock Count (Units)";
                    pricePerKgField.style.display = 'none';
                }
            }
        })
        .catch(error => console.error("Error fetching product data:", error));
    } else {
        console.error("Hidden input not found in the search container.");
    }
    currentFocus = -1;
}




// Outprice & MRP Validation

const outPriceInputs = document.querySelectorAll('input[name="out_price[]"]');
const mrpInputs = document.querySelectorAll('input[name="mrp[]"]');

function validatePrice() {
    var mrp = parseFloat(document.getElementById('mrp').value) || 0;
    var outPrice = parseFloat(document.getElementById('outprice').value) || 0;
    var errorMrp = document.getElementById('mrp-error');
    var errorPrice = document.getElementById('price-error');
    var mrpLabel = document.getElementById("mrp_label")

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
        e.preventDefault();
    }
    if(mrp < outPrice){
        errorMrp.style.display = 'block';
        errorMrp.textContent = 'MRP should be ≥ Outprice and non-zero';
        document.getElementById('mrp').setCustomValidity('MRP should be ≥ Outprice and non-zero');
        return false;
        e.preventDefault();
    }

    if (outPrice > mrp) {
        errorPrice.style.display = 'block';
        errorPrice.textContent = 'Out-Price must be less than or equal to MRP';
        document.getElementById('outprice').setCustomValidity('Out-Price must be less than or equal to MRP');
        return false;
        e.preventDefault();
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