<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delivery Fee Details</title>
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
    <div class="coupon_rightbar container">
        <h2>Delivery Fee Details</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search City,Min Order Amount.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addcoupon_sect">
                <!-- <a href="addCoupon.php" class="export_btn employee_link">Add Coupon</a> -->
                <button type="button" class="export_btn" onclick="exportData()">Export</button>
            </div>
        </div>
        <div class="employee_action">
            <div class="action_sect">
                <!-- <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon" alt="delete-icon-img">      -->
                <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon" alt="edit-icon-img"> 
            </div> 
        </div>
        
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,3">
                    <thead class="table_head">
                        <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
                        <th>City</th>
                        <th>Image</th>
                        <th>Delivery Charge</th>
                        <th>Min Order Amount</th>
                        <th>Message</th>
                    </thead>
                    <?php
                    $i=1;
                    foreach($deliveryFee_details as $deliveryFee_detail):
                    ?>
                    <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>" onclick="redirect(this, <?php echo $deliveryFee_detail['id'];?>)">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $deliveryFee_detail['id']; ?>">&emsp;<?php echo $i;?></td>
                        <td><?php echo $deliveryFee_detail['title'];?></td>
                        <td><img src="../../<?php echo $deliveryFee_detail['c_img'];?>"  width="50px" height="50px"></td>
                        <td><?php echo $deliveryFee_detail['d_charge'];?></td>
                        <td><?php echo $deliveryFee_detail['min_amt'];?></td>
                        <td><?php echo $deliveryFee_detail['disc_alert_msg'];?></td>
                    </tr>
                    <?php
                    $i++;
                    endforeach;
                    ?>
                </table>
    </div>

    <?php
        if(isset($_SESSION['success'])): 
        ?>
        <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
        <script>
            iziToast.success({
                title: 'Success',
                message: '<?php echo $_SESSION['success']; ?>',
                position: 'bottomCenter',
                timeout: 5000
            });
        </script>
        <?php
        unset($_SESSION['success']);
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
    const url = `addDeliveryFee.php?deliveryFeeid=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select  one row to edit.');
  }
});

// const deleteIcon=document.getElementById('delete_icon');
// deleteIcon.addEventListener('click', function(){
//     const { checkedRowIds, selectedBatchValues } = displayCheck();
//     if (checkedRowIds.length > 0) {
//     const url = `com_ins_upd.php?coup_dids=${checkedRowIds.join(',')}`;
//     window.location.href = url;
//     } else {
//     alert('Please select at least one row to delete.');
//   }
// });
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
            $export_query=$mysqli->query("SELECT id,title,d_charge,min_amt,disc_alert_msg,c_img from e_data_city WHERE active=1 AND cos_id = '$cos_id' ORDER BY id DESC");
    
            $cityDelivery_details= [];
            while ($cityDelivery_export = $export_query->fetch_assoc()){
                $cityDelivery_details[] = $cityDelivery_export;
            }
            foreach ($cityDelivery_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
        const headers = ["City Id","City","Delivery Charge","Minimum Order Amount","Message","Image"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Delivery Fee Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "cityDelivery_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>

<script>
function redirect(row, deliveryFeeId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addDeliveryFee.php?deliveryFeeid=' + deliveryFeeId;
    }
}
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>