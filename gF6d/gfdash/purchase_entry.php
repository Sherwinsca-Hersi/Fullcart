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
    <title>Purchase Entry</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous"> -->
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
    <div class="report_rightbar container">


        <h2>Purchase Entry</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Product Name, Batch No.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="stock_btn_sect">
                <div>
                    <button type="button" class="export_btn" onclick="exportData()">Export</button>
                </div>
            </div>
        </div>
        <div class="btn_sect">
            <div class="btn_grp">
                <button type="button" class="filter_btn"><img src="..\assets\images\filter_icon.png" alt="filter-icon-img">Filter</button>
                <button type="button" class="default_btn">Total Sales</button>
                <button type="button" class="default_btn">Online Sales</button>
                <button type="button" class="default_btn">Reorder Level</button>
                <button type="button" class="default_btn">Low Stock Alerts</button>
                <button type="button" class="default_btn">22:02:24 to 25:02:24</button>  
            </div>
            <div class="action_sect">
                <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon" alt="delete-icon-img">     
                <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon" alt="edit-icon-img"> 
            </div> 
        </div>
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,3,4">
                    <thead class="table_head">
                        <!-- <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th> -->
                        <th>S.No</th>
                        <th>Invoice No</th>
                        <th>Supplier Name</th>
                        <th>Supplier Mobile No</th>
                        <th>Stock Bill</th>
                    </thead>
                    <tbody>
                    <?php
                        $i=1;
                        foreach($purchaseEntry as $purchaseEntry):
                        ?>
                        <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>"  onclick="redirect(this, <?php echo $purchaseEntry['id'];?>)">
                        <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;<?php echo $i; ?></td> -->
                        <td><?php echo $i; ?></td>
                        <td class="hightlight"><?php echo $purchaseEntry['invoice_no']?? 'N/A'; ?></td>
                        
                        <td class="hightlight">
                    <?php  
                    $vendors_name = $mysqli->query("select v_name from e_vendor_details where cos_id = '$cos_id' and v_id=".$purchaseEntry['supplier_id']."")->fetch_assoc();
                    echo $vendors_name['v_name'] ?? 'N/A';
                    ?>
                </td>
                <td>
                    <?php  
                    $vendors_phone = $mysqli->query("select v_mobile from e_vendor_details where cos_id = '$cos_id' and v_id=".$purchaseEntry['supplier_id']."")->fetch_assoc();
                    echo $vendors_phone['v_mobile'] ?? 'N/A';
                    ?>
                </td>
                <td style="width:100px;height:100px">
                    <a href="../<?php echo $purchaseEntry['stock_bill'];?>" target="_blank"><?php if($purchaseEntry['stock_bill'] !=NULL){
                    ?>
                        <img src="../../<?php echo $purchaseEntry['stock_bill']; ?>" width="100" height="100">
                    </a>
                </td>
                <?php
                }
                else{
                    echo 'N/A';
                }
                ?>
                    </tr>
                    
                    <?php
                    $i++;
                        endforeach;
                    ?>
                </tbody>
            </table>
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
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- <script src="../assets/js/common_script.js"></script> -->
<?php 
    require 'footer.php';
?>
<script>
function redirect(row, purchaseId) {
    window.location.href = 'multipleEachStock.php?purchaseid=' + purchaseId;
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
