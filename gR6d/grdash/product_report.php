<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Report</title>
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
    <div class="report_rightbar container">
        <h2>All Stock</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Product Name, Batch No.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="stock_btn_sect">
               <!-- <div>
                    <a href="inventory_addStock.php" class="export_btn">Add Stock</a>
                </div>  -->
                <div>
                    <button type="button" class="export_btn" onclick="exportData()">Export</button>
                </div>
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
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="4,9,10">
                <thead class="table_head">
                        <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
                        <!-- <th>S.No</th> -->
                        <th>Model No</th>
                        <th>Batch No</th>
                        <th>Product Name</th>
                        <!-- <th>Product Variation</th> -->
                        <th>MRP</th>
                        <th>In-Price</th>
                        <th>Out-Price</th>
                        <th>Price Per Kg</th>
                        <th>Expiry Date</th>
                        <th>Bill Image</th>
                        <th>Stock In-Count</th>
                        <th>Current Stock Level</th>
                        <th>Reorder Level</th>
                        <th>Low Stock Alerts</th>
                        <th>Stock Arrived On</th>
                        <th>Stock Updated On</th>
                </thead>
                <tbody>
                    <?php
                    $i=1;
                        foreach($stock_details as $stock_detail):
                        ?>
                        <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>" onclick="redirect(this, '<?php echo $stock_detail['s_product_id'];?>','<?php echo $stock_detail['s_batch_no'];?>','<?php echo $stock_detail['purchase_id'];?>')">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox"  data-id="<?php echo $stock_detail['s_product_id']?>" data-batch="<?php echo $stock_detail['s_batch_no'];?>">&emsp;<?php echo $i; ?></td>
                        <!-- <td><?php echo $i; ?></td> -->
                        <td><?php $stock_skuid=$mysqli->query("select sku_id from e_product_details where cos_id='$cos_id' AND id=".$stock_detail['s_product_id']."")->fetch_assoc();
                            echo $stock_skuid['sku_id'];
                        ?>
                        </td>
                        <td class="highlight"><?php echo $stock_detail['s_batch_no']; ?></td>
                        <td class="highlight"><a href="allbatches.php?product_id='<?php echo $stock_detail['s_product_id'];?>'">
                            <?php $product_name=$mysqli->query("select p_title from e_product_details where cos_id='$cos_id' AND id=".$stock_detail['s_product_id']."")->fetch_assoc();
                                echo $product_name['p_title']." ";
                                $product_name=$mysqli->query("select p_variation,unit from e_product_details where cos_id='$cos_id' AND id=".$stock_detail['s_product_id']."")->fetch_assoc();
                                echo $product_name['p_variation']." ".$product_name['unit'];
                            ?></a></td>
                        <td><?php echo $stock_detail['s_mrp']; ?></td>
                        <td><?php echo $stock_detail['in_price']; ?></td>
                        <td class="highlight"><?php echo $stock_detail['s_out_price']; ?></td>
                        <td>
                        <?php 
                            $price_data=$mysqli->query("select per_g from e_product_price where cos_id='$cos_id' AND product_id=".$stock_detail['s_product_id']."")->fetch_assoc();
                            echo  $price_data['per_g'] == 'NULL' || '' ? "-" : $price_data['per_g'] * 1000;
                            ?>
                        </td>
                        <!-- <td><?php echo $stock_detail['s_expiry_date']; ?></td> -->
                        <!-- <td >
                            <?php 
                                $date = date_create($stock_detail['s_expiry_date']);
                                echo date_format($date, "d/m/Y");
                            ?>
                        </td> -->
                        <td data-sort='<?php echo date_format(date_create($stock_detail['s_expiry_date']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($stock_detail['s_expiry_date']);
                                echo date_format($date, "d/m/Y");
                                    ?>
                        </td>
                        <td style="width:100px;height:100px"><?php
                            if($stock_detail['stock_bill']==NULL || ''){
                                echo 'N/A';
                            }
                            else{
                                ?>
                                <img src='../<?php echo $stock_detail['stock_bill']; ?>' width="80px" height="60px"/>
                                <?php
                            }
                           
                        ?></td>
                        <td><?php echo $stock_detail['qty']; ?></td>
                        <td class="highlight"><?php echo $stock_detail['qty_left']; ?></td>
                        <td><?php $reorder_lvl = $mysqli->query("select reorder_level from e_product_details where cos_id = '$cos_id' and id=".$stock_detail['s_product_id']."")->fetch_assoc();
								echo $reorder_lvl['reorder_level']; ?></td>
                        <td><?php $emergency_lvl = $mysqli->query("select emergency_level from e_product_details where cos_id = '$cos_id' and id=".$stock_detail['s_product_id']."")->fetch_assoc();
								echo $emergency_lvl['emergency_level']; ?></td>
                        <td data-sort='<?php echo date_format(date_create($stock_detail['updated_ts']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($stock_detail['created_ts']);
                                echo date_format($date, "d/m/Y h:i A");
                            ?>
                        </td>
                        <td data-sort='<?php echo date_format(date_create($stock_detail['updated_ts']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($stock_detail['updated_ts']);
                                echo date_format($date, "d/m/Y h:i A");
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
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<script src="../assets/js/common_script.js"></script>
<script>

editIcon.addEventListener('click', function() {
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    console.log(checkedRowIds)
    window.location.href = `inventory_addStock.php?productid=${checkedRowIds.join(',')}&&batch_id=${selectedBatchValues.join(',')}`;
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

    let url = `com_ins_upd.php?stock_dids=${selectedRowIds}`;
    if (selectedBatchValuesStr) {
      url += `&prod_batch_dids=${selectedBatchValuesStr}`;
    }
    window.location.href = url;
  } else {
    alert('Please select at least one row to delete.');
  }
});

</script>
<?php 
    require 'footer.php';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
        
        const fetchedData = [
            <?php
            $export_query=$mysqli->query("SELECT pd.sku_id,ps.s_product_id,ps.s_batch_no,pd.p_title,pd.p_variation,pd.unit,ps.qty,ps.s_expiry_date,ps.in_price,ps.s_mrp,ps.s_out_price,pp.qty_left,ps.updated_ts FROM e_product_details pd,e_product_stock ps,e_product_price pp
                  WHERE pd.id = ps.s_product_id AND ps.s_product_id = pp.product_id AND pp.product_id=pd.id AND pp.batch_no=ps.s_batch_no AND pp.qty_left > 0 AND pp.cos_id=$cos_id AND pd.active=1
                  AND pd.cos_id=$cos_id AND ps.active=1 AND ps.cos_id=$cos_id");
     
            $allStock_details= [];
            while ($allStock_export = $export_query->fetch_assoc()){
                $allStock_details[] = $allStock_export;
            }
            foreach ($allStock_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
        const headers = ["Model No", "Product Id","Batch No","Product Title", "Variation","Unit","Total Stock","Expiry Date","Inprice","MRP", "Outprice","Current Stock","Updated Date/Time"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "All Stock Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "stock_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>

<script>
function redirect(row, productId,batchId,purchaseId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = `inventory_addStock.php?productid=` + productId +`&&batch_id=` + batchId +`&&purchase_id=` + purchaseId;
    }
}
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
