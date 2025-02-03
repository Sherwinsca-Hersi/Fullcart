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
    <title><?php echo $vendor; ?>s Stock Page</title>

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

        <h2><?php echo $vendor; ?>s Stock</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Product Name, Batch No.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="stock_btn_sect">
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
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,6,7">
                    <thead class="table_head">
                        <!-- <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th> -->
                        <th>S.No</th>
                        <th>Invoice No</th>
                        <th>Model No</th>
                        <th>Batch No</th>
                        <th>Product Name</th>
                        <th>Stock Count</th>
                        <th>Stock Arrival Date</th>
                        <th><?php echo $vendor; ?>s Name</th>
                        <th><?php echo $vendor; ?>s Phone</th>
                        <th>Stock Bill</th>
                    </thead>
                    <tbody>
                    <?php
                    if (isset($_GET['supplierid'])){

                        $vendor_stock=$mysqli->query("SELECT ps.invoice_no,ps.s_product_id,ps.s_batch_no,
                        ps.qty,ps.created_ts,ps.supplier_id,ps.stock_bill FROM e_product_stock ps JOIN 
                        e_product_details pd ON ps.s_product_id = pd.id AND pd.active = 1 AND pd.cos_id = '$cos_id'
                        JOIN e_product_price pp ON ps.s_product_id = pp.product_id AND 
                        pp.batch_no = ps.s_batch_no AND pp.qty_left > 0 AND pp.cos_id = $cos_id
                        WHERE ps.active = 1 AND ps.cos_id = $cos_id AND ps.supplier_id = {$_GET['supplierid']}
                        ORDER BY ps.created_ts DESC");

                    }
                    else if(isset($_GET['invoice_no'])){
                        $vendor_stock=$mysqli->query("SELECT ps.invoice_no,ps.s_product_id,ps.s_batch_no,
                        ps.qty,ps.created_ts,ps.supplier_id,ps.stock_bill
                        FROM e_product_stock ps JOIN e_product_details pd ON ps.s_product_id = pd.id 
                        AND pd.active = 1 AND pd.cos_id = '$cos_id' JOIN e_product_price pp ON ps.s_product_id = pp.product_id 
                        AND pp.batch_no = ps.s_batch_no AND pp.qty_left > 0 AND pp.cos_id = '$cos_id'
                        WHERE ps.active = 1 AND ps.cos_id = '$cos_id'  AND ps.invoice_no = {$_GET['invoice_no']}
                        ORDER BY  ps.created_ts DESC");
                    }
                    else{
                        $vendor_stock=$mysqli->query("SELECT ps.invoice_no,ps.s_product_id,
                        ps.s_batch_no,ps.qty,ps.created_ts,ps.supplier_id,ps.stock_bill,pp.product_id
                        FROM e_product_stock ps JOIN e_product_details pd ON ps.s_product_id = pd.id 
                        AND pd.active = 1 AND pd.cos_id = '$cos_id'
                        JOIN e_product_price pp ON ps.s_product_id = pp.product_id AND pp.batch_no = ps.s_batch_no 
                        AND pp.qty_left > 0 AND pp.cos_id = '$cos_id' WHERE ps.active = 1 AND ps.cos_id = '$cos_id' 
                        ORDER BY ps.created_ts DESC");
                    }

                    $stock_details=[];
                    while($vendor_stock_details=$vendor_stock->fetch_assoc()){
                        $stock_details[]=$vendor_stock_details;
                    }
                    $i=1;
                        foreach($stock_details as $stock_detail):
                        ?>
                        <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
                        <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox">&emsp;<?php echo $i; ?></td> -->
                        <td><?php echo $i; ?></td>
                        <td class="hightlight"><a href="vendorsStock.php?invoice_no='<?php echo $stock_detail['invoice_no'];?>'"><?php echo $stock_detail['invoice_no']?? 'N/A'; ?></a></td>
                        <td><?php $stock_skuid=$mysqli->query("SELECT sku_id FROM e_product_details WHERE id=".$stock_detail['s_product_id']." AND cos_id='$cos_id'")->fetch_assoc();
                            echo $stock_skuid['sku_id'];
                        ?>
                        </td>
                        <td><?php echo $stock_detail['s_batch_no']; ?></td>
                        <td class="hightlight"><a href="allbatches.php?product_id='<?php echo $stock_detail['s_product_id'];?>'">
                            <?php $product_name=$mysqli->query("SELECT p_title FROM e_product_details WHERE id=".$stock_detail['s_product_id']." AND cos_id='$cos_id'")->fetch_assoc();
                                echo $product_name['p_title']." ";
                                $product_name=$mysqli->query("SELECT p_variation,unit FROM e_product_details WHERE id=".$stock_detail['s_product_id']." AND cos_id='$cos_id'")->fetch_assoc();
                                echo $product_name['p_variation']." ".$product_name['unit'];
                            ?></a></td>
                        <td><?php echo $stock_detail['qty'];?></td>
                        <td data-sort='<?php echo date_format(date_create($stock_detail['created_ts']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($stock_detail['created_ts']);
                                echo date_format($date, "d/m/Y h:i A");
                            ?>
                        </td>
                        <td class="hightlight"><a href="vendorsStock.php?supplierid='<?php echo $stock_detail['supplier_id'];?>'">
                    <?php  
                    $vendors_name = $mysqli->query("SELECT v_name FROM e_vendor_details WHERE v_id=".$stock_detail['supplier_id']." AND cos_id = '$cos_id'")->fetch_assoc();
                    echo $vendors_name['v_name'] ?? 'N/A';
                    ?></a>
                </td>
                <td>
                    <?php  
                    $vendors_phone = $mysqli->query("SELECT v_mobile FROM e_vendor_details WHERE v_id=".$stock_detail['supplier_id']." AND cos_id = '$cos_id'")->fetch_assoc();
                    echo $vendors_phone['v_mobile'] ?? 'N/A';
                    ?>
                </td>
                <td style="width:100px;height:100px">
                    <a href="../<?php echo $stock_detail['stock_bill'];?>" target="_blank"><?php if($stock_detail['stock_bill'] !=NULL){
                    ?>
                        <img src="../../<?php echo $stock_detail['stock_bill']; ?>" width="100" height="100">
                    </a>
                </td>
                <?php
                }
                else{
                    echo 'N/A';
                }
                ?>
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
<!-- <script src="../assets/js/common_script.js"></script> -->
<?php 
    require 'footer.php';
?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script>
        
        const fetchedData = [
            <?php
            $export_query=$mysqli->query("SELECT  ps.invoice_no,ps.s_product_id,ps.s_batch_no,pd.p_title,
                        pd.p_variation,pd.unit,ps.qty,ps.created_ts,ps.supplier_id,ps.stock_bill
                        FROM e_product_stock ps JOIN e_product_details pd ON ps.s_product_id = pd.id 
                        AND pd.active = 1 AND pd.cos_id = '$cos_id'
                        JOIN e_product_price pp ON ps.s_product_id = pp.product_id AND pp.batch_no = ps.s_batch_no 
                        AND pp.qty_left > 0 AND pp.cos_id = '$cos_id' WHERE ps.active = 1 AND ps.cos_id = '$cos_id' 
                        ORDER BY ps.created_ts DESC");
    
            $vendorStock_details= [];
            while ($vendorStock_export = $export_query->fetch_assoc()){
                $vendorStock_details[] = $vendorStock_export;
            }
            foreach ($vendorStock_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
        const headers = ["Invoice No", "Product Id", "Batch No", "Product Title","Product Variation","Unit","Total Stock","Created Date/Time","Supplier Id","Stock Bill"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Vendors Stock Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "vendor_stock_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
