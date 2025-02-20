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

    $main_prod_img=$_SESSION['old_product_data']['main_prod_img'];

    $prod_img = $_SESSION['old_product_data']['prod_img'];

    $imagePaths = explode('|', $prod_img);
    
    unset($_SESSION['old_product_data']);
}
?>

<script>
    const imagePaths = <?php echo json_encode($imagePaths); ?>;
    console.log(imagePaths);
</script>

<?php 
unset($_SESSION['old_product_data']);

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
				$data = $mysqli->query("SELECT *
                FROM `e_product_details`
                WHERE cos_id = '$cos_id'  and active!=2  and id=".$_GET['productid']."")->fetch_assoc();
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
                            <label for="sku_id" class="form-label">Model No</label>
                            <div>
                                <input type="text" name="sku_id"  class="input_style" placeholder="Enter Model No" value="<?php if(!($data['sku_id']==NULL || '')){ echo $data['sku_id'];}?>" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="hsn_code" class="form-label">HSN Code</label>
                            <div>
                                <input type="text" name="hsn_code"  class="input_style" placeholder="Enter HSN Code" value="<?php if(!($data['hsn_code']==NULL || '')){ echo $data['hsn_code'];}?>" maxlength="10">
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
                            <label for="pVariation" class="form-label">Variation</label>
                            <div>
                                <input type="text" name="p_variation" class="input_style" placeholder="Enter Variation" value="<?php if(!($data['p_variation']==NULL || '')){echo $data['p_variation'];}?>" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="unit" class="form-label">Unit</label>
                            <div>
                                <input type="text" name="unit" class="input_style" placeholder="Enter Unit" value="<?php if(!($data['unit']==NULL || '')){echo $data['unit'];}?>" maxlength="10">
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
                                <script>
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
                                </script>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="imprint" class="form-label">Imprinting</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="imprint" id="imprint" class="input_style" value="1" <?php if($data['is_loose']==1){echo "checked";}?>>Enable
                                <input type="radio" name="imprint" id="imprint" class="input_style" value="0" <?php if($data['is_loose']==0){echo "checked";}?>>Disable
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="publish" class="form-label">Publish</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="p_status" id="publish" class="input_style" value="1" <?php if($data['active']==1){echo "checked";}?>>Published
                                <input type="radio" name="p_status" id="publish" class="input_style" value="0" <?php if($data['active']==0){echo "checked";}?>>Unpublished
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
                            </div>
                        </div>
                        
                        <!-- Multiple Images -->
<div class="img_input">
    <div class="file_upload">
        <i class="fa-3x fa fa-search" aria-hidden="true"></i>
        <span>Multiple Image Upload</span>
        <input type="file" id="prod_img" class="img_upload" name="prod_img[]" multiple>
    </div>
    <div id="previewContainer" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
</div>

<script>
let selectedFiles = [];
let existingFiles = [];
let deletedFiles = [];


function displayImagePreview(src, isExisting = false, fileIndex = null) {
    const previewContainer = document.getElementById('previewContainer');
    

    const imageDiv = document.createElement('div');
    imageDiv.classList.add('image-preview');

    const imgElement = document.createElement('img');
    imgElement.classList.add('preview-image');

    if (isExisting) {
        imgElement.src = src;
    } else {
        imgElement.src = URL.createObjectURL(src);
    }

    const deleteButton = document.createElement('button');
    deleteButton.innerHTML = '<i class="fa fa-trash-alt"></i>';
    deleteButton.classList.add('delete-button');

    deleteButton.addEventListener('click', function() {
        if (isExisting) {
            existingFiles = existingFiles.filter(file => file !== src);
            deletedFiles.push(src);
        } else {
            selectedFiles = selectedFiles.filter((_, index) => index !== fileIndex);
        }

        previewContainer.removeChild(imageDiv);

        if (!isExisting && src instanceof Blob) {
            URL.revokeObjectURL(src);
        }

        refreshFileIndexes();
    });

    imageDiv.appendChild(imgElement);
    imageDiv.appendChild(deleteButton);
    previewContainer.appendChild(imageDiv);
}

function refreshFileIndexes() {
    const previewImages = document.querySelectorAll('.image-preview img');
    previewImages.forEach((img, index) => {
        const imgSrc = img.src;

        const fileIndex = selectedFiles.findIndex(file => URL.createObjectURL(file) === imgSrc);
        
        if (fileIndex !== -1 && fileIndex !== index) {
            const tempFile = selectedFiles[fileIndex];
            selectedFiles.splice(fileIndex, 1);
            selectedFiles.splice(index, 0, tempFile);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {
    const existingImagePaths = "<?php echo $data['imgs'] ?? ''; ?>";
    if (existingImagePaths.trim() !== '') {
        const existingFiles = existingImagePaths.split('|');

        existingFiles.forEach(filePath => {
            const fullPath = '../../' + filePath;
            displayImagePreview(fullPath, true);
        });
    }
});


document.getElementById('prod_img').addEventListener('change', function(event) {
    const files = event.target.files;

    for (let i = 0; i < files.length; i++) {
        const file = files[i];
        selectedFiles.push(file);
        displayImagePreview(file, false, selectedFiles.length - 1);
    }
});

const form = document.querySelector('form');
form.addEventListener('submit', function(event) {

    const inputFile = document.getElementById('prod_img');
   
    const dataTransfer = new DataTransfer();

    selectedFiles.forEach(file => {
        if (file instanceof File) {
            dataTransfer.items.add(file);
        }
    });

    inputFile.files = dataTransfer.files;

    const existingImagesInput = document.createElement('input');
    existingImagesInput.type = 'hidden';
    existingImagesInput.name = 'existing_images';
    existingImagesInput.value = existingFiles.join('|');
    form.appendChild(existingImagesInput);

    const deletedImagesInput = document.createElement('input');
    deletedImagesInput.type = 'hidden';
    deletedImagesInput.name = 'deleted_images';
    deletedImagesInput.value = deletedFiles.join('|');
    form.appendChild(deletedImagesInput);


    console.log(existingImagesInput.value);
    console.log(deletedImagesInput.value);

    console.log("Form data preview complete. Form submission is stopped.");
});
</script>
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
                            <label for="sku_id" class="form-label">Model No</label>
                            <div>
                                <input type="text" name="sku_id" value="<?= htmlspecialchars($old_product_data['sku_id'] ?? '') ?>" class="input_style" placeholder="Enter Model No" maxlength="10">
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="hsn_code" class="form-label">HSN Code</label>
                            <div>
                                <input type="text" name="hsn_code" value="<?= htmlspecialchars($old_product_data['hsn_code'] ?? '') ?>" class="input_style" placeholder="Enter HSN Code" maxlength="10">
                            </div>
                        </div>
                        <!-- <div class="form-div">
                            <label for="pcategory" class="form-label">Category<span class="star">*</span></label>
                            <div>
                                <select name="pcategory" class="input_style" placeholder="Select Product Category" required>
                                    <option value=""  class="option_style" disabled selected>Select Product Category</option>
                                    <?php
                                     $cat = $mysqli->query("select * from e_category_details where cos_id = '$cos_id' and active=1");
                                    while($row = $cat->fetch_assoc())
                                    {
	                                ?>
	                                    <option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>1
	                                    <?php 
                                        }	
									   ?>
                                </select>
                            </div>
                        </div> -->
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
                        <!-- <div class="form-div">
                            <label for="psub_category" class="form-label">Sub Category<span class="star">*</span></label>
                            <div>
                                <select name="psub_category" class="input_style" placeholder="Select Product Subcategory" required>
                                    <option value=""  class="option_style" disabled selected>Select Product Subcategory</option>
                                    <?php
                                        $cat = $mysqli->query("select * from e_subcategory_details where cos_id = '$cos_id' and active=1");
                                        while($row = $cat->fetch_assoc())
                                        {
	                                    ?>
	                                        <option value="<?php echo $row['id'];?>"><?php echo $row['title'];?></option>
	                                    <?php 
                                        }	
									    ?>
                                </select>
                            </div>
                        </div> -->
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
                            <label for="pVariation" class="form-label">Variation<span class="star">*</span></label>
                            <div>
                                <input type="text" name="p_variation" class="input_style" placeholder="Enter Variation" value="<?= htmlspecialchars($old_product_data['p_variation'] ?? '') ?>" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="unit" class="form-label">Unit<span class="star">*</span></label>
                            <div>
                                <input type="text" name="unit" class="input_style" placeholder="Enter Unit" value="<?= htmlspecialchars($old_product_data['unit'] ?? '') ?>" required>
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
                                    <input type="text" name="barcode" id="barcode" class="input_style" value="<?= htmlspecialchars($old_product_data['barcode'] ?? '') ?>" placeholder="Barcode" maxlength="12" required>
                                    <button type="button" class="barcode_btn" id="generateBarcode"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                                </div>
                                
                                <script>
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
                                </script>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="imprint" class="form-label">Imprinting</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="imprint" id="imprint" class="input_style" value="1" <?= isset($old_product_data['imprint']) && $old_product_data['imprint'] == '1' ? 'checked' : '' ?>>Enable
                                <input type="radio" name="imprint" id="imprint" class="input_style" value="0" <?= isset($old_product_data['imprint']) && $old_product_data['imprint'] == '0' ? 'checked' : '' ?>>Disable
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="publish" class="form-label">Publish</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="p_status" id="publish" class="input_style" value="1" <?= isset($old_product_data['p_status']) && $old_product_data['p_status'] == '1' ? 'checked' : '' ?>>Publish
                                <input type="radio" name="p_status" id="publish" class="input_style" value="0" <?= isset($old_product_data['p_status']) && $old_product_data['p_status'] == '0' ? 'checked' : '' ?>>Unpublish
                            </div>
                        </div>
                        
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="main_prod_img" class="img_upload" name="main_prod_img" required>
                            </div>
                            <div>
                                <img id="previewImage" src="../../<?= !empty($main_prod_img) ? $main_prod_img : '' ?>" width="100px"/>
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

                        <!-- Multiple Images -->
                         
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Multiple Image Upload</span>
                                <input type="file" id="prod_img" class="img_upload" name="prod_img[]" multiple>
                            </div>
                            <div id="previewContainer" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>
                            <script>
    const previewContainer = document.getElementById('previewContainer');
    
    imagePaths.forEach(function(imagePath) {
        
        const imageDiv = document.createElement('div');
        imageDiv.classList.add('image-preview');
        
        const imgElement = document.createElement('img');
        console.log(imagePath);
        imgElement.src = `../../${imagePath}`;
        imgElement.classList.add('preview-image');

        const deleteButton = document.createElement('button');
        deleteButton.innerHTML = 'X';
        deleteButton.classList.add('delete-button');

        deleteButton.addEventListener('click', function() {

            previewContainer.removeChild(imageDiv);
        });

        imageDiv.appendChild(imgElement);
        imageDiv.appendChild(deleteButton);

        previewContainer.appendChild(imageDiv);
    });
</script>
                        </div>

                        <script>
    let selectedFiles = [];
    
    document.getElementById('prod_img').addEventListener('change', function(event) {
        const files = event.target.files;
        const previewContainer = document.getElementById('previewContainer');

        // Loop through each selected file
        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Add the selected file to the selectedFiles array
            selectedFiles.push(file);

            // Create a container for the image preview and delete button
            const imageDiv = document.createElement('div');
            imageDiv.classList.add('image-preview');
            
            // Create the image element
            const imgElement = document.createElement('img');
            imgElement.src = URL.createObjectURL(file);
            imgElement.classList.add('preview-image');

            // Create the delete button
            const deleteButton = document.createElement('button');
            deleteButton.innerHTML = 'X';
            deleteButton.classList.add('delete-button');

            // Add functionality to delete the previewed image
            deleteButton.addEventListener('click', function() {
                // Remove the file from the selectedFiles array
                const index = selectedFiles.indexOf(file);
                if (index > -1) {
                    selectedFiles.splice(index, 1);  // Remove the file from the array
                }
                // Remove the image from the preview container
                previewContainer.removeChild(imageDiv);
            });

            // Append image and delete button to the preview container
            imageDiv.appendChild(imgElement);
            imageDiv.appendChild(deleteButton);
            previewContainer.appendChild(imageDiv);
        }
    });

    // Override the form submit process to ensure that the selected files are sent as an array
    const form = document.querySelector('form');
    form.addEventListener('submit', function(event) {
        // Set the files array to the input element before submitting
        const inputFile = document.getElementById('prod_img');
        const dataTransfer = new DataTransfer();
        
        // Add all selected files to the input's FileList (which is not directly editable)
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        
        // Assign the updated file list to the input field
        inputFile.files = dataTransfer.files;
    });
</script>
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
    <?php
$category_name=[];
$category = $mysqli->query("SELECT `id`,`title` FROM `e_category_details` WHERE cos_id='$cos_id' AND active=1 GROUP BY id ORDER BY title");
while($row = $category->fetch_assoc()){
    $category_name[]=$row; 
}

$subcategory_name = [];
$subcategory = $mysqli->query("SELECT `id`, `title`,`c_id` FROM `e_subcategory_details` WHERE cos_id='$cos_id' AND active=1 GROUP BY id ORDER BY title");
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

// Show filtered items in dropdown
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

// Select an item
function selectItem(item, dropdown, hiddenInput, onSelectCallback) {
    dropdown.previousElementSibling.value = item.title;
    hiddenInput.value = item.id;
    dropdown.innerHTML = '';
    dropdown.style.display = 'none';

    if (onSelectCallback) {
        onSelectCallback(item.id);
    }
}

// Add active class to focused item
function addActive(items, currentFocus) {
    if (!items || items.length === 0) return false;
    removeActive(items);
    if (currentFocus >= items.length) currentFocus = 0;
    if (currentFocus < 0) currentFocus = items.length - 1;
    items[currentFocus].classList.add('active');
    items[currentFocus].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
}

// Remove active class from all items
function removeActive(items) {
    for (var i = 0; i < items.length; i++) {
        items[i].classList.remove('active');
    }
}

// Attach search listeners with keyboard navigation for both dropdowns
function attachSearchListener(searchBox, dropdown, data, hiddenInput, onSelectCallback, focusVar) {
    searchBox.addEventListener('input', function() {
        var searchTerm = this.value.trim();
        if (searchTerm.length >= 2) {
            showFilteredItems(searchTerm, dropdown, data, hiddenInput, onSelectCallback);
        } else {
            showFilteredItems('', dropdown, data, hiddenInput, onSelectCallback); // Show all items if empty
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

// Main initialization
if (categorySearchBox && categoryDropdown && categoryHiddenInput) {
    attachSearchListener(categorySearchBox, categoryDropdown, categories, categoryHiddenInput, onCategorySelect, currentFocusCategory);

    // Show all categories when focused
    categorySearchBox.addEventListener('focus', function() {
        showFilteredItems('', categoryDropdown, categories, categoryHiddenInput, onCategorySelect);
    });

    // Hide dropdown on blur
    categorySearchBox.addEventListener('blur', function() {
        setTimeout(() => {
            categoryDropdown.style.display = 'none';
            categoryDropdown.innerHTML = '';
            currentFocusCategory = -1;
        }, 200);
    });
}

// Callback for selecting a category
function onCategorySelect(categoryId) {
    const filteredSubcategories = subcategories.filter(subcategory => subcategory.c_id === categoryId);

    subcategorySearchBox.value = '';
    subcategoryHiddenInput.value = '';

    attachSubcategoryListener(filteredSubcategories);
    subcategorySearchBox.focus();
}

// Attach subcategory listener with filtered data
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