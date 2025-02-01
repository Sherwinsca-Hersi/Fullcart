<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unsold Products</title>
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

        <h2>Unsold Products</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Product Name, Model No..." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addBanner_sect">
                <!-- <a href="addproduct.php" class="export_btn employee_link"> Add Product </a> -->
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
<table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,1,3,4">
    <thead class="table_head">
        <tr>
            <th>S.No</th>
            <th>Model No</th>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Product Variation</th>
            <th>Unsold Quantity</th>
            <th>Unsold For(In Days)</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        foreach($unsold as $unsold):
        ?>
            <tr class="<?php echo ($i % 2 === 0) ? 'teven' : 'todd'; ?>">
                <td><?php echo $i; ?></td>
                <td><?php echo $unsold['sku_id']; ?></td>
                <td><?php echo $unsold['p_title']; ?></td>
                <td><img src='../<?php echo $unsold['p_img']; ?>' width="60px" height="60px"></td>
                <td><?php echo $unsold['p_variation']." ".$unsold['unit']; ?></td>
                <td><?php echo $unsold['stock_qty']; ?></td>
                <td><?php echo $unsold['aging_days']; ?></td>
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
<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>

<script>
    document.getElementById('exportButton').addEventListener('click', function() {

        var table = document.getElementById('example');
        var rows = table.rows;
        var data = [];

        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].cells;
            for (var j = 0; j < cols.length; j++) {
                row.push(cols[j].innerText);
            }
            data.push(row);
        }

        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(data);

        XLSX.utils.book_append_sheet(wb, ws, "Sheet1");

        XLSX.writeFile(wb, 'table_data.xlsx');
    });
</script> -->

<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
        
        const fetchedData = [
            <?php
            $export_query=$mysqli->query("SELECT 
    pd.p_title,
    pd.sku_id,
    pd.p_variation,
    pd.unit,
    ps.qty AS stock_qty,
    pp.qty_left AS unsold_qty,
    pd.p_img,
    DATEDIFF(CURRENT_DATE, ps.created_ts) AS aging_days
FROM 
    e_product_price AS pp
JOIN 
    e_product_stock AS ps ON pp.product_id = ps.s_product_id AND ps.cos_id = '$cos_id'
JOIN 
    e_product_details AS pd ON pp.product_id = pd.id AND pd.cos_id = '$cos_id'
WHERE  
    pp.active = 1 
    AND ps.qty = pp.qty_left 
    AND pd.active = 1
    AND pp.cos_id = '$cos_id'
ORDER BY 
    aging_days DESC");

     
            $products_details= [];
            while ($product_export = $export_query->fetch_assoc()){
                $product_details[] = $product_export;
            }
            foreach ($product_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];
        
        function exportData() {
            
            const headers = ["Product Name","Model No","Variation","Unit","Total Stock","Current Stock","Product Image","Unsold For (In days)"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Product Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "product_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>

<script>
function redirect(row, productId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addproduct.php?productid=' + productId;
    }
}
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
