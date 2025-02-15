<?php
ob_start(); 
require 'config.php';
            
$today = new DateTime();
$firstDayOfWeek = clone $today;
$currentDayOfWeek = $today->format('w');
$daysToSubtract = $currentDayOfWeek;
$firstDayOfWeek->modify("-$daysToSubtract days");

//date Ranges
$startDate1 = $_GET['startDate1'] ?? $_POST['startDate1'] ?? $today->format('Y-m-d');
$endDate1 = $_GET['endDate1'] ?? $_POST['endDate1'] ?? date('Y-m-d');

$startDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $startDate1)));
$endDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $endDate1)));

//dashboard DateRange
$startDate1 = $_GET['startDate'] ?? $_POST['startDate'] ?? $today->format('Y-m-d');
$endDate1 = $_GET['endDate'] ?? $_POST['endDate'] ?? date('Y-m-d');

$startDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $startDate1)));
$endDate1 = date('Y-m-d', strtotime(str_replace('/', '-', $endDate1)));

//city array
$cities = [
    "Arakkonam", "Ariyalur", "Chennai", "Chidambaram", "Coimbatore", 
    "Cuddalore", "Dharmapuri", "Dindigul", "Erode", "Gudiyatham", 
    "Hosapete", "Kanchipuram", "Karaikkudi", "Karur", "Krishnagiri", 
    "Kumbakonam", "Madurai", "Mayiladuthurai", "Nagapattinam", "Nagercoil", 
    "Namakkal", "Neyveli", "Pallavaram", "Perambalur", "Pollachi", 
    "Pudukkottai", "Rajapalayam", "Ranipet", "Salem", "Sivaganga", 
    "Sivakasi", "Tenkasi", "Thanjavur", "Theni", "Thoothukudi", 
    "Tiruchirappalli", "Tirunelveli", "Tiruppur", "Tiruvannamalai", 
    "Udhagamandalam", "Vellore", "Viluppuram", "Virudhunagar"
];

//dashboard page
$productss = [];
$total_products=$mysqli->query("SELECT `id` FROM `e_product_details` WHERE active != '2' AND (type != '2' OR type IS NULL) AND cos_id = '$cos_id'")->num_rows;

$total_sales=$mysqli->query("SELECT `id` FROM `e_normal_order_details` WHERE cos_id = '$cos_id'  ORDER BY id DESC")->num_rows;
$instore_orders=$mysqli->query("SELECT id FROM `e_normal_order_details` WHERE invoice_no!=0  AND  cos_id = '$cos_id' ORDER BY id DESC")->num_rows;
$online_orders=$mysqli->query("SELECT id FROM `e_normal_order_details` WHERE bill_type='1' AND cos_id = '$cos_id' ORDER BY id DESC")->num_rows;

$total_customers=$mysqli->query("SELECT `id` FROM `e_user_details` WHERE  active=1 AND cos_id='$cos_id'")->num_rows;

$sales=$mysqli->query("SELECT SUM(o_total) AS order_total FROM e_normal_order_details WHERE active=6 AND  cos_id = '$cos_id'")->fetch_assoc();
$online_sales=$mysqli->query("SELECT SUM(o_total) AS order_total FROM `e_normal_order_details` WHERE  active=6 AND bill_type=1  AND cos_id = '$cos_id'")->fetch_assoc();
$instore_sales=$mysqli->query("SELECT SUM(o_total) AS order_total FROM `e_normal_order_details` WHERE  invoice_no!=0 AND bill_type=2 AND  cos_id = '$cos_id'")->fetch_assoc();

//Unsold Products

$unsold_products_query = $mysqli->query("SELECT 
    pd.p_title,
    pp.created_ts,
    pd.p_variation,
    pd.unit,
    ps.qty AS stock_qty,
    pp.qty_left AS unsold_qty,
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
    aging_days DESC
LIMIT 5");

$unsold_products = [];
if (!$unsold_products_query) {
    // echo "Query Error: " . $mysqli->error;
} else{
    if ($unsold_products_query && $unsold_products_query->num_rows > 0) {
        
        while ($row = $unsold_products_query->fetch_assoc()) {
    
            if ($row !== null) {
                $unsold_products[] = $row;
            }
        }
    } else {
        // echo "No results found.";
    }
}

//Unsold Products-All
$unsold_query=$mysqli->query("SELECT 
    pd.p_title,
    pd.sku_id,
    pd.p_img,
    pp.created_ts,
    pd.p_variation,
    pd.unit,
    ps.qty AS stock_qty,
    pp.qty_left AS unsold_qty,
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

     
$unsold= [];
while ($unsold_table = $unsold_query->fetch_assoc()){
    $unsold[] = $unsold_table;
}


//low Stock Products

$low_stock_products=$mysqli->query("SELECT 
    p.p_title,
    p.p_variation,
    p.unit, 
    q.qty_left,
    p.emergency_level 
FROM 
    e_product_details AS p
JOIN 
    e_product_price AS q ON p.id = q.product_id 
    AND p.active = 1 AND q.cos_id = '$cos_id'
WHERE  
    q.qty_left < p.emergency_level 
    AND q.qty_left > 0 
    AND p.active = 1
    AND q.cos_id = '$cos_id' 
ORDER BY 
    q.qty_left ASC 
LIMIT 5");

$low_stock_prod = [];
while ($low_qty = $low_stock_products->fetch_assoc()) {
    $low_stock_prod[] = $low_qty;
}

//Out Of Stock Products

$out_stock_products=$mysqli->query("SELECT 
    pd.id, 
    pd.p_title, 
    pd.p_variation, 
    pd.unit, 
    pd.sku_id, 
    pd.reorder_level, 
    pd.emergency_level, 
    ps.s_product_id, 
    ps.s_batch_no, 
    ps.s_mrp, 
    ps.in_price, 
    ps.s_out_price, 
    ps.s_expiry_date, 
    ps.stock_bill, 
    ps.qty, 
    pp.qty_left,
    ps.updated_ts, 
    ps.created_ts
FROM e_product_details pd 
LEFT JOIN e_product_stock ps 
    ON ps.s_product_id = pd.id 
    AND ps.cos_id = pd.cos_id 
    AND (ps.active IS NULL OR ps.active != 1)
LEFT JOIN e_product_price pp 
    ON pp.product_id = ps.s_product_id  
    AND pp.cos_id = ps.cos_id
WHERE pd.cos_id = '$cos_id' 
AND (pp.qty_left = 0 OR pp.qty_left IS NULL)
ORDER BY ps.s_id DESC 
LIMIT 5");

$out_stock_prod = [];
while ($out_qty = $out_stock_products->fetch_assoc()) {
    $out_stock_prod[] = $out_qty;
}

//Revenue Chart (Monthly)

$sales_monthwise=$mysqli->query("SELECT 
    months.month AS month,
    COALESCE(ROUND(SUM(e.o_total)), 0) AS total_sales
FROM 
    (
        SELECT 1 AS month
        UNION SELECT 2
        UNION SELECT 3
        UNION SELECT 4
        UNION SELECT 5
        UNION SELECT 6
        UNION SELECT 7
        UNION SELECT 8
        UNION SELECT 9
        UNION SELECT 10
        UNION SELECT 11
        UNION SELECT 12
    ) AS months
LEFT JOIN 
    `e_normal_order_details` AS e 
    ON EXTRACT(MONTH FROM e.created_ts) = months.month 
    AND e.cos_id = '$cos_id' WHERE  e.active = 1
    AND e.cos_id = '$cos_id' 
GROUP BY 
    months.month
ORDER BY 
    month");
$sales_month = [];
$total_sales_per_month=[];
while ($sales_month_details = $sales_monthwise->fetch_assoc()) {
    $sales_month[] = $sales_month_details;
    $total_sales_per_month[]=$sales_month_details['total_sales'];
}

//Highly Purchased Customer

$highly_purchased_customer=$mysqli->query("SELECT 
    od.u_id,
    od.name,
    COUNT(od.id) AS total_orders,
    SUM(od.o_total) AS purchased_price,
    SUM(op.p_quantity) AS purchased_quantity,
    MAX(od.o_date) AS latest_odate
FROM  
    `e_normal_order_details` od
JOIN 
    `e_normal_order_product_details` op 
    ON od.id = op.o_id 
    AND od.active = 1 
    AND op.cos_id = '$cos_id'
WHERE  
    od.name != '' 
    AND od.updated_ts IS NOT NULL 
    AND od.active = 1
    AND od.cos_id = '$cos_id'
GROUP BY 
    od.name
ORDER BY 
    purchased_price DESC
LIMIT 5");

$high_purchased_cust = [];
while ($highly_purchased_customer_details = $highly_purchased_customer->fetch_assoc()) {
    $high_purchased_cust[] = $highly_purchased_customer_details;
}

//recent Orders

$recent_saled_product=$mysqli->query("SELECT id,invoice_no,created_ts,bill_type,name,mobile,o_total,DATE_FORMAT(created_ts, '%d %m %Y') AS formatted_date
FROM `e_normal_order_details` WHERE DATE(created_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' ORDER BY id DESC LIMIT 5");

$recent_sales = [];
while ($recent_sales_product_details = $recent_saled_product->fetch_assoc()) {
    $recent_sales[] = $recent_sales_product_details;
}


//Product page
$product_query=$mysqli->query("SELECT `id`, `sku_id`, `hsn_code`, `barcode`, `cat_id`, `sub_cat_id`, `p_img`, `p_title`, `brand`, `p_variation`, 
`cgst`, `sgst`, `igst`, `p_disc`, `unit`, `type`, `p_desc`, `reorder_level`, `emergency_level`, `location`, `godown_location`, 
`created_ts`, `updated_ts`, `active` FROM `e_product_details` WHERE active != '2' AND (type != '2' OR type IS NULL) AND cos_id = '$cos_id' ORDER BY id DESC");

$products_details= [];
while ($product_table = $product_query->fetch_assoc()){
    $products_details[] = $product_table;
}

//Combo Page
$combo_query=$mysqli->query("SELECT `id`, `sku_id`, `p_id`, `offer_amt`, `title`, `c_img`, `from_qty`, `to_qty`, `bulk_price`,`active`
 FROM `e_data_collection` WHERE  active!='2' AND cos_id='$cos_id' ORDER BY id DESC");
$combo_details=[];

while ($combo_table = $combo_query->fetch_assoc()){
    $combo_details[] = $combo_table;
}

//category
$category_query=$mysqli->query("SELECT id,title,c_img,active FROM `e_category_details` WHERE active!='2' AND cos_id = '$cos_id' AND cos_id = '$cos_id' ORDER BY id DESC");

$categories=[];

while ($category_table = $category_query->fetch_assoc()){
    $categories[] = $category_table;
}
//sub category
$subcategory_query=$mysqli->query("SELECT id,c_id,title,c_img,active FROM `e_subcategory_details` WHERE active!='2' AND cos_id = '$cos_id' ORDER BY id DESC");

$subcategories=[];

while ($subcategory_table = $subcategory_query->fetch_assoc()){
    $subcategories[] = $subcategory_table;
}

//Feedback Screen

$feedback_users = [];


if (isset($_SESSION['auth_id'])) {
    $auth_id = $_SESSION['auth_id'];

    $feedback_query=$mysqli->query("SELECT f.sender_id,f.receiver_id,u.id,u.name,u.mobile,u.email_id 
    FROM `e_user_details` u,`e_feedback` f WHERE  f.sender_id !='$auth_id' AND  u.id=f.sender_id AND 
    u.cos_id='$cos_id' AND u.active =1 GROUP BY f.sender_id");

    while ($feedback_table = $feedback_query->fetch_assoc()) {
        $feedback_users[] = $feedback_table;
    }
}

//Inventory

// All Stock

$product_stock=$mysqli->query("SELECT ps.s_id,ps.purchase_id,ps.s_product_id,ps.s_batch_no,pd.p_title,
pd.p_variation,pd.unit,ps.s_mrp,ps.in_price,ps.s_out_price,ps.s_expiry_date,
ps.stock_bill,ps.qty,pp.qty_left,ps.updated_ts,ps.created_ts FROM e_product_details pd
JOIN e_product_stock ps ON pd.id = ps.s_product_id AND ps.cos_id = '$cos_id'
JOIN e_product_price pp ON pp.product_id = ps.s_product_id AND pp.batch_no = ps.s_batch_no 
AND pp.cos_id = '$cos_id' AND pp.qty_left > 0
WHERE pd.active = 1 AND pd.cos_id = '$cos_id' ORDER BY ps.s_id DESC");

$stock_details=[];
while($product_stock_details=$product_stock->fetch_assoc()){
    $stock_details[]=$product_stock_details;
}

//low stock
$low_stock_alert=$mysqli->query("SELECT p.p_id,pd.sku_id,p.batch_no,p.product_id,pd.p_title,pd.p_variation,pd.unit,
p.mrp,p.out_price,p.qty_left,pd.reorder_level,pd.emergency_level,p.updated_ts,p.created_ts
FROM e_product_price p JOIN e_product_details pd ON p.product_id = pd.id AND p.cos_id = '$cos_id' 
AND pd.cos_id = '$cos_id' AND p.active = 1 WHERE p.qty_left <= pd.emergency_level AND p.qty_left > 0 
AND pd.active = 1 AND pd.cos_id = '$cos_id' ORDER BY p.p_id DESC");

$lowStockAlert=[];
while($low_stock_products=$low_stock_alert->fetch_assoc()){
    $lowStockAlert[]= $low_stock_products;
}

//Reorder Level Page

$reorder_level_query=$mysqli->query("SELECT pd.sku_id,p.batch_no,p.product_id,pd.p_title,pd.p_variation,
pd.unit,p.mrp,p.out_price,p.qty_left,pd.reorder_level,pd.emergency_level,p.updated_ts,p.created_ts
FROM e_product_price p JOIN e_product_details pd ON p.product_id = pd.id AND p.cos_id = '$cos_id' AND pd.cos_id = '$cos_id' 
AND p.active = 1 AND pd.active = 1 WHERE p.qty_left <= pd.reorder_level AND p.qty_left > pd.emergency_level ORDER BY p.p_id DESC");
$reorderLevel=[];
while($reorder_products=$reorder_level_query->fetch_assoc()){
    $reorderLevel[]=$reorder_products;
}

//Missing Stock

$missing_stock_query=$mysqli->query("SELECT pd.sku_id,p.batch_no,p.product_id,pd.p_title,pd.p_variation,
pd.unit,p.mrp,p.out_price,p.qty_left,pd.reorder_level,pd.emergency_level,p.updated_ts,p.created_ts,missing_qty
FROM e_product_price p JOIN e_product_details pd ON p.product_id = pd.id AND p.cos_id = '$cos_id' 
AND pd.cos_id = '$cos_id' AND p.active = 1 AND pd.active = 1 WHERE missing_qty > 0 ORDER BY p.p_id DESC");
$missingQty=[];
while($missing_qty=$missing_stock_query->fetch_assoc()){
    $missingQty[]=$missing_qty;
}

//Out of Stock

$out_stock_query=$mysqli->query("SELECT 
    pd.id, 
    pd.p_title, 
    pd.p_variation, 
    pd.unit, 
    pd.sku_id, 
    pd.reorder_level, 
    pd.emergency_level, 
    ps.s_product_id, 
    ps.s_batch_no, 
    ps.s_mrp, 
    ps.in_price, 
    ps.s_out_price, 
    ps.s_expiry_date, 
    ps.stock_bill, 
    ps.qty, 
    pp.qty_left,
    ps.updated_ts, 
    ps.created_ts
FROM e_product_details pd 
LEFT JOIN e_product_stock ps 
    ON ps.s_product_id = pd.id 
    AND ps.cos_id = pd.cos_id 
    AND (ps.active IS NULL OR ps.active != 1)
LEFT JOIN e_product_price pp 
    ON pp.product_id = ps.s_product_id  
    AND pp.cos_id = ps.cos_id
WHERE pd.cos_id = '$cos_id' 
AND (pp.qty_left = 0 OR pp.qty_left IS NULL)
ORDER BY ps.s_id DESC");
$outOfStock=[];
while($out_of_stock_products=$out_stock_query->fetch_assoc()){
    $outOfStock[]=$out_of_stock_products;
}

// purchase Entry
$purchase_entry_query=$mysqli->query("SELECT  `id`, `cos_id`, `invoice_no`, `supplier_id`, `stock_bill`,`created_ts`, `updated_ts`,`active`  FROM `e_purchase_entry` WHERE active=1 AND cos_id='$cos_id' ORDER BY id DESC");
$purchaseEntry=[];
while($purchase_entry_data=$purchase_entry_query->fetch_assoc()){
    $purchaseEntry[]=$purchase_entry_data;
}


//Banner page

$banner_query=$mysqli->query("SELECT id,bg_img,c_id,active from e_dat_banner WHERE active!=2 AND cos_id = '$cos_id' ORDER BY id DESC");
$banners_details= [];
while ($banner_table = $banner_query->fetch_assoc()){
    $banners_details[] = $banner_table;
}

//TimeSlot Page

$timeslot_query=$mysqli->query("SELECT id,max_time,min_time,slot_limit,active from e_dat_timeslot WHERE active!=2 AND cos_id = '$cos_id' ORDER BY id DESC");
$timeslot_details= [];
while ($timeslot_table = $timeslot_query->fetch_assoc()){
    $timeslot_details[] = $timeslot_table;
}

//Delivery Fee

$deliveryFee_query=$mysqli->query("SELECT id,title,c_img,d_charge,min_amt,disc_alert_msg from e_data_city WHERE active=1 AND cos_id = '$cos_id' ORDER BY id DESC");
$deliveryFee_details= [];
while ($deliveryFee_table = $deliveryFee_query->fetch_assoc()){
    $deliveryFee_details[] = $deliveryFee_table;
}

//employee Page

$employee_query=$mysqli->query("SELECT id,s_name,email,s_mobile,whatsapp,s_address,role,other_roles,password,
joining_date,salary,bonus FROM `e_salesman_details`
WHERE active=1 AND cos_id = '$cos_id' ORDER BY id DESC");
$employee_details=[];
while ($employee_table = $employee_query->fetch_assoc()) {
    $employee_details[] = $employee_table;
}

//Roles
$role_query=$mysqli->query("SELECT id,role_title,role_desc,active FROM `e_salesman_role`
WHERE active!=2 AND cos_id = '$cos_id' ORDER BY id DESC");
$role_details=[];
while ($role_table = $role_query->fetch_assoc()) {
    $role_details[] = $role_table;
}

//Delivery Person
if(isset($cos_id)){
    $delivery_person= $mysqli->query("SELECT id FROM `e_salesman_role` WHERE role_title like '%delivery%' 
    AND active != 2 AND cos_id='$cos_id'")->fetch_assoc();
    // print_r($delivery_person);
    // echo "SELECT id FROM `e_salesman_role` WHERE role_title like '%delivery%' AND active != 2 AND cos_id='$cos_id'";
    $d_role = $delivery_person['id'] ?? '';
}else{
    $d_role='';
}
$delivery_query=$mysqli->query("SELECT id,s_name,email,s_mobile,whatsapp,s_address,joining_date,salary,bonus,password 
FROM `e_salesman_details` WHERE role='$d_role' AND active=1 AND cos_id = '$cos_id' ORDER BY id DESC");
$delivery_details=[];
while ($delivery_table = $delivery_query->fetch_assoc()) {
    $delivery_details[] = $delivery_table;
}

//customer Page

// $cust_query=$mysqli->query("SELECT u.`id`, u.`name`, u.`mobile`, u.`email_id`, u.`password`, u.`whatsapp`, u.`wallet`,
// a.`id`, a.`user_id`, a.`area`,a.`pincode`, a.`address_line_1`, a.`landmark`,  a.`name`, a.`mobile`, a.`city`, a.`state`,
// a.`address_line_2`, a.`country` FROM e_user_details u JOIN e_address_details a ON u.id = a.user_id 
// AND u.cos_id = a.cos_id WHERE  u.active = 1 AND a.active = 1  AND u.cos_id = '$cos_id'");

// $cust_query=$mysqli->query("SELECT u.id, u.name, u.mobile, u.email_id, u.password, u.whatsapp, u.wallet,
//        a.id AS address_id, a.area, a.pincode, a.address_line_1, a.landmark,  
//        a.name AS address_name, a.mobile AS address_mobile, a.city, a.state, 
//        a.address_line_2, a.country
// FROM e_user_details u
// LEFT JOIN (
//     SELECT ad.*, ROW_NUMBER() OVER (PARTITION BY ad.user_id ORDER BY ad.created_ts DESC) AS rn
//     FROM e_address_details ad
//     WHERE ad.active = 1 AND ad.type IN ('Home', 'Office')
// ) a ON u.id = a.user_id AND u.cos_id = a.cos_id AND a.rn = 1
// WHERE u.active = 1 AND u.cos_id = '$cos_id' ORDER BY u.id DESC");

$cust_query=$mysqli->query("SELECT  `id`, `name`, `mobile`, `email_id`, `password`, `whatsapp`,`wallet` FROM `e_user_details`  WHERE active=1 AND  cos_id = '$cos_id' ORDER BY id DESC");

$customer_details=[];
while ($cust_table=$cust_query->fetch_assoc()){
    $customer_details[]=$cust_table;
}

//UnPurchased Customers
$unpurchase_cust_query=$mysqli->query("SELECT 
   ud.`id`, 
   ud.`name`, 
   ud.`mobile`, 
   ud.`email_id`, 
   ud.`whatsapp`,
   ud.`created_ts`, 
   ad.`id` AS address_id, 
   ad.`user_id`, 
   ad.`area`,
   ad.`pincode`, 
   ad.`address_line_1`, 
   ad.`landmark`,  
   ad.`name` AS address_name, 
   ad.`mobile` AS address_mobile, 
   ad.`city`, 
   ad.`state`, 
   ad.`address_line_2`, 
   ad.`country`
FROM 
   e_user_details ud 
JOIN 
   e_address_details ad 
   ON ud.id = ad.user_id AND ad.cos_id = '$cos_id' 
LEFT JOIN 
   e_normal_order_details od 
   ON ud.id = od.u_id AND od.cos_id = '$cos_id' 
WHERE 
   ud.active = 1 
   AND ud.cos_id = '$cos_id' 
   AND od.u_id IS NULL 
ORDER BY 
   ud.created_ts ASC");

$unpurchase_cust_details=[];
while ($unpurchase_cust_table=$unpurchase_cust_query->fetch_assoc()){
    $unpurchase_cust_details[]=$unpurchase_cust_table;
}

//coupon Page

$coupon_query=$mysqli->query("SELECT id,c_title,c_img,c_date,min_amt,c_value,c_desc,active FROM `e_data_coupon` 
WHERE  active!=2 AND cos_id = '$cos_id' ORDER BY id DESC");
$coupon_details=[];
while ($coupon_table = $coupon_query->fetch_assoc()) {
    $coupon_details[] = $coupon_table;
}

//expense Page

$expense_query=$mysqli->query("SELECT exp_id,exp_date,exp_img,exp_desc,exp_amount FROM `e_expense_details` 
WHERE  active=1 AND cos_id = '$cos_id' ORDER BY exp_id DESC");
$expense_details=[];
while ($expense_table = $expense_query->fetch_assoc()) {
    $expense_details[] = $expense_table;
}

//vendors Page

$vendor_query=$mysqli->query("SELECT v_id,v_name,business_name,contact_person,v_mobile,v_whatsapp,gst_no,
v_address FROM `e_vendor_details` WHERE  active=1 AND cos_id = '$cos_id' ORDER BY v_id DESC");
$vendor_details=[];
while ($vendor_table = $vendor_query->fetch_assoc()) {
    $vendor_details[] = $vendor_table;
}

//bank Page

$bank_query=$mysqli->query("SELECT id,bank_name,account_holder,account_no,ifsc_code,upi_id,app_status 
FROM `e_bank_details` WHERE  active=1  AND cos_id = '$cos_id' ORDER BY id DESC");
$bank_details=[];
while ($bank_table = $bank_query->fetch_assoc()) {
    $bank_details[] = $bank_table;
}

//wallet Page

$wallet_query=$mysqli->query("SELECT id,u_id,amt,amt_type,balance,status FROM `e_wallet_report_details` 
WHERE  active=1 AND amt!=0 AND cos_id = '$cos_id' ORDER BY id DESC");
$wallet_details=[];
while ($wallet_table = $wallet_query->fetch_assoc()) {
    $wallet_details[] = $wallet_table;
}

//Profile Page
$profile_query=$mysqli->query("SELECT id,logo_img,business_name,mobile_1,mobile_2,email_id,gst_no,
address,active FROM `e_data_profile` WHERE  active!=2 AND cos_id = '$cos_id' ORDER BY id DESC");

$profile_details=[];
while ($profile_table = $profile_query->fetch_assoc()) {
    $profile_details[] = $profile_table;
}

//Product Review Page
$product_review_query=$mysqli->query("SELECT `id`, `cos_id`, `product_id`, `user_id`, `rating`, `comment`, `added_on`, `updated_on`, `active` 
FROM `e_products_rating` WHERE cos_id='$cos_id' AND active=1 GROUP BY product_id ORDER BY id DESC");

$review_details=[];
while ($product_reviews = $product_review_query->fetch_assoc()) {
    $review_details[] = $product_reviews;
}


//Orders Page

//real Order

// $real_query = $mysqli->query("SELECT *, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date  FROM `e_normal_order_details` where DATE(updated_ts) BETWEEN  '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' and active='6' and bill_type='2' order by id desc");
$real_query = $mysqli->query("SELECT id,invoice_no,created_ts,name,o_total,created_by FROM `e_normal_order_details` 
WHERE  invoice_no!=0 AND cos_id = '$cos_id'ORDER BY id DESC");
$real_orders = [];
                                            
while($row = $real_query->fetch_assoc())
{
    $real_orders[] = $row;
}


//Online Orders
//completed
$completed_query = $mysqli->query("SELECT id,created_ts,name,o_total,p_method_id,trans_id,upi_id,t_slot,updated_by,salesman_id,status,updated_ts 
FROM `e_normal_order_details` WHERE  active='6' AND bill_type='1' AND  cos_id = '$cos_id' order by id desc"); 
//processing
$processing_query = $mysqli->query("SELECT  id,created_ts,name,o_total,p_method_id,trans_id,upi_id,t_slot,updated_by,salesman_id,status,updated_ts 
FROM `e_normal_order_details` WHERE  active='2' AND bill_type='1' AND  cos_id = '$cos_id' order by id desc");
//packed
$packed_query = $mysqli->query("SELECT id,created_ts,name,o_total,p_method_id,trans_id,upi_id,t_slot,updated_by,salesman_id,status,updated_ts 
FROM `e_normal_order_details` WHERE  active='3'  AND  bill_type='1' AND cos_id = '$cos_id' order by id desc");
//db_assigned
$db_assigned_query = $mysqli->query("SELECT id,created_ts,name,o_total,p_method_id,trans_id,upi_id,t_slot,updated_by,salesman_id,status,updated_ts 
FROM `e_normal_order_details` WHERE active='4' AND bill_type='1' AND cos_id = '$cos_id' order by id desc");
//out_delivery
$out_delivery_query = $mysqli->query("SELECT id,created_ts,name,o_total,p_method_id,trans_id,upi_id,t_slot,updated_by,salesman_id,status,updated_ts 
FROM `e_normal_order_details` WHERE  active='5'  AND bill_type='1' AND cos_id = '$cos_id' order by id desc");
//pending
$pending_query = $mysqli->query("SELECT id,created_ts,name,o_total,p_method_id,trans_id,upi_id,t_slot,updated_by,salesman_id,status,updated_ts 
FROM `e_normal_order_details` WHERE active='1' AND status='Order Placed'  AND bill_type='1' AND  cos_id = '$cos_id' order by id desc");
//All Orders
$all_orders_query = $mysqli->query("SELECT id,created_ts,name,o_total,p_method_id,trans_id,upi_id,t_slot,updated_by,salesman_id,status,updated_ts 
FROM `e_normal_order_details` WHERE   bill_type='1' AND cos_id = '$cos_id' order by id desc");

//Revenue Page

//amount display
$revenue_cash=$mysqli->query("SELECT sum(o_total) AS order_total, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date 
FROM e_normal_order_details WHERE DATE(updated_ts) BETWEEN  '$startDate1' AND '$endDate1' AND 
p_method_id=2 AND active=6 AND cos_id = '$cos_id'")->fetch_assoc();
$revenue_upi=$mysqli->query("SELECT sum(o_total) AS order_total, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date
FROM e_normal_order_details WHERE DATE(updated_ts) BETWEEN  '$startDate1' AND '$endDate1'  
AND p_method_id=1 AND active=6 AND cos_id = '$cos_id'")->fetch_assoc();

//revenue cash
$cash_query = $mysqli->query("SELECT id,invoice_no,created_ts,name,o_total,p_method_id,updated_ts,bill_type, 
DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` 
WHERE DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' AND p_method_id=2 
AND active=6  AND cos_id='$cos_id' ORDER BY id DESC");

$cash = [];                                          
while($row = $cash_query->fetch_assoc())
{
    $cash[] = $row;
}

//revenue_upi
$upi_query = $mysqli->query("SELECT id,created_ts,name,o_total,p_method_id,status,updated_ts,trans_id,upi_id,recon_status,bill_type,
DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` WHERE DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' 
AND p_method_id=1  AND active=6 AND cos_id='$cos_id' ORDER BY id DESC");

$upi = [];                                          
while($row = $upi_query->fetch_assoc())
{
    $upi[] = $row;
}

//Orders Page

// //completed
// $completed_query = $mysqli->query("SELECT *, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` 
// where DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' and active='6'  and bill_type='1' order by id desc"); 
// //processing
// $processing_query = $mysqli->query("SELECT *, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` 
// where DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' and active='2'  and bill_type='1' order by id desc");
// //packed
//     $packed_query = $mysqli->query("SELECT *, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` 
// where DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' and active='3'  and bill_type='1' order by id desc");
// //db_assigned
// $db_assigned_query = $mysqli->query("SELECT *, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` 
// where DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' and active='4'  and bill_type='1' order by id desc");
// //out_delivery
// $out_delivery_query = $mysqli->query("SELECT *, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` 
// where DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' and active='5'  and bill_type='1' order by id desc");
// //pending
// $pending_query = $mysqli->query("SELECT *, DATE_FORMAT(updated_ts, '%d %m %Y') AS formatted_date FROM `e_normal_order_details` 
// where DATE(updated_ts) BETWEEN '$startDate1' AND '$endDate1' AND cos_id = '$cos_id' and active='1' and status='Order Placed'  
// and bill_type='1' order by id desc");

ob_end_flush();
?>
