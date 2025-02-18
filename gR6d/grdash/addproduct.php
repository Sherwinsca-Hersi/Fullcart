<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <?php 
    require_once '../api/header.php';
    ?>
    <!--iziToast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">


</head>
<body>
<?php 

if(isset($_SESSION['old_product_data'])){
    $old_product_data = $_SESSION['old_product_data'] ?? [];
    $prod_img=$_SESSION['old_product_data']['main_prod_img'];
    unset($_SESSION['old_product_data']);
}


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
                echo  "<h2>Update Product</h2>";
            }
            else{
                echo "<h2>Add Product</h2>";
            }
        ?>
        <div class="product_form">
        <?php 
			if(isset($_GET['productid']))
			{
				$data = $mysqli->query("SELECT `id`, `cos_id`, `sku_id`, `hsn_code`, `barcode`, `cat_id`,`p_desc`,
                 `sub_cat_id`, `p_img`, `imgs`, `p_title`, `brand`, `p_variation`,`unit`, `type`, `is_loose`, `reorder_level`, 
                `emergency_level`, `location`, `godown_location`,  `created_ts`, `updated_ts`, `active` 
                FROM `e_product_details` WHERE   active!=2  AND id=".$_GET['productid']." AND cos_id = '$cos_id'")->fetch_assoc();
			?>
            <form class="addproduct_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="pname" class="form-label">Product Name</label>
                            <div>
                                <input type="text" name="p_title" class="input_style" placeholder=" Enter Product Name" value="<?php if(!($data['p_title']==NULL || '')){ echo $data['p_title'];}?>" maxlength="80" autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="sku_id" class="form-label">SKU</label>
                            <div>
                                <input type="text" name="sku_id"  class="input_style" placeholder="Enter SKU ID" value="<?php if(!($data['sku_id']==NULL || '')){ echo $data['sku_id'];}?>" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="brand" class="form-label">Brand</label>
                            <div>
                                <input type="text" name="brand"  class="input_style" placeholder="Enter Brand" value="<?php if(!($data['brand']==NULL || '')){echo $data['brand'];}?>" maxlength="50">
                            </div>
                        </div>
                        <?php
                            $prod_category=$mysqli->query("SELECT id,title FROM `e_category_details` WHERE active=1 AND id='".$data['cat_id']."' AND cos_id='$cos_id'")->fetch_assoc();
                            $prod_subcategory=$mysqli->query("SELECT id,title FROM `e_subcategory_details` WHERE active=1 AND id='".$data['sub_cat_id']."' AND cos_id='$cos_id'")->fetch_assoc();
                        ?>
                        <div class="form-div">
                            <label for="pcategory" class="form-label">Product Category</label>
                            <div class="search-container">
                                <input type="text" placeholder="Search Categories..." class="input_style search-box" name="pcategory" value="<?php if(!($prod_category['title']==NULL || '')){ echo htmlspecialchars($prod_category['title']); }?>" required>
                                <div id="dropdown" class="dropdown">
                                    <!-- Suggestions will be dynamically added here -->
                                </div>
                                <input type="hidden" id="category-id" name="category-id" value="<?php if(!($prod_category['id']==NULL || '')){echo htmlspecialchars($prod_category['id']);} ?>">
                                    <!-- other form fields -->
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="psubcategory" class="form-label">Product Subcategory</label>
                            <div class="search-container">
                                <input type="text" placeholder="Search Subcategories..." class="input_style search-box" name="psubcategory" value="<?php if(!($prod_subcategory['title']==NULL || '')){echo htmlspecialchars($prod_subcategory['title']); }?>" required>
                                <div id="dropdown-subcategory" class="dropdown">
                                    <!-- Subcategory suggestions will be dynamically added here -->
                                </div>
                                <input type="hidden" id="subcategory-id" name="subcategory-id" value="<?php if(!($prod_subcategory['id']==NULL || '')){echo htmlspecialchars($prod_subcategory['id']);} ?>">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="is_loose" class="form-label">Is the product is in loose ?</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="is_loose" id="is_loose" class="input_style" value="1" <?php if($data['is_loose']==1){echo "checked";}?>>Yes
                                <input type="radio" name="is_loose" id="is_loose" class="input_style" value="0" <?php if($data['is_loose']==0){echo "checked";}?>>No
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="pVariation" class="form-label">Variation(eg: 50,100,500)</label>
                            <div>
                                <input type="text" name="p_variation" id="variation" class="input_style" placeholder="Enter Variation" value="<?php if(!($data['p_variation']==NULL || '')){echo $data['p_variation'];}?>" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="unit" class="form-label">Unit</label>
                            <div>
                                <?php 
                                
                                    $unit_query = $mysqli->query("SELECT id,unit,active FROM `e_unit_details` WHERE cos_id = '$cos_id' AND active!=2");
                                    if ($unit_query->num_rows > 0) {
                                        ?>
                                            <select name="unit" id="unit" class="input_style">
                                                <option value="" disabled selected>Select Unit</option>
                                                <?php
                                                while ($row = $unit_query->fetch_assoc()) {
                                                    $selected = ($data['unit'] == $row['unit']) ? 'selected' : '';
                                                    echo "<option value='" . htmlspecialchars($row['unit']) . "' class='option_style' $selected>" . htmlspecialchars($row['unit']) . "</option>";
                                                }
                                                ?>
                                            </select>
                                        <?php
                                        } else {
                                            echo "<select name='unit' id='unit' class='input_style'>
                                                    <option value='' disabled selected>No Units Available</option>
                                                  </select>";
                                        }
                                ?>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="p_desc" class="form-label">Product Description</label>
                            <div>
                                <textarea rows="5" cols="10" class="input_style" name="p_desc"><?php if(!($data['p_desc']==NULL || '')){echo $data['p_desc'];}?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="barcode" class="form-label">Barcode<span class="star">*</span></label>
                            <div>
                                <div class="barcode_input">
                                    <input type="text" name="barcode" id="barcode" class="input_style" placeholder="Barcode" value="<?php if(!($data['barcode']==NULL || '')){echo $data['barcode'];}?>" maxlength="12" required>
                                    <button type="button" class="barcode_btn" id="generateBarcode"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="flocation" class="form-label">Floor Location</label>
                            <div>
                                <input type="text" name="flocation" id="flocation" class="input_style" placeholder="Enter Floor Location" value="<?php if(!($data['location']==NULL || '')){echo $data['location'];}?>">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="glocation" class="form-label">Godown Location</label>
                            <div>
                                <input type="text" name="glocation" id="glocation" class="input_style" placeholder="Enter Godown Location" value="<?php if(!($data['godown_location']==NULL || '')){echo $data['godown_location'];}?>">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emergency_level" class="form-label emergency_level_label">Emergency Level</label>
                            <div>
                                <input type="number" name="emergency_level" id="emergency_level" class="input_style" value="<?php if(!($data['emergency_level']==NULL || '')){echo $data['emergency_level'];}?>" placeholder="Enter Emergency Level" maxlength="10">
                            </div>
                        </div>
                       
                        <div class="form-div">
                            <label for="reorder_level" class="form-label reorder_level_label">Reorder Level</label>
                            <div>
                                <input type="number" name="reorder_level" id="reorder_level" class="input_style" value="<?php if(!($data['reorder_level']==NULL || '')){echo $data['reorder_level'];}?>" placeholder="Enter Reorder Level" maxlength="10">
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="main_prod_img" class="img_upload" name="main_prod_img">
                            </div>
                            <div>
                                <?php
                                    if(!($data['p_img']==NULL || '')){
                                ?>
                                    <img id="previewImage"  src="../../<?php echo $data['p_img'];?>" width="100px"/>
                            <?php
                            }
                            ?>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="publish" class="form-label">Publish</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="p_status" id="publish" class="input_style" value="1" <?php if($data['active']==1){echo "checked";}?>>Published
                                <input type="radio" name="p_status" id="publish" class="input_style" value="0" <?php if($data['active']==0){echo "checked";}?>>Unpublished
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="productid" value="<?php echo isset($_GET['productid']) ? htmlspecialchars($_GET['productid']) : ''; ?>">
                    <input type="submit" value="Update Product" class="add_btn" name="product_update">
                </div>
            </form>
            <?php } else{?>
            <form class="addproduct_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <div class="form-div">
                            <label for="pname" class="form-label">Product Name<span class="star">*</span></label>
                            <div>
                                <input type="text" name="p_title" class="input_style" value="<?= htmlspecialchars($old_product_data['p_title'] ?? '') ?>" placeholder=" Enter Product Name" maxlength="80" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="sku_id" class="form-label">SKU</label>
                            <div>
                                <input type="text" name="sku_id"  class="input_style" value="<?= htmlspecialchars($old_product_data['sku_id'] ?? '') ?>" placeholder="Enter SKU ID" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="brand" class="form-label">Brand</label>
                            <div>
                                <input type="text" name="brand"  class="input_style" value="<?= htmlspecialchars($old_product_data['brand'] ?? '') ?>" placeholder="Enter Brand" maxlength="50">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="pcategory" class="form-label">Product Category<span class="star">*</span></label>
                            <div class="search-container">
                                <input type="text" placeholder="Search Categories..." class="input_style search-box" name="pcategory" value="<?= htmlspecialchars($old_product_data['pcategory'] ?? '') ?>" required>
                                <div id="dropdown" class="dropdown">
                                    <!-- Suggestions will be dynamically added here -->
                                </div>
                                <input type="hidden" id="category-id" name="category-id" value="<?= htmlspecialchars($old_product_data['category-id'] ?? '') ?>">
                                    <!-- other form fields -->
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="psubcategory" class="form-label">Product Subcategory <span class="star">*</span></label>
                            <div class="search-container">
                                <input type="text" placeholder="Search Subcategories..." class="input_style search-box" name="psubcategory" value="<?= htmlspecialchars($old_product_data['psubcategory'] ?? '') ?>" required>
                                <div id="dropdown-subcategory" class="dropdown">
                                    <!-- Subcategory suggestions will be dynamically added here -->
                                </div>
                                <input type="hidden" id="subcategory-id" name="subcategory-id" value="<?= htmlspecialchars($old_product_data['subcategory-id'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="is_loose" class="form-label">Is the product is in loose ?</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="is_loose" id="is_loose" class="input_style" value="1" <?= isset($old_product_data['is_loose']) && $old_product_data['is_loose'] == '1' ? 'checked' : '' ?>>Yes
                                <input type="radio" name="is_loose" id="is_loose" class="input_style" value="0" <?= isset($old_product_data['is_loose']) && $old_product_data['is_loose'] == '0' ? 'checked' : '' ?>>No
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="pVariation" class="form-label">Variation<span class="star">*</span>(eg: 50,100,500)</label>
                            <div>
                                <input type="text" name="p_variation"  id="variation" value="<?= htmlspecialchars($old_product_data['p_variation'] ?? '') ?>" class="input_style" placeholder="Enter Variation" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="unit" class="form-label">Unit<span class="star">*</span></label>
                            <div>
                            <?php 
                                
                                $unit_query = $mysqli->query("SELECT id,unit,active FROM `e_unit_details` WHERE cos_id = '$cos_id' AND active!=2");
                                if ($unit_query->num_rows > 0) {
                                    ?>
                                        <select name="unit" id="unit" class="input_style">
                                            <option value="" disabled selected>Select Unit</option>
                                            <?php
                                            while ($row = $unit_query->fetch_assoc()) {
                                                $selected_unit =  htmlspecialchars($old_product_data['unit']) ?? '';
                                                $isSelected = ($row['unit'] === $selected_unit) ? 'selected' : '';
                                                echo "<option value='" . htmlspecialchars($row['unit']) . "' class='option_style' $isSelected>" . htmlspecialchars($row['unit']) . "</option>";
                                            }
                                            ?>
                                        </select>
                                    <?php
                                    } else {
                                        echo "<select name='unit' id='unit' class='input_style'>
                                                <option value='' disabled selected>No Units Available</option>
                                              </select>";
                                    }
                            ?>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="p_desc" class="form-label">Product Description</label>
                            <div>
                                <textarea rows="5" cols="10" class="input_style" name="p_desc"><?= htmlspecialchars($old_product_data['p_desc'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="barcode" class="form-label">Barcode<span class="star">*</span></label>
                            <div>
                                <div class="barcode_input">
                                    <input type="text" name="barcode" id="barcode" class="input_style" value="<?= htmlspecialchars($old_product_data['barcode'] ?? '') ?>"  placeholder="Barcode" maxlength="12" required>
                                    <button type="button" class="barcode_btn" id="generateBarcode"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                </div>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="flocation" class="form-label">Floor Location</label>
                            <div>
                                <input type="text" name="flocation" id="flocation" value="<?= htmlspecialchars($old_product_data['flocation'] ?? '') ?>" class="input_style" placeholder="Enter Floor Location">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="glocation" class="form-label">Godown Location</label>
                            <div>
                                <input type="text" name="glocation" id="glocation" value="<?= htmlspecialchars($old_product_data['glocation'] ?? '') ?>"  class="input_style" placeholder="Enter Godown Location">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="emergency_level" class="form-label emergency_level_label">Emergency Level</label>
                            <div>
                                <input type="number" name="emergency_level" id="emergency_level" value="<?= htmlspecialchars($old_product_data['emergency_level'] ?? '') ?>"  class="input_style" placeholder="Enter Emergency Level" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="reorder_level" class="form-label reorder_level_label">Reorder Level</label>
                            <div>
                                <input type="number" name="reorder_level" id="reorder_level" value="<?= htmlspecialchars($old_product_data['reorder_level'] ?? '') ?>" class="input_style" placeholder="Enter Reorder Level" maxlength="10">
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="main_prod_img" class="img_upload" name="main_prod_img">
                            </div>
                            <div>
                                <img id="previewImage" src="../../<?= !empty($prod_img) ? $prod_img : '' ?>"  width="100px"/>
                            </div>
                        </div>
                        <script>
                            document.getElementById('main_prod_img').addEventListener('change', function(event){
                            const file = event.target.files[0];
                            const reader = new FileReader();
                            reader.onload = function(event){
                                document.getElementById('previewImage').src = event.target.result;
                            };
                            reader.readAsDataURL(file);
                            });
                        </script>
                        <div class="form-div">
                            <label for="publish" class="form-label">Publish</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="p_status" id="publish" class="input_style" value="1" <?= isset($old_product_data['is_loose']) && $old_product_data['is_loose'] == '1' ? 'checked' : '' ?>>Publish
                                <input type="radio" name="p_status" id="publish" class="input_style" value="0" <?= isset($old_product_data['is_loose']) && $old_product_data['is_loose'] == '0' ? 'checked' : '' ?>>Unpublish
                            </div>
                        </div>
                </div>
            
        </div>
        <div class="add_btnDiv">
            <input type="submit" value="Add Product" class="add_btn" name="product_add">
        </div>
        <?php } ?>
        </form>
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
    </div>
    <div>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>
    <script>

document.addEventListener("DOMContentLoaded", function () {
    const looseRadios = document.querySelectorAll('input[name="is_loose"]');
    const variationField = document.getElementById('variation');

    const initialVariationValue = variationField.value;

    looseRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === "1" && this.checked) {
                variationField.value = "Loose";
            } else if (this.value === "0" && this.checked) {
                variationField.value = "";
            }
        });
    });
});
        //Image Preview
        document.getElementById('main_prod_img').addEventListener('change', function(event){
            const file = event.target.files[0];
            const reader = new FileReader();
            reader.onload = function(event){
                document.getElementById('previewImage').src = event.target.result;
            };
            reader.readAsDataURL(file);
        });
        //Barcode Generation
        document.getElementById("generateBarcode").addEventListener("click", function() {
            var randomNumber = Math.floor(Math.random() * 1000000);
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "checkBarcode.php?barcode=" + randomNumber, true);
            xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                if (xhr.responseText === "exists") {
                    console.log("Barcode already exists. Generating a new one...");
                    document.getElementById("barcode").value = "";
                    document.getElementById("generateBarcode").click();
                } else {
                    console.log("Barcode doesn't exist. Proceeding...");
                    document.getElementById("barcode").value = randomNumber;
                }
            }
        };
        xhr.send();
    });

    document.getElementById("unit").addEventListener("change", function () {
        const selectedUnit = this.value;
        const variationField = document.getElementById("variation");

        if (variationField && variationField.value === "Loose") {
            document.querySelector(".emergency_level_label").textContent = `Emergency Level ( in ${selectedUnit}):`;
            document.querySelector(".reorder_level_label").textContent = `Reorder Level ( in ${selectedUnit}):`;
        }
    });

    // Multiple Images
    // <div class="img_input">
    //      <div class="file_upload">
    //           <i class="fa-3x fa fa-search" aria-hidden="true"></i>
    //           <span>Search Images to Upload</span>
    //              <input type="file" id="prod_img" class="img_upload" name="prod_img[]" multiple>
    //      </div>
    //      <div id="previewContainer" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
    // </div>                          


    //  let selectedFiles = [];
     
    //  document.getElementById('prod_img').addEventListener('change', function(event) {
    //      const files = event.target.files;
    //      const previewContainer = document.getElementById('previewContainer');
 
    //      for (let i = 0; i < files.length; i++) {
    //          const file = files[i];
 
    //          selectedFiles.push(file);
 
    //          const imageDiv = document.createElement('div');
    //          imageDiv.classList.add('image-preview');
             
 
    //          const imgElement = document.createElement('img');
    //          imgElement.src = URL.createObjectURL(file);
    //          imgElement.classList.add('preview-image');
 
    //          const deleteButton = document.createElement('button');
    //          deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
    //          deleteButton.classList.add('delete-button');
 
 
    //          deleteButton.addEventListener('click', function() {
 
    //              const index = selectedFiles.indexOf(file);
    //              if (index > -1) {
    //                  selectedFiles.splice(index, 1);
    //              }
 
    //              previewContainer.removeChild(imageDiv);
    //          });
 
 
    //          imageDiv.appendChild(imgElement);
    //          imageDiv.appendChild(deleteButton);
    //          previewContainer.appendChild(imageDiv);
    //      }
    //  });
 
    //  const form = document.querySelector('form');
    //  form.addEventListener('submit', function(event) {
 
    //      const inputFile = document.getElementById('prod_img');
    //      const dataTransfer = new DataTransfer();
 
    //      selectedFiles.forEach(file => {
    //          dataTransfer.items.add(file);
    //      });
 
    //      inputFile.files = dataTransfer.files;
    //  });

 </script>

<!-- Search Dropdown -->

</script>
    <?php
$category_name=[];
$category = $mysqli->query("SELECT `id`,`title` FROM `e_category_details` WHERE active=1 AND cos_id='$cos_id' GROUP BY id ORDER BY title");
while($row = $category->fetch_assoc()){
    $category_name[]=$row; 
}

$subcategory_name = [];
$subcategory = $mysqli->query("SELECT `id`, `title`,`c_id` FROM `e_subcategory_details` WHERE active=1 AND cos_id='$cos_id' GROUP BY id ORDER BY title");
while ($row = $subcategory->fetch_assoc()) {
    $subcategory_name[] = $row;
}
?>
<script>
var categories = <?php echo json_encode($category_name); ?>;
var subcategories = <?php echo json_encode($subcategory_name); ?>;

var currentFocusCategory = -1;
var currentFocusSubcategory = -1;

const categorySearchBox = document.querySelector('input[name="pcategory"]');
const categoryDropdown = document.getElementById('dropdown');
const categoryHiddenInput = document.getElementById('category-id');

const subcategorySearchBox = document.querySelector('input[name="psubcategory"]');
const subcategoryDropdown = document.getElementById('dropdown-subcategory');
const subcategoryHiddenInput = document.getElementById('subcategory-id');
subcategorySearchBox.disabled = true;

function showFilteredItems(searchTerm, dropdown, data, hiddenInput, onSelectCallback) {
    dropdown.innerHTML = '';
    const filteredData = data.filter(item => item.title.toLowerCase().includes(searchTerm.toLowerCase()));
    if (filteredData.length > 0) {
        filteredData.forEach(item => {
            var option = document.createElement('div');
            option.textContent = item.title;
            option.classList.add('dropdown-item');
            option.addEventListener('click', () => {
                selectItem(item, dropdown, hiddenInput, onSelectCallback);
            });
            dropdown.appendChild(option);
        });
        dropdown.style.display = 'block';
    } else {
        dropdown.innerHTML = '<div class="dropdown-item no-results">No results found</div>';
        dropdown.style.display = 'block';
    }
}

function selectItem(item, dropdown, hiddenInput, onSelectCallback) {
    dropdown.previousElementSibling.value = item.title;
    hiddenInput.value = item.id;
    dropdown.innerHTML = '';
    dropdown.style.display = 'none';

    if (onSelectCallback) {
        onSelectCallback(item.id);
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
    for (var i = 0; i < items.length; i++) {
        items[i].classList.remove('active');
    }
}

function attachSearchListener(searchBox, dropdown, data, hiddenInput, onSelectCallback, focusVar) {
    searchBox.addEventListener('input', function() {
        var searchTerm = this.value.trim();
        if (searchTerm.length >= 2) {
            showFilteredItems(searchTerm, dropdown, data, hiddenInput, onSelectCallback);
        } else {
            showFilteredItems('', dropdown, data, hiddenInput, onSelectCallback);
        }
    });

    searchBox.addEventListener('keydown', function(e) {
        var items = dropdown.getElementsByClassName('dropdown-item');
        if (e.key === 'ArrowDown') {
            focusVar++;
            if (focusVar >= items.length) focusVar = 0;
            addActive(items, focusVar);
            e.preventDefault();
        } else if (e.key === 'ArrowUp') {
            focusVar--;
            if (focusVar < 0) focusVar = items.length - 1;
            addActive(items, focusVar);
            e.preventDefault();
        } else if (e.key === 'Enter') {
            e.preventDefault();
            if (focusVar > -1 && items[focusVar]) {
                items[focusVar].click();
            }
        }
    });
}

if (categorySearchBox && categoryDropdown && categoryHiddenInput) {
    attachSearchListener(categorySearchBox, categoryDropdown, categories, categoryHiddenInput, onCategorySelect, currentFocusCategory);

    categorySearchBox.addEventListener('focus', function() {
        showFilteredItems('', categoryDropdown, categories, categoryHiddenInput, onCategorySelect);
    });

    categorySearchBox.addEventListener('blur', function() {
        setTimeout(() => {
            categoryDropdown.style.display = 'none';
            categoryDropdown.innerHTML = '';
            currentFocusCategory = -1;
        }, 200);
    });
}

function onCategorySelect(categoryId) {
    const filteredSubcategories = subcategories.filter(subcategory => subcategory.c_id === categoryId);

    subcategorySearchBox.value = '';
    subcategoryHiddenInput.value = '';

    attachSubcategoryListener(filteredSubcategories);
    subcategorySearchBox.focus();
}

function attachSubcategoryListener(filteredSubcategories) {
    if (filteredSubcategories.length > 0) {
        subcategorySearchBox.disabled = false;
        showFilteredItems('', subcategoryDropdown, filteredSubcategories, subcategoryHiddenInput, null);
    } else {
        subcategorySearchBox.disabled = true;
        subcategoryDropdown.innerHTML = '<div class="dropdown-item no-results">No subcategories available</div>';
        subcategoryDropdown.style.display = 'block';

        setTimeout(() => {
            subcategoryDropdown.style.display = 'none';
            subcategoryDropdown.innerHTML = '';
        }, 3000);
    }

    attachSearchListener(subcategorySearchBox, subcategoryDropdown, filteredSubcategories, subcategoryHiddenInput, null, currentFocusSubcategory);

    subcategorySearchBox.addEventListener('blur', function() {
        setTimeout(() => {
            subcategoryDropdown.style.display = 'none';
            subcategoryDropdown.innerHTML = '';
            currentFocusSubcategory = -1;
        }, 150);
    });
}
</script>

<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>