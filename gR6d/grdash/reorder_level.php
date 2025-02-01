<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reorder Level</title>
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
        <h2>Reorder Level</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Product Name, Batch No.." id="customSearchBox" class="searchInput" >  
            </div>
            <div>
                <button type="button" class="export_btn" onclick="exportData()">Export</button>
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
                        <!-- <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th> -->
                        <th>S.No</th>
                        <th>Model No</th>
                        <th>Batch No</th>
                        <th>Product Name</th>
                        <th>MRP</th>
                        <th>In-Price</th>
                        <th>Out-Price</th>
                        <th>Current Stock Level</th>
                        <th>Reorder Level</th>
                        <th>Low Stock Alert</th>
                        <th>Stock Arrived On</th>
                        <th>Stock Updated On</th>
                    </thead>
                    <?php
                        $i=1;
                        foreach($reorderLevel as $reorderLvl):
                        ?>
                        <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
                        <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;<?php echo $i; ?></td> -->
                        <td><?php echo $i; ?></td>
                        <td><?php echo $reorderLvl['sku_id'] ?></td>
                        <td class="highlight"><?php echo $reorderLvl['batch_no']; ?></td>
                        <td class="highlight"><a href="allbatches.php?product_id='<?php echo $reorderLvl['product_id'];?>'"><?php echo $reorderLvl['p_title'].' '.$reorderLvl['p_variation'] .''. $reorderLvl['unit']; ?></a></td>
                        <td><?php echo $reorderLvl['mrp']; ?></td>
                        <td><?php $p_stock_query = $mysqli->query("select in_price from e_product_stock where cos_id = '$cos_id' and s_product_id=".$reorderLvl['product_id']." AND s_batch_no = '" . $reorderLvl['batch_no'] . "'")->fetch_assoc();
                            echo $p_stock_query['in_price']; 
                        ?></td>
                        <td><?php echo $reorderLvl['out_price']; ?></td>
                        <td class="highlight"><?php echo $reorderLvl['qty_left']??'N/A'; ?></td>
                        <td class="highlight"><?php echo $reorderLvl['reorder_level']??'N/A'; ?></td>
                        <td><?php echo $reorderLvl['emergency_level']??'N/A'; ?></td>
                        <td data-sort='<?php echo date_format(date_create($reorderLvl['created_ts']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($reorderLvl['created_ts']);
                                echo date_format($date, "d/m/Y h:i A");
                            ?>
                        </td>
                        <td data-sort='<?php echo date_format(date_create($reorderLvl['updated_ts']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($reorderLvl['updated_ts']);
                                echo date_format($date, "d/m/Y h:i A");
                            ?>
                        </td>
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
<div class="popup" id="popup">
    <h4>All unsaved changes will be lost.</h4>
    <div class="popup_btns">
        <button class="price_btn">Price</button>
        <button class="stock_btn">Stock</button>
        <button class="popup_cancel" id="cancel_btn">Cancel</button>
    </div>
</div>
<div class="popup" id="delete_popup">
    <h4>Are you really want to delete this?</h4>
    <div class="popup_btns">
        <button class="yes_btn">Yes</button>
        <button class="popup_cancel" id="del_cancel_btn">Cancel</button>
    </div>
</div> 

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
            $export_query=$mysqli->query("SELECT pd.sku_id,p.batch_no,p.product_id,pd.p_title,pd.p_variation,pd.unit,
p.mrp,p.out_price,p.qty_left,p.updated_ts,p.created_ts
FROM e_product_price p JOIN e_product_details pd ON p.product_id = pd.id AND p.cos_id = '$cos_id' 
AND pd.cos_id = '$cos_id' AND p.active = 1 WHERE p.qty_left <= pd.emergency_level AND p.qty_left > 0 
AND pd.active = 1");
     
            $reorderLevel_details= [];
            while ($reorderLevel_export = $export_query->fetch_assoc()){
                $reorderLevel_details[] = $reorderLevel_export;
            }
            foreach ($outOfStock_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
        const headers = ["Model No","Batch No","Product Id","Product Title", "Variation","Unit","MRP", "Outprice","Current Stock","Updated Date/Time","Created Date/Time"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Reorder Level Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "reorderLevel_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
