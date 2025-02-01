<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
        require '../api/header.php';  
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $project_name;?></title>

    <!-- datepicker -->
<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<!-- Include jQuery UI -->
<link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> 
<!-- Chart Js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>
</head>
<body>
<?php 
    require '../api/sidebar.php';
?>
    <div class="navbar_div">
        <?php
            require '../api/navbar.php';
        ?>
    </div>
    <div class="rightbar container">
        <h2>Dashboard</h2>
        <div class="card-style">
            <a href="products.php" class="card_link"><div class="product_card">
                <div>
                    <h2><?php echo $total_products;?></h2>
                </div>
                <div class="card-text">
                    <img src="..\assets\images\product_icon.png" alt="product-icon-img">
                    <h3>Total Products</h3>
                </div>
            </div></a>
            <a href="orders.php" class="card_link"><div class="sales_card">
                <div class="sales_count">
                    <div class="instore_count">
                        <h2><?php echo $instore_orders;?></h2>
                        <h4>In-Store Orders</h4>
                    </div>
                    <div class="online_count">
                        <h2><?php echo $online_orders;?></h2>
                        <h4>Online Orders</h4>
                    </div>
                </div>
                <div class="card-text">
                    <img src="..\assets\images\sales_icon.png" alt="sales-icon-img">
                    <h3>Total Orders</h3>
                    <div><h3><?php echo " ".$total_sales;?></h3></div>
                </div>
            </div></a>
            <a href="customers.php" class="card_link"><div class="customer_card">
                <div><h2><?php echo $total_customers;?></h2></div>
                <div class="card-text">
                    <img src="..\assets\images\customer_icon.png" alt="customer-icon-img">
                    <h3>Total Customers</h3>
                </div>
            </div></a>
            <a href="revenue.php" class="card_link"><div class="revenue_card">
                <div class="sales_count">
                    <div class="instore_count">
                        <h2><?php if($instore_sales['order_total']==NULL ){
                            echo "₹ 0";
                            }else{
                                echo "₹".formatIndianRupees(number_format((float)$instore_sales['order_total'], 2, '.', ''));
                                
                            }?></h2>
                        <h4>In-Store Orders</h4>
                    </div>
                    <div class="online_count">
                        <h2><?php echo "₹".formatIndianRupees(number_format((float)$online_sales['order_total'], 2, '.', ''));?></h2>
                        <h4>Online Orders</h4>
                    </div>
                </div>
                <div class="card-text">
                    <img src="..\assets\images\revenue_icon.png" alt="revenue-icon-img">
                    <h3>Total Revenue </h3>
                    <div><h3><?php 
                $sales;
                $sa = 0;
    if($sales['order_total'] == ''){
        echo '₹'.' '.$sa;}
        else {
            echo '₹' . formatIndianRupees(number_format((float)$sales['order_total'], 2, '.', ''));
    }?></h3></div>
                </div>
            </div></a>
        </div>
        <div class="date_sect">
            <!-- <div class="date_dropdown">
                <select name="date_range" id="date_range_select" class="input_style">
					<option value="Today"  class="option_style" selected>Today</option>
                    <option value="Yesturday"  class="option_style">Yesturday</option>
                    <option value="ThisWeek"  class="option_style">This Week</option>
                    <option value="Last7days"  class="option_style">Last 7 days</option>
                    <option value="ThisMonth"  class="option_style">This Month</option>
                    <option value="Last30Days"  class="option_style">Last 30 Days</option>
                    <option value="ThisQuater"  class="option_style">This Quater</option>
                    <option value="Last3Months"  class="option_style">Last 3 Months</option>
                    <option value="Others"  class="option_style">Others</option>
                </select>
            </div>
             <div class="date_dropdown">
                <select name="date_range" id="date_range3" class="input_style">
                <option value="Year-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Year-Date') ? 'selected' : ''; ?>>Year to Date</option>
                <option value="Month-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Month-Date') ? 'selected' : ''; ?>>Month to Date</option>
                <option value="Week-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Week-Date') ? 'selected' : ''; ?>>Week to Date</option>
                <option value="Date-Date" class="option_style" <?php echo (isset($_GET['selectedDateRange']) && $_GET['selectedDateRange'] == 'Date-Date') ? 'selected' : ''; ?>>Date to Date</option>
                </select>
            </div>
    <p class="datepicker dateRange">
        <input type="text" id="datepicker1" placeholder="Start Date" readonly>
        <span id="start_icon" class="date-icon"><img src="../assets/images/date_icon.png" alt="date-icon-img"></span>
        <span id="start_date1" class="selected-date startDate">01/01/2024</span>
        to
        <input type="text" id="datepicker2" placeholder="End Date" readonly>
        <span id="end_icon" class="date-icon"><img src="../assets/images/date_icon.png" alt="date-icon-img"></span>
        <span id="end_date1" class="selected-date endDate"><?php echo date("d/m/Y"); ?></span>
    </p> -->
    <?php 
            $selectedDateRange = isset($_GET['selectedDateRange']) ? $_GET['selectedDateRange'] : 'today';
        ?>
        <!-- <form id="dateRangeForm">
            <label><input type="radio" name="dateRange" value="today" <?php if($selectedDateRange == 'today') echo "checked"?>> Today</label>
            <label><input type="radio" name="dateRange" value="yesterday" <?php if($selectedDateRange == 'yesterday') echo "checked"?>> Yesterday</label>
            <label><input type="radio" name="dateRange" value="this_week" <?php if($selectedDateRange == 'this_week') echo "checked"?>> This Week</label>
            <label><input type="radio" name="dateRange" value="7_days" <?php if($selectedDateRange == '7_days') echo "checked"?>> Last 7 Days</label>
            <label><input type="radio" name="dateRange" value="30_days" <?php if($selectedDateRange == '30_days') echo "checked"?>> Last 30 Days</label>
            <label><input type="radio" name="dateRange" value="1_year" <?php if($selectedDateRange == '1_year') echo "checked"?>> Last Year</label>
        </form> -->
        <!-- <form id="dateRangeForm">
            <button type="button" data-value="today" class="date-range-btn <?php if($selectedDateRange == 'today') echo 'active'; ?>">Today</button>
            <button type="button" data-value="yesterday" class="date-range-btn <?php if($selectedDateRange == 'yesterday') echo 'active'; ?>">Yesterday</button>
            <button type="button" data-value="this_week" class="date-range-btn <?php if($selectedDateRange == 'this_week') echo 'active'; ?>">This Week</button>
            <button type="button" data-value="7_days" class="date-range-btn <?php if($selectedDateRange == '7_days') echo 'active'; ?>">Last 7 Days</button>
            <button type="button" data-value="30_days" class="date-range-btn <?php if($selectedDateRange == '30_days') echo 'active'; ?>">Last 30 Days</button>
            <button type="button" data-value="1_year" class="date-range-btn <?php if($selectedDateRange == '1_year') echo 'active'; ?>">Last Year</button>
        </form> -->
        <!-- <?php
            $today = new DateTime();
            $firstDayOfWeek = clone $today;
            $currentDayOfWeek = $today->format('w');
            $daysToSubtract = $currentDayOfWeek;
            $firstDayOfWeek->modify("-$daysToSubtract days");
        ?>
        <p class="datepicker dateRange">
            <span id="startDate"><i class="fa fa-arrow-left" aria-hidden="true"></i>&emsp;<?php echo isset($_GET['startDate1']) ?  $_GET['startDate1'] : $today->format('d/m/Y');?></span> to <span id="endDate"><?php echo date("d/m/Y"); ?>&emsp;<i class="fa fa-arrow-right" aria-hidden="true"></i></span>
        </p> -->
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
        
    </div>
        <div class="grid-container">
            <div class="grid_main_div">
                <!-- <div class="grid_card_heading">
                    <h3>Sales Efficiency
                        <div id="date-selector1">
                            <button data-value="today">Today</button>
                            <button data-value="this-week">This Week</button>
                            <button data-value="this-month">This Month</button>
                            <button data-value="last-year">Last Year</button>
                        </div></h3>
                </div>
                <div class="grid_card-1">
                    <div class="progressbar" id="progressbar">
                        <div class="progressbarValue" id="progressbarValue">
                            <h1 id="barvalue"></h1>
                        </div>
                    </div>
                </div> -->
                <div class="grid_card_heading">
    <h3>Sales Efficiency(Monthly)</h3>
</div>
<div class="grid_card-1">
    <!-- <div class="progressbar" id="progressbar">
        <div class="progressbarValue" id="progressbarValue">
            <h1 id="barvalue">0%</h1>
        </div>
    </div> -->
    <canvas id="salesEfficiencyChart"></canvas>
</div>
            </div>
            <div class="grid_main_div">
                <div class="grid_card_heading">
                    <h3>Top 5 Selling Products
                    <div id="date-selector">
                        <button data-value="today">Today</button>
                        <button data-value="this-week">This Week</button>
                        <button data-value="this-month">This Month</button>
                        <button data-value="last-year">Last Year</button>
                    </div></h3>
                </div>
                <div class="grid_card-2">
                    <!-- <div class="topselling_productList">
                        <?php foreach ($productss as $product):?>
                            <div class="top_products">
                                <img src='../<?php echo $product['p_img'];?>'>
                                <h4 class="product_text"><?php echo $product['p_title'].' <span>('.$product['p_type'].')</span>' ;?></h4>
                                <h5><?php echo $product['total'];?>&nbsp;<span>Pcs</span></h5>
                            </div>
                        <?php endforeach; ?>
                    </div> -->
                    <!-- <div class="stock_progress">
                        <div class="circular-progress-bar">
                            <svg>
                                <circle cx="100" cy="100" r="85" class="circle-bg"></circle>
                            </svg>
                        </div>
                        <div class="legend" id="legend">
                            <div class="text" id="progress-text">Top Selling Products</div>
                        </div>
                    </div>
                    <div class="noData_div"><h2 id='no_top_selling'></h2></div> -->
                    <div id="chart-container">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="grid_main_div">
                <div class="grid_card_heading">
                    <h3>Stock Alert</h3>
                </div>
                <div class="grid_card-3">
                    <!-- <div class="stock_progress">
                        <div class="circular-progress-bar">
                            <svg>
                                <circle cx="100" cy="100" r="85" class="circle-bg"></circle>
                            </svg>
                        </div>
                        <div class="legend" id="legend">
                            <div class="text" id="progress-text">Low Stock</div>
                        </div>
                    </div> -->
                    
                        <div class="grid_subcard">
                            <h4 class="sub_card_head">Low Stock</h4>
                            <div class="topselling_productList">
                        
                        <?php foreach ($low_stock_prod as $low_prod):?>
                            <div class="top_products">
                                <h4 class="product_text"><?php echo $low_prod['p_title'].' <span>('.$low_prod['p_variation'].' '.$low_prod['unit'].')</span>' ;?></h4>
                                <h4><?php echo $low_prod['qty_left'];?>&nbsp;<span>Pcs</span></h4>
                            </div>
                        <?php endforeach; ?>
                    </div>

                        </div>
                        <div class="grid_subcard">
                            <h4 class="sub_card_head">Out Of Stock</h4>
                            <div class="topselling_productList">
                        
                        <?php foreach ($out_stock_prod as $out_prod):?>
                            <div class="top_products">
                                <h4 class="product_text"><?php echo $out_prod['p_title'].' <span>('.$out_prod['p_variation'].' '.$out_prod['unit'].')</span>' ;?></h4>
                                <h4><?php echo $out_prod['qty_left'];?>&nbsp;<span>Pcs</span></h4>
                            </div>
                        <?php endforeach; ?>
                    </div>

                        </div>
                </div>
            </div>
            <div class="grid_main_div">
                <div class="grid_card_heading">
                    <h3>Top 5 Unsold Products</h3>
                </div>
                <div class="grid_card-4">
                    <a href="unsold_products.php">View More...</a>
                    <div class="unsold_progress">
                        <div class="unsold-progress-bar">
                            <svg>
                                <circle cx="60" cy="60" r="65" class="circle-bg"></circle>
                            </svg>
                        </div>
                        <div class="legend" id="unsold_prod_legend">
                            <!-- <div class="text" id="progress-text">Unsold Products</div> -->
                        </div>
                    </div>
                    <div class="noData_div"><h2 id='no_unsold'></h2></div>
                </div>
            </div>
            <div class="grid_main_div">
                <div class="grid_card_heading">
                    <h3 id="chart-heading">
                        Revenue Chart (Monthly)
                    </h3>
                    <div id="button-selector1">
                        <button data-value="revenue">Revenue Chart</button>
                        <button data-value="customers">New Customer</button>
                        <button data-value="products">New Product</button>
                        <!-- <button data-value="">Last Year</button> -->
                    </div>
                </div>
                <div class="grid_card-5">
                    <div class="linechart_div">
                        <canvas id="lineChart"></canvas>
                    </div>
                    <div id="data"></div>
                </div>
            </div>

            <div class="grid_main_div">
                <div class="grid_card_heading">
                    <h3>Top 5 Customers</h3>
                    <div class="cust_btn_div">
                        <button id="byRevenue" data-value="byRevenue">By Revenue</button>
                        <button id="byRecentPurchase" data-value="byRecentPurchase">By Recent Purchase</button>
                        <button id="byNumberOfOrders" data-value="byNoofOrders">By No of Orders</button>
                    </div>
                    <!-- <h5>Total Customers: <?php echo $total_customers;?></h5> -->
                </div>
                <form id="customerForm" action="#" method="POST">
    <input type="hidden" id="customerDataField" name="customerData" value="">
</form>
<script>
window.onload = function() {
    const lastSelectedOrder = localStorage.getItem('selectedOrder') || 'purchased_price';
    highlightButton(lastSelectedOrder);
};

document.getElementById('byRevenue').addEventListener('click', function() {
    fetchData('purchased_price');
    highlightButton('purchased_price');
});

document.getElementById('byRecentPurchase').addEventListener('click', function() {
    fetchData('recent_purchase');
    highlightButton('recent_purchase');
});

document.getElementById('byNumberOfOrders').addEventListener('click', function() {
    fetchData('total_orders');
    highlightButton('total_orders');
});


function highlightButton(orderBy) {
    document.getElementById('byRevenue').classList.remove('active');
    document.getElementById('byRecentPurchase').classList.remove('active');
    document.getElementById('byNumberOfOrders').classList.remove('active');

    if (orderBy === 'purchased_price') {
        document.getElementById('byRevenue').classList.add('active');
    } else if (orderBy === 'recent_purchase') {
        document.getElementById('byRecentPurchase').classList.add('active');
    } else if (orderBy === 'total_orders') {
        document.getElementById('byNumberOfOrders').classList.add('active');
    }
}

function fetchData(orderBy) {
    localStorage.setItem('selectedOrder', orderBy);
    const xhr = new XMLHttpRequest();
    xhr.open('GET', 'customer_dash.php?order_by=' + orderBy, true);
    xhr.onload = function() {
        if (xhr.status === 200) {
            const customerData = JSON.parse(xhr.responseText);
            document.getElementById('customerDataField').value = JSON.stringify(customerData);
            document.getElementById('customerForm').submit();
        }
    };
    xhr.send();
}
</script>


                <div class="grid_card-6">
                <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style" id="example" data-disablesortingcolumns="4,5">
                    <thead class="table_head">
                        <th>Customer Id</th>
                        <th>Customer Name</th>
                        <th>No of Orders</th>
                        <th>Total Amount</th>
                        <th>Last Purchased Date</th>
                    </thead>
                        <?php 
                        $i=1;
                        if (isset($_POST['customerData'])) {
                            $customerData = json_decode($_POST['customerData'], true);
                    
                            $_SESSION['customerData'] = $customerData;
                            // print_r($customerData);
                        }
                        else{
                            $customerData=$high_purchased_cust;
                        }
                        foreach ($customerData as $high_cust):?>
                            <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
                                <td><?php echo $high_cust['u_id'];?></td>
                                <td><?php echo $high_cust['name'];?></td>
                                <td><?php echo $high_cust['total_orders'];?></td>
                                <td><?php echo formatIndianRupees(number_format((float)$high_cust['purchased_price'], 2, '.', ''));?></td>
                                <td><?php echo $high_cust['latest_odate'];?></td>
                            </tr>
                        <?php
                            $i++;
                        endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <div class="grid_recent_saled">
                <div class="grid_card_heading">
                    <h3>Recent Orders</h3>
                </div>
                <div class="recent_saled_card">
                    <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style">
                        <thead class="table_head">
                        <th>Order Id</th>
                        <th>Invoice No</th>
                        <th>Order Date/Time </th>
                        <th>Order Type</th>
                        <th>Customer Name</th>
                        <th>Customer Mobile</th>
                        <th>Bill Amount</th>
                        </thead>
                        <?php 
                        $i=1;
                        foreach ($recent_sales as $recent_saled_prod):?>
                            <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
												<td> <?php echo $recent_saled_prod['id']; ?> </td>
                                                <td> <?php echo $recent_saled_prod['invoice_no']; ?> </td>
                                                <td data-sort='<?php echo date_format(date_create($recent_saled_prod['created_ts']), "Ymd"); ?>'>
                                                    <?php 
                                                        $date = date_create($recent_saled_prod['created_ts']);
                                                        echo date_format($date, "d/m/Y h:i A");
                                                    ?>
                                                </td>
                                                <td><?php
                                                if($recent_saled_prod['bill_type']==1){
                                                    echo "Online";
                                                }else if($recent_saled_prod['bill_type']==2){
                                                    echo "Instore";
                                                }else{
                                                    echo "N/A";
                                                }
                                                 ?></td>
                                                <td><?php echo $recent_saled_prod['name']; ?></td>
                                                <td><?php echo $recent_saled_prod['mobile']; ?></td>
                                                <td><?php echo number_format((double)$recent_saled_prod['o_total'], 2, '.', ''); ?></td>
                            </tr>
                        <?php
                            $i++;
                        endforeach; ?>
                    </table>
                </div>
        </div>
    </div>
    <div id="snackbar">Login Successful...</div>
    <div>
    <?php 
        require 'datecharts.php';
        require 'showData.php';
        require 'fetch_sales_eff.php';
        // require 'customer_dash.php';
    ?>

        <script src="../assets/js/session_check.js"></script>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>
    <script src="../assets/js/date.js"></script>

   <script>
        document.addEventListener("DOMContentLoaded", function() {
            <?php if ($login_success): ?>
            var x = document.getElementById("snackbar");
            x.className = "show";
            setTimeout(function() { 
                x.className = x.className.replace("show", ""); 
            }, 3000);
            <?php endif; ?>
        });
    </script>


<!--Piechart Function-->
<?php
function customerConicGradient($high_purchased_cust) {
    $colors_cust = [
        'rgba(241, 120, 182, 1)',
        'rgba(120, 121, 241, 1)',
        'rgba(120, 234, 241, 1)',
        'rgba(241, 120, 120, 1)',
    ];
    
    $cust_gradient = 'conic-gradient(';
    $cust_start = 0;
    function calculatePurchasePrice($high_purchased_cust) {
    $purchase_price = 0;
    foreach ($high_purchased_cust as $high_cust) {
        if (isset($high_cust['purchased_price']) && is_numeric($high_cust['purchased_price'])) {
            $purchase_price += $high_cust['purchased_price'];
        }
    }
    return $purchase_price;
}
$purchased_price_sum = calculatePurchasePrice($high_purchased_cust);
$colorIndex_cust = 0;

foreach ($high_purchased_cust as $high_cust) {
    $cust_end = round($cust_start + ($high_cust['purchased_price'] / $purchased_price_sum * 360));
    $color_cust = $colors_cust[$colorIndex_cust];
    
    $cust_gradient .= "$color_cust $cust_start" . "deg, ";
    $cust_gradient .= "$color_cust $cust_end" . "deg, ";
    
    $colorIndex_cust = ($colorIndex_cust + 1) % count($colors_cust);

    $cust_start = $cust_end;
}

$cust_gradient = rtrim($cust_gradient, ', ');

return "$cust_gradient)";
}

function salesConicGradient($products) {
    $colors = [
        'rgba(241, 120, 182, 1)',
        'rgba(120, 121, 241, 1)',
        'rgba(120, 234, 241, 1)',
        'rgba(241, 120, 120, 1)',
        'rgba(60, 179, 113,1)',
    ];
    
    $gradient = 'conic-gradient(';
    $start = 0;
    function calculateTotal($products) {
    $total = 0;
    foreach ($products as $product) {
        if (isset($product['total']) && is_numeric($product['total'])) {
            $total += $product['total'];
        }
    }
    return $total;
}
$total_sum = calculateTotal($products);
$colorIndex = 0;

foreach ($products as $product) {
    $end = round($start + ($product['total'] / $total_sum * 360));
    
    $color = $colors[$colorIndex];
    
    $gradient .= "$color $start" . "deg, ";
    $gradient .= "$color $end" . "deg, ";
    
    $colorIndex = ($colorIndex + 1) % count($colors);

    $start = $end;
}

$gradient = rtrim($gradient, ', ');

return "$gradient)";
}
?>


<script>
// const  progressbar=document.getElementById('progressbar');
// const progressbarValue=document.getElementById('progressbarValue');
// const barValue=document.getElementById('barvalue');
// var progressStart=0;
// var salesPercentage = `${''}`.trim();
// var progressEnd = (salesPercentage !== '' && salesPercentage !== null) ? parseInt(salesPercentage) : 0;


// if (progressEnd === 0) {
//     barValue.textContent = '0%';
//     progressbar.style.background = 'conic-gradient(rgba(59, 0, 228, 1), rgba(55, 147, 255, 1) 0deg, rgba(252, 252, 252, 1) 0deg)';
// } else {
//     speed = 100;
//     let progress = setInterval(() => {
//         progressStart++;
//         barValue.textContent = `${progressStart}%`;
//         progressbar.style.background = `conic-gradient(rgba(59, 0, 228, 1), rgba(55, 147, 255, 1) ${progressStart * 3.6}deg, rgba(252, 252, 252, 1) 0deg)`;
//         if (progressStart == progressEnd) {
//             clearInterval(progress);
//         }
//     }, speed);
// }



</script>

<!-- TopSelling Products -->

<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
    const products = [
        <?php
        $i=0;
        $progress_color=['#D278F1','#A4CD3C','#7879F1','#F178C8','#2DD6AD'];
        foreach ($productss as $top_product):
        ?>
        { name: '<?php echo  $top_product['p_title'];?>',variation: '<?php echo  $top_product['p_type'];?>',total:'<?php echo  $top_product['total'];?>', lowStockThreshold: 23, color: '<?php echo $progress_color[$i]; ?>' },
        <?php 
            $i++;
            endforeach 
        ?>
    ];
    console.log(products)

    const svg = document.querySelector('.circular-progress-bar svg');
    const legend = document.getElementById('legend');
<?php
$stockValues = [];
foreach ($productss as $top_product):
    $stockValues[] = $top_product['total'];
endforeach;
?>
    const stockValues = <?php echo json_encode($stockValues); ?>;
    const totalStock = stockValues.reduce((acc, total) => acc + parseInt(total), 0);
    let offset = 0;

    products.forEach(product => {
        let percentage = (product.total / totalStock) * 100;
        createCircularSegment(svg, offset, percentage, product.color);
        offset += percentage;

        // Create legend item
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';
        legendItem.innerHTML = `
            <div class="legend-color" style="background-color: ${product.color};"></div>
            ${product.name} (${product.variation})- ${product.total}
        `;
        legend.appendChild(legendItem);
    });

    function createCircularSegment(svg, offset, percentage, color) {
        const r = 85;
        const circumference = 2 * Math.PI * r;
        const offsetValue = (offset / 100) * circumference;
        const strokeDashArray = `${(percentage / 100) * circumference} ${circumference}`;

        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('cx', 100);
        circle.setAttribute('cy', 100);
        circle.setAttribute('r', r);
        circle.setAttribute('class', 'circle');
        circle.setAttribute('stroke', color);
        circle.setAttribute('stroke-dasharray', strokeDashArray);
        circle.setAttribute('stroke-dashoffset', -offsetValue);
        svg.appendChild(circle);
    }

if (stockValues=='') {
    document.getElementById("no_top_selling").innerHTML="No Top Selling Products Found...";
}
});
</script> -->


<!--Stock  Progressbar -->
<!-- <script>
    document.addEventListener('DOMContentLoaded', () => {
    const products = [
        <?php
        $i=0;
        $progress_color=['#D278F1','#A4CD3C','#7879F1','#F178C8','#2DD6AD'];
        foreach ($low_stock_prod as $low_prod):
        ?>
        { name: '<?php echo  $low_prod['p_title'];?>', stock: '<?php echo  $low_prod['qty_left'];?>',variation: '<?php echo  $low_prod['p_variation'];?>',unit: '<?php echo  $low_prod['unit'];?>', lowStockThreshold: 23, color: '<?php echo $progress_color[$i]; ?>' },
        <?php 
            $i++;
            endforeach 
        ?>
    ];

    const svg = document.querySelector('.circular-progress-bar svg');
    const legend = document.getElementById('legend');
<?php
$stockValues = [];
foreach ($low_stock_prod as $low_prod):
    $stockValues[] = $low_prod['qty_left'];
endforeach;
?>
    const stockValues = <?php echo json_encode($stockValues); ?>;
    const totalStock = stockValues.reduce((acc, stock) => acc + parseInt(stock), 0);
    let offset = 0;

    products.forEach(product => {
        let percentage = (product.stock / totalStock) * 100;
        createCircularSegment(svg, offset, percentage, product.color);
        offset += percentage;

        // Create legend item
        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';
        legendItem.innerHTML = `
            <div class="legend-color" style="background-color: ${product.color};"></div>
            ${product.name} (${product.variation}${product.unit})- ${product.stock}
        `;
        legend.appendChild(legendItem);
    });

    function createCircularSegment(svg, offset, percentage, color) {
        const r = 85;
        const circumference = 2 * Math.PI * r;
        const offsetValue = (offset / 100) * circumference;
        const strokeDashArray = `${(percentage / 100) * circumference} ${circumference}`;

        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('cx', 100);
        circle.setAttribute('cy', 100);
        circle.setAttribute('r', r);
        circle.setAttribute('class', 'circle');
        circle.setAttribute('stroke', color);
        circle.setAttribute('stroke-dasharray', strokeDashArray);
        circle.setAttribute('stroke-dashoffset', -offsetValue);
        svg.appendChild(circle);
    }
});
</script> -->

<!--Unsold Progressbar -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
    const unsoldProd = [
        <?php
        $i=0;
        $unsold_progress_color=['#F1EC78','#78EAF1','#00B8D4','#78F1B7','#788BF1'];
        foreach ($unsold_products as $unsold_product):
            ?>
            { name: '<?php echo  $unsold_product['p_title'];?>', stock: '<?php echo  $unsold_product['unsold_qty'];?>',variation: '<?php echo  $unsold_product['p_variation'];?>',unit: '<?php echo  $unsold_product['unit'];?>', lowStockThreshold: 23, color: '<?php echo $unsold_progress_color[$i]; ?>' },
            <?php 
            $i++;
        endforeach; 
        ?>
    ];
    // console.log(unsoldProd);
    const u_svg = document.querySelector('.unsold-progress-bar svg');
    const unsoldLegend = document.getElementById('unsold_prod_legend');
<?php
$unsoldValues = [];
foreach ($unsold_products as $unsold_product):
    $unsoldValues[] = $unsold_product['unsold_qty'];
endforeach;
?>
    let unsoldValues = <?php echo json_encode($unsoldValues); ?>;
    const totalStock = unsoldValues.reduce((acc, stock) => acc + parseInt(stock), 0);
    // console.log(totalStock)
    let offset = 0;

    unsoldProd.forEach(unsold => {
        let percentage = (unsold.stock / totalStock) * 100;
        createCircularSegment(u_svg, offset, percentage, unsold.color);
        offset += percentage;

        const legendItem = document.createElement('div');
        legendItem.className = 'legend-item';
        legendItem.innerHTML = `
            <div class="legend-color" style="background-color: ${unsold.color};"></div>
            ${unsold.name} (${unsold.variation}${unsold.unit})- ${unsold.stock}
        `;
        unsoldLegend.appendChild(legendItem);
    });

    function createCircularSegment(svg, offset, percentage, color) {
        const r = 85;
        const circumference = 2 * Math.PI * r;
        const offsetValue = (offset / 100) * circumference;
        const strokeDashArray = `${(percentage / 100) * circumference} ${circumference}`;

        const circle = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
        circle.setAttribute('cx', 100);
        circle.setAttribute('cy', 100);
        circle.setAttribute('r', r);
        circle.setAttribute('class', 'circle');
        circle.setAttribute('stroke', color);
        circle.setAttribute('stroke-dasharray', strokeDashArray);
        circle.setAttribute('stroke-dashoffset', -offsetValue);
        u_svg.appendChild(circle);
    }

    if (unsoldValues=='') {
    document.getElementById("no_unsold").innerHTML="No Unsold Products Found...";
    }
});
</script>

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

    $formatted_amount = $formatted_whole;

    if ($is_negative) {
        $formatted_amount = '-' . $formatted_amount;
    }

    return $formatted_amount;
}
?>
</body>
</html>

