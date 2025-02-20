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
    if (!empty($old_multiple_stock['pname'])) {
        foreach ($old_multiple_stock['pname'] as $index => $pname) {
    ?>
    <div class="grid_col" id="cloningDiv">
        <div class="grid-col-1">
            <div class="form-div">
                <label for="pname" class="form-label">Product Name<span class="star">*</span></label>
                <div class="search-container">
                    <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" 
                        value="<?= htmlspecialchars($pname) ?>" required>
                    <div id="dropdown" class="dropdown">
                        <!-- Suggestions will be dynamically added here -->
                    </div>
                    <input type="hidden" class="product-id" name="product-id[]" 
                        value="<?= htmlspecialchars($old_multiple_stock['product-id'][$index] ?? '') ?>">
                </div>
            </div>

            <div class="form-div">
                <label for="batch_no" class="form-label">Batch No<span class="star">*</span></label>
                <div class="batch_div">
                    <input type="text" name="batch_no[]" class="input_style batch-no" placeholder="Enter Batch No"
                        value="<?= htmlspecialchars($old_multiple_stock['batch_no'][$index] ?? '') ?>" required>
                    <button type="button" class="generate-batch-no barcode_btn">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="form-div">
                <label for="inprice" class="form-label">In-Price<span class="star">*</span></label>
                <div>
                    <input type="number" name="in_price[]" class="input_style" placeholder="Enter In-Price"
                        value="<?= htmlspecialchars($old_multiple_stock['in_price'][$index] ?? '') ?>" required>
                    <span class="rupees_symbol">₹</span>
                </div>
            </div>

            <div class="form-div">
                <label for="outprice" class="form-label">Out-Price<span class="star">*</span></label>
                <div>
                    <input type="number" name="out_price[]" class="input_style outprice" placeholder="Enter Out-Price"
                        value="<?= htmlspecialchars($old_multiple_stock['out_price'][$index] ?? '') ?>" required>
                    <span class="rupees_symbol">₹</span>
                </div>
            </div>

            <div class="form-div">
                <label for="tstock" class="form-label">Stock Count<span class="star">*</span></label>
                <div>
                    <input type="number" name="tstock[]" class="input_style" placeholder="Enter Stock Count"
                        value="<?= htmlspecialchars($old_multiple_stock['tstock'][$index] ?? '') ?>" required>
                </div>
            </div>

            <div class="form-div">
                <div>
                    <img src="../assets/images/delete_icon.png" class="deleteButton" alt="delete-icon-img">
                </div>
            </div>
        </div>
    </div>
    <?php
        }
    } else { 
    ?>
    <!-- Default empty field when there's no old session data -->
    <div class="grid_col" id="cloningDiv">
        <div class="grid-col-1">
            <div class="form-div">
                <label for="pname" class="form-label">Product Name<span class="star">*</span></label>
                <div class="search-container">
                    <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" required>
                    <div id="dropdown" class="dropdown"></div>
                    <input type="hidden" class="product-id" name="product-id[]">
                </div>
            </div>

            <div class="form-div">
                <label for="batch_no" class="form-label">Batch No<span class="star">*</span></label>
                <div class="batch_div">
                    <input type="text" name="batch_no[]" class="input_style batch-no" placeholder="Enter Batch No" required>
                    <button type="button" class="generate-batch-no barcode_btn">
                        <i class="fa fa-refresh" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="form-div">
                <label for="inprice" class="form-label">In-Price<span class="star">*</span></label>
                <div>
                    <input type="number" name="in_price[]" class="input_style" placeholder="Enter In-Price" required>
                    <span class="rupees_symbol">₹</span>
                </div>
            </div>

            <div class="form-div">
                <label for="outprice" class="form-label">Out-Price<span class="star">*</span></label>
                <div>
                    <input type="number" name="out_price[]" class="input_style outprice" placeholder="Enter Out-Price" required>
                    <span class="rupees_symbol">₹</span>
                </div>
            </div>

            <div class="form-div">
                <label for="tstock" class="form-label">Stock Count<span class="star">*</span></label>
                <div>
                    <input type="number" name="tstock[]" class="input_style" placeholder="Enter Stock Count" required>
                </div>
            </div>

            <div class="form-div">
                <div>
                    <img src="../assets/images/delete_icon.png" class="deleteButton" alt="delete-icon-img">
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>
            <div class="clone_btn">
                <!-- <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>"> -->
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
                        <img id="previewImage" src="../../<?= !empty($stock_bill) ? $stock_bill : '' ?>"  width="100px"/>
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
    <div class="popup" id="popup">
        <h4>All unsaved changes will be lost.</h4>
        <div class="popup_btns">
            <button class="price_btn">Price</button>
            <button class="stock_btn">Stock</button>
            <button class="popup_cancel" id="cancel_btn">Cancel</button>
        </div>
    </div>

    <?php
        if(isset($_SESSION['error_message'])): 
            echo "I am in...";
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

                    // Handle special cases for invoice_no and supplier_id
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
                            input.selectedIndex = 0; // Default to first option for the first clone
                        }
                    } else {
                        if (input.type !== 'hidden') {
                            input.value = ''; // Clear value for new clones
                        }
                    }
                }
            });

            // Setup search box and dropdown for the cloned item
            setupSearch(clonedItem, index);

            // Handle delete button
            const deleteButton = clonedItem.querySelector('.deleteButton');
            deleteButton.addEventListener('click', function() {
                if (containerStock.children.length > 1) {
                    containerStock.removeChild(clonedItem);
                } else {
                    alert("At least one stock entry must remain.");
                }
            });

            // Event listener for generating batch number in cloned input
            const generateBatchNoBtn = clonedItem.querySelector('.generate-batch-no');
            generateBatchNoBtn.addEventListener('click', function() {
                const batchNoInput = clonedItem.querySelector('.batch-no');
                checkAndGenerateBatchNo(batchNoInput);
            });

            // Event listener for outprice validation
            const outpriceInput = clonedItem.querySelector('.outprice');
            outpriceInput.addEventListener('input', function() {
                validateOutprice(outpriceInput, clonedItem);
            });

            // const mrpInput = clonedItem.querySelector('.mrp');
            // mrpInput.addEventListener('input', function() {
            //     validateMRP(mrpInput, clonedItem);
            // });

            containerStock.appendChild(clonedItem);
        });

        // Original batch number generation
        const originalGenerateBatchNoBtn = document.querySelector('.generate-batch-no');
        originalGenerateBatchNoBtn.addEventListener('click', function() {
            const originalBatchNoInput = document.querySelector('.batch-no');
            checkAndGenerateBatchNo(originalBatchNoInput);
        });

        // Setup the original search box
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
    const thresholdPrice = 100;

    const comparePriceInput = clonedItem.querySelector('.mrp'); 
    const comparePriceValue = parseFloat(comparePriceInput.value);
    
    const priceError = clonedItem.querySelector('.price-error');

    priceError.style.display = 'none'; 
    priceError.textContent = '';

    if (isNaN(outpriceValue)) {
        priceError.textContent = "Please enter a valid number";
        priceError.style.display = 'block'; 
        outpriceInput.classList.add('invalid');
    } else if (outpriceValue > comparePriceValue) {
        priceError.textContent = `Outprice must be less than and the mrp`;
        priceError.style.display = 'block';
        outpriceInput.classList.add('invalid');
    } else {
        outpriceInput.classList.remove('invalid');
    }
}

// function validateMRP(mrpInput, clonedItem) {
//     const mrpValue = parseFloat(mrpInput.value);
    
//     // Get the error display element for MRP
//     const mrpError = clonedItem.querySelector('.mrp-error'); // Adjusted to mrp-error

//     // Reset the error display at the start of validation
//     mrpError.style.display = 'none'; 
//     mrpError.textContent = ''; // Clear any previous error message

//     // MRP validation
//     if (mrpValue === 0) {
//         mrpError.textContent = "MRP cannot be 0"; // Error message specific to MRP
//         mrpError.style.display = 'block'; // Show error message
//         mrpInput.classList.add('invalid'); // Mark MRP field as invalid
//     } else {
//         mrpInput.classList.remove('invalid'); // Remove invalid class if MRP is valid
//     }
// }

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
        dropdown.innerHTML = '';
        products.forEach(function(product) {
            var title = product.p_title.toLowerCase();
            if (title.includes(searchTerm.toLowerCase())) {
                var variation = product.p_variation ? product.p_variation : '';
                var unit = product.unit ? product.unit : '';
                var option = document.createElement('div');
                option.textContent = product.p_title + " " + variation + " " + unit;
                option.classList.add('dropdown-item');
                option.setAttribute('data-value', product.id);
                option.addEventListener('click', function() {
                    selectProduct(this, dropdown);
                });
                dropdown.appendChild(option);
            }
        });
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
    } else {
        console.error("Hidden input not found in the search container.");
    }

    currentFocus = -1; 
}

</script>


<script>
const outPriceInputs = document.querySelectorAll('input[name="out_price[]"]');
const mrpInputs = document.querySelectorAll('input[name="mrp[]"]');

function validatePrice(index) {
    const mrpValue = parseFloat(mrpInputs[index].value);
    const outPriceValue = parseFloat(outPriceInputs[index].value);
    const priceError = document.getElementById('price-error');
    const mrpError = document.getElementById('mrp-error');

    if (mrpValue <= 0) {
        mrpError.style.display = 'block';
    } else {
        mrpError.style.display = 'none';
    }

    if (outPriceValue > mrpValue) {
        priceError.style.display = 'block';
    } else {
        priceError.style.display = 'none';
    }
}

outPriceInputs.forEach((outPriceInput, index) => {
    outPriceInput.addEventListener('input', () => validatePrice(index));
});

// mrpInputs.forEach((mrpInput, index) => {
//     mrpInput.addEventListener('input', () => validatePrice(index));
// });
</script>

<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>