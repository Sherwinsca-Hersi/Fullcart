<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Banner</title>
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
            if(isset($_GET['bannerid'])){
                echo  "<h2>Update Banner</h2>";
            }
            else{
                echo "<h2>Add Banner</h2>";
            }
        ?>
        <div class="product_form">
            <?php 
			if(isset($_GET['bannerid']))
			{
				$data = $mysqli->query("SELECT id,c_id,bg_img,active FROM e_dat_banner WHERE cos_id = '$cos_id' AND id=".$_GET['bannerid']."")->fetch_assoc();
			?>
            <form class="addBanner_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <!-- <div class="form-div">
                            <label for="b_category" class="form-label">Banner Category</label>
                            <div>
                                <select name="b_category" class="input_style" placeholder="Enter Banner Category">
                                    <option value=""  class="option_style" disabled selected>Banner Category</option>
                                    <?php
                                        $cat = $mysqli->query("SELECT id,title FROM e_category_details WHERE active=1 AND  cos_id = '$cos_id'");
                                    while($row = $cat->fetch_assoc())
                                    {
	                                ?>
                                        <option value="<?php echo $row['id'];?>" <?php if($row['id'] == $data['c_id']) {echo 'selected';} ?>><?php echo $row['title'];?></option>
	                                    <?php 
                                        }	
									   ?>
                                </select>
                            </div>
                        </div> -->
                        <?php
                                $prod_category=$mysqli->query("SELECT id,title FROM `e_category_details` WHERE active=1 AND id='".$data['c_id']."' AND cos_id='$cos_id'")->fetch_assoc();
                            ?>
                            <div class="form-div">
                                <label for="pcategory" class="form-label">Product Category</label>
                                <div class="search-container">
                                    <input type="text" placeholder="Search Categories..." class="input_style search-box" name="pcategory" value="<?php if(!($prod_category['title']==NULL || '')){
                                        echo htmlspecialchars($prod_category['title']); 
                                    }
                                    ?>" required>
                                    <div id="dropdown" class="dropdown">
                                        <!-- Suggestions will be dynamically added here -->
                                    </div>
                                    <input type="hidden" id="category-id" name="category-id" value="<?php if(!($prod_category['title']==NULL || '')){echo htmlspecialchars($prod_category['id']);} ?>">
                                        <!-- other form fields -->
                                </div>
                            </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="b_img" class="img_upload" name="b_img" accept="image/*">
                            </div>
                            <div>
                                <?php if(!($data['bg_img']==NULL || '')){
                                    ?>
                                    <img id="previewImage" src="..\..\<?php echo $data['bg_img'];?>" width="100px"/>
                                <?php
                                } ?>
                            </div>
                        </div>
                            <script>
                                document.getElementById('b_img').addEventListener('change', function(event) {
                                const file = event.target.files[0];
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    document.getElementById('previewImage').src = event.target.result;
                                };
                                reader.readAsDataURL(file);
                                });
                            </script>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="b_status" class="form-label">Banner Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="b_status" class="input_style" value="1" <?php if($data['active'] == 1){echo 'checked';}?>>Publish
                                <input type="radio" name="b_status" class="input_style" value="0" <?php if($data['active'] == 0){echo 'checked';}?>>Unpublish
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="bannerid" value="<?php echo isset($_GET['bannerid']) ? htmlspecialchars($_GET['bannerid']) : ''; ?>">
                    <input type="submit" value="Update Banner" class="add_btn" name="banner_update">
                </div>
            </form>
            <?php 
            }
            else {
            ?>
            <form class="addBanner_form" method="post" action="com_ins_upd.php" id="myForm" onsubmit="return validateForm()" enctype="multipart/form-data" autocomplete="off">
                <div class="grid_col">
                    <div class="grid-col-1">
                        <!-- <div class="form-div">
                            <label for="b_category" class="form-label">Banner <span class="star">*</span></label>
                            <div>
                                <select name="b_category" class="input_style" placeholder="Enter Banner Category" required autofocus>
                                    <option value=""  class="option_style" disabled selected>Banner Category</option>
                                    <?php
                                     $cat = $mysqli->query("SELECT title FROM e_category_details WHERE cos_id = '$cos_id' AND active=1");
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
                                <label for="pcategory" class="form-label">Product Category</label>
                                <div class="search-container">
                                    <input type="text" placeholder="Search Categories..." class="input_style search-box" name="pcategory" required>
                                    <div id="dropdown" class="dropdown">
                                        <!-- Suggestions will be dynamically added here -->
                                    </div>
                                    <input type="hidden" id="category-id" name="category-id">
                                        <!-- other form fields -->
                                </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="b_img" class="img_upload" name="b_img" accept="image/*">
                            </div>
                            <div>
                                <img id="previewImage"   width="100px"/>
                            </div>
                        </div>
                        <script>
                        document.getElementById('b_img').addEventListener('change', function(event) {
                            const file = event.target.files[0];
                            const reader = new FileReader();
                            reader.onload = function(event) {
                                document.getElementById('previewImage').src = event.target.result;
                            };
                            reader.readAsDataURL(file);
                        });
                        </script>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="b_status" class="form-label">Banner Status</label>
                            <div class="radio_btn_div">
                                <input type="radio" name="b_status" class="input_style" value="1">Publish
                                <input type="radio" name="b_status" class="input_style" value="0">Unpublish
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add Banner" class="add_btn" name="banner_add">
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
    <?php
$category_name=[];
$category = $mysqli->query("SELECT `id`,`title` FROM `e_category_details` WHERE cos_id='$cos_id' AND active=1 GROUP BY id ORDER BY title");
while($row = $category->fetch_assoc()){
    $category_name[]=$row; 
}
?>
<script>
    // Search Dropdown script
    var categories = <?php echo json_encode($category_name); ?>;
    console.log(categories);
    var currentFocus = -1;

    const searchBox = document.querySelector('.search-box');
    const dropdown = document.querySelector('.dropdown');
    const categoryIdInput = document.getElementById('category-id'); 

    if (searchBox && dropdown) {
        attachSearchListener(searchBox, dropdown);
    }

    function attachSearchListener(searchBox, dropdown) {
        searchBox.addEventListener('focus', function() {
            dropdown.style.display = 'block';
            displayAllProducts(dropdown); 
        });

        searchBox.addEventListener('input', function() {
            var searchTerm = this.value.trim();
            if (searchTerm.length >= 2) {
                searchProducts(searchTerm, dropdown);
            } else {
                displayAllProducts(dropdown); 
            }
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
                e.preventDefault(); 
                if (currentFocus > -1 && items[currentFocus]) {
                    items[currentFocus].click();
                }
                dropdown.style.display = 'none'; 
            }
        });

        // Hide dropdown on focus out
        searchBox.addEventListener('blur', function() {
            setTimeout(() => {
                dropdown.style.display = 'none';
            }, 150); 
        });
    }

    function displayAllProducts(dropdown) {
        dropdown.innerHTML = ''; 
        categories.forEach(function(category) {
            var option = document.createElement('div');
            option.textContent = category.title;
            option.classList.add('dropdown-item');
            option.addEventListener('click', function() {
                
                searchBox.value = category.title;

            
                categoryIdInput.value = category.id;

             
                dropdown.innerHTML = '';
                dropdown.style.display = 'none';

               
                updateSubcategoryOptions(category.id);
            });
            dropdown.appendChild(option);
        });
        dropdown.style.display = 'block';
    }

    function searchProducts(searchTerm, dropdown) {
        var filteredProducts = categories.filter(function(category) {
            return category.title.toLowerCase().includes(searchTerm.toLowerCase());
        });

        dropdown.innerHTML = '';

        if (filteredProducts.length === 0) {
            var noResults = document.createElement('div');
            noResults.textContent = 'No products found';
            noResults.classList.add('dropdown-item', 'no-results');
            dropdown.appendChild(noResults);
        } else {
            filteredProducts.forEach(function(category) {
                var option = document.createElement('div');
                option.textContent = category.title;
                option.classList.add('dropdown-item');
                option.addEventListener('click', function() {
                    
                    searchBox.value = category.title;

                   
                    categoryIdInput.value = category.id;


                    dropdown.innerHTML = '';
                    dropdown.style.display = 'none';

                    updateSubcategoryOptions(category.id);
                });
                dropdown.appendChild(option);
            });
        }

        dropdown.style.display = 'block';
    }

    function addActive(items) {
        if (!items) return false;
        removeActive(items);
        if (currentFocus >= items.length) currentFocus = 0;
        if (currentFocus < 0) currentFocus = (items.length - 1);
        items[currentFocus].classList.add('active');
        
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

    function updateSubcategoryOptions(categoryId) {
        console.log('Selected Category ID: ', categoryId);
    }
</script>
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>