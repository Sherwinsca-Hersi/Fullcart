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
    <title><?php echo $combo;?>s</title>
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
        <h2><?php echo $combo;?>s</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Combo.." id="customSearchBox" class="searchInput"> 
                <!-- <button type="submit">Search</button>  -->
            </div>
            <div class="addBanner_sect">
                <a href="addCombo.php" class="export_btn employee_link"> Add Combo </a>
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
<table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="1,3">
    <thead class="table_head">
        <tr>
            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
            <th>Model No</th>
            <th>Image</th>
            <th><?php echo $combo;?> Name</th>
            <th><?php echo $combo;?> Products</th>
            <th>Bulk Quantity</th>
            <th>Bulk Price</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        foreach($combo_details as $combo_detail):
            $combo_asproduct_query=$mysqli->query("SELECT id FROM `e_product_details` 
                WHERE p_title='".$combo_detail['title']."' AND  type='2' AND sku_id='".$combo_detail['sku_id']."'
                AND cos_id='$cos_id'")->fetch_assoc();
            $comboProdId=$combo_asproduct_query['id']??'';
        ?>
            
            <tr class="<?php echo ($i % 2 === 0 ) ? 'teven' : 'todd'; ?>" onclick="redirect(this, <?php echo $combo_detail['id'];?>)">
                <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $combo_detail['id']?>" data-batch="<?php echo $comboProdId;?>">&emsp;<?php echo $i; ?></td>
                <td><?php echo $combo_detail['sku_id']; ?></td>
                <td><?php if($combo_detail['c_img']==NULL||''){
                ?>
                    <img src='../defaultimgs/nullimg.png' width="60px" height="60px">
                <?php
                }else{
                    ?>
                    <img src="../../<?php echo $combo_detail['c_img']; ?>" width="100">
                    <?php
                }
                ?>
                </td>
                <td><?php echo $combo_detail['title']; ?></td>
                <td>
                    <table rules='all' cellpadding='50px' cellspacing='50px' width="100%" style="background-color:transparent;">
                        <thead>
                            <tr style="background-color:transparent;">
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Product Price</th>
                            </tr>
                        </thead> 
                        <tbody>
                        <?php
                        $combo_product=$mysqli->query("SELECT `id`, `cos_id`, `c_id`, `prod_id`, `offer_amt`, `qty`, `active` FROM `e_product_collection_map` WHERE  active=1 AND 
                                                        c_id=".$combo_detail['id']." AND cos_id='$cos_id'");

                        $combo_prod_details=[];
                        
                        while ($combo_table = $combo_product->fetch_assoc()){
                            $combo_prod_details[] = $combo_table;
                        }
                        
                                foreach($combo_prod_details as $combo_prod_detail):
                                    ?>
                                    <tr style="background-color:transparent;">
                                        <td><?php 
                                        $product_name=$mysqli->query("SELECT p_title,p_variation,unit FROM `e_product_details` 
                                        WHERE cos_id='$cos_id' AND id=".$combo_prod_detail['prod_id']." AND active=1")->fetch_assoc();
                                        echo $product_name['p_title']." ".$product_name['p_variation']." ".$product_name['unit'];?></td>
                                        <td><?php echo $combo_prod_detail['qty'];?></td>
                                        <td><?php echo $combo_prod_detail['offer_amt'];?></td>
                                    </tr>
                                    <?php
                                endforeach;
                                ?>
                        </tbody>
                    
                    </table>
                </td>
                <td>
                    <table rules='all' cellpadding='50px' cellspacing='50px' width="100%">
                    <thead>
                            <tr style="background-color:transparent;">
                                <th>Quantity Range</th>
                            </tr>
                        </thead> 
                    <?php
                        $fromQts = explode(',', $combo_detail['from_qty']);
                        $toQts = explode(',', $combo_detail['to_qty']);
                        $arrayLength = count($fromQts);
                        for ($j = 0; $j < $arrayLength; $j++):
                            ?>
                            <tr style="background-color:transparent;">
                              <td ><?php echo htmlspecialchars($fromQts[$j])."-".htmlspecialchars($toQts[$j]); ?></td>
                            </tr>
                        <?php endfor; ?>
                    </table>
                </td>
                <td>
                    <table rules='all' cellpadding='50px' cellspacing='50px' width="100%" style="background-color:transparent;">
                    <thead>
                            <tr style="background-color:transparent;">
                                <th>Price</th>
                            </tr>
                        </thead> 
                    <?php
                        $bulkPrice = explode(',', $combo_detail['bulk_price']);
                        $arrayLength = count($bulkPrice);
                        for ($k = 0; $k < $arrayLength; $k++):
                            ?>
                            <tr style="background-color:transparent;">
                              <td ><?php echo htmlspecialchars($bulkPrice[$k]); ?></td>
                            </tr>
                        <?php endfor; ?>
                    </table>
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
    window.location.href = `addCombo.php?comboid=${checkedRowIds.join(',')}`;
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

    let url = `com_ins_upd.php?combo_dids=${selectedRowIds}`;
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
<script>
        
        const fetchedData = [
            <?php
            $export_query=$mysqli->query("SELECT `sku_id`, `p_id`, `offer_amt`, `title`, `c_img`, `from_qty`, `to_qty`, `bulk_price`,`active`
                FROM `e_data_collection` WHERE  active!='2' AND cos_id='$cos_id'");

     
            $combo_details= [];
            while ($combo_export = $export_query->fetch_assoc()){
                $combo_details[] = $combo_export;
            }
            foreach ($combo_details as $row) {
                echo "['" . implode("','", array_map('htmlspecialchars', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
        const headers = ["Model No", "Product Id", "Offer Amount", "Combo Title", "Combo Image", "From Qty", "To Qty", "Bulk Prices","Active"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Combo Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "combo_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>
<script>
function redirect(row, comboId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addCombo.php?comboid=' + comboId;
    }
}
</script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>
