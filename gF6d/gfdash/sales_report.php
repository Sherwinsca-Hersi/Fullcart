<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
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
    <div class="sales_rightbar container">
        <h2>Sales Report</h2>
        <div class="searchbar_sect">
            <div>
                <button type="button" class="export_btn">Export</button>
            </div>
            <div class="search_div">
                <form>
                    <input type="search" placeholder="Product Name, SKU Id, Batch No.">
                    <button type="submit">Search</button>
                </form>
            </div>
        </div>
        <div class="btn_sect">
                <button type="button" class="filter_btn"><img src="..\assets\images\filter_icon.png">Filter</button>
                <button type="button" class="default_btn">22:02:24 to 25:02:24</button>
                <div class="sales_div">Total Sales Amount<span>₹ 2500</span></div>
                <div class="action_sect">
                    <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon">     
                    <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon"> 
                </div> 
        </div>
        <div>
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style">
                    <thead class="table_head">
                        <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;SKU ID</th>
                        <th>Product Name</th>
                        <th>Item Sold</th>
                        <th>Total Amount</th>
                        <th>Date of Sale</th>
                    </thead>
                    <tr class="todd">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                    <tr class="teven">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                    <tr class="todd">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                    <tr class="teven">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                    <tr class="todd">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                    <tr class="teven">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                    <tr class="todd">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                    <tr class="teven">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;00123</td>
                        <td>vim pudina</td>
                        <td>20</td>
                        <td>₹ 100.00</td>
                        <td>2024-02-15</td>
                    </tr>
                </table>
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
   <div class="popup" id="delete_popup">
    <h4>Are you really want to delete this?</h4>
    <div class="popup_btns">
        <button class="yes_btn">Yes</button>
        <button class="popup_cancel" id="del_cancel_btn">Cancel</button>
    </div>
</div> 
<script src="../assets/js/common_script.js"></script>
</body>
</html>