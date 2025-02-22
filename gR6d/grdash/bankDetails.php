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
    <title>Bank Details</title>
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
        <h2>Bank Details</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Bank Details.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addBanner_sect">
                <a href="addBank.php" class="export_btn employee_link"> Add Bank</a>
                <button type="button" class="export_btn"  onclick="exportData()" id="exportButton">Export</button>
            </div>
        </div>
        <div class="btn_sect">
            <div class="btn_grp">
                <button type="button" class="filter_btn"><img src="..\assets\images\filter_icon.png" alt="Filter-icon-img">Filter</button>
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
<table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,2,3">
    <thead class="table_head">
        <tr>
            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
            <th>Bank Name</th>
            <th>Account Holder Name</th>
            <th>Account No</th>
            <th>IFSC Code</th>
            <th>UPI ID</th>
            <th>App Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        foreach($bank_details as $bank_detail):
        ?>
            <tr class="<?php echo ($i % 2 === 0) ? 'teven' : 'todd'; ?>" onclick="redirect(this, <?php echo $bank_detail['id'];?>)">
                <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $bank_detail['id']?>"?>">&emsp;<?php echo $i; ?></td> -->
                <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $bank_detail['id']?>">&emsp;<?php echo $i; ?></td>
                <td><?php echo $bank_detail['bank_name']; ?></td>
                <td><?php echo $bank_detail['account_holder']; ?></td>
                <td><?php echo $bank_detail['account_no']; ?></td>
                <td><?php echo $bank_detail['ifsc_code'] ?? 'N/A'; ?></td>
                <td><?php echo $bank_detail['upi_id'] ?? 'N/A'; ?></td>
                <td class="<?php echo ($bank_detail['app_status'] == 1) ? 'success_style' : 'error_style'; ?>">
                <?php  if($bank_detail['app_status']==1){
                    echo  "Active"; 
                }else{
                    echo "In-Active";
                }
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
editIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `addBank.php?bankid=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select one row to edit.');
  }
});

const deleteIcon=document.getElementById('delete_icon');
deleteIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `com_ins_upd.php?bank_dids=${checkedRowIds.join(',')}`;
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
        $export_query = $mysqli->query("SELECT bank_name, account_no, upi_id FROM `e_bank_details` WHERE cos_id = '$cos_id' 
        AND active != 2");
        $bank_details = [];
        while ($bank_export = $export_query->fetch_assoc()) {
            $bank_details[] = $bank_export;
        }
        foreach ($bank_details as $row) {
            echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
        }
        ?>
    ];

    function exportData() {
        const headers = ["Bank Name", "Account No", "UPI ID"];
        
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

        // Append the worksheet to the workbook
        XLSX.utils.book_append_sheet(wb, ws, "Bank Data");

        // Write the workbook to binary
        let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

        // Convert binary string to array buffer
        function s2ab(s) {
            let buf = new ArrayBuffer(s.length);
            let view = new Uint8Array(buf);
            for (let i = 0; i < s.length; i++) {
                view[i] = s.charCodeAt(i) & 0xFF;
            }
            return buf;
        }

        // Save file using FileSaver.js
        let fileName = "bank_data.xlsx";
        saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
    }
</script>

<script>
function redirect(row, bankId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addBank.php?bankid=' + bankId;
    }
}
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
