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
    <title><?php echo $vendor; ?>s</title>
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


        <h2><?php echo $vendor; ?>s</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search <?php echo $vendor; ?>s.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addBanner_sect">
                <a href="addVendors.php" class="export_btn employee_link"> Add <?php echo $vendor; ?></a>
                <button type="button" class="export_btn"  onclick="exportData()" id="exportButton">Export</button>
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
<table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="1,4,5">
    <thead class="table_head">
        <tr>
            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
            <th><?php echo $vendor; ?> Name</th>
            <th>Business Name</th>
            <th>Contact Person</th>
            <th>Phone Number</th>
            <th>whatsapp</th>
            <th>GSTIN</th>
            <th>Address</th>
            <th>View Products</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        foreach($vendor_details as $vendor_detail):
        ?>
            <tr class="<?php echo ($i % 2 === 0) ? 'teven' : 'todd'; ?>" onclick="redirect(this, <?php echo $vendor_detail['v_id'];?>)">
                <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $vendor_detail['v_id']?>"?>">&emsp;<?php echo $i; ?></td> -->
                <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $vendor_detail['v_id']?>">&emsp;<?php echo $i; ?></td>
                <td><?php echo $vendor_detail['v_name']; ?></td>
                <td><?php echo $vendor_detail['business_name'] ?? 'N/A'; ?></td>
                <td><?php echo $vendor_detail['contact_person']; ?></td>
                <td><?php echo $vendor_detail['v_mobile'] ?? 'N/A'; ?></td>
                <td><?php echo $vendor_detail['v_whatsapp'] ?? 'N/A'; ?></td>
                <td><?php echo $vendor_detail['gst_no'] ?? 'N/A'; ?></td>
                <td><?php echo $vendor_detail['v_address'] ?? 'N/A'; ?></td>
                <td><button class="viewProd_btn view_btn" id="view_products" onclick="event.stopPropagation(); openPopup('<?php echo $vendor_detail['v_id']; ?>');">View</button></td>
                
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
<?php
        foreach($vendor_details as $vendor_detail):
        ?>
<div class="popup view_products" id="view_popup_<?php echo $vendor_detail['v_id'];?>">
                        <h4>Products Supplied by <?php echo $vendor_detail['v_name'];?></h4>
                        <ul>
                            <?php 
                            $vendor_prod=$mysqli->query("SELECT pd.p_title,pd.p_variation,pd.unit FROM `e_product_stock` as ps JOIN `e_product_details` as pd ON ps.s_product_id=pd.id WHERE ps.supplier_id=".$vendor_detail['v_id']." and ps.cos_id='$cos_id' and ps.active=1 and ps.cos_id=pd.cos_id and ps.active=pd.active;");
                            $vendor_products=[];
                            while ($vendor_stock = $vendor_prod->fetch_assoc()) {
                                $vendor_products[] = $vendor_stock;
                            }
                            if($vendor_prod->num_rows==0){
                                echo "<h3>No Products Found</h3>";
                            }
                            else{
                                foreach($vendor_products as $vendor_product):
                                    ?>
                                        <li><?php echo $vendor_product['p_title']." ".$vendor_product['p_variation']."".$vendor_product['unit'];?></li>
                                    <?php
                                    endforeach;
                               
                            }
                            ?>
                        </ul>
    <div class="popup_btns">
        <button class="popup_cancel" id="close_popup" onclick="closePopup('<?php echo $vendor_detail['v_id'];?>');">Close</button>
    </div>
</div>
<?php 
endforeach;
?>
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
    const url = `addVendors.php?vendorid=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select one row to edit.');
  }
});

const deleteIcon=document.getElementById('delete_icon');
deleteIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `com_ins_upd.php?vendor_dids=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select at least one row to delete.');
  }
});


function openPopup(id){
  const viewPopup=document.getElementById("view_popup_"+id);
    if (viewPopup) {
        viewPopup.classList.add("open_popup");
        Container.classList.toggle("active");
    }
}

function closePopup(id){
             const viewPopup=document.getElementById("view_popup_"+ id);
             if (viewPopup) {
                viewPopup.classList.remove("open_popup");
                Container.classList.toggle("active");
             }
         }

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
            $export_query=$mysqli->query("SELECT v_name,contact_person,v_mobile,v_whatsapp,v_address FROM `e_vendor_details` where cos_id = '$cos_id' and active=1");

     
            $vendors_details= [];
            while ($vendors_export = $export_query->fetch_assoc()){
                $vendors_details[] = $vendors_export;
            }
            foreach ($vendors_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
        const headers = ["Vendor Name", "Contact Person", "Mobile No", "Whatsapp","Vendor Address"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "<?php echo $vendor; ?>s Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "vendors_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>
            <script>
function redirect(row, vendorId) {

    const checkbox = row.querySelector('input[type="checkbox"]');
    if (checkbox.checked) {
        return;
    }
    window.location.href = 'addVendors.php?vendorid=' + vendorId;
}
</script>

<script>
function redirect(row, vendorId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addVendors.php?vendorid=' + vendorId;
    }
}
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
