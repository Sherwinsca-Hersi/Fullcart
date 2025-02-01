<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
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
        <h2>Products</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Products.." id="customSearchBox" class="searchInput"> 
                <!-- <button type="submit">Search</button>  -->
            </div>
            <div class="addBanner_sect">
                <a href="addproduct.php" class="export_btn employee_link"> Add Product </a>
                <button type="button" class="export_btn"  onclick="exportData()" id="exportButton">Export</button>
            </div>
        </div>
        <div class="btn_sect">
            <div class="btn_grp">
                <button type="button" class="filter_btn"><img src="..\assets\images\filter_icon.png">Filter</button>
                <button type="button" class="default_btn">Total Sales</button>
                <button type="button" class="default_btn">Online Sales</button>
                <button type="button" class="default_btn">Reorder Level</button>
                <button type="button" class="default_btn">Low Stock Alerts</button>
                <button type="button" class="default_btn">22:02:24 to 25:02:24</button>  
            </div>
            <div class="action_sect">
                <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon">     
                <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon"> 
            </div> 
        </div>
<table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="1,3,4">
    <thead class="table_head">
        <tr>
            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
            <th>SKU ID</th>
            <th>HSN Code</th>
            <th>Product Name</th>
            <th>Product Variation</th>
            <th>Product Image</th>
            <th>Category</th>
            <th>Subcategory</th>
            <th>Floor Location</th>
            <th>Godown Location</th>
            <th>Description</th>
            <th>Barcode</th>
            <th>Type</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        foreach($products_details as $product_detail):
        ?>
            <tr class="<?php echo ($i % 2 === 0) ? 'teven' : 'todd'; ?>" onclick="redirect(this, <?php echo $product_detail['id'];?>)">
                <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $product_detail['id']?>" data-batch="<?php echo $product_detail['s_batch_no'];?>">&emsp;<?php echo $i; ?></td> -->
                <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $product_detail['id']?>">&emsp;<?php echo $i; ?></td>
                <td><?php echo $product_detail['sku_id']; ?></td>
                <td><?php echo $product_detail['hsn_code']??'N/A'; ?></td>
                <td><?php echo $product_detail['p_title']; ?></td>
                <td><?php if(($product_detail['p_variation'] && $product_detail['unit'])!= NULL || ''){
                    $product_unit = $product_detail['unit']==null? null:$mysqli->query("select unit from e_unit_details where cos_id = '$cos_id' and id=".$product_detail['unit'] ?? "0"."")->fetch_assoc();
                    echo $product_detail['p_variation']." ".strtolower($product_unit['unit']) ?? 'N/A';
                }
                else{
                    echo 'N/A';
                }?></td>
                <td><?php if($product_detail['p_img']==NULL||''){
                ?>
                    <img src='../defaultimgs/id-card.png' width="60px" height="60px">
                <?php
                }else{
                    ?>
                    <img src='../<?php echo $product_detail['p_img']; ?>' width="60px" height="60px">
                    <?php
                }
                ?>
                </td>
                <td>
                    <?php  
                    $product_category = $product_detail['cat_id']==null? null:$mysqli->query("select * from e_category_details where cos_id = '$cos_id' and id=".$product_detail['cat_id'] ?? "0"."")->fetch_assoc();
                    echo $product_category['title'] ?? 'N/A';
                    ?>
                </td>
                <td>
                    <?php 
                    $product_sub_category = $product_detail['sub_cat_id']==null? null:$mysqli->query("select * from e_subcategory_details where cos_id = '$cos_id' and id=".$product_detail['sub_cat_id']."")->fetch_assoc();
                    echo $product_sub_category['title']??'N/A';
                    ?>
                </td>
                <td><?php echo $product_detail['location']??'N/A'; ?></td>
                <td><?php echo $product_detail['godown_location']??'N/A'; ?></td>
                <td><?php echo $product_detail['p_desc']; ?></td>
                <td><?php echo $product_detail['barcode']??'N/A'; ?></td>
                <td><?php if($product_detail['type']=='3'){
                    echo 'Raw Material';
                    }else{
                        echo 'Final Sales';
                    }?></td>
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
            $export_query=$mysqli->query("SELECT sku_id,barcode,cat_id,sub_cat_id,p_img,p_title,brand,p_variation,unit,p_disc,location,godown_location,active FROM `e_product_details` WHERE cos_id='$cos_id' and active=1");

     
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
            
            let wsData = fetchedData;

            
            let wb = XLSX.utils.book_new();
           
            let ws = XLSX.utils.aoa_to_sheet(wsData);
            
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
    // Check if any checkbox in the row is checked
    const checkbox = row.querySelector('input[type="checkbox"]');
    if (checkbox.checked) {
        return; // Do nothing if the checkbox is checked
    }
    window.location.href = 'addproduct.php?productid=' + productId;
}
</script>
</body>
</html>
