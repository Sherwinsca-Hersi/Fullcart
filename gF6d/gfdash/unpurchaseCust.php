<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unpurchased Customers</title>
    <?php 
        require_once '../api/header.php';
    ?>
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
    <div class="employee_rightbar container">
        <h2>Unpurchased Customers</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Customers.." id="customSearchBox" class="searchInput">  
            </div>
            <!-- <div class="addEmployee_sect">
                <a href="addCustomer.php" class="export_btn employee_link">Add Customer</a>
                <button type="button" class="export_btn" onclick="exportData()">Export</button>
            </div> -->
        </div>
        <div class="employee_action">
            <div class="action_sect">
                <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon" alt="delete-icon-img">     
                <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon" alt="edit-icon-img"> 
            </div> 
        </div>                                                                                       
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="4,5">
                    <thead class="table_head">
                        <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
                        <th>Full Name</th>
                        <th>Phone Number</th>
                        <th>Whatsapp</th>
                        <th>Email</th>
                        <th>Joined on</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>State</th>
                    </thead>
                    <?php
                   
                    $i=1;
                    foreach($unpurchase_cust_details as $customer_unpurchase):
                    ?>
                    <!-- <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>" onclick="redirect(this, <?php echo $customer_unpurchase['user_id'];?>)"> -->
                    <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
                        <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $customer_unpurchase['user_id'];?>">&emsp;<?php  echo $i; ?></td> -->
                        <td><?php  echo $i; ?></td>
                        <td class="highlight"><a href="customer_sales_report.php?customer_id='<?php echo $customer_unpurchase['user_id'];?>'"><?php  echo $customer_unpurchase['name'];?></a></td>
                        <td><?php  echo $customer_unpurchase['mobile'];?></td>
                        <td><?php  
                        if($customer_unpurchase['whatsapp'] == '' || NULL){
                            echo 'N/A';
                        }
                        else{
                        echo $customer_unpurchase['whatsapp'];
                        }?></td>
                        <td><?php  
                        if($customer_unpurchase['email_id'] == '' || NULL){
                            echo 'N/A';
                        }
                        else{
                            echo $customer_unpurchase['email_id'];
                        }
                        ?></td>
                        <td  data-sort='<?php echo date_format(date_create($customer_unpurchase['created_ts']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($customer_unpurchase['created_ts']);
                                echo date_format($date, "d/m/Y h:i A");
                            ?>
                        </td>
                        <td><?php  
                        if(($customer_unpurchase['address_line_1'] && $customer_unpurchase['landmark'] && $customer_unpurchase['area'] && $customer_unpurchase['city'] && $customer_unpurchase['state'] && $customer_unpurchase['country'] && $customer_unpurchase['pincode']) == '' || NULL){
                            echo 'N/A';
                        }
                        else{
                            $cust_address=$customer_unpurchase['address_line_1'].' ,'.$customer_unpurchase['landmark'].' ,'.$customer_unpurchase['area'].' ,'.$customer_unpurchase['city'].' ,'.$customer_unpurchase['state'].' ,'.$customer_unpurchase['country'].' -'.$customer_unpurchase['pincode'];
                            echo $cust_address;
                        }?></td>
                        <td><?php  echo $customer_unpurchase['city'];?></td>
                        <td><?php  echo $customer_unpurchase['state'];?></td>
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

editIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `addCustomer.php?customerid=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select one row to edit.');
  }
});

const deleteIcon=document.getElementById('delete_icon');
deleteIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `com_ins_upd.php?cust_dids=${checkedRowIds.join(',')}`;
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



<!-- <script>
function redirect(row, customerId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addCustomer.php?customerid=' + customerId;
    }
}
</script> -->
    <script src="../assets/js/session_check.js"></script>
</body>
</html>