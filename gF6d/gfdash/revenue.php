<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Revenue</title>
    <?php 
        require_once '../api/header.php';
    ?>
     <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">

     <!-- datepicker -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>

    <!-- Include jQuery UI -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> 
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
    <div class="orders_rightbar container">
        <h2>Revenue</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Order ID,Customers.." id="customSearchBox" class="searchInput">  
            </div>
            <!-- <div class="addBanner_sect">
                <a href="addproduct.php" class="export_btn employee_link"> Add Product </a>
                <button type="button" class="export_btn" onclick="downloadPDF()">Export</button>
            </div> -->
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
        <?php 
            $selectedDateRange = isset($_GET['selectedDateRange']) ? $_GET['selectedDateRange'] : 'today';
        ?>
        <form id="dateRangeForm">
            <button type="button" class="date-range-btn" data-range="today" <?php if($selectedDateRange == 'today') echo 'active'; ?>>Today</button>
            <button type="button" class="date-range-btn" data-range="yesterday" <?php if($selectedDateRange == 'yesterday') echo 'active'; ?>>Yesterday</button>
            <button type="button" class="date-range-btn" data-range="this_week" <?php if($selectedDateRange == 'this_week') echo 'active'; ?>>This Week</button>
            <button type="button" class="date-range-btn" data-range="7_days" <?php if($selectedDateRange == '7_days') echo 'active'; ?>>Last 7 Days</button>
            <button type="button" class="date-range-btn" data-range="30_days" <?php if($selectedDateRange == '30_days') echo 'active'; ?>>Last 30 Days</button>
            <button type="button" class="date-range-btn" data-range="1_year" <?php if($selectedDateRange == '1_year') echo 'active'; ?>>Last Year</button>
        </form>

        <p class="datepicker dateRange">
            <i class="fa fa-arrow-left arr-left" aria-hidden="true"></i>&emsp;
            <span id="startDate">
                <span id="startDateText">
                    <?php echo isset($_GET['startDate1']) ?  $_GET['startDate1'] : $today->format('d/m/Y'); ?>
                </span>
            </span> 
                to 
            <span id="endDateText"><?php echo date("d/m/Y"); ?></span>&emsp;<i class="fa fa-arrow-right arr-right" aria-hidden="true"></i>
        </p>

        <form method="post">
            <div class="row btn-row">
                <button class="cash_btn" name="cash"  id="cash" data-tab="real_order"><h1>Cash </h1><h1 class="btn_amount"> <?php   echo "  ₹  ".formatIndianRupees(number_format((float)$revenue_cash['order_total'] ?? 0, 2, '.', ''));?></h1></button>
                <button class="upi_btn" name="upi" id="upi" data-tab="online_order"><h1>UPI </h1><h1  class="btn_amount"> <?php   echo "  ₹  ".formatIndianRupees(number_format((float)$revenue_upi['order_total'] ?? 0, 2, '.', ''));?></h1></button>
            </div>
        </form>
            <?php
            if(isset($_POST['cash'])){
                // echo "<h1>Cash:</h1><br><br>";
            ?>
                <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,6">
                    <thead class="table_head">
                        <tr>
                            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
							<th>Order Id</th>
                            <th>Invoice No</th>
                            <th>Order Date/Time </th>
                            <th>Customer Name</th>
                            <th>Bill Amount</th>
                            <th>Payment Method</th>
                            <th>Order Completed Date</th>
                            <th>View Order Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i=1;
                            foreach($cash as $cash):
                        ?>
                        <tr>
                            <td><?php echo $i;?></td>
							<td> <?php echo $cash['id']; ?> </td>
                            <td> <?php echo $cash['invoice_no']; ?> </td>
                            <td data-sort='<?php echo date_format(date_create($cash['created_ts']), "Ymd"); ?>'>
                                <?php 
                                    $date = date_create($cash['created_ts']);
                                    echo date_format($date, "d/m/Y h:i A");
                                ?>
                            </td>
                            <td><?php echo $cash['name']; ?></td>
                            <td><?php echo number_format((double)$cash['o_total'], 2, '.', ''); ?></td>
                            <td><?php 
                                    if($cash['p_method_id']==1){
                                        echo "RazorPay";
                                    }
                                    else if($cash['p_method_id']==2){
                                        echo "COD";
                                    }
                                    else{
                                        echo "N/A";
                                    }
                                ?>
                            </td>
                            <!--<td><?php echo number_format((double)$cash['updated_ts'], 2, '.', ''); ?></td>-->
                             <td data-sort='<?php echo date_format(date_create($cash['updated_ts']), "Ymd"); ?>'>
                                <?php 
                                    $date = date_create($cash['updated_ts']);
                                    echo date_format($date, "d/m/Y h:i A");
                                ?>
                            </td>
                            <!-- <td><button class="view_btn preview_d">View Bill</button></td> -->
							<td><button class="view_btn preview_d" data-id="<?php echo $cash['id'];?>" data-toggle="modal" id="modal_<?php echo $cash['id'];?>">View</button></td>
            			</tr>                                
                        <?php 
                            $i++;
                            endforeach;
                        ?>                                  
                    </tbody> 
                </table>
            <?php
            }
            else if(isset($_POST['upi'])){
                // echo "<h1>UPI:</h1><br><br>";
            ?>
                <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,4">
                    <thead class="table_head">
                        <tr>
                            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
							<th>Order Id</th>
                            <th class="remove_arrow">Order Date/Time </th>
                            <th>Customer Name</th>
                            <th>Bill Amount</th>
                            <th>Payment Method</th>
                            <th>Current Status</th>
                            <th>Order completed Date</th>
                            <th>Bank Transaction Id</th>
                            <th>UPI Id</th>
                            <th>View Order Details</th>
                            <th>Reconcilation</th>
                        </tr>
                    </thead>                   
                    <tbody>
                        <?php 
                            $i=1;
                            // print_r($upi);
                            foreach($upi as $upi):
                        ?>
                        <tr>
							<td><?php echo $i; ?></td>
												
							<td> <?php echo $upi['id']; ?> </td>
                                                
                            <td data-sort='<?php echo date_format(date_create($upi['created_ts']), "Ymd"); ?>'>
                                <?php
                                    $date = date_create($upi['created_ts']);
                                    echo date_format($date, "d/m/Y h:i A");
                                ?>
                            </td>
                            <td><?php echo $upi['name']; ?></td>
                            <td><?php echo number_format((double)$upi['o_total'], 2, '.', ''); ?></td>
                            <td><?php 
                                if($upi['p_method_id']==1){
                                    echo "RazorPay";
                                }
                                else if($upi['p_method_id']==2){
                                    echo "COD";
                                }
                                else{
                                    echo "N/A";
                                }
                                ?>
                            </td>
                            <td> <?php echo $upi['status']; ?></td>
                            <!--<td><?php echo $upi['updated_ts']; ?></td>-->
                             <td data-sort='<?php echo date_format(date_create($upi['updated_ts']), "Ymd"); ?>'>
                                <?php 
                                    $date = date_create($upi['updated_ts']);
                                    echo date_format($date, "d/m/Y h:i A");
                                ?>
                            </td>
                            <td><?php if($upi['trans_id'] == NULL || ""){
                                    echo '-';
                                }
                                else{
                                    echo $upi['trans_id'];
                                }
                                ?></td>
                            <td><?php if($upi['upi_id'] == NULL || ""){
                                    echo '-';
                                }
                                else{
                                    echo $upi['upi_id'];
                                }
                                ?></td>
                            <td class="flex_style">
                                <button class="view_btn preview_d" data-id="<?php echo $upi['id'];?>" data-toggle="modal" id="modal_<?php echo $upi['id'];?>">View</button>
                                    <?php 
                                        if($upi['bill_type']==2){
                                        ?>
                                            <!-- <button class="view_btn preview_d">View Bill</button> -->
                                        <?php
                                            }
                                        ?>
                            </td>
                            <td><a href="reconcilation.php?orderid=<?php echo $upi['id'];?>">
                            <?php
                                if($upi['recon_status']==0){
                                    echo "Pending";
                                }
                                else{
                                    echo "Completed";
                                }
                                ?>
                            </a></td>
            			</tr>
                        <?php 
                            $i++;
                            endforeach; ?>                                  
                    </tbody>
                </table>
                <?php
            }
            else{
                // echo "<h1>Cash:</h1><br><br>";
            ?>
                <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,6">
                    <thead class="table_head">
                        <tr>
                            <th>S.No</th>
							<th>Order Id</th>
                            <th>Invoice No</th>
                            <th>Order Date/Time </th>
                            <th>Customer Name</th>
                            <th>Bill Amount</th>
                            <th>Payment Method</th>
                            <th>Order Completed Date</th>
                            <th>View Order Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                            $i=1;
                            foreach($cash as $cash):
                        ?>
                        <tr>                                            
                            <td><?php echo $i;?></td>
						    <td> <?php echo $cash['id']; ?> </td>
                            <td> <?php echo $cash['invoice_no']; ?> </td>
                            <td data-sort='<?php echo date_format(date_create($cash['created_ts']), "Ymd"); ?>'>
                                <?php 
                                    $date = date_create($cash['created_ts']);
                                    echo date_format($date, "d/m/Y h:i A");
                                ?>
                            </td>
                            <td><?php echo $cash['name']; ?></td>
                            <td><?php echo number_format((double)$cash['o_total'], 2, '.', ''); ?></td>
                            <td><?php 
                                if($cash['p_method_id']==1){
                                    echo "RazorPay";
                                }
                                else if($cash['p_method_id']==2){
                                    echo "COD";
                                }
                                else{
                                    echo "N/A";
                                }
                                ?>
                            </td>
                            <!--<td><?php echo number_format((double)$cash['updated_ts'], 2, '.', ''); ?></td>-->
                             <td data-sort='<?php echo date_format(date_create($cash['updated_ts']), "Ymd"); ?>'>
                                <?php 
                                    $date = date_create($cash['updated_ts']);
                                    echo date_format($date, "d/m/Y h:i A");
                                ?>
                            </td>
                            <!-- <td><button class="view_btn preview_d">View Bill</button></td> -->
						    <td><button class="view_btn preview_d" data-id="<?php echo $cash['id'];?>" data-toggle="modal" id="modal_<?php echo $cash['id'];?>">View</button></td>
            			</tr>
                        <?php 
                            $i++;
                            endforeach; ?>                                  
                    </tbody>               
                </table>
            <?php
            }
            ?>               
    </div>
    <div>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>
<script src="../assets/js/dateData.js"></script>

<!-- <div class="popup preview_popup" id="preview_popup">
<div id="myModal" class="modal" role="dialog">
  <div class="modal-dialog modal-lg ">

    
    <div class="modal-content gray_bg_popup">
      <div class="preview_data">
        <h2>Pending Order Preivew</h2>
        <button type="button" class="close export_btn" id="cancel_btn" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body p_data">
      
      </div>
     
    </div>

  </div>
</div> 
</div> -->
<?php 
if(isset($_POST['cash'])){
    $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active=6 and p_method_id=2 order by id desc");
    while($row1 = $stmt->fetch_assoc())
    {
    ?>
    <div class="popup preview_popup" id="preview_popup_<?php echo $row1['id'];?>">
        <div id="modal_<?php echo $row1['id'];?>" class="modal" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content gray_bg_popup">
                    <div class="preview_data">
                        <h2>Order Preview</h2>
                        <button type="button" class="close" data-dismiss="modal" id="cancel_btn_<?php echo $row1['id'];?>">&times;</button>
                    </div>
                    <div class="modal-body p_data">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
}   
else if(isset($_POST['upi'])){
    $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active=6 and p_method_id=1 order by id desc");
    while($row1 = $stmt->fetch_assoc())
    {
    ?>
    <div class="popup preview_popup" id="preview_popup_<?php echo $row1['id'];?>">
        <div id="modal_<?php echo $row1['id'];?>" class="modal" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content gray_bg_popup">
                    <div class="preview_data">
                        <h2>Order Preview</h2>
                        <button type="button" class="close" data-dismiss="modal" id="cancel_btn_<?php echo $row1['id'];?>">&times;</button>
                    </div>
                    <div class="modal-body p_data">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
}
else{
        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active=6 and p_method_id=2 order by id desc");
    while($row1 = $stmt->fetch_assoc())
    {
    ?>
    <div class="popup preview_popup" id="preview_popup_<?php echo $row1['id'];?>">
        <div id="modal_<?php echo $row1['id'];?>" class="modal" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content gray_bg_popup">
                    <div class="preview_data">
                        <h2>Order Preview</h2>
                        <button type="button" class="close" data-dismiss="modal" id="cancel_btn_<?php echo $row1['id'];?>">&times;</button>
                    </div>
                    <div class="modal-body p_data">

                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    }
} 
?>

<div class="popup" id="delete_popup">
    <h4>Are you really want to delete this?</h4>
    <div class="popup_btns">
        <button class="yes_btn" id="delete_icon">Yes</button>
        <button class="popup_cancel" id="del_cancel_btn">Cancel</button>
    </div>
</div>
<script>
//Preview page
    $(document).ready(function() {
            $(".preview_d").click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "POST",
                url: "order_product_data.php",
                data: { 
                    p_id: $(this).attr("data-id"),
                },
                success: function(result) {
                    console.log("success");
                    $(".p_data").html(result);
                },
                error: function(result) {
                    console.log("failed");
                    alert('error');
                }
            });
        });
    });
</script>
<script>
    const cashButton = document.querySelector("#cash");
    const upiButton = document.querySelector("#upi");

    let lastSelectedTab = localStorage.getItem('revenueselectedTab') || "cash";
    document.getElementById(lastSelectedTab).style.borderBottom = '2px solid grey';

    function handleTabSelection(event) {
    document.querySelectorAll('.btn-row button').forEach(item => {
        item.style.borderBottom = 'none';
    });
    event.currentTarget.style.borderBottom = '2px solid grey';

    const selectedTab = event.currentTarget.id;
    localStorage.setItem('revenueselectedTab', selectedTab);
}

document.querySelectorAll('.btn-row button').forEach(item => {
    item.addEventListener('click', handleTabSelection);
});

localStorage.setItem('revenueselectedTab', 'cash');

</script>

<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script> -->
<?php 
    require 'footer.php';
?>
<?php
function formatIndianRupees($amount) {
    $amount_str = (string)$amount;
    $is_negative = false;

    if ($amount < 0) {
        $is_negative = true;
        $amount_str = ltrim($amount_str, '-');
    }

    $parts = explode('.', $amount_str);
    $whole = $parts[0];
    $fraction = count($parts) > 1 ? '.' . $parts[1] : '';

    $last_three = substr($whole, -3);
    $other_numbers = substr($whole, 0, -3);
    if ($other_numbers != '') {
        $last_three = ',' . $last_three;
    }
    $formatted_whole = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $other_numbers) . $last_three;

    $formatted_amount = $formatted_whole . $fraction;              

    if ($is_negative) {
        $formatted_amount = '-' . $formatted_amount;
    }

    return $formatted_amount;                                                       
}
?>

<script src="../assets/js/session_check.js"></script>
</body>
</html>
