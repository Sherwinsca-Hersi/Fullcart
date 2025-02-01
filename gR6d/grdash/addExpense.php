<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Expense</title>
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
    <div class="addCoupon_rightbar container">
    <?php
            if(isset($_GET['expenseid'])){
                echo  "<h2>Update Expense</h2>";
            }
            else{
                echo "<h2>Add Expense</h2>";
            }
        ?>
        <div class="banner_form">
        <?php 
			if(isset($_GET['expenseid']))
			{
				$data = $mysqli->query("select  `exp_id`, `cos_id`, `exp_title`, `exp_desc`, `exp_amount`, `exp_date`, `exp_img`, `active` from `e_expense_details`  where cos_id = '$cos_id' and exp_id=".$_GET['expenseid']."")->fetch_assoc();
			?>
            <form class="addcoupon_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data" autocomplete="off" id="myForm" onsubmit="return validateForm()">
                <div class="grid_col">
                    <div class="grid-col-1">
                    <div class="form-div">
                            <label for="exp_title" class="form-label">Expense Title</label>
                            <div>
                                <input type="text" name="exp_title"  class="input_style" value="<?php if(!($data['exp_title'] == NULL || '')){echo $data['exp_title'];}?>" placeholder="Enter Expense Title" maxlength="100" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="exp_date" class="form-label">Expense Date</label>
                            <div>
                                <input type="date" class="input_style" name="exp_date"  value="<?php  if(!($data['exp_date'] == NULL || '')){ echo $data['exp_date'];}?>" placeholder="Enter Expense Date" required>
                            </div>
                        </div>
                        
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="exp_img" class="exp_upload" name="exp_img">
                            </div>
                            <div>
                                <?php
                                    if(!($data['exp_img'] == NULL || '')){
                                ?>
                                    <img id="previewImage"  src="../<?php echo $data['exp_img']?>" width="100px"/>
                                <?php 
                                }?>
                            </div>
                        </div>
                        <script>
                            document.getElementById('exp_img').addEventListener('change', function(event){
                        const file = event.target.files[0];
                        const reader = new FileReader();
                        reader.onload = function(event){
                            document.getElementById('previewImage').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                </script>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="exp_amount" class="form-label">Expense Amount</label>
                            <div>
                                <input type="number" name="exp_amount" class="input_style" value="<?php if(!($data['exp_amount'] == NULL || '')){echo $data['exp_amount'];}?>" maxlength="10" placeholder="Enter Expense Amount" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="exp_desc" class="form-label">Expense Description</label>
                            <div>
                                <textarea rows="6" cols="30" name="exp_desc"  class="input_style" value="<?php  if(!($data['exp_desc'] == NULL || '')){echo $data['exp_desc'];}?>"  placeholder="Enter Expense Description" maxlength="100"><?php echo $data['exp_desc'];?></textarea> 
                            </div>
                        </div>
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="hidden" name="expenseid" value="<?php echo isset($_GET['expenseid']) ? htmlspecialchars($_GET['expenseid']) : ''; ?>">
                    <input type="submit" value="Update Expense" class="add_btn" name="expense_update">
                </div>
            </form>
            <?php
            }
            else {
            ?>
            <form class="addcoupon_form" method="post" action="com_ins_upd.php" enctype="multipart/form-data" autocomplete="off" id="myForm" onsubmit="return validateForm()">
                <div class="grid_col">
                    <div class="grid-col-1">
                    <div class="form-div">
                            <label for="exp_title" class="form-label">Expense Title<span class="star">*</span></label>
                            <div>
                                <input type="text" name="exp_title"  class="input_style"  placeholder="Enter Expense Title" maxlength="100" required autofocus>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="exp_date" class="form-label">Expense Date<span class="star">*</span></label>
                            <div>
                                <input type="date" class="input_style" name="exp_date"   placeholder="Enter Expense Date" required>
                            </div>
                        </div>
                        <div class="img_input">
                            <div class="file_upload">
                                <i class="fa-3x fa fa-search" aria-hidden="true"></i>
                                <span>Search Image to Upload</span>
                                <input type="file" id="exp_img" class="exp_upload" name="exp_img">
                            </div>
                            <div>
                                <img id="previewImage"  width="100px"/>
                            </div>
                        </div>
                        <script>
                            document.getElementById('exp_img').addEventListener('change', function(event){
                        const file = event.target.files[0];
                        const reader = new FileReader();
                        reader.onload = function(event){
                            document.getElementById('previewImage').src = event.target.result;
                        };
                        reader.readAsDataURL(file);
                    });
                </script>
                    </div>
                    <div class="grid-col-2">
                        <div class="form-div">
                            <label for="exp_amount" class="form-label">Expense Amount<span class="star">*</span></label>
                            <div>
                                <input type="number" name="exp_amount" class="input_style"  maxlength="10" placeholder="Enter Expense Amount" required>
                            </div>
                        </div>
                        <div class="form-div">
                            <label for="exp_desc" class="form-label">Expense Description</label>
                            <div>
                                <textarea rows="6" cols="30" name="exp_desc"  class="input_style"  placeholder="Enter Expense Description" maxlength="100"></textarea> 
                            </div>
                        </div>
                        
                    </div>
                </div>
                <div class="add_btnDiv">
                    <input type="submit" value="Add Expense" class="add_btn" name="expense_add">
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
    <div class="popup" id="popup">
        <h4>All unsaved changes will be lost.</h4>
        <div class="popup_btns">
            <button class="price_btn">Price</button>
            <button class="stock_btn">Stock</button>
            <button class="popup_cancel" id="cancel_btn">Cancel</button>
        </div>
    </div>
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
<script src="../assets/js/validateForm.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>