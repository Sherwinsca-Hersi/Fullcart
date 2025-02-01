<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Batches</title>
    <?php 
        require_once '../api/header.php';
    ?>
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
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
    <div class="product_rightbar container" id="divprint">
        <h2>Product Batches</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Product Name, Model No, Barcode.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addBanner_sect">
                <!-- <a href="addproduct.php" class="export_btn employee_link"> Add Product </a> -->
                <!-- <button type="button" class="export_btn"  onclick="exportData()" id="exportButton">Export</button> -->
            </div>
        </div>
        <div class="btn_sect">
            <div class="btn_grp">
                <button type="button" class="filter_btn"><img src="..\assets\images\filter_icon.png" alt="Filter Icon-img">Filter</button>
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
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="4,9,10">
                    <thead class="table_head">
                        <!-- <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th> -->
                        <th>S.No</th>
                        <th>Model No</th>
                        <th>Batch No</th>
                        <th>Product Name</th>
                        <th>MRP</th>
                        <th>In-Price</th>
                        <th>Out-Price</th>
                        <th>Expiry Date</th>
                        <th>Stock-In Count</th>
                        <th><?php echo $vendor; ?> Name</th>
                        <th><?php echo $vendor; ?> Phone</th>
                        <th>Stock Updated On</th>
                    </thead>
                    <tbody>
                    <?php
                    $product_stock=$mysqli->query("SELECT `s_id`, `cos_id`, `s_product_id`, `qty`, `stock_date`,
                     `supplier_id`, `s_batch_no`, `s_expiry_date`, `in_price`, `s_mrp`, `s_out_price`, `invoice_no`,
                      `stock_bill`, `purchase_id`,`active`,`updated_ts`,`created_ts` FROM `e_product_stock` 
                      WHERE s_product_id=".$_GET['product_id']." AND cos_id='$cos_id'");    
                      
                    $stock_details=[];
                    while($product_stock_details=$product_stock->fetch_assoc()){
                        $stock_details[]=$product_stock_details;
                    }
                    $i=1;
                        foreach($stock_details as $stock_detail):
                        ?>
                        <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
                        <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;<?php echo $i; ?></td> -->
                        <td><?php echo $i; ?></td>
                        <td><?php $stock_skuid=$mysqli->query("SELECT sku_id FROM e_product_details 
                        WHERE id=".$stock_detail['s_product_id']." AND cos_id='$cos_id'")->fetch_assoc();
                            echo $stock_skuid['sku_id'];
                        ?>
                        </td>
                        <td class="highlight"><?php echo $stock_detail['s_batch_no']; ?></td>
                        <td>
                            <?php $product_name=$mysqli->query("SELECT p_title,p_variation,unit FROM e_product_details WHERE 
                              id=".$stock_detail['s_product_id']." AND cos_id='$cos_id'")->fetch_assoc();
                                echo $product_name['p_title']." ".$product_name['p_variation']." ".$product_name['unit'];
                            ?>
                        </td>
                        <td><?php echo $stock_detail['s_mrp']; ?></td>
                        <td><?php echo $stock_detail['in_price']; ?></td>
                        <td><?php echo $stock_detail['s_out_price']; ?></td>
                        <td data-sort='<?php echo date_format(date_create($stock_detail['s_expiry_date']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($stock_detail['s_expiry_date']);
                                echo date_format($date, "d/m/Y");
                            ?>
                        </td>
                        <td class="highlight"><?php echo $stock_detail['qty']; ?></td>
                        <td>
                    <?php  
                    $vendors_name = $mysqli->query("SELECT v_name FROM e_vendor_details WHERE 
                     v_id=".$stock_detail['supplier_id']." AND cos_id = '$cos_id'")->fetch_assoc();
                    echo $vendors_name['v_name'] ?? 'N/A';
                    ?>
                </td>
                <td>
                    <?php  
                    $vendors_phone = $mysqli->query("SELECT v_mobile FROM e_vendor_details WHERE 
                     v_id=".$stock_detail['supplier_id']." AND cos_id = '$cos_id'")->fetch_assoc();
                    echo $vendors_phone['v_mobile'] ?? 'N/A';
                    ?>
                </td>
                <td data-sort='<?php echo date_format(date_create($stock_detail['updated_ts']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($stock_detail['updated_ts']);
                                echo date_format($date, "d/m/Y h:i A");
                            ?>
                </td>
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

<div class="popup" id="delete_popup">
    <h4>Are you really want to delete this?</h4>
    <div class="popup_btns">
        <button class="yes_btn" id="delete_icon">Yes</button>
        <button class="popup_cancel" id="del_cancel_btn">Cancel</button>
    </div>
</div>
<script>

editIcon.addEventListener('click', function() {
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    console.log(checkedRowIds)
    window.location.href = `addproduct.php?productid=${checkedRowIds.join(',')}`;
  } else {
    alert('Please select a row to edit.');
  }
});

const deleteIcon=document.getElementById('delete_icon');
deleteIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();

  if (checkedRowIds.length > 0) {
    const selectedRowIds = checkedRowIds.join(',');
    const selectedBatchValuesStr = selectedBatchValues.filter(value => value !== null).join(',');

    let url = `com_ins_upd.php?product_dids=${selectedRowIds}`;
    if (selectedBatchValuesStr) {
      url += `&prod_batch_dids=${selectedBatchValuesStr}`;
    }
    window.location.href = url;
  } else {
    alert('Please select at least one row to delete.');
  }
});

</script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="../assets/js/common_script.js"></script>
<?php 
    require 'footer.php';
?>


<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>
