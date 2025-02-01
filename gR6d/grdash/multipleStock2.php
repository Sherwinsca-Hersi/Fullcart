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
    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php';
        ?>
    </div>
    <div class="addproduct_rightbar container">
        <h2>Add Multiple Stock</h2><br><br>
                <form class="addstock_form" method="post" action="com_ins_upd.php"  enctype="multipart/form-data" autocomplete="off">
                <div id="containerStock">
                <div class="grid_col" id="cloningDiv">
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
            <input type="text" placeholder="Search..." class="input_style search-box" name="pname[]" required>
            <div id="dropdown" class="dropdown">
                <!-- Suggestions will be dynamically added here -->
            </div>
            <input type="hidden" id="product-id"  class="product-id" name="product-id[]">
            <!-- other form fields -->
        </div>
    <!-- </div> -->
</div>
                        <script>
        // function updateURL() {
        //     var selectedValue = document.getElementById('product').value;
        //     var newURL = window.location.origin + window.location.pathname + '?productid=' + selectedValue;
        //     window.location.href = newURL;
        // }
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
                                <input type="text" name="batch_no[]" class="input_style" placeholder="Enter Batch No"  required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="mrp" class="form-label">MRP<span class="star">*</span></label>
                            <div>
                                <input type="number" name="mrp[]" id="mrp" class="input_style" placeholder="Enter MRP" required>
                                <span class="rupees_symbol">₹</span>
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
                                <input type="number" name="out_price[]" class="input_style" placeholder="Enter Out-Price" id="outprice" required>
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
                                <input type="number" name="tstock[]" id="tstock" class="input_style" placeholder="Enter Stock Count" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="exp_date" class="form-label">Expiry Date<span class="star">*</span></label>
                            <div>
                                <input type="date" name="expiry_date[]" id="exp_date" class="input_style" placeholder="Enter Expiry Date" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="supplier_id" class="form-label">Supplier Name</label>
                            <div>
                            <select name="supplier_id[]" class="input_style" required>
                                    <option value=""  class="option_style" disabled selected>Select Supplier</option>
                                    <?php
                                     $vendor = $mysqli->query("select v_id,v_name from e_vendor_details where cos_id = '$cos_id' and active=1");
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
                            <label for="invoice_no" class="form-label">Invoice No</label>
                            <div>
                                <input type="text" name="invoice_no[]" class="input_style" placeholder="Enter Invoive No" id="invoiceNo">
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
                <div class="clone_btn">
                    <!-- <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>"> -->
                    <input type="button" value="Add Another Stock" class="add_btn clone_stock_btn" name="clone_stock_btn" id="cloneStockBtn">
                </div>
                <div class="stockBillDiv"><h4>Add Stock bill Image</h4></div>
                    <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="stock_bill" class="img_upload" name="stock_bill">
                            </div>
                            <div>
                                <img id="previewImage"  width="100px"/>
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
                <div class="add_btnDiv">
                    <input type="hidden" name="stock_prod_id" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                    <input type="submit" value="Add Stock" class="add_btn" name="multiple_stock_add">
                </div>
            </form>
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
<?php
$product_name=[];
$product = $mysqli->query("SELECT `id`,`p_title`, `p_variation`,`unit` FROM `e_product_details` WHERE cos_id='$cos_id' AND active=1 GROUP BY id ORDER BY p_title");
while($row = $product->fetch_assoc()){
    $product_name[]=$row; 
}
?>

<script>
    var products = <?php echo json_encode($product_name); ?>;
    console.log(products);
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
                    // Copy the invoice number from the last clone
                    if (index > 0) { // Ensure there is at least one previous clone
                        const previousInvoiceNoElement = containerStock.querySelector(`input[name="invoice_no[${index - 1}]"]`);
                        if (previousInvoiceNoElement) {
                            const previousInvoiceNo = previousInvoiceNoElement.value;
                            input.value = previousInvoiceNo; // Copy invoice value from the last clone
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

        const searchBox = clonedItem.querySelector('.search-box');
        const dropdown = clonedItem.querySelector('.dropdown');
        if (searchBox && dropdown) {
            const uniqueSearchBoxId = 'searchBox_' + index;
            const uniqueDropdownId = 'dropdown_' + index;

            searchBox.setAttribute('id', uniqueSearchBoxId);
            dropdown.setAttribute('id', uniqueDropdownId);

            // Attach search listener to the new cloned search-box and dropdown
            attachSearchListener(searchBox, dropdown);
        }

        // Handle delete functionality for cloned items
        const deleteButton = clonedItem.querySelector('.deleteButton');
        deleteButton.addEventListener('click', function() {
            if (containerStock.children.length > 1) {
                containerStock.removeChild(clonedItem);
            } else {
                alert("At least one stock entry must remain.");
            }
        });

        containerStock.appendChild(clonedItem); // Append the cloned item to the container
    });

    var originalSearchBox = document.querySelector('.search-box');
    var originalDropdown = document.getElementById('dropdown');
    attachSearchListener(originalSearchBox, originalDropdown); // Attach listener to the original search box
});

// Reusable function to attach search functionality
function attachSearchListener(searchBox, dropdown) {
    searchBox.addEventListener('input', function() {
        var searchTerm = this.value.trim();
        if (searchTerm.length >= 2) { // Minimum characters for search
            searchProducts(searchTerm, dropdown);
        } else {
            // If less than 2 characters, show all products
            showAllProducts(dropdown); 
        }
    });

    // Show all products on focus
    searchBox.addEventListener('focus', function() {
        showAllProducts(dropdown); // Show all products every time focused
        dropdown.style.display = 'block'; // Show dropdown
    });

    // Close dropdown on focusout
    searchBox.addEventListener('focusout', function() {
        setTimeout(() => {
            dropdown.style.display = 'none'; // Hide dropdown after losing focus
        }, 200); // Extended timeout to ensure the dropdown click is registered
    });

    searchBox.addEventListener('keydown', function(e) {
        var items = dropdown.getElementsByClassName('dropdown-item');
        if (e.key === 'ArrowDown') {
            currentFocus++;
            addActive(items);
        } else if (e.key === 'ArrowUp') {
            currentFocus--;
            addActive(items);
        } else if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission
            if (currentFocus > -1 && items[currentFocus]) {
                items[currentFocus].click(); // Trigger item click
            }
        }
    });
}

function showAllProducts(dropdown) {
    dropdown.innerHTML = ''; // Clear previous results
    products.forEach(function(product) {
        var variation = product.p_variation ? product.p_variation : '';
        var unit = product.unit ? product.unit : '';
        var option = document.createElement('div');
        option.textContent = product.p_title + " " + variation + " " + unit;
        option.classList.add('dropdown-item');
        option.setAttribute('data-product-id', product.id); // Set product ID as data attribute

        option.addEventListener('click', function() {
            // Update the search input with the selected product details
            const inputField = dropdown.previousElementSibling;
            if (inputField) {
                inputField.value = product.p_title + " " + variation + " " + unit;
            }

            // Find the hidden product-id input inside the same .search-container and update its value
            const searchContainer = dropdown.closest('.search-container');
            if (searchContainer) {
                const hiddenProductIdInput = searchContainer.querySelector('.product-id');
                if (hiddenProductIdInput) {
                    hiddenProductIdInput.value = product.id;
                }
            }

            // Clear and hide the dropdown after selection
            dropdown.innerHTML = '';
            dropdown.style.display = 'none';
        });
        
        dropdown.appendChild(option);
    });

    dropdown.style.display = 'block'; // Show dropdown
}
// Search functionality for filtering products and displaying dropdown options
function searchProducts(searchTerm, dropdown) {
    var filteredProducts = products.filter(function(product) {
        return product.p_title.toLowerCase().includes(searchTerm.toLowerCase());
    });

    dropdown.innerHTML = '';

    if (filteredProducts.length === 0) {
        var noResults = document.createElement('div');
        noResults.textContent = 'No products found';
        noResults.classList.add('dropdown-item', 'no-results');
        dropdown.appendChild(noResults);
    } else {
        filteredProducts.forEach(function(product) {
            var variation = product.p_variation ? product.p_variation : '';
            var unit = product.unit ? product.unit : '';
            var option = document.createElement('div');
            option.textContent = product.p_title + " " + variation + " " + unit;
            option.classList.add('dropdown-item');
            option.addEventListener('click', function() {
                dropdown.previousElementSibling.value = product.p_title + " " + variation + " " + unit;
                document.getElementById('product-id').value = product.id;
                dropdown.innerHTML = '';
                dropdown.style.display = 'none';
            });
            dropdown.appendChild(option);
        });
    }

    dropdown.style.display = 'block'; // Show dropdown
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

</script>

<script>
document.getElementById('outprice').addEventListener('input', function() {
    var mrp = parseFloat(document.getElementById('mrp').value);
    var outPrice = parseFloat(this.value);
    
    if (outPrice > mrp) {
        document.getElementById('price-error').style.display = 'block';
        this.setCustomValidity('Out-Price must be less than or equal to MRP');
    } else {
        document.getElementById('price-error').style.display = 'none';
        this.setCustomValidity('');
    }
});
</script>

<script src="../assets/js/session_check.js"></script>
</body>
</html>