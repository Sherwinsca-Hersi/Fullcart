<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
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
        <h2>Customers</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Customers.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addEmployee_sect">
                <a href="addCustomer.php" class="export_btn employee_link">Add Customer</a>
                <button type="button" class="export_btn" onclick="exportData()">Export</button>
            </div>
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
                        <th>Address</th>
                        <th>Latest Order Date</th>
                        <th>Latest Order Amount</th>
                        <th>City</th>
                        <th>State</th>
                    </thead>
                    <?php
                   
                    $i=1;
                    foreach($customer_details as $customer_detail):
                    ?>
                    <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>" onclick="redirect(this, <?php echo $customer_detail['id'];?>)">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $customer_detail['id'];?>">&emsp;<?php  echo $i; ?></td>
                        <td class="highlight"><a href="customer_sales_report.php?customer_id='<?php echo $customer_detail['id'];?>'"><?php  echo $customer_detail['name'];?></a></td>
                        <td><?php  echo $customer_detail['mobile'];?></td>
                        <td><?php  
                        if($customer_detail['whatsapp'] == '' || NULL){
                            echo 'N/A';
                        }
                        else{
                        echo $customer_detail['whatsapp'];
                        }?></td>
                        <td><?php  
                        if($customer_detail['email_id'] == '' || NULL){
                            echo 'N/A';
                        }
                        else{
                            echo $customer_detail['email_id'];
                        }
                        ?></td>
                        <?php 
                            $cust_address = $mysqli->query("SELECT `id`, `user_id`, `area`, `pincode`, `address_line_1`, `landmark`, `name`, 
                            `mobile`, `city`, `state`, `address_line_2`, `country` 
                            FROM `e_address_details`  
                            WHERE `cos_id` = '$cos_id' 
                            AND `user_id` = '{$customer_detail['id']}' 
                            AND `mobile` = '{$customer_detail['mobile']}' 
                            AND `name` = '{$customer_detail['name']}' 
                            AND `active` = '1'
                            GROUP BY `name`, `mobile` 
                            ORDER BY `created_ts` LIMIT 1");
                        ?>
                        <td><?php 
                            if ($cust_address && $cust_address->num_rows > 0) {
                                $cust_address = $cust_address->fetch_assoc();

        if (!empty($cust_address)) {
            $cust_address_parts = array(
                $cust_address['address_line_1'], 
                $cust_address['landmark'], 
                $cust_address['area'], 
                $cust_address['city'], 
                $cust_address['state'], 
                $cust_address['country'], 
                $cust_address['pincode']
            );

            $cust_address_parts = array_filter($cust_address_parts, function($part) {
                return !empty($part);
            });

            $cust_address_string = empty($cust_address_parts) ? 'N/A' : implode(", ", $cust_address_parts);
            echo $cust_address_string;
        } else {
            echo 'N/A';
        }
    } else {
        echo 'N/A';
    }
?></td>
                        <?php 
                            $cust_order_query=$mysqli->query("SELECT created_ts,subtotal FROM `e_normal_order_details`
                            WHERE cos_id='$cos_id'  AND u_id=".$customer_detail['id']." ORDER BY created_ts DESC LIMIT 1")->fetch_assoc();
                        ?>
                        <td><?php  echo $cust_order_query['created_ts']??'N/A';?></td>
                        <td><?php  echo $cust_order_query['subtotal']??'N/A';?></td>
                        <td><?php  echo $customer_detail['city']??'N/A';?></td>
                        <td><?php  echo $customer_detail['state']??'N/A';?></td>
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
<script>
        
        const fetchedData = [
            <?php
            $export_query=$mysqli->query("SELECT u.name,u.mobile,u.email_id,u.whatsapp,address_line_1,landmark,area,city,state,country FROM `e_user_details` u LEFT JOIN e_address_details a ON u.id = a.user_id  WHERE u.cos_id = '$cos_id' AND a.cos_id='$cos_id' AND u.active=1 AND a.active=1");

     
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
        const headers = ["Customer Name", "Mobile No", "Email", "Whatsapp", "Door", "Street", "Area", "City","State", "Country"];
        
        // Add headers to data
        let wsData = [headers, ...fetchedData];
        
        let wb = XLSX.utils.book_new();
        let ws = XLSX.utils.aoa_to_sheet(wsData);

        // Apply bold styling to the headers
        headers.forEach((header, index) => {
            let cellAddress = XLSX.utils.encode_cell({ r: 0, c: index });
            if (!ws[cellAddress]) ws[cellAddress] = {};
            ws[cellAddress].s = {
                font: { bold: true },
                alignment: { horizontal: "center" }
            };
        });
            
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
<script>
function redirect(row, customerId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addCustomer.php?customerid=' + customerId;
    }
}
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>