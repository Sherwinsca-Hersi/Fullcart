<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Sales Report</title>
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
    <div class="customer_sales_rightbar container">
        <h2>Customer Sales Report</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Customer.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addEmployee_sect">
                <button type="button" class="export_btn" onclick="exportData()">Export</button>
            </div>
        </div>
        <div class="employee_action">
            <!-- <button type="button" class="filter_btn"><img src="..\assets\images\filter_icon.png" alt="filter-icon-img">Filter</button>
            <button type="button" class="default_btn">22:02:24 to 25:02:24</button>
            <button type="button" class="default_btn">Vinoth Raj</button>
            <div class="sales_div">Total Sales Amount<span>₹ 2500</span></div> -->
            <div class="action_sect">
                <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon" alt="delete-icon-img">     
                <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon" alt="edit-icon-img"> 
            </div> 
        </div>   
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,4">
                    <thead class="table_head">
                        <!-- <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th> -->
                        <th>S.No</th>
                        <th>Invoice No</th>
                        <th>Order Date</th>
                        <th>Bill Amount</th>
                        <th>Bill Image</th>
                    </thead>
                    <?php
                    if(isset($_GET['customer_id'])){
                        $cust_sales_query=$mysqli->query("SELECT `id`, `cos_id`, `u_id`, `invoice_no`, `o_date`,
                         `p_method_id`, `address`, `landmark`, `d_charge`, `cou_id`, `cou_amt`, `o_total`,
                          `subtotal`,`trans_id`, `payment_active`, `salesman_id`, `wall_amt`, `name`, `mobile`,
                        `status`, `bill_type`, `bank_trans_id`, `upi_id`, `recon_status`, `active` 
                        FROM `e_normal_order_details` WHERE cos_id='$cos_id' and active=1 and 
                        u_id=".$_GET['customer_id']." ORDER BY created_ts DESC");
                    }
                    else{
                        $cust_sales_query=$mysqli->query("SELECT `id`, `cos_id`, `u_id`, `invoice_no`, `o_date`,
                         `p_method_id`, `address`, `landmark`, `d_charge`, `cou_id`, `cou_amt`, `o_total`,
                          `subtotal`,`trans_id`, `payment_active`, `salesman_id`, `wall_amt`, `name`, `mobile`,
                        `status`, `bill_type`, `bank_trans_id`, `upi_id`, `recon_status`, `active` FROM `e_normal_order_details` WHERE cos_id='$cos_id' and active=1 ORDER BY created_ts DESC");
                    }

                    $cust_sales_details=[];
                    while ($cust_sales_table=$cust_sales_query->fetch_assoc()){
                        $cust_sales_details[]=$cust_sales_table;
                    }
                    $i=1;
                    foreach($cust_sales_details as $cust_sales_detail):
                    ?>
                    
                    <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
                        <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $customer_detail['user_id'];?>">&emsp;<?php  echo $i; ?></td> -->
                        <td><?php  echo $i; ?></td>
                        <td><?php  echo $cust_sales_detail['invoice_no'];?></td>
                        <td><?php  echo $cust_sales_detail['o_date'];?></td>
                        <td><?php  echo $cust_sales_detail['subtotal'];?></td>
                        <td><button class="view_btn">View</button></td>
                    </tr>
                    <?php
                        $i++;
                        endforeach;
                    ?>
                </table>
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
    
<div class="popup" id="delete_popup">
    <h4>Are you really want to delete this?</h4>
    <div class="popup_btns">
        <button class="yes_btn" id="delete_icon">Yes</button>
        <button class="popup_cancel" id="del_cancel_btn">Cancel</button>
    </div>
</div> 
<script>

// editIcon.addEventListener('click', function(){
//     const { checkedRowIds, selectedBatchValues } = displayCheck();
//     if (checkedRowIds.length > 0) {
//     const url = `addCustomer.php?customerid=${checkedRowIds.join(',')}`;
//     window.location.href = url;
//     } else {
//     alert('Please select one row to edit.');
//   }
// });

// const deleteIcon=document.getElementById('delete_icon');
// deleteIcon.addEventListener('click', function(){
//     const { checkedRowIds, selectedBatchValues } = displayCheck();
//     if (checkedRowIds.length > 0) {
//     const url = `com_ins_upd.php?cust_dids=${checkedRowIds.join(',')}`;
//     window.location.href = url;
//     } else {
//     alert('Please select at least one row to delete.');
//   }
// });

</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<!-- <script src="../assets/js/common_script.js"></script> -->
<?php 
    require 'footer.php';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
        
        const fetchedData = [
            <?php
            $export_query=$mysqli->query("select u.name,u.mobile,u.email_id,u.whatsapp,address_line_1,landmark,area,city,state,country FROM `e_user_details` u LEFT JOIN e_address_details a ON u.id = a.user_id  WHERE u.cos_id = '$cos_id' AND a.cos_id='$cos_id' AND u.active=1 AND a.active=1");

     
            $customer_details= [];
            while ($customer_export = $export_query->fetch_assoc()){
                $customer_details[] = $customer_export;
            }
            foreach ($customer_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
            
            let wsData = fetchedData;

            
            let wb = XLSX.utils.book_new();
           
            let ws = XLSX.utils.aoa_to_sheet(wsData);
            
            XLSX.utils.book_append_sheet(wb, ws, "Customer Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "customer_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>