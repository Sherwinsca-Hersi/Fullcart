<?php
    require 'session.php';
?>
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
                <input type="text" name="search_input" placeholder="Search Product Name, Model No, Barcode.." id="customSearchBox" class="searchInput"> 
                <!-- <div class="filter">
                    <button type="button" class="export_btn add_stock">Brand</button>
                    <button type="button" class="export_btn add_stock">Category</button>
                    <button type="button" class="export_btn add_stock">Subcategory</button>
                    <button type="button" class="export_btn add_stock"><i class="fa fa-refresh" aria-hidden="true"></i></button>
                </div>  -->
            </div>
            
            <div class="addBanner_sect">
                <a href="addproduct.php" class="export_btn employee_link"> Add Product </a>
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
<table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="1,4,8">
    <thead class="table_head">
        <tr>
            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
            <th>Add Stock</th>
            <th>Model No</th>
            <th>HSN Code</th>
            <th>Product Name</th>
            <th>Product Image</th>
            <th>Category</th>
            <th>Sub Category</th>
            <th>Product Variation</th>
            <th>Barcode</th>
            <th>Status</th>
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
                <td><a href="inventory_addStock.php?productid=<?php echo $product_detail['id']; ?>" class="export_btn add_stock"><i class="fa fa-solid fa-plus" id="addStock"></i>&emsp;Add Stock</a></td>
                <td><?php echo $product_detail['sku_id']; ?></td>
                <td><?php echo $product_detail['hsn_code']; ?></td>
                <td><?php echo $product_detail['p_title']; ?></td>
                <td><img src='../../<?php echo $product_detail['p_img']; ?>'  width="60px" height="60px"></td>
                <td>
                    <?php  
                    if($product_detail['cat_id']!=NULL){
                        $product_category = $mysqli->query("select title from e_category_details where cos_id = '$cos_id' and id=".$product_detail['cat_id']."")->fetch_assoc();
                        echo $product_category['title'] ?? 'N/A';
                    }
                    else{
                        echo 'N/A';
                    }
                    ?>
                </td>
                <td>
                <?php  
                    if($product_detail['sub_cat_id']!=NULL){
                        $product_sub_category = $mysqli->query("select title from e_subcategory_details where cos_id = '$cos_id' and id=".$product_detail['sub_cat_id']."")->fetch_assoc();
                        echo $product_sub_category['title']?? 'N/A';
                    }
                    else{
                        echo 'N/A';
                    }
                    ?>
                    <?php 
                    
                    ?>
                </td>
                <td><?php echo $product_detail['p_variation']." ".$product_detail['unit']; ?></td>
                <td><?php echo $product_detail['barcode'] ?? 'N/A'; ?></td>
                <td class="<?php echo ($product_detail['active'] == 1) ? 'success_style' : 'error_style'; ?>">
                    <?php  
                        if($product_detail['active']==1){
                            echo  "Published"; 
                        }else{
                            echo "Unpublished";
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx-style/0.8.13/xlsx-style.min.js"></script>

<script>
    const fetchedData = [
        <?php
        $export_query = $mysqli->query("SELECT sku_id, barcode, cat_id, sub_cat_id, p_title, p_variation, unit, location, godown_location, p_img,active FROM `e_product_details` WHERE cos_id='$cos_id' and active=1");

        $product_details = [];
        while ($product_export = $export_query->fetch_assoc()) {
            $product_details[] = $product_export;
        }
        foreach ($product_details as $row) {
            echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
        }
        ?>
    ];

    function exportData() {
        const headers = ["Model No", "Barcode", "Category ID", "Sub-Category ID","Title", "Variation", "Unit","Location", "Godown Location","Image","Active"];
        
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

        saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), "product_data.xlsx");
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
