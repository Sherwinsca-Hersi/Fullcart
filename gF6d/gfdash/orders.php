<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orders</title>
    <?php 
        require_once '../api/header.php';
    ?>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
    
    <!-- izitoast -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

<!-- datepicker -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body>

<?php
$currentStatus = isset($_GET['currentStatus']) ? $_GET['currentStatus'] : 'pending';
if(isset($_GET['currentStatus'])){
    $currentStatus = isset($_GET['currentStatus']) ? $_GET['currentStatus'] : 'pending';
?>
  <script>
        document.addEventListener('DOMContentLoaded', function() {
            var currentStatus = <?php echo json_encode($currentStatus); ?>;
            console.log("currentStatus:", currentStatus);
            if (!currentStatus) {
                currentStatus = localStorage.getItem('selectedBtn') || 'pending';
            }

            localStorage.setItem('selectedBtn', currentStatus);

            var obtainedBtns = document.getElementsByName(currentStatus);
            console.log("obtainedBtns:", obtainedBtns);

            // var statusDisplay = document.getElementById('statusDisplay');
            // if (statusDisplay) {
            //     statusDisplay.innerText = currentStatus;
            // }

            if (obtainedBtns.length > 0) {
                for (var i = 0; i < obtainedBtns.length; i++) {
                    obtainedBtns[i].style.backgroundColor = "var(--theme-color)";
                    obtainedBtns[i].style.color = "white";
                }
            } else {
                console.warn("No elements found with the name: " + currentStatus);
            }
        });
    </script>


<?php
}
if (isset($_SESSION['toast'])) {
    
    $toast = $_SESSION['toast'];
    
    ?>
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
    <script>
    iziToast.<?php echo $toast['type']; ?>({
        title: '<?php echo $toast['title']; ?>',
        message: '<?php echo $toast['message']; ?>',
        position: 'topRight'
    });
    </script>
    <?php
    unset($_SESSION['toast']);
}
?>

<?php 
    require_once '../api/sidebar.php';
    ?>
    <div class="navbar_div">
        <?php
            require_once '../api/navbar.php';
        ?>
    </div>
    <div class="orders_rightbar container">
        <h2>Orders</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Order id,Customer.." id="customSearchBox" class="searchInput">  
            </div>
            <!-- <div class="addBanner_sect">
                <a href="addproduct.php" class="export_btn employee_link"> Add Product </a>
                <button type="button" class="export_btn" onclick="downloadPDF()">Export</button>
            </div> -->
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
        <!-- <div class="date_sect">
            <div class="date_dropdown">
                <select name="date_range" id="date_range3" class="input_style">
                <option value="Year-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Year-Date') ? 'selected' : ''; ?>>Year to Date</option>
                <option value="Month-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Month-Date') ? 'selected' : ''; ?>>Month to Date</option>
                <option value="Week-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Week-Date') ? 'selected' : ''; ?>>Week to Date</option>
                <option value="Date-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Date-Date') ? 'selected' : ''; ?>>Date to Date</option>
                </select>
            </div>
            <p class="datepicker dateRange">
                <input type="text" id="datepicker1">
                <span id="start_icon" class="date-icon"><img src="../assets/images/date_icon.png"></span>
                <span id="start_date1" class="selected-date startDate">01/01/2024</span>
                to
                <input type="text" id="datepicker2">
                <span id="end_icon" class="date-icon"><img src="../assets/images/date_icon.png"><input type="date"></span>
                <span id="end_date1" class="selected-date endDate"><?php echo date("d/m/Y"); ?></span>
            </p>
        </div> -->
    <!-- <div class="date_sect"> -->
        <?php 
            $selectedDateRange = isset($_GET['selectedDateRange']) ? $_GET['selectedDateRange'] : 'today';
            // echo "<h1>$selectedDateRange </h1>";
        ?>
        <!-- <form id="dateRangeForm">
            <label><input type="radio" name="dateRange" value="today" <?php if($selectedDateRange == 'today') echo "checked"?>> Today</label>
            <label><input type="radio" name="dateRange" value="yesterday" <?php if($selectedDateRange == 'yesterday') echo "checked"?>> Yesterday</label>
            <label><input type="radio" name="dateRange" value="this_week" <?php if($selectedDateRange == 'this_week') echo "checked"?>> This Week</label>
            <label><input type="radio" name="dateRange" value="7_days" <?php if($selectedDateRange == '7_days') echo "checked"?>> Last 7 Days</label>
            <label><input type="radio" name="dateRange" value="30_days" <?php if($selectedDateRange == '30_days') echo "checked"?>> Last 30 Days</label>
            <label><input type="radio" name="dateRange" value="1_year" <?php if($selectedDateRange == '1_year') echo "checked"?>> Last Year</label>
        </form>
        <?php
            $today = new DateTime();
            $firstDayOfWeek = clone $today;
            $currentDayOfWeek = $today->format('w');
            $daysToSubtract = $currentDayOfWeek;
            $firstDayOfWeek->modify("-$daysToSubtract days");
        ?>
        <p class="datepicker dateRange">
            <span id="startDate"><?php echo isset($_GET['startDate1']) ?  $_GET['startDate1'] : $today->format('d/m/Y');?></span> to <span id="endDate"><?php echo date("d/m/Y"); ?></span>
        </p> -->
     <!-- </div> -->
        <form method="post" action="">
            <div class="row btn-row">
            <button class="online_btn" name="online_order" id="online_order" data-tab="online_order"><h2>Online Orders</h2></button>    
            <button class="real_btn" name="real_order"  id="real_order" data-tab="real_order"><h2>In-Store Orders</h2></button>
            </div>
        </form>
       
        <?php
        
                            if(isset($_POST['real_order'])){
                            ?>
                                    <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example"  data-disablesortingcolumns="0,7">
                                       <thead class="table_head">
                                            <tr>
                                                <th>S.No</th>
												<th>Order Id</th>
                                                <th>Invoice No</th>
                                                <th>Order Date/Time </th>
                                                <th>Customer Name</th>
                                                <th>Bill Amount</th>
                                                <th>Billed By</th>
                                                <th class="remove_arrow">View Order Details</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                                $i=1;
                                                foreach($real_orders as $real_order):
                                            ?>
                                            <tr>
                                                <td><?php echo $i;?></td>
												<td> <?php echo $real_order['id']; ?> </td>
                                                <td> <?php echo $real_order['invoice_no']; ?> </td>
                                                <td data-sort='<?php echo date_format(date_create($real_order['created_ts']), "Ymd"); ?>'>
                                                    <?php 
                                                        $date = date_create($real_order['created_ts']);
                                                        echo date_format($date, "d/m/Y h:i A");
                                                    ?>
                                                </td>
                                                <td><?php echo $real_order['name']; ?></td>
                                                <td><?php echo number_format((double)$real_order['o_total'], 2, '.', ''); ?></td>
                                                <td><?php echo $real_order['created_by']??'N/A';?></td>
                                                <!-- <td><button class="view_btn">View Bill</button></td> -->
												 <td><button class="view_btn preview_d" data-id="<?php echo $real_order['id'];?>" data-toggle="modal" id="modal_<?php echo $real_order['id'];?>">View</button></td>
            								</tr>     
                                            <?php 
                                            $i++;
                                            endforeach;
                                            ?>                                  
                                        </tbody>



                                        
                                    </table>
                                    <?php
                                    }
                                    else if(isset($_POST['online_order'])){
                                       
                                        ?>
                                         <form id="statusForm" method="post" action="orders.php">
							            <div class="order_btns"> 
                                            <div>
                                                <input type="hidden" name="selectedBtn" id="selectedBtn0" />
                                                <input type="submit" value="All Orders (<?php echo $all_orders_query->num_rows;?>)" name="all_orders" class="export_btn order_btn">
                                            </div>
                                            <div>
                                                <input type="hidden" name="selectedBtn" id="selectedBtn1" />
                                                <input type="submit" value="Orders Received (<?php echo $pending_query->num_rows;?>)" name="pending" class="export_btn order_btn">
                                            </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn2" /> 
							                    <input type="submit"  value="Orders in Packing (<?php echo $processing_query->num_rows;?>)" name="processing" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn3" />
							                    <input type="submit"  value="Orders Packed (<?php echo $packed_query->num_rows;?>)" name="packed" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn4" />
							                    <input type="submit"  value="Assigned Orders (<?php echo $db_assigned_query->num_rows;?>)" name="db_assigned" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn5" />
							                    <input type="submit"  value="Out For Delivery (<?php echo $out_delivery_query->num_rows;?>)" name="out_delivery" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn6" />
							                    <input type="submit"  value="Completed (<?php echo $completed_query->num_rows;?>)" name="completed" class="export_btn order_btn">
							                </div>
                                        </div>
							            </form>
                                        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,6">
                                       <thead class="table_head">
                                            <tr>
                                                <th>S.No</th>
												<th>Order Id</th>
                                                <th>Order Date/Time </th>
                                                <th>Customer Name</th>
                                                <th>Bill Amount</th>
                                                <th>Payment Method</th>
                                                <th>Transaction Id</th>
                                                <th>Timeslot</th>
                                                <th>Previous Action</th>
                                                <?php if (isset($_POST['out_delivery']) || isset($_POST['completed']) || isset($_POST['all_orders'])):
                                                    if(!isset($_POST['all_orders'])){
                                                        ?>
                                                        <th>Delivery Person</th>
                                                        <?php
                                                    }
                                                    ?>
                                                    <th>Current Status</th>
                                                <?php endif; ?>
                                                <th>Previous Action Done on</th>
                                                <?php if (!(isset ($_POST['all_orders']) || isset($_POST['out_delivery']) || isset ($_POST['completed']))):
                                                    ?>
                                                    <th>Action</th>
                                                <?php endif; ?>
                                                <th class="remove_arrow">View Order Details</th>
												
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php 
                                            if(isset($currentStatus)){
                                                ?>
                                                
                                            <?php    
                                            
                                            if(isset($_POST['completed'])){
                                                $currentStatus = 'completed';
                                            } elseif(isset($_POST['processing'])){
                                                $currentStatus = 'processing';
                                            } elseif(isset($_POST['packed'])){
                                                $currentStatus = 'packed';
                                            } elseif(isset($_POST['db_assigned'])){
                                                $currentStatus = 'db_assigned';
                                            } elseif(isset($_POST['out_delivery'])){
                                                $currentStatus = 'out_delivery';
                                            }elseif(isset($_POST['pending'])){
                                                $currentStatus = 'pending';
                                            }
                                            elseif(isset($_POST['all_orders'])){
                                                $currentStatus = 'all_orders';
                                            }
                                            
                                            $i=0;
                                            if($currentStatus === 'completed') {
                                                $stmt = $completed_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateStatusToURL();
                                                    });
                                                </script>
                                            <?php
                                            }  
                                            else if ($currentStatus === 'processing'){
                                                $stmt = $processing_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            } 
                                            else if ($currentStatus === 'packed'){
                                                $stmt = $packed_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                            <?php
                                            }
                                            else if ($currentStatus === 'db_assigned'){
                                                $stmt = $db_assigned_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            else if ($currentStatus === 'out_delivery'){
                                                $stmt = $out_delivery_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            else if($currentStatus === 'all_orders'){
                                                $stmt = $all_orders_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            else{
                                                $stmt = $pending_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            
                                             $online_orders = [];
                                            
                                            while($row = $stmt->fetch_assoc())
                                            {
                                            	$online_orders[] = $row;
                                            }
											?>
                                            <?php 
                                                $i=1;
                                                foreach($online_orders as $online_order):
                                            ?>
                                            <tr>
												<td><?php echo $i; ?></td>
												<td> <?php echo $online_order['id']; ?> </td>
                                                <td  data-sort='<?php echo date_format(date_create($online_order['created_ts']), "Ymd"); ?>'>
                                                    <?php 
                                                        $date = date_create($online_order['created_ts']);
                                                        echo date_format($date, "d/m/Y h:i A");
                                                    ?>
                                                </td>
                                                <td><?php echo $online_order['name']; ?></td>
                                                <td><?php echo number_format((double)$online_order['o_total'], 2, '.', ''); ?></td>
                                                <td><?php 
                                                if($online_order['p_method_id']==1){
                                                    echo "RazorPay";
                                                }
                                                else if($online_order['p_method_id']==2){
                                                    echo "COD";
                                                }
                                                else{
                                                    echo "N/A";
                                                }
                                                ?>
                                               </td>
                                                <td><?php echo $online_order['trans_id'];?></td>
                                                <td><?php echo $online_order['t_slot'];?></td>
                                                <td><?php echo $online_order['updated_by']?? 'N/A'; ?></td>
                                                <?php if (isset($_POST['out_delivery']) || isset($_POST['completed'])|| isset($_POST['all_orders'])):
                                                    if(!isset($_POST['all_orders'])){
                                                        ?>
                                                        <td><?php 
                                                            $delivery_person=$mysqli->query("SELECT  s_name FROM `e_salesman_details` WHERE cos_id='$cos_id' AND id=".$online_order['salesman_id']."")->fetch_assoc();
                                                            echo $delivery_person['s_name']??'N/A';
                                                        ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td> <?php echo $online_order['status']; ?></td>
                                                <?php endif; ?>
                                                <td  data-sort='<?php echo date_format(date_create($online_order['updated_ts']), "Ymd"); ?>'>
                                                    <?php 
                                                        $date = date_create($online_order['updated_ts']);
                                                        echo date_format($date, "d/m/Y h:i A");
                                                    ?>
                                                </td>
                                                <?php if (!(isset ($_POST['all_orders'])|| $currentStatus=='all_orders' || isset($_POST['out_delivery']) || $currentStatus=='out_delivery' || isset ($_POST['completed']) || $currentStatus=='completed')):
                                                    ?>
                                                    <td>
                                                    <?php if (isset($_POST['pending']) || $currentStatus=='pending'):
                                                    ?>
                                                        <!-- Select Packager -->
                                                        <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
        $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Packager%'  AND active != 2 AND active != 0")->fetch_assoc();
        $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Packager</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'orders.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=1&currentStatus=pending";
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    <?php if (isset($_POST['processing']) || $currentStatus=='processing'):
                                                    ?>
                                                    <!-- Select Packager2 -->
                                                       <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
         $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Packager%'  AND active != 2 AND active != 0")->fetch_assoc();
         $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Packager</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'orders.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=2&currentStatus=processing";
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    <?php if (isset($_POST['packed']) || $currentStatus=='packed'):
                                                    ?>
                                                        <!-- Select Delivery Person -->
                                                        <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
         $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Delivery%'  AND active != 2 AND active != 0")->fetch_assoc();
        $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Delivery Person</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'porder.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=3&currentStatus=packed";
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    <?php if (isset($_POST['db_assigned']) || $currentStatus=='db_assigned'):
                                                    ?>
                                                        <!-- Select Delivery Person2 -->
                                                        <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
         $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Delivery%' AND active != 2 AND active != 0")->fetch_assoc();
         $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Delivery Person</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'orders.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=4&currentStatus=db_assigned"; 
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    </td>
                                                <?php endif; ?>
												 <td class="action_def"><button class="view_btn preview_d" data-id="<?php echo $online_order['id'];?>" data-toggle="modal" id="modal_<?php echo $online_order['id'];?>">View</button></td>
            											  </tr>
                                            <?php 
                                            $i++;
                                                endforeach; ?>                                  
                                            </tbody>
                                        </table>
                                    <?php
                                        }
                                    }
                                    else{
                                    ?>
                                    <form id="statusForm" method="post" action="orders.php">
							            <div class="order_btns"> 
                                            <div>
                                                <input type="hidden" name="selectedBtn" id="selectedBtn0" />
                                                <input type="submit" value="All Orders (<?php echo $all_orders_query->num_rows;?>)" name="all_orders" class="export_btn order_btn">
                                            </div>
                                            <div>
                                                <input type="hidden" name="selectedBtn" id="selectedBtn1" />
                                                <input type="submit" value="Orders Received (<?php echo $pending_query->num_rows;?>)" name="pending" class="export_btn order_btn">
                                            </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn2" /> 
							                    <input type="submit"  value="Orders in Packing (<?php echo $processing_query->num_rows;?>)" name="processing" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn3" />
							                    <input type="submit"  value="Orders Packed (<?php echo $packed_query->num_rows;?>)" name="packed" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn4" />
							                    <input type="submit"  value="Assigned Orders (<?php echo $db_assigned_query->num_rows;?>)" name="db_assigned" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn5" />
							                    <input type="submit"  value="Out For Delivery (<?php echo $out_delivery_query->num_rows;?>)" name="out_delivery" class="export_btn order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn6" />
							                    <input type="submit"  value="Completed (<?php echo $completed_query->num_rows;?>)" name="completed" class="export_btn order_btn">
							                </div>
                                        </div>
							            </form>
                                        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,6">
                                       <thead class="table_head">
                                            <tr>
                                                <th>S.No</th>
												<th>Order Id</th>
                                                <th>Order Date/Time </th>
                                                <th>Customer Name</th>
                                                <th>Bill Amount</th>
                                                <th>Payment Method</th>
                                                <th>Transaction Id</th>
                                                <th>Timeslot</th>
                                                <th>Previous Action</th>
                                                <?php if (isset($_POST['out_delivery']) || isset($_POST['completed']) || isset($_POST['all_orders'])):
                                                    if(!isset($_POST['all_orders'])){
                                                        ?>
                                                        <th>Delivery Person</th>
                                                        <?php
                                                    }
                                                    ?>
                                                    <th>Current Status</th>
                                                <?php endif; ?>
                                                <th>Previous Action Done on</th>
                                                <?php if (!(isset ($_POST['all_orders'])|| $currentStatus=='all_orders' || isset($_POST['out_delivery']) || $currentStatus=='out_delivery' || isset ($_POST['completed']) || $currentStatus=='completed')):
                                                    ?>
                                                    <th>Action</th>
                                                <?php endif; ?>
                                                <th class="remove_arrow">View Order Details</th>
												
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php 
                                            if(isset($currentStatus)){
                                                ?>
                                                
                                            <?php    
                                            
                                            if(isset($_POST['completed'])){
                                                $currentStatus = 'completed';
                                            } elseif(isset($_POST['processing'])){
                                                $currentStatus = 'processing';
                                            } elseif(isset($_POST['packed'])){
                                                $currentStatus = 'packed';
                                            } elseif(isset($_POST['db_assigned'])){
                                                $currentStatus = 'db_assigned';
                                            } elseif(isset($_POST['out_delivery'])){
                                                $currentStatus = 'out_delivery';
                                            }elseif(isset($_POST['pending'])){
                                                $currentStatus = 'pending';
                                            }
                                            elseif(isset($_POST['all_orders'])){
                                                $currentStatus = 'all_orders';
                                            }
                                            
                                            $i=0;
                                            if($currentStatus === 'completed') {
                                                $stmt = $completed_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateStatusToURL();
                                                    });
                                                </script>
                                            <?php
                                            }  
                                            else if ($currentStatus === 'processing'){
                                                $stmt = $processing_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            } 
                                            else if ($currentStatus === 'packed'){
                                                $stmt = $packed_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                            <?php
                                            }
                                            else if ($currentStatus === 'db_assigned'){
                                                $stmt = $db_assigned_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            else if ($currentStatus === 'out_delivery'){
                                                $stmt = $out_delivery_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            else if($currentStatus === 'all_orders'){
                                                $stmt = $all_orders_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            else{
                                                $stmt = $pending_query;
                                                ?>
                                                <script>
                                                    document.addEventListener('DOMContentLoaded', function() {
                                                        updateLocalStorage();
                                                    });
                                                </script>
                                                <?php
                                            }
                                            
                                             $online_orders = [];
                                            
                                            while($row = $stmt->fetch_assoc())
                                            {
                                            	$online_orders[] = $row;
                                            }
											?>
                                            <?php 
                                                $i=1;
                                                foreach($online_orders as $online_order):
                                            ?>
                                            <tr>
												<td><?php echo $i; ?></td>
												<td> <?php echo $online_order['id']; ?> </td>
                                                <td  data-sort='<?php echo date_format(date_create($online_order['created_ts']), "Ymd"); ?>'>
                                                    <?php 
                                                        $date = date_create($online_order['created_ts']);
                                                        echo date_format($date, "d/m/Y h:i A");
                                                    ?>
                                                </td>
                                                <td><?php echo $online_order['name']; ?></td>
                                                <td><?php echo number_format((double)$online_order['o_total'], 2, '.', ''); ?></td>
                                                <td><?php 
                                                if($online_order['p_method_id']==1){
                                                    echo "RazorPay";
                                                }
                                                else if($online_order['p_method_id']==2){
                                                    echo "COD";
                                                }
                                                else{
                                                    echo "N/A";
                                                }
                                                ?>
                                               </td>
                                                <td><?php echo $online_order['trans_id'];?></td>
                                                <td><?php echo $online_order['t_slot'];?></td>
                                                <td><?php echo $online_order['updated_by']?? 'N/A'; ?></td>
                                                <?php if (isset($_POST['out_delivery']) || isset($_POST['completed'])|| isset($_POST['all_orders'])):
                                                    if(!isset($_POST['all_orders'])){
                                                        ?>
                                                        <td><?php 
                                                            $delivery_person=$mysqli->query("SELECT  s_name FROM `e_salesman_details` WHERE cos_id='$cos_id' AND id=".$online_order['salesman_id']."")->fetch_assoc();
                                                            echo $delivery_person['s_name']??'N/A';
                                                        ?>
                                                    </td>
                                                    <?php } ?>
                                                    <td> <?php echo $online_order['status']; ?></td>
                                                <?php endif; ?>
                                                <td  data-sort='<?php echo date_format(date_create($online_order['updated_ts']), "Ymd"); ?>'>
                                                    <?php 
                                                        $date = date_create($online_order['updated_ts']);
                                                        echo date_format($date, "d/m/Y h:i A");
                                                    ?>
                                                </td>
                                                <?php if (!(isset ($_POST['all_orders'])|| $currentStatus=='all_orders' || isset($_POST['out_delivery']) || $currentStatus=='out_delivery' || isset ($_POST['completed']) || $currentStatus=='completed')):
                                                    ?>
                                                    <td>
                                                    <?php if (isset($_POST['pending']) || $currentStatus=='pending'):
                                                    ?>
                                                        <!-- Select Packager -->
                                                        <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
         $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Packager%'  AND active != 2 AND active != 0")->fetch_assoc();
         $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Packager</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'orders.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=1&currentStatus=pending";
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    <?php if (isset($_POST['processing']) || $currentStatus=='processing'):
                                                    ?>
                                                    <!-- Select Packager2 -->
                                                       <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
         $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Packager%'  AND active != 2 AND active != 0")->fetch_assoc();
         $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Packager</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'orders.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=2&currentStatus=processing";
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    <?php if (isset($_POST['packed']) || $currentStatus=='packed'):
                                                    ?>
                                                        <!-- Select Delivery Person -->
                                                        <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
         $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Delivery%' AND active != 2 AND active != 0")->fetch_assoc();
         $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Delivery Person</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'porder.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=3&currentStatus=packed";
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    <?php if (isset($_POST['db_assigned']) || $currentStatus=='db_assigned'):
                                                    ?>
                                                        <!-- Select Delivery Person2 -->
                                                        <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $online_order['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
        <?php
         $role_query = $mysqli->query("SELECT id FROM e_salesman_role WHERE cos_id = '$cos_id' AND role_title LIKE '%Delivery%'  AND active != 2 AND active != 0")->fetch_assoc();
         $query = "SELECT id, s_name FROM e_salesman_details WHERE cos_id = '$cos_id' AND role = ".$role_query['id']." AND active != 2 AND active != 0";
        $result = $mysqli->query($query);
    if ($result && $result->num_rows > 0) {
?>
<option value="0" <?php if($online_order['salesman_id']==0){
    echo "selected";
    }?>>Select Delivery Person</option>
    <?php
    while ($row = $result->fetch_assoc()) {
        $selected = ($online_order['salesman_id'] != 0 && $online_order['salesman_id'] == $row['id']) ? 'selected' : '';
        ?>
        <option value="<?php echo $row['id']; ?>" <?php echo $selected; ?>>
            <?php echo $row['s_name']; ?>
        </option>
        <?php
    }
}
?>
    </select>
  </div>
  <script>
 document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                var anchorElement = selectElement.closest('.form-group').querySelector('.myLink');
                var dsid = anchorElement.getAttribute('data-dsid');
                console.log(dsid);

                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            console.log(xhr.responseText);
                        } else {
                            console.error('Error occurred: ' + xhr.status);
                        }
                    }
                };
                xhr.open('GET', 'orders.php?dsid=' + dsid + '&salesman_id=' + selectedValue);
                xhr.send();
                console.log(selectedValue);
                var href = "com_ins_upd.php?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=4&currentStatus=db_assigned"; 
                anchorElement.setAttribute('href', href);

                var hrefValue = anchorElement.getAttribute('href');
                window.location.href = hrefValue;
            });
        });
    });
    </script>
                                                    <?php
                                                    endif;?>
                                                    </td>
                                                <?php endif; ?>
												 <td class="action_def"><button class="view_btn preview_d" data-id="<?php echo $online_order['id'];?>" data-toggle="modal" id="modal_<?php echo $online_order['id'];?>">View</button></td>
            											  </tr>
                                            <?php 
                                            $i++;
                                                endforeach; ?>                                  
                                            </tbody>
                                        </table>
                                    <?php
                                    }
                                    
                                    }
                                    ?>

    </div>
   

<div>
    <?php
        require_once "logoutpopup.php";
    ?>
</div>
<!-- <script src="../assets/js/date2.js"></script> -->
<?php 
if(isset($_POST['real_order'])){
    $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and invoice_no!=0 order by id desc");
    while($row1 = $stmt->fetch_assoc())
    {
    ?>
    <div class="popup preview_popup" id="preview_popup_<?php echo $row1['id'];?>">
        <div id="modal_<?php echo $row1['id'];?>" class="modal" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content gray_bg_popup">
                    <div class="preview_head">
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
else if(isset($_POST['online_order'])){
    if(isset($_POST['completed'])){
        $currentStatus = 'completed';
    } elseif(isset($_POST['processing'])){
        $currentStatus = 'processing';
    } elseif(isset($_POST['packed'])){
        $currentStatus = 'packed';
    } elseif(isset($_POST['db_assigned'])){
        $currentStatus = 'db_assigned';
    } elseif(isset($_POST['out_delivery'])){
        $currentStatus = 'out_delivery';
    }elseif(isset($_POST['pending'])){
        $currentStatus = 'pending';
    }
    else if(isset($_POST['all_orders'])){
        $currentStatus = 'all_orders';
    }
    
                                                $i=0;
                                                    if($currentStatus === 'completed') {
                                                        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='6'  and bill_type='1' order by id desc");
                                                    } 
                                                    else if ($currentStatus === 'processing'){
                                                        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='2'  and bill_type='1' order by id desc");
                                                    }
                                                    else if ($currentStatus === 'packed'){
                                                        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='3'  and bill_type='1' order by id desc");
                                                    }
                                                    else if ($currentStatus === 'db_assigned'){
                                                        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='4'  and bill_type='1' order by id desc");
                                                    }
                                                    else if ($currentStatus === 'out_delivery'){
                                                        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='5'  and bill_type='1' order by id desc");
                                                    }
                                                    else if ($currentStatus === 'all_orders'){
                                                        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id'  and bill_type='1' order by id desc");
                                                    }
                                                    else{
                                                        $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='1' and status='Order Placed'  and bill_type='1' order by id desc");
                                                    }
    // $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='1' and bill_type='1' order by id desc");
    while($row1 = $stmt->fetch_assoc())
    {
    ?>
    <div class="popup preview_popup" id="preview_popup_<?php echo $row1['id'];?>">
        <div id="modal_<?php echo $row1['id'];?>" class="modal" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content gray_bg_popup">
                    <div class="preview_head">
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
        if(isset($_POST['completed'])){
            $currentStatus = 'completed';
        } elseif(isset($_POST['processing'])){
            $currentStatus = 'processing';
        } elseif(isset($_POST['packed'])){
            $currentStatus = 'packed';
        } elseif(isset($_POST['db_assigned'])){
            $currentStatus = 'db_assigned';
        } elseif(isset($_POST['out_delivery'])){
            $currentStatus = 'out_delivery';
        }elseif(isset($_POST['pending'])){
            $currentStatus = 'pending';
        }
        
                                                    $i=0;
                                                        if($currentStatus === 'completed') {
                                                            $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='6'  and bill_type='1' order by id desc");
                                                        } 
                                                        else if ($currentStatus === 'processing'){
                                                            $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='2'  and bill_type='1' order by id desc");
                                                        }
                                                        else if ($currentStatus === 'packed'){
                                                            $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='3'  and bill_type='1' order by id desc");
                                                        }
                                                        else if ($currentStatus === 'db_assigned'){
                                                            $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='4'  and bill_type='1' order by id desc");
                                                        }
                                                        else if ($currentStatus === 'out_delivery'){
                                                            $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='5'  and bill_type='1' order by id desc");
                                                        }
                                                        else if ($currentStatus === 'all_orders'){
                                                            $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id'  and bill_type='1' order by id desc");
                                                        }
                                                        else{
                                                            $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='1' and status='Order Placed'  and bill_type='1' order by id desc");
                                                        }
    while($row1 = $stmt->fetch_assoc())
    {
    ?>
    <div class="popup preview_popup" id="preview_popup_<?php echo $row1['id'];?>">
        <div id="modal_<?php echo $row1['id'];?>" class="modal" role="dialog">
            <div class="modal-dialog modal-lg ">
                <div class="modal-content gray_bg_popup">
                    <div class="preview_head">
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
            var dataId = $(this).attr("data-id");
            console.log("Data ID: ", dataId);
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
    const onlineOrderButton = document.querySelector("#online_order");
    const realOrderButton = document.querySelector("#real_order");

    let lastSelectedTab = localStorage.getItem('salesselectedTab') || "online_order";
    document.getElementById(lastSelectedTab).style.borderBottom = '2px solid grey';

    function handleTabSelection(event) {
    document.querySelectorAll('.btn-row button').forEach(item => {
        item.style.borderBottom = 'none';
    });
    event.currentTarget.style.borderBottom = '2px solid grey';

    const selectedTab = event.currentTarget.id;
    localStorage.setItem('salesselectedTab', selectedTab);
}

document.querySelectorAll('.btn-row button').forEach(item => {
    item.addEventListener('click', handleTabSelection);
});

localStorage.setItem('salesselectedTab', 'online_order');
</script>


<?php 
    require 'footer.php';
?>

<script>
    // // Get references to the buttons
    // const orderButtons = document.querySelectorAll('.order_btn');
    // const selectedButtons = [selectedBtn1, selectedBtn2, selectedBtn3, selectedBtn4, selectedBtn5, selectedBtn6];

    // // Retrieve the last selected button from localStorage, if available
    // let lastSelectedButton = localStorage.getItem('selectedBtn');

    // // If a button was previously selected, highlight it; otherwise, highlight the default button (selectedBtn1)
    // let buttonToHighlight = lastSelectedButton
    //     ? document.querySelector(`.order_btn[name=${lastSelectedButton}]`)
    //     : document.querySelector('.order_btn[name="pending"]'); // Assuming "pending" is the default button's name

    // // Apply highlight style to the buttonToHighlight
    // if (buttonToHighlight) {
    //     buttonToHighlight.style.backgroundColor = 'var(--theme-color)';
    //     buttonToHighlight.style.color = 'white';
    // }

    // // Add click event listeners to each order button
    // orderButtons.forEach(item => {
    //     item.addEventListener('click', event => {
    //         // Reset the styles for all buttons
    //         orderButtons.forEach(otherItem => {
    //             otherItem.style.backgroundColor = 'var(--card-title-color)';
    //             otherItem.style.color = ''; // Reset color
    //         });

    //         // Apply the highlight style to the clicked button
    //         const clickedButton = event.currentTarget;
    //         clickedButton.style.backgroundColor = 'var(--theme-color)';
    //         clickedButton.style.color = 'white';

    //         // Update selected button values and save the selection to localStorage
    //         const selectedName = clickedButton.name;
    //         selectedButtons.forEach(btn => btn.value = selectedName);
    //         localStorage.setItem('selectedBtn', selectedName);

    //         // Submit the form to display data related to the selected button
    //         document.querySelector('form').submit();
    //     });
    // });


    document.addEventListener("DOMContentLoaded", () => {
    const orderButtons = document.querySelectorAll(".order_btn");
    const selectedButtons = document.querySelectorAll(".selected_btn");

    // Retrieve the current status from localStorage (default to "pending" if not set)
    let currentStatus = localStorage.getItem("currentStatus") || "pending"; 

    // First, reset all button styles to avoid multiple highlights
    orderButtons.forEach((btn) => {
        btn.style.backgroundColor = "var(--card-title-color)";
        btn.style.color = ""; // Reset color
    });

    // Find and highlight the correct button based on currentStatus
    let buttonToHighlight = document.querySelector(`.order_btn[name="${currentStatus}"]`);
    
    if (buttonToHighlight) {
        buttonToHighlight.style.backgroundColor = "var(--theme-color)";
        buttonToHighlight.style.color = "white";
    }

    // Add click event listeners to each order button
    orderButtons.forEach((item) => {
        item.addEventListener("click", (event) => {
            // Reset styles for all buttons
            orderButtons.forEach((otherItem) => {
                otherItem.style.backgroundColor = "var(--card-title-color)";
                otherItem.style.color = ""; // Reset color
            });

            // Apply highlight style to clicked button
            const clickedButton = event.currentTarget;
            clickedButton.style.backgroundColor = "var(--theme-color)";
            clickedButton.style.color = "white";

            // Get selected button status and update localStorage
            const selectedStatus = clickedButton.name;
            selectedButtons.forEach((btn) => (btn.value = selectedStatus));
            localStorage.setItem("currentStatus", selectedStatus);

            // Submit form
            document.querySelector("form").submit();
        });
    });
});

</script>



<script>

function updateLocalStorage() {
    // Update local storage values
    let currentStatus = '<?php echo $currentStatus; ?>';
    localStorage.setItem('currentStatus', currentStatus);

    // const selectedDateRange = getSelectedDateRange();
    // localStorage.setItem('selectedDateRange', selectedDateRange);
}

function updateURLFromLocalStorage() {
    // Retrieve values from local storage
    const storedStatus = localStorage.getItem('currentStatus');
    // const storedDateRange = localStorage.getItem('selectedDateRange');

    let currentUrl = new URL(window.location.href);
    console.log(currentUrl.href);

    // Update URL with stored status
    if (currentUrl.searchParams.get('currentStatus') !== storedStatus) {
        currentUrl.searchParams.set('currentStatus', storedStatus);
        window.history.replaceState({}, '', currentUrl.href);
        console.log("URL updated with status:", currentUrl.href);
    }

    // Update URL with stored date range
    // if (currentUrl.searchParams.get('selectedDateRange') !== storedDateRange) {
    //     currentUrl.searchParams.set('selectedDateRange', storedDateRange);
    //     window.history.replaceState({}, '', currentUrl.href);
    //     console.log("URL updated with date range:", currentUrl.href);
    // }
}

// function getSelectedDateRange() {
//     const dateRangeForm = document.getElementById('dateRangeForm');
//     const selectedDateRange = dateRangeForm.querySelector('input[name="dateRange"]:checked').value;
//     return selectedDateRange;
// }

// First update local storage values
updateLocalStorage();
updateURLFromLocalStorage();
// Then update URL based on local storage values


// Retrieve and log stored values for debugging
function retrieveStoredValues() {
    const storedStatus = localStorage.getItem('currentStatus');
    // const storedDateRange = localStorage.getItem('selectedDateRange');

    console.log("Stored Status:", storedStatus);
    // console.log("Stored Date Range:", storedDateRange);
}
retrieveStoredValues();

</script>
<script>
    document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
            selectElement.addEventListener('change', function() {
                var selectedValue = selectElement.value;
                console.log(selectedValue);
            });
        });
</script>
<script src="../assets/js/session_check.js"></script>
</body>
</html>
