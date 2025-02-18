<?php
include '../api/config.php';
header('Content-type: text/json');
// ini_set('display_errors', 1); 
// ini_set('display_startup_errors', 1); 
// error_reporting(E_ALL); 
session_start();
$platform = "3";
$cos_id= $_SESSION['cos_id'];
$created_by =$_SESSION['username'];
$updated_by =$_SESSION['username'];

if (!$mysqli -> commit()) {
    echo "Commit transaction failed";
    exit();
}
  
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    //banner Insert
    if (isset($_POST["banner_add"])) {
        if (isset($_FILES['b_img'])) {
            $b_status = trim($_POST['b_status']) == '' || NULL ? 1 : trim($_POST['b_status']);
            // $b_category_id = $_POST['b_category'];
            $b_category_id=trim($_POST['category-id']);
            if(empty($_FILES["b_img"]["name"])){
                $targetFile = "defaultimgs/nullimg.png";
            }
            else{
            $targetDir = "../../banner/";
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $b_category_id);
            $imageFileType = strtolower(pathinfo($_FILES["b_img"]["name"], PATHINFO_EXTENSION));

            $newFileName = $sanitizedTitle . '_' .'banner'. '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["b_img"]["tmp_name"], $targetFile);

            $targetFile=substr($targetFile,6);
            }
            try{
                $fields="`cos_id`,`bg_img`, `active`, `c_id`,`created_by`,`platform`";
                $values="'$cos_id','$targetFile','$b_status','$b_category_id','$created_by','$platform'";

                $banner_insert="INSERT INTO  `e_dat_banner` ($fields) VALUES ($values)";

                $insert_query=$mysqli->query($banner_insert);
                $_SESSION['success'] = "Banner Details Added Successfully!";
                header("Location: banners.php");
                exit();
            }catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $insert_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                $_SESSION['old_banner'] = $_POST;
                $_SESSION['old_banner']['b_img'] = $targetFile; 
                // echo $targetFile;
                header("Location: addBanner.php");
                header("Location: addBanner.php");
                exit();
            }
        }
        else{
            $b_status = trim($_POST['b_status'])??0;
            $b_category_id = trim($_POST['b_category']);
            try{
                $fields="`cos_id`, `active`, `c_id`,`created_by`, `platform`";
                $values="'$cos_id','$b_status','$b_category_id','$created_by','$platform'";
                $banner_insert="INSERT INTO  `e_dat_banner` ($fields) VALUES ($values)";
                $insert_query=$mysqli->query($banner_insert);
                $_SESSION['success'] = "Banner Details Added Successfully!";
                header("Location: banners.php");
                exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $insert_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                $_SESSION['old_banner'] = $_POST;
                $_SESSION['old_banner']['b_img'] = $targetFile; 
                // echo $targetFile;
                header("Location: addBanner.php");
                header("Location: addBanner.php");
                exit();
            }
            
        }
    }
    //banner update
    elseif(isset($_POST['banner_update'])){
        if (isset($_FILES['b_img'])) {
            $banner_id=trim($_POST['bannerid']);
            $b_status = trim($_POST['b_status']) == '' || NULL ? 1 : trim($_POST['b_status']);
            // $b_category_id = $_POST['b_category'];
            $b_category_id=trim($_POST['category-id']);
            if(empty($_FILES["b_img"]["name"])){
                //$targetFile = "defaultimgs/nullimg.png";
                try{
                    $banner_update="UPDATE `e_dat_banner` SET  `active`='$b_status',`updated_by`='$updated_by' ,`up_platform`='$platform' ,`c_id`='$b_category_id' WHERE `cos_id`= '$cos_id' AND `id`='$banner_id'";
                    $update_query=$mysqli->query($banner_update);
                    $_SESSION['success'] = "Banner Updated Successfully!";
                    header("Location: banners.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $err_msg = $exception->getMessage();
                    $update_query = false;
                    $_SESSION['error_message'] = $err_msg;
                    header("Location: addBanner.php?bannerid=$banner_id");
                    exit();
                }
            }
            else{
                $targetDir = "../../banner/";
     
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $b_category_id);
            $imageFileType = strtolower(pathinfo($_FILES["b_img"]["name"], PATHINFO_EXTENSION));

            $newFileName = $sanitizedTitle . '_' .'banner'. '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["b_img"]["tmp_name"], $targetFile);

            // $targetFile=substr($targetFile,6);
                if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                    $targetFile=substr($targetFile,6);
        
                    try{
                        $banner_update="UPDATE `e_dat_banner` SET `bg_img`='$targetFile', `active`='$b_status',`up_platform`='$platform',`updated_by`='$updated_by',`c_id`='$b_category_id' WHERE `cos_id`= '$cos_id' AND `id`='$banner_id'";
                        $update_query=$mysqli->query($banner_update);
                        $_SESSION['success'] = "Banner Details Updated Successfully!";


                        header("Location: banners.php");
                        exit();
                    }
                    catch (mysqli_sql_exception $exception) {
                        mysqli_rollback($mysqli);
                        $err_msg = $exception->getMessage();
                        $update_query = false;
                        $_SESSION['error_message'] = $err_msg;
                        header("Location: addBanner.php?bannerid=$banner_id");
                        exit();
                    }
                }
           
            }
        }
    }
    //coupon Insert
    elseif (isset($_POST["coupon_add"])) {
      if (isset($_FILES['coup_img'])) {
        $coup_code = $mysqli->real_escape_string(trim($_POST['coup_code']));
		$coup_exp_date = trim($_POST['coup_exp_date']);
		$coup_min_amount = trim($_POST['coup_min_amount']);
		$coup_title = $mysqli->real_escape_string(trim($_POST['coup_title']));
		$coup_status = trim($_POST['coup_status'])== '' || NULL ? 1 : trim($_POST['coup_status']);
		$coup_value = trim($_POST['coup_value']);
		$coup_desc = $mysqli->real_escape_string(trim($_POST['coup_desc']));
        if(empty($_FILES["coup_img"]["name"])){
            $targetFile = "defaultimgs/nullimg.png";
        }
        else{
            $targetDir = "../../coupon/";
     
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $coup_title);
            $imageFileType = strtolower(pathinfo($_FILES["coup_img"]["name"], PATHINFO_EXTENSION));

            $newFileName = $sanitizedTitle . '_' .'coupon'. '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["coup_img"]["tmp_name"], $targetFile);

            $targetFile=substr($targetFile,6);
        }
          try{
              $fields="`cos_id`,`c_img`,`c_desc`,`c_value`,`c_code`,`active`,`c_date`,`c_title`,`min_amt`,`created_by`, `platform`";
              $values="'$cos_id','$targetFile','$coup_desc','$coup_value','$coup_code','$coup_status','$coup_exp_date','$coup_title','$coup_min_amount','$created_by','$platform'";             
              $coup_insert="INSERT INTO  `e_data_coupon` ($fields) VALUES ($values)";
              $insert_query=$mysqli->query($coup_insert);
              $_SESSION['success'] = "Coupon Details Added Successfully!";
              header("Location: coupons.php");
              exit();
          }
          catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $insert_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            $_SESSION['old_coupon'] = $_POST;
            $_SESSION['old_coupon']['coup_img'] = $targetFile; 
            header("Location: addCoupon.php");
            exit();
        }
        
      }
    }
    //coupon Update
    elseif(isset($_POST['coupon_update'])){
      if(isset($_FILES["coup_img"])){
        $coupon_id=trim($_POST['couponid']);
        $coup_code = $mysqli->real_escape_string(trim($_POST['coup_code']));
		$coup_exp_date = trim($_POST['coup_exp_date']);
		$coup_min_amount = trim($_POST['coup_min_amount']);
		$coup_title = $mysqli->real_escape_string(trim($_POST['coup_title']));
		$coup_status = trim($_POST['coup_status'])== '' || NULL ? 1 : trim($_POST['coup_status']);
		$coup_value = trim($_POST['coup_value']);
		$coup_desc = $mysqli->real_escape_string(trim($_POST['coup_desc']));
        if(empty($_FILES["coup_img"]["name"])){
            // $targetFile = "defaultimgs/nullimg.png";
            try{
                $coupon_update="UPDATE `e_data_coupon` SET  `c_desc`='$coup_desc', `c_value`='$coup_value' ,`c_code`='$coup_code',`active`='$coup_status', `c_date`='$coup_exp_date', `c_title`='$coup_title' ,`min_amt`='$coup_min_amount', `updated_by`='$updated_by' ,`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$coupon_id'";
                $update_query=$mysqli->query($coupon_update);
                $_SESSION['success'] = "Coupon Details Updated Successfully!";
                header("Location: coupons.php");
                exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $update_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                header("Location: addCoupon.php?couponid=$coupon_id");
                exit();
            }
            
        }
        else{
            $targetDir = "../../coupon/";
     
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $coup_title);
            $imageFileType = strtolower(pathinfo($_FILES["coup_img"]["name"], PATHINFO_EXTENSION));

            $newFileName = $sanitizedTitle . '_' .'coupon'. '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["coup_img"]["tmp_name"], $targetFile);

            $targetFile=substr($targetFile,6);
           
            if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                try{
                    $coupon_update="UPDATE `e_data_coupon` SET `c_img`='$targetFile', `c_desc`='$coup_desc',
                     `c_value`='$coup_value' ,`c_code`='$coup_code',`active`='$coup_status', `c_date`='$coup_exp_date',
                     `c_title`='$coup_title' ,`min_amt`='$coup_min_amount',`updated_by`='$updated_by' ,`up_platform`='$platform'  
                      WHERE `cos_id`= '$cos_id' AND `id`='$coupon_id'";
                    $update_query=$mysqli->query($coupon_update);
                    $_SESSION['success'] = "Coupon Details Updated Successfully!";
                    header("Location: coupons.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $update_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    header("Location: addCoupon.php?couponid=$coupon_id");
                    exit();
                }
            }
        }
        }
    }

    // product_insert
    else if(isset($_POST["product_add"])){
        if(isset($_FILES["main_prod_img"])){
            $p_title = $mysqli->real_escape_string(trim($_POST['p_title']))??'';
            $sku_id = trim($_POST['sku_id'])??'';
            $barcode = trim($_POST['barcode'])??NULL;
            // $pcategory = $_POST['pcategory']??'';
            $pcategory = trim($_POST['category-id'])??'';
            // $psub_category = $_POST['psub_category']??'';
            $psub_category = trim($_POST['subcategory-id'])??'';
            $p_variation = trim($_POST['p_variation'])??'';
            $unit = trim($_POST['unit'])??'';
            $brand = trim($_POST['brand'])??'';
            $p_desc = trim($_POST['p_desc'])==='' ? NULL : trim($_POST['p_desc']);
            $flocation = trim($_POST['flocation'])??'';
            $glocation = trim($_POST['glocation'])??'';

            if ($p_variation == 'Loose') {
                
                if (in_array($unit, ['mg', 'MG', 'Mg', 'ml', 'ML', 'Ml'])) {

                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) / 1000 : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) / 1000 : '10';

                } else if (in_array($unit, ['g', 'G', 'l', 'L'])) {

                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) : '';

                } else if (in_array($unit, ['Kg', 'KG', 'kg', 'Kl', 'KL', 'kl'])) {

                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) * 1000 : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) * 1000 : '';

                } else {
                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) : '';

                }
            } else {

                $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) : '20';
                $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) : '10';
                
            }

            $p_status = trim($_POST['p_status'])== '' || NULL ? 1 : trim($_POST['p_status']);
            $is_loose = trim($_POST['is_loose'])== '' || NULL ? 0 : trim($_POST['is_loose']);
    
            if(empty($_FILES["main_prod_img"]["name"])){
                $targetFile = "defaultimgs/nullimg.png";
            } else {
                $targetDir = "../../product/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $p_title);
                $sanitizedVariation = preg_replace('/[^A-Za-z0-9\-]/', '_', $p_variation);
                $imageFileType = strtolower(pathinfo($_FILES["main_prod_img"]["name"], PATHINFO_EXTENSION));
    
                $newFileName = $sanitizedTitle . '_' . $sanitizedVariation . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
    
                move_uploaded_file($_FILES["main_prod_img"]["tmp_name"], $targetFile);
                $targetFile = substr($targetFile, 6);
            }
    
            $prod_query = $mysqli->query("SELECT count(*) as total FROM `e_product_details` WHERE p_title = '$p_title' AND p_variation='$p_variation' AND unit='$unit' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            if($prod_query['total'] > 0){
                $_SESSION['error_message'] = "Product already exists!";
                $_SESSION['old_product'] = $_POST;
                $_SESSION['old_product']['prod_img'] = $targetFile; 
                header("Location: addproduct.php");
                exit();
            } else {
                if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                try {
                    $fields1 = "`cos_id`,`sku_id`,`barcode`,`cat_id`,`sub_cat_id`,`p_img`,`p_title`,`p_variation`,`unit`,`brand`,`p_desc`,`active`,`is_loose`,`reorder_level`,`emergency_level`,`platform`";
                    $values1 = "'$cos_id','$sku_id','$barcode','$pcategory','$psub_category','$targetFile','$p_title','$p_variation','$unit','$brand','$p_desc','$p_status','$is_loose','$reorder_level','$emergency_level','$platform'";
    
                    $product_insert = "INSERT INTO e_product_detail ($fields1) VALUES ($values1)";

                    $insert_query = $mysqli->query($product_insert);
    
                    if (!$insert_query) {
                        throw new Exception("Error occurred during the insert into e_product_details: " . $mysqli->error);
                    }
                    $_SESSION['success'] = "Product Details Added Successfully!";
                    header("Location: products.php");
                    exit();
                    
                }catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $insert_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    $_SESSION['old_product_data'] = $_POST;
                    $_SESSION['old_product_data']['main_prod_img'] = $targetFile; 
                    header("Location: addproduct.php");
                    exit();
                }
            }
            }
        }
    }
    // product update
    elseif(isset($_POST["product_update"])){
        if(isset($_FILES["main_prod_img"])){
            $product_id=trim($_POST['productid']);
            $p_title = $mysqli->real_escape_string(trim($_POST['p_title']));
		    $sku_id = trim($_POST['sku_id']);
		    $barcode = trim($_POST['barcode'])??NULL;
            $pcategory = trim($_POST['category-id'])??'';
            $psub_category = trim($_POST['subcategory-id'])??'';
            $is_loose = trim($_POST['is_loose'])== '' || NULL ? 0 : trim($_POST['is_loose']);
		    $p_variation = trim($_POST['p_variation']);
		    $unit = trim($_POST['unit']);
            $brand = trim($_POST['brand']);
            $p_desc = trim($_POST['p_desc'])==='' ? NULL : trim($_POST['p_desc']);
            $reorder_level = $_POST['reorder_level'];
            $emergency_level = $_POST['emergency_level'];

            if ($p_variation == 'Loose') {
                
                if (in_array($unit, ['mg', 'MG', 'Mg', 'ml', 'ML', 'Ml'])) {

                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) / 1000 : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) / 1000 : '10';

                } else if (in_array($unit, ['g', 'G', 'l', 'L'])) {

                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) : '';

                } else if (in_array($unit, ['Kg', 'KG', 'kg', 'Kl', 'KL', 'kl'])) {

                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) * 1000 : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) * 1000 : '';

                } else {

                    $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) : '';
                    $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) : '';

                }
            } else {

                $reorder_level = $_POST['reorder_level'] != null ? trim($_POST['reorder_level']) : '20';
                $emergency_level = $_POST['emergency_level'] != null ? trim($_POST['emergency_level']) : '10';   

            }
            $flocation = trim($_POST['flocation']);
		    $glocation = trim($_POST['glocation']);
            $p_status = trim($_POST['p_status'])== '' || NULL ? 1 : trim($_POST['p_status']);
        

		    if(empty($_FILES["main_prod_img"]["name"])){
                try{
                    $update_query1 = "UPDATE e_product_details 
                    SET city_id = '1001', 
                     sku_id = '$sku_id', 
                     barcode = '$barcode', 
                     cat_id = '$pcategory', 
                     sub_cat_id = '$psub_category', 
                     p_title = '$p_title', 
                     p_variation = '$p_variation', 
                     unit = '$unit',
                     p_desc = '$p_desc',
                     reorder_level = '$reorder_level',
                     emergency_level = '$emergency_level',
                     brand = '$brand',
                     active = '$p_status', 
                     is_loose = '$is_loose', 
                     up_platform = '$platform'
                     WHERE cos_id = '$cos_id' AND id = '$product_id'";

                    $result1 = mysqli_query($mysqli, $update_query1);
                    if (!$result1) {
                        throw new Exception("Error occurred during the update of e_product_details: " . mysqli_error($mysqli));
                    }
                    $_SESSION['success'] = "Product Details Updated Successfully!";
                    header("Location: products.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $result1 = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    header("Location: addproduct.php?productid=$product_id");
                    exit();
                }
		    }
            else{            
                $targetDir = "../../product/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $p_title);
                $sanitizedVariation = preg_replace('/[^A-Za-z0-9\-]/', '_', $p_variation);
                $imageFileType = strtolower(pathinfo($_FILES["main_prod_img"]["name"], PATHINFO_EXTENSION));

                $newFileName = $sanitizedTitle . '_' . $sanitizedVariation . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["main_prod_img"]["tmp_name"], $targetFile);

                $targetFile=substr($targetFile,6);
            
                    try {
                        if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                            $update_query1 = "UPDATE e_product_details 
                                SET city_id = '1001', 
                               sku_id = '$sku_id',  
                               barcode = '$barcode', 
                               cat_id = '$pcategory', 
                               sub_cat_id = '$psub_category', 
                               p_img = '$targetFile', 
                               p_title = '$p_title', 
                               p_variation = '$p_variation', 
                               unit = '$unit',
                               brand = '$brand',
                               p_desc = '$p_desc',
                               reorder_level = '$reorder_level',
                               emergency_level = '$emergency_level',
                               active = '$p_status', 
                               is_loose = '$is_loose', 
                               up_platform = '$platform'
                            WHERE cos_id = '$cos_id' AND id = '$product_id'";
                            $result1 = mysqli_query($mysqli, $update_query1);
                            if (!$result1) {
                              throw new Exception("Error occurred during the update of e_product_details: " . mysqli_error($mysqli));
                            }
                            $_SESSION['success'] = "Product Details Updated Successfully!";
                              header("Location: products.php");
                            exit();
                        }
                    } 
                    catch (mysqli_sql_exception $exception) {
                        mysqli_rollback($mysqli);
                        $result1 = false;
                        $err_msg=$exception->getMessage();
                        $_SESSION['error_message'] = $err_msg;
                        header("Location: addproduct.php?productid=$product_id");
                        exit();
                    }
            }
        }
    }  
    
    //Combo Add
    else if(isset($_POST["combo_add"])){
        $bulkPricing = [];
        if (isset($_POST['f_quant'], $_POST['t_quant'], $_POST['price'])) {
            $fromQtyArray = $_POST['f_quant'];
            $toQtyArray = $_POST['t_quant'];
            $priceArray = $_POST['price'];

            foreach ($fromQtyArray as $index => $fromQty) {
                $toQty = isset($toQtyArray[$index]) ? $toQtyArray[$index] : null;
                $price = isset($priceArray[$index]) ? $priceArray[$index] : null;

               
                $bulkPricing[] = [
                    'from_qty' => trim($fromQty),
                    'to_qty' => trim($toQty),
                    'price' => trim($price)
                ];
            }
            $fromQty = implode(',', array_column($bulkPricing, 'from_qty'));
            $toQty = implode(',', array_column($bulkPricing, 'to_qty'));
            $price = implode(',', array_column($bulkPricing, 'price'));
        }
        $comboProducts = [];
        if (isset($_POST['product-id'], $_POST['quantity'], $_POST['Outprice'])) {
            $productNames = $_POST['product-id'];
            $quantities = $_POST['quantity'];
            $outprices = $_POST['Outprice'];

            $totalOutprice = array_sum($outprices);

                foreach ($productNames as $index => $productName) {
                    $quantity = isset($quantities[$index]) ? $quantities[$index] : null;
                    $outprice = isset($outprices[$index]) ? $outprices[$index] : null;
    
                    $comboProducts[] = [
                        'product_name' => trim($productName),
                        'quantity' => trim($quantity),
                        'outprice' => trim($outprice)
                    ];
                }
        }
         
        $combo_name = $mysqli->real_escape_string(trim($_POST['combo_name']));
		$sku_id = trim($_POST['sku_id']);
        $offer_amt=trim($totalOutprice);
		if(empty($_FILES["combo_img"]["name"])){
            $targetFile = "defaultimgs/nullimg.png";
        }
        else{
            $targetDir = "../../combo/";
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $combo_name);
            $imageFileType = strtolower(pathinfo($_FILES["combo_img"]["name"], PATHINFO_EXTENSION));

            $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["combo_img"]["tmp_name"], $targetFile);
        }
        $targetFile=substr($targetFile,6);
        try{
            // echo $sku_id;
            $fields1="`cos_id`,`sku_id`,`c_img`,`title`,`offer_amt`,`from_qty`,`to_qty`,`bulk_price`,`created_by`,`platform`";
            $values1="'$cos_id','$sku_id','$targetFile','$combo_name','$offer_amt','$fromQty','$toQty','$price','$created_by','$platform'";              
            $combo_insert="INSERT INTO  `e_data_collection` ($fields1) VALUES ($values1)";
            // echo $combo_insert;
            $insert_query1=$mysqli->query($combo_insert);
            $combo_id=$mysqli->insert_id;
            if (!$insert_query1) {
                throw new Exception("Error occurred during the insert into e_data_collection: " . mysqli_error($mysqli));
            }
            $fields3="`cos_id`,`sku_id`,`p_img`,`p_title`,`type`,`platform`";
            $values3="'$cos_id','$sku_id','$targetFile','$combo_name','2','$platform'";         
            $product_insert="INSERT INTO  `e_product_details` ($fields3) VALUES ($values3)";
            $insert_query3=$mysqli->query($product_insert);
            $prod_id=$mysqli->insert_id;
            // echo $product_insert;
            foreach ($comboProducts as $comboProduct) {   
                $fields2="`cos_id`,`c_id`,`prod_id`,`qty`,`offer_amt`,`created_by`,`platform`";
                $values2="'$cos_id','$combo_id','$comboProduct[product_name]','$comboProduct[quantity]','$comboProduct[outprice]','$created_by','$platform'";
                $stock_insert="INSERT INTO  `e_product_collection_map` ($fields2) VALUES ($values2)";
                $insert_query2=$mysqli->query($stock_insert);
                // echo $stock_insert;
            }

            if (!$insert_query2) {
                throw new Exception("Error occurred during the insert into e_product_collection_map: " . mysqli_error($mysqli));
            }
            $_SESSION['success'] = "Combo Details Added Successfully!";
            header("Location: combo.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $insert_query1 = false;
            $insert_query2 = false;
            $insert_query3 = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            $_SESSION['old_combo'] = $_POST;
            $_SESSION['old_combo']['combo_img'] = $targetFile;
            $_SESSION['old_combo']['bulkPricing'] = $bulkPricing;
            $_SESSION['old_combo']['comboProducts'] = $comboProducts;
            header("Location: addCombo.php");
            exit();
        }
    }
    //Combo Update
    elseif(isset($_POST["combo_update"])){
        if(isset($_FILES["combo_img"])){
            $bulkPricing = [];
            if (isset($_POST['f_quant'], $_POST['t_quant'], $_POST['price'])) {

                
                $fromQtyArray = $_POST['f_quant'];
                $toQtyArray = $_POST['t_quant'];
                $priceArray = $_POST['price'];
                
                foreach ($fromQtyArray as $index => $fromQty) {
                    $toQty = isset($toQtyArray[$index]) ? $toQtyArray[$index] : null;
                    $price = isset($priceArray[$index]) ? $priceArray[$index] : null;

               
                    $bulkPricing[] = [
                        'from_qty' => trim($fromQty),
                        'to_qty' => trim($toQty),
                        'price' => trim($price)
                    ];
                }
                    $fromQty = implode(',', array_column($bulkPricing, 'from_qty'));
                    $toQty = implode(',', array_column($bulkPricing, 'to_qty'));
                    $price = implode(',', array_column($bulkPricing, 'price'));

                    
            }
            $comboProducts = [];

            if (isset($_POST['product-id'], $_POST['quantity'], $_POST['Outprice'], $_POST['cp_id'])) {
                $productNames = $_POST['product-id'];
                $quantities = $_POST['quantity'];
                $outprices = $_POST['Outprice'];
                $ids = $_POST['cp_id'];
                
                $totalOutprice = array_sum($outprices);

                foreach ($productNames as $index => $productName) {
                    $quantity = isset($quantities[$index]) ? $quantities[$index] : null;
                    $outprice = isset($outprices[$index]) ? $outprices[$index] : null;
                    $id = isset($ids[$index]) ? $ids[$index] : null;
    
                    $comboProducts[] = [
                        'product_name' => trim($productName),
                        'quantity' => trim($quantity),
                        'outprice' => trim($outprice),
                        'id' => $id,
                    ];
                }
                // print_r($comboProducts);

                // echo $totalOutprice;
            }
            $combo_id=trim($_POST['comboid']);
            $combo_prod_id=trim($_POST['combo_prod_id']);
            $combo_name = $mysqli->real_escape_string(trim($_POST['combo_name']));
		    $sku_id = trim($_POST['sku_id']);
            $offer_amt=trim($totalOutprice);

		    if(empty($_FILES["combo_img"]["name"])){
            // $targetFile = "defaultimgs/nullimg.png";
                try{
                    $update_query1 = "UPDATE `e_data_collection` 
                    SET 
                     sku_id = '$sku_id', 
                     title = '$combo_name', 
                     offer_amt = '$offer_amt', 
                     from_qty = '$fromQty', 
                     to_qty = '$toQty', 
                     bulk_price = '$price',  
                     updated_by = '$updated_by',
                     up_platform = '$platform'
                 WHERE cos_id = '$cos_id' AND id = '$combo_id'";
                // echo $update_query1;
                 $result1 = mysqli_query($mysqli, $update_query1);
                 if (!$result1) {
                     throw new Exception("Error occurred during the update of e_data_collection: " . mysqli_error($mysqli));
                 }
                 $update_query2 = "UPDATE `e_product_details` 
                    SET 
                    sku_id = '$sku_id', 
                    p_title = '$combo_name',   
                     up_platform = '$platform'
                 WHERE cos_id = '$cos_id' AND id = '$combo_prod_id'";
                // echo $update_query2;
                 $result2 = mysqli_query($mysqli, $update_query2);
                 if (!$result2) {
                     throw new Exception("Error occurred during the update of e_product_details: " . mysqli_error($mysqli));
                }
                foreach($comboProducts as $comboProduct) {
                    $cp_id=$comboProduct['id'];

                    if($cp_id == ''){
                        $combo_prod_insert="INSERT INTO  `e_product_collection_map` (`cos_id`,`c_id`,`prod_id`,`qty`,`offer_amt`,`created_by`,`platform`) 
                        VALUES ('$cos_id','$combo_id','$comboProduct[product_name]','$comboProduct[quantity]','$comboProduct[outprice]','$created_by','$platform')";
                        // echo $combo_prod_insert;
                        $insert_query=$mysqli->query($combo_prod_insert);
                        if (!$insert_query) {
                            throw new Exception("Error occurred during the insert into e_product_collection_map: " . mysqli_error($mysqli));
                        }
                    }
                    else{
                        $update_query3 = "UPDATE `e_product_collection_map` 
                        SET 
                        c_id='$combo_id', 
                        prod_id='$comboProduct[product_name]',
                        qty='$comboProduct[quantity]',
                        offer_amt ='$comboProduct[outprice]',   
                        updated_by = '$updated_by',
                     up_platform = '$platform'
                        WHERE cos_id = '$cos_id' AND c_id = '$combo_id' AND id='$cp_id'";
                        // echo $update_query3;
                        $result3 = mysqli_query($mysqli, $update_query3);
                        if (!$result3) {
                            throw new Exception("Error occurred during the update of e_product_collection_map: " . mysqli_error($mysqli));
                        }
                    }
                 
                }
                $_SESSION['success'] = "Combo Details Updated Successfully!";
                 header("Location: combo.php");
                 exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $result1 = false;
                $result2 = false;
                $result3 = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                header("Location: addCombo.php?comboid=$combo_id");
                exit();
            }
        
        }
        else{
            $targetDir = "../../combo/";
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $combo_name);
            $imageFileType = strtolower(pathinfo($_FILES["combo_img"]["name"], PATHINFO_EXTENSION));

            $newFileName = $sanitizedTitle . '_' . $sanitizedVariation . '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["combo_img"]["tmp_name"], $targetFile);
            $targetFile=substr($targetFile,6);
                try{
                    $update_query1 = "UPDATE `e_data_collection` 
                    SET 
                     sku_id = '$sku_id', 
                     title = '$combo_name', 
                     c_img='$targetFile',
                     offer_amt = '$offer_amt', 
                     from_qty = '$fromQty', 
                     to_qty = '$toQty', 
                     bulk_price = '$price',  
                     updated_by = '$updated_by',
                     up_platform = '$platform'
                 WHERE cos_id = '$cos_id' AND id = '$combo_id'";

                 $result1 = mysqli_query($mysqli, $update_query1);
                 if (!$result1) {
                     throw new Exception("Error occurred during the update of e_data_collection: " . mysqli_error($mysqli));
                 }
                 $update_query2 = "UPDATE `e_product_details` 
                    SET 
                    sku_id = '$sku_id', 
                    p_title = '$combo_name',
                    p_img='$targetFile',   
                     up_platform = '$platform'
                 WHERE cos_id = '$cos_id' AND id = '$combo_prod_id'";

                 $result2 = mysqli_query($mysqli, $update_query2);
                 if (!$result2) {
                     throw new Exception("Error occurred during the update of e_product_details: " . mysqli_error($mysqli));
                }
                foreach($comboProducts as $comboProduct) {
                    $cp_id=$comboProduct['id'];

                    if($cp_id == ''){
                        $combo_prod_insert="INSERT INTO  `e_product_collection_map` (`cos_id`,`c_id`,`prod_id`,`qty`,`offer_amt`,`created_by`,`platform`) 
                        VALUES ('$cos_id','$combo_id','$comboProduct[product_name]','$comboProduct[quantity]','$comboProduct[outprice]','$created_by','$platform')";
                        $insert_query=$mysqli->query($combo_prod_insert);
                        if (!$insert_query) {
                            throw new Exception("Error occurred during the insert into e_product_collection_map: " . mysqli_error($mysqli));
                        }
                    }
                    else{
                        $update_query3 = "UPDATE `e_product_collection_map` 
                        SET 
                        c_id='$combo_id', 
                        prod_id='$comboProduct[product_name]',
                        qty='$comboProduct[quantity]',
                        offer_amt ='$comboProduct[outprice]',   
                        updated_by = '$updated_by',
                        up_platform = '$platform'
                        WHERE cos_id = '$cos_id' AND c_id = '$combo_id' AND id='$cp_id'";
                    }
                 $result3 = mysqli_query($mysqli, $update_query3);
                 if (!$result3) {
                    throw new Exception("Error occurred during the update of e_product_collection_map: " . mysqli_error($mysqli));
                }
                }
                $_SESSION['success'] = "Combo Details Updated Successfully!";
                 header("Location: combo.php");
                 exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $result1 = false;
                $result2 = false;
                $result3 = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                header("Location: addCombo.php?comboid=$combo_id");
                exit();
            }
        }
        }      
    }
    //level Update
    elseif(isset($_POST['level_update'])){

        $product_id=trim($_POST['productid']);
        $reorder_level = trim($_POST['reorder_level']);
        $emergency_level= trim($_POST['emergency_level']);
        try{
            $update_query1 = "UPDATE e_product_details 
                SET city_id = '1001', 
                    reorder_level= '$reorder_level',
                    emergency_level= '$emergency_level',
                    up_platform='$platform'
                    WHERE cos_id = '$cos_id' AND id = '$product_id'";
                    $result1 = mysqli_query($mysqli, $update_query1);
                    if (!$result1) {
                        throw new Exception("Error occurred during the update of e_product_details: " . mysqli_error($mysqli));
                    }
                    $_SESSION['success'] = "Level Details Updated Successfully!";
                    header("Location: level.php");
                    exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $result1 = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            header("Location: editLevel.php?productid=$product_id");
            exit();
        }
    }
    //Stock Add
    else if(isset($_POST["stock_add"])){
            if(isset($_FILES["stock_bill"])){
                $p_perg = 0;
                if(isset($_POST['p_perkg'])){
                    $p_perg=floatval(trim($_POST['p_perkg'])/1000);
                }
                $product_id=trim($_POST['stock_prod_id']);
		        $tstock = trim($_POST['tstock']);
                $is_loose=$mysqli->query("SELECT is_loose FROM e_product_details WHERE id='$product_id' AND active=1 AND cos_id = '$cos_id'")->fetch_assoc();
                if($is_loose['is_loose']==1){
                    $tstock = $tstock * 1000;
                }
		        $supplier_id = trim($_POST['supplier_id']);
		        $batch_no = trim($_POST['batch_no']);
		        $expiry_date = trim($_POST['expiry_date']);
		        $in_price = trim($_POST['in_price']);
		        $mrp = trim($_POST['mrp']);
		        $out_price = trim($_POST['out_price']);
                $invoice_no=trim($_POST['invoice_no']);

                if(empty($_FILES["stock_bill"]["name"])){
                    $targetFile = "defaultimgs/nullimg.png";
                }
                else{
                    $targetDir = "../../stockBill/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $invoice_no);
                $imageFileType = strtolower(pathinfo($_FILES["stock_bill"]["name"], PATHINFO_EXTENSION));
    
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["stock_bill"]["tmp_name"], $targetFile);
    
                $targetFile=substr($targetFile,6);
            }
            $batchno_query = $mysqli->query("SELECT count(*) as total FROM `e_product_stock` WHERE s_batch_no='$batch_no' AND active=1 AND cos_id='$cos_id'")->fetch_assoc();
            if($batchno_query['total'] > 0){
                $_SESSION['error_message'] = "Stock With Same Batch No already exists!";
                $_SESSION['old_stock']['stock_bill'] = $targetFile; 
                header("Location: inventory_addStock.php");
                exit();
            } else {
                try{
                    $fields1 = "`cos_id`, `invoice_no`,`stock_bill`,  `supplier_id`,`created_by`,`platform`";
                    $values1 = "'$cos_id', '$invoice_no', '$targetFile', '$supplier_id','$created_by','$platform'";
                    $purchase_insert = "INSERT INTO `e_purchase_entry` ($fields1) VALUES ($values1)";
                    $insert_query1 = $mysqli->query($purchase_insert);
                    $purchase_id = mysqli_insert_id($mysqli);
                    if (!$insert_query1) {
                        throw new Exception("Error occurred during the insert into e_purchase_entry: " . mysqli_error($mysqli));
                    }


                    if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                        $fields2="`cos_id`,`s_product_id`,`qty`,`stock_bill`,`purchase_id`,`invoice_no`,`supplier_id`,`s_batch_no`,`s_expiry_date`,`in_price`,`s_mrp`,`s_out_price`,`platform`";
                        $values2="'$cos_id','$product_id','$tstock','$targetFile','$purchase_id','$invoice_no','$supplier_id', '$batch_no', '$expiry_date', '$in_price', '$mrp', '$out_price','$platform'";              
                        $stock_insert="INSERT INTO  `e_product_stock` ($fields2) VALUES ($values2)";
                        $insert_query2=$mysqli->query($stock_insert);
                        if (!$insert_query2) {
                            throw new Exception("Error occurred during the insert into e_product_stock: " . mysqli_error($mysqli));
                        }
                    }else{
                        $fields2="`cos_id`,`s_product_id`,`qty`,`purchase_id`,`invoice_no`,`supplier_id`,`s_batch_no`,`s_expiry_date`,`in_price`,`s_mrp`,`s_out_price`,`platform`";
                        $values2="'$cos_id', '$product_id', '$tstock','$purchase_id','$invoice_no','$supplier_id', '$batch_no', '$expiry_date', '$in_price', '$mrp', '$out_price','$platform'";          
                        $stock_insert="INSERT INTO  `e_product_stock` ($fields2) VALUES ($values2)";
                        $insert_query2=$mysqli->query($stock_insert);
                        if (!$insert_query2) {
                            throw new Exception("Error occurred during the insert into e_product_stock: " . mysqli_error($mysqli));
                        }
                    }
                    if(isset($_POST['p_perkg'])){
                        $fields3="`cos_id`,`product_id`,`mrp`,`out_price`,`batch_no`,`per_g`,`qty_left`,`expiry_date`,`platform`";
                        $values3="'$cos_id','$product_id', '$mrp', '$out_price', '$batch_no','$p_perg','$tstock', '$expiry_date','$platform'";              
                        $price_insert="INSERT INTO  `e_product_price` ($fields3) VALUES ($values3)";
                    }else{
                        $fields3="`cos_id`,`product_id`,`mrp`,`out_price`,`batch_no`,`qty_left`,`expiry_date`,`platform`";
                        $values3="'$cos_id','$product_id', '$mrp', '$out_price', '$batch_no', '$tstock', '$expiry_date','$platform'";              
                        $price_insert="INSERT INTO  `e_product_price` ($fields3) VALUES ($values3)";
                    }
                    
                    $insert_query3=$mysqli->query($price_insert);
                    if (!$insert_query3) {
                        throw new Exception("Error occurred during the insert into e_product_price: " . mysqli_error($mysqli));
                    }
                    $_SESSION['success'] = "Stock Added Successfully!";
                    $_SESSION['old_stock']['stock_bill'] = $targetFile; 
                    header("Location: inventory_addStock.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $insert_query1 = false;
                    $insert_query2 = false;
                    $insert_query3 = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    $_SESSION['old_stock']['stock_bill'] = $targetFile; 
                    header("Location: inventory_addStock.php");
                    exit();
                }
            }
        }
    }
    //Stock Update
    elseif(isset($_POST["stock_update"])){
        if($_FILES["stock_bill"]){
            $p_perg = 0;
            if(isset($_POST['p_perkg'])){
                $p_perg=floatval(trim($_POST['p_perkg'])/1000);
            }
            $product_id=trim($_POST['stock_prod_id']);
            $batch_id=trim($_POST['stock_batch_id']);
            $purchase_id=trim($_POST['stock_purchase_id']);
            $tstock = trim($_POST['tstock']);
            $is_loose=$mysqli->query("SELECT is_loose FROM e_product_details WHERE id='$product_id' AND active=1 AND cos_id = '$cos_id'")->fetch_assoc();
            if($is_loose['is_loose']==1){
                $tstock = $tstock * 1000;
            }
            $supplier_id = trim($_POST['supplier_id']);
            $batch_no = trim($_POST['batch_no']);
            $expiry_date = trim($_POST['expiry_date']);
            $in_price = trim($_POST['in_price']);
            $mrp = trim($_POST['mrp']);
            $out_price = trim($_POST['out_price']);
            $invoice_no=trim($_POST['invoice_no']);
            if(empty($_FILES["stock_bill"]["name"])){
                // $targetFile = "defaultimgs/nullimg.png";
                // $batchno_query = $mysqli->query("SELECT count(*) as total FROM `e_product_stock` WHERE s_batch_no='$batch_no' AND active=1 AND cos_id='$cos_id'")->fetch_assoc();
                //     if($batchno_query['total'] > 0){
                //         $_SESSION['error_message'] = "Stock With Same Batch No already exists!";
                //         header("Location: inventory_addStock.php?productid=$product_id&&batch_id=$batch_no");
                //         exit();
                //     } else {
                        try{
                            $purchase_update="UPDATE `e_purchase_entry` SET `supplier_id`='$supplier_id',`invoice_no`='$invoice_no',
                            `up_platform`='$platform' WHERE `cos_id`= '$cos_id' AND `id`='$purchase_id'";
                            //  echo $purchase_update;
                            $update_query3=$mysqli->query($purchase_update);

                            $stock_update="UPDATE `e_product_stock` SET `qty`='$tstock',`supplier_id`='$supplier_id',`s_batch_no`='$batch_no',
                            `s_expiry_date`='$expiry_date',`in_price`='$in_price',`s_mrp`='$mrp',`s_out_price`='$out_price',`invoice_no`='$invoice_no',
                            `up_platform`='$platform' WHERE `cos_id`= '$cos_id' AND `s_product_id`='$product_id' AND `s_batch_no`='$batch_id'";
                            // echo $stock_update;
                            $update_query1=$mysqli->query($stock_update);

            
                            if(isset($_POST['p_perkg'])){
                                $price_update="UPDATE `e_product_price` SET `batch_no`='$batch_no',`per_g`='$p_perg',`qty_left`='$tstock',`mrp`='$mrp',`out_price`='$out_price',`expiry_date`='$expiry_date',
                            `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `product_id`='$product_id' AND `batch_no`='$batch_id'";
                            }else{
                                $price_update="UPDATE `e_product_price` SET `batch_no`='$batch_no',`qty_left`='$tstock',`mrp`='$mrp',`out_price`='$out_price',`expiry_date`='$expiry_date',
                            `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `product_id`='$product_id' AND `batch_no`='$batch_id'";
                            }
                            $update_query2=$mysqli->query($price_update);
                            $_SESSION['success'] = "Stock Details Updated Successfully!";
                            header("Location: product_report.php");
                            exit();
                        }
                        catch (mysqli_sql_exception $exception) {
                            mysqli_rollback($mysqli);
                            $update_query1 = false;
                            $update_query2 = false;
                            $update_query3 = false;
                            $err_msg=$exception->getMessage();
                            $_SESSION['error_message'] = $err_msg;
                            header("Location: inventory_addStock.php?productid=$product_id&&batch_id=$batch_no");
                            exit();
                        }
                    // }
            }
            else{
                $targetDir = "../../stockBill/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $invoice_no);
                $imageFileType = strtolower(pathinfo($_FILES["stock_bill"]["name"], PATHINFO_EXTENSION));
    
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["stock_bill"]["tmp_name"], $targetFile);
                if ($_FILES["stock_bill"]["error"] !== UPLOAD_ERR_OK) {
                    die("File upload error: " . $_FILES["stock_bill"]["error"]);
                }
    
            $targetFile=substr($targetFile,6);

            if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                $batchno_query = $mysqli->query("SELECT count(*) as total FROM `e_product_stock` WHERE s_batch_no='$batch_no' AND active=1 AND cos_id='$cos_id'")->fetch_assoc();
                // if($batchno_query['total'] > 0){
                //     $_SESSION['error_message'] = "Stock With Same Batch No already exists!";
                //     header("Location: inventory_addStock.php?productid=$product_id&&batch_id=$batch_id");
                //     exit();
                // } else {
                    try{
                        $purchase_update="UPDATE `e_purchase_entry` SET `supplier_id`='$supplier_id',`invoice_no`='$invoice_no',`stock_bill`='$targetFile',
                            `up_platform`='$platform' WHERE `cos_id`= '$cos_id' AND `id`='$purchase_id'";
                            $update_query3=$mysqli->query($purchase_update);

                        $stock_update="UPDATE `e_product_stock` SET `qty`='$tstock',`supplier_id`='$supplier_id',`s_batch_no`='$batch_no',
                        `s_expiry_date`='$expiry_date',`in_price`='$in_price',`s_mrp`='$mrp',`s_out_price`='$out_price',`invoice_no`='$invoice_no',
                        `stock_bill`='$targetFile',`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `s_product_id`='$product_id' AND `s_batch_no`='$batch_id'";
                        // echo $stock_update;
                        $update_query1=$mysqli->query($stock_update);
                        // echo $update_query1;
        
                        if(isset($_POST['p_perkg'])){
                            $price_update="UPDATE `e_product_price` SET `batch_no`='$batch_no',`per_g`='$p_perg',`qty_left`='$tstock',`mrp`='$mrp',`out_price`='$out_price',`expiry_date`='$expiry_date',
                        `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `product_id`='$product_id' AND `batch_no`='$batch_id'";
                        }else{
                            $price_update="UPDATE `e_product_price` SET `batch_no`='$batch_no',`qty_left`='$tstock',`mrp`='$mrp',`out_price`='$out_price',`expiry_date`='$expiry_date',
                        `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `product_id`='$product_id' AND `batch_no`='$batch_id'";
                        }
                        $update_query2=$mysqli->query($price_update);
                        $_SESSION['success'] = "Stock Details Updated Successfully!";
                        header("Location: product_report.php");
                        exit();
                    }
                    catch (mysqli_sql_exception $exception) {
                        mysqli_rollback($mysqli);
                        $update_query1 = false;
                        $update_query2 = false;
                        $err_msg=$exception->getMessage();
                        $_SESSION['error_message'] = $err_msg;
                        header("Location: inventory_addStock.php?productid=$product_id&&batch_id=$batch_no");
                        exit();
                    }
                // }
            }
        }
        }
    }
    //Multiple Stock Add-new
    elseif(isset($_POST["multiple_stock_add"])) {
        if(isset($_FILES["stock_bill"])) {
            $productId = $_POST['product-id'];
            $inprice = $_POST['in_price'];
            $outprice = $_POST['out_price'];
            $mrp = $_POST['mrp'];
            $tstock = $_POST['tstock'];
            $batchNo = $_POST['batch_no'];
            $expiryDate = $_POST['expiry_date'];
            $p_perkg = $_POST['p_perkg'];
            $invoiceNo = $_POST['invoice_no'];
            $supplierId = $_POST['supplier_id'];

            if(empty($_FILES["stock_bill"]["name"])) {
                $targetFile = "defaultimgs/nullimg.png";
            } else {
                $targetDir = "../../stockBill/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $invoiceNo[0]);
                $imageFileType = strtolower(pathinfo($_FILES["stock_bill"]["name"], PATHINFO_EXTENSION));
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["stock_bill"]["tmp_name"], $targetFile);
                $targetFile = substr($targetFile, 6);
            }
            try {

                $fields1 = "`cos_id`, `invoice_no`,`stock_bill`,  `supplier_id`,`created_by`,`platform`";
                $values1 = "'$cos_id', '$invoiceNo', '$targetFile', '$supplierId','$created_by','$platform'";
                $purchase_insert = "INSERT INTO `e_purchase_entry` ($fields1) VALUES ($values1)";
                $insert_query1 = $mysqli->query($purchase_insert);
                $purchase_id = mysqli_insert_id($mysqli);
                if (!$insert_query1) {
                    throw new Exception("Error occurred during the insert into e_purchase_entry: " . mysqli_error($mysqli));
                }

                foreach($productId as $index => $pid) {
                    $current_in_price = $inprice[$index];
                    $current_out_price = $outprice[$index];
                    $current_mrp = $mrp[$index];
                    $current_tstock = $tstock[$index];
                    $is_loose=$mysqli->query("SELECT is_loose FROM e_product_details WHERE id='$pid' AND active=1 AND cos_id = '$cos_id'")->fetch_assoc();
                    if($is_loose['is_loose'] == 1 ){
                        $current_tstock = $current_tstock * 1000;
                    }
                    $current_batchNo = $batchNo[$index];
                    $current_expiryDate = $expiryDate[$index];
                    $current_p_perg = $p_perkg[$index]==NULL || '' ? '': ($p_perkg[$index]/1000);

                    $fields2 = "`cos_id`, `s_product_id`, `qty`, `stock_bill`, purchase_id,`invoice_no`, `supplier_id`, `s_batch_no`, `s_expiry_date`, `in_price`, `s_mrp`, `s_out_price`,`platform`";
                    $values2 = "'$cos_id','$pid','$current_tstock','$targetFile','$purchase_id','$invoiceNo', '$supplierId', '$current_batchNo','$current_expiryDate', '$current_in_price', '$current_mrp', '$current_out_price','$platform'";
                    $stock_insert = "INSERT INTO `e_product_stock` ($fields2) VALUES ($values2)";
                    // echo $stock_insert;
                    $insert_query2 = $mysqli->query($stock_insert);
                    if (!$insert_query2) {
                        throw new Exception("Error occurred during the insert into e_product_stock: " . mysqli_error($mysqli));
                    }
    
                    $fields3 = "`cos_id`, `product_id`, `mrp`, `out_price`, `batch_no`,`per_g`,`qty_left`, `expiry_date`,`platform`";
                    $values3 = "'$cos_id', '$pid', '$current_mrp', '$current_out_price','$current_batchNo','$current_p_perg','$current_tstock', '$current_expiryDate','$platform'";
                    $price_insert = "INSERT INTO `e_product_price` ($fields3) VALUES ($values3)";
                    // echo $price_insert;
                    $insert_query3 = $mysqli->query($price_insert);
                    if (!$insert_query3) {
                        throw new Exception("Error occurred during the insert into e_product_price: " . mysqli_error($mysqli));
                    }
                }
                $_SESSION['success'] = "Multiple Stocks Added Successfully!";
                header("Location: multipleStock.php");
                exit();

    
            } catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $insert_query1 = false;
                $insert_query2 = false;
                $insert_query3 = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                header("Location: multipleStock.php");
                exit();
            }
        }
    }
    
    //category add
    elseif (isset($_POST["category_add"])) {
        if (isset($_FILES['cat_img'])) {
          $cat_status = trim($_POST['cat_status'])== '' || NULL ? 1 : trim($_POST['cat_status']);
          $category_title= trim($_POST['pcategory']);
            if(empty($_FILES["cat_img"]["name"])){
            $targetFile = "defaultimgs/nullimg.png";
            }
            else{
                $targetDir = "../../category/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $category_title);
                $imageFileType = strtolower(pathinfo($_FILES["cat_img"]["name"], PATHINFO_EXTENSION));
    
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["cat_img"]["tmp_name"], $targetFile);
    
                $targetFile=substr($targetFile,6);
            }
            $category_query = $mysqli->query("SELECT count(*) as total FROM `e_category_details` WHERE title='$category_title'  AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            if($category_query['total'] > 0){
                $_SESSION['error_message'] = "Category already exists!";
                $_SESSION['old_category'] = $_POST;
                $_SESSION['old_category']['cat_img'] = $targetFile;  
                header("Location: addcategory.php");
                exit();
            } else {
                try{
                    $fields="`cos_id`,`c_img`,`title`,`active`,`created_by`,`platform`";
                    $values="'$cos_id','$targetFile','$category_title','$cat_status','$created_by','$platform'";
                    $category_insert="INSERT INTO  `e_category_details` ($fields) VALUES ($values)";
                    $insert_query=$mysqli->query($category_insert);
                    $_SESSION['success'] = "Category Details Added Successfully!";
                    header("Location: category.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $insert_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    $_SESSION['old_category'] = $_POST;
                    $_SESSION['old_category']['cat_img'] = $targetFile;  
                    header("Location: addcategory.php");
                    exit();
                }
            }
        }
    }
    //category Update
    elseif(isset($_POST['category_update'])){
        if(isset($_FILES["cat_img"])){
            $category_id=trim($_POST['categoryid']);
            $cat_status = trim($_POST['cat_status'])== '' || NULL ? 1 : trim($_POST['cat_status']);
        $category_title= trim($_POST['pcategory'])
        
        ;
        if(empty($_FILES["cat_img"]["name"])){
            // $targetFile = "defaultimgs/nullimg.png";
            // $category_query = $mysqli->query("SELECT count(*) as total FROM `e_category_details` WHERE title='$category_title'  AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            // if($category_query['total'] > 0){
            //     $_SESSION['error_message'] = "Category already exists!";
            //     header("Location: addcategory.php?categoryid=$category_id");
            //     exit();
            // }else{
                try{
                    $category_update="UPDATE `e_category_details` SET `title`='$category_title', `active`='$cat_status',`updated_by`='$updated_by',`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$category_id'";
                    $update_query=$mysqli->query($category_update);
                    $_SESSION['success'] = "Category Details Updated Successfully!";
                    header("Location: category.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $update_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    header("Location: addcategory.php?categoryid=$category_id");
                    exit();
                }
            // }
        }
        else{
            $targetDir = "../../category/";
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $category_title);
            $imageFileType = strtolower(pathinfo($_FILES["cat_img"]["name"], PATHINFO_EXTENSION));
    
            $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["cat_img"]["tmp_name"], $targetFile);
    
            $targetFile=substr($targetFile,6);
        }
        
        if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
            // $category_query = $mysqli->query("SELECT count(*) as total FROM `e_category_details` WHERE title='$category_title'  AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            // if($category_query['total'] > 0){
            //     $_SESSION['error_message'] = "Category already exists!";
            //     header("Location: addcategory.php?categoryid=$category_id");
            //     exit();
            // }else{
                try{
                    $category_update="UPDATE `e_category_details` SET `title`='$category_title', `active`='$cat_status',`updated_by`='$updated_by',`up_platform`='$platform' ,`c_img`='$targetFile' WHERE `cos_id`= '$cos_id' AND `id`='$category_id'";
                    $update_query=$mysqli->query($category_update);
                    $_SESSION['success'] = "Category Details Updated Successfully!";
                    header("Location: category.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $update_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    header("Location: addcategory.php?categoryid=$category_id");
                    exit();
                }
            // }
        }
        }
    }
    //sub category add
    elseif (isset($_POST["subcategory_add"])) {
        if (isset($_FILES['subcat_img'])) {
        //   $cat_id=$_POST['category'];
            $cat_id=trim($_POST['category-id']);
          $subcat_status = trim($_POST['subcat_status'])== '' || NULL ? 1 : trim($_POST['subcat_status']);
          $subcategory= trim($_POST['sub_category']);
            if(empty($_FILES["subcat_img"]["name"])){
                $targetFile = "defaultimgs/nullimg.png";
            }
            else{
                $targetDir = "../../subcategory/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $subcategory);
                $imageFileType = strtolower(pathinfo($_FILES["subcat_img"]["name"], PATHINFO_EXTENSION));
        
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["subcat_img"]["tmp_name"], $targetFile);
        
                $targetFile=substr($targetFile,6);
            }
            $subcategory_query = $mysqli->query("SELECT count(*) as total FROM `e_subcategory_details` WHERE c_id='$cat_id' AND title='$subcategory' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            if($subcategory_query['total'] > 0){
                $_SESSION['error_message'] = "Subcategory already exists!";
                $_SESSION['old_subcategory'] = $_POST;
                $_SESSION['old_subcategory']['subcat_img'] = $targetFile;  
                header("Location: addsubcategory.php");
                exit();
            } else {
                try{
                    $fields="`cos_id`,`c_id`,`title`,`c_img`,`active`,`created_by`,`platform`";
                    $values="'$cos_id','$cat_id','$subcategory','$targetFile','$subcat_status','$created_by','$platform'";
                    $subcategory_insert="INSERT INTO  `e_subcategory_details` ($fields) VALUES ($values)";
                    $insert_query=$mysqli->query($subcategory_insert);
                    $_SESSION['success'] = "Subcategory Details Added Successfully!";
                    header("Location: subcategory.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $insert_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    $_SESSION['old_subcategory'] = $_POST;
                    $_SESSION['old_subcategory']['subcat_img'] = $targetFile;  
                    header("Location: addsubcategory.php");
                    exit();
                }
            }
        }
    }
    //sub category Update
    elseif(isset($_POST['subcategory_update'])){
        if($_FILES["subcat_img"]){
            $cat_id=trim($_POST['category-id']);
            $subcategory_id=trim($_POST['subcategoryid']);
            $subcat_status = trim($_POST['subcat_status'])== '' || NULL ? 1 : trim($_POST['subcat_status']);
            $subcategory= trim($_POST['sub_category']);
            if(empty($_FILES["subcat_img"]["name"])){
                // $targetFile = "defaultimgs/nullimg.png";
                // $subcategory_query = $mysqli->query("SELECT count(*) as total FROM `e_subcategory_details` WHERE c_id='$cat_id' AND title='$subcategory' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
                // if($subcategory_query['total'] > 0){
                //     $_SESSION['error_message'] = "Subcategory already exists!";
                //     header("Location: addsubcategory.php?subcategoryid=$subcategory_id");
                //     exit();
                // } else {
                    try{
                        $subcategory_update="UPDATE `e_subcategory_details` SET `title`='$subcategory', `active`='$subcat_status',`updated_by`='$updated_by',`up_platform`='$platform' ,`c_id`='$cat_id' WHERE `cos_id`= '$cos_id' AND `id`='$subcategory_id'";
                        $update_query=$mysqli->query($subcategory_update);
                        $_SESSION['success'] = "Subcategory Details Updated Successfully!";
                        header("Location: subcategory.php");
                        exit();
                    }
                    catch (mysqli_sql_exception $exception) {
                        mysqli_rollback($mysqli);
                        $update_query = false;
                        $err_msg=$exception->getMessage();
                        $_SESSION['error_message'] = $err_msg;
                        // echo $err_msg;
                        header("Location: addsubcategory.php?subcategoryid=$subcategory_id");
                        exit();
                    }
                // }
            }
            else{
                $targetDir = "../../subcategory/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $subcategory);
                $imageFileType = strtolower(pathinfo($_FILES["subcat_img"]["name"], PATHINFO_EXTENSION));
        
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["subcat_img"]["tmp_name"], $targetFile);
        
                $targetFile=substr($targetFile,6);
            }
            if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                // $subcategory_query = $mysqli->query("SELECT count(*) as total FROM `e_subcategory_details` WHERE  title='$subcategory' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
                // if($subcategory_query['total'] > 0){
                //     $_SESSION['error_message'] = "Subcategory already exists!";
                //     header("Location: addsubcategory.php?subcategoryid=$subcategory_id");
                //     exit();
                // } else {
                    try{
                        $subcategory_update="UPDATE `e_subcategory_details` SET `title`='$subcategory', `active`='$subcat_status', `updated_by`='$updated_by',`up_platform`='$platform' ,`c_img`='$targetFile',`c_id`='$cat_id' WHERE `cos_id`= '$cos_id' AND `id`='$subcategory_id'";
                        $update_query=$mysqli->query($subcategory_update);
                        $_SESSION['success'] = "Subcategory Details Updated Successfully!";
                        header("Location: subcategory.php");
                        exit();
                    }
                    catch (mysqli_sql_exception $exception) {
                        mysqli_rollback($mysqli);
                        $update_query = false;
                        $err_msg=$exception->getMessage();
                        $_SESSION['error_message'] = $err_msg;
                        // echo $err_msg;
                        header("Location: addsubcategory.php?subcategoryid=$subcategory_id");
                        exit();
                    }
                // }
            }
        }
    }
  
    //timeslot add
    elseif (isset($_POST["timeslot_add"])) {
        $from_time = date("h:i A", strtotime($_POST['from_time']));
        $to_time= date("h:i A", strtotime($_POST['to_time']));
        $slot_limit= trim($_POST['slot_limit']);
        $timeslot_status= trim($_POST['timeslot_status'])== '' || NULL ? 1 : trim($_POST['timeslot_status']);
        try{
            $fields="`cos_id`,`min_time`,`max_time`,`slot_limit`,`active`,`created_by`,`platform`";
            $values="'$cos_id','$from_time','$to_time','$slot_limit','$timeslot_status','$created_by','$platform'";
            $timeslot_insert="INSERT INTO  `e_dat_timeslot` ($fields) VALUES ($values)";
            $insert_query=$mysqli->query($timeslot_insert);
            $_SESSION['success'] = "Timeslot Added Successfully!";
            header("Location: timeslot.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $insert_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            $_SESSION['old_timeslot'] = $_POST;
            // echo $err_msg;
            header("Location: addTimeslot.php");
            exit();
        }
    }
    //timeslot_update
    elseif(isset($_POST['timeslot_update'])){
        $timeslot_id=$_POST['timeslotid'];
        $from_time = date("h:i A", strtotime($_POST['from_time']));
        $to_time= date("h:i A", strtotime($_POST['to_time']));
        $slot_limit= trim($_POST['slot_limit']);
        $timeslot_status= trim($_POST['timeslot_status'])== '' || NULL ? 1 : trim($_POST['timeslot_status']);
        try{
            $timeslot_update="UPDATE `e_dat_timeslot` SET `min_time`='$from_time', `max_time`='$to_time',`slot_limit`='$slot_limit',`active`='$timeslot_status',`updated_by`='$updated_by',`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$timeslot_id'";
            $update_query=$mysqli->query($timeslot_update);
            $_SESSION['success'] = "Timeslot Updated Successfully!";
            header("Location: timeslot.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            // echo $err_msg;
            header("Location: addTimeslot.php?timeslotid=$timeslot_id");
            exit();
        } 
    }
    //profile add
    elseif (isset($_POST["profile_add"])) {
        if(isset($_FILES['add_logo_img'])) {
            $business_name = $mysqli->real_escape_string(trim($_POST['business_name']));
            $mobile1 = trim($_POST['mobile1']);
            $mobile2 = trim($_POST['mobile2']);
            $b_email = trim($_POST['b_email']);
            $gst_no=trim($_POST['gst_no']);
            $doorno = trim($_POST['doorno']);
            $street = trim($_POST['street']);
            $area = trim($_POST['area']);
            $city = trim($_POST['city']);
            $state = trim($_POST['state']);
            $country = trim($_POST['country']);
            $pincode= trim($_POST['pincode']);
            if(empty($_FILES["add_logo_img"]["name"])){
                $targetFile = "defaultimgs/nullimg.png";
            }
            else{
                $targetDir = "../../logo/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $business_name);
                $imageFileType = strtolower(pathinfo($_FILES["add_logo_img"]["name"], PATHINFO_EXTENSION));
        
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["add_logo_img"]["tmp_name"], $targetFile);
        
                $targetFile=substr($targetFile,6);
            }
            $address = $doorno . ", " . $street . "," . $area . "," . $city . "," . $state ."," . $country."-" . $pincode;
            if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                $mobile_query = $mysqli->query("SELECT count(*) as total FROM `e_data_profile` 
                    WHERE `mobile_1` = '$mobile1' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
                if($mobile_query['total'] > 0){
                    $_SESSION['error_message'] = "Profile with same mobile number already exists!";
                    $_SESSION['old_profile'] = $_POST;
                    $_SESSION['old_profile']['add_logo_img'] = $targetFile; 
                    header("Location: addProfile.php");
                    exit();
                } else{
                    try{
                        $fields="`cos_id`,`logo_img`,`business_name`,`mobile_1`,`mobile_2`,`email_id`,`gst_no`,`address`, `created_by`,`active`,`platform`";
                        $values="'$cos_id','$targetFile','$business_name','$mobile1','$mobile2','$b_email','$gst_no','$address','$created_by','0','$platform'";              
                        $v_insert="INSERT INTO  `e_data_profile` ($fields) VALUES ($values)"; 
                        $insert_query=$mysqli->query($v_insert);
                        $_SESSION['success'] = "Profile Details Added Successfully!";
                        header("Location: profile.php");
                        exit();
                    }
                    catch (mysqli_sql_exception $exception) {
                        mysqli_rollback($mysqli);
                        $insert_query = false;
                        $err_msg=$exception->getMessage();
                        $_SESSION['error_message'] = $err_msg;
                        // echo $err_msg;
                        $_SESSION['old_profile'] = $_POST;
                        $_SESSION['old_profile']['add_logo_img'] = $targetFile;  
                        header("Location: addProfile.php");
                        exit();
                    } 
                }
            }
            else{
                $_SESSION['error_message'] = "Image File Format is Invalid";
                $_SESSION['old_profile'] = $_POST;
                $_SESSION['old_profile']['add_logo_img'] = $targetFile; 
                // echo $err_msg;
                header("Location: addProfile.php");
                exit();
            }
        }else{
            $_SESSION['error_message'] = "Please Choose the Image";
            // echo $err_msg;
            $_SESSION['old_profile'] = $_POST;
                        $_SESSION['old_profile']['add_logo_img'] = $targetFile; 
            header("Location: addProfile.php");
            exit();
        }
    }
    //profile update
    elseif(isset($_POST['profile_update'])){
    if(isset($_FILES["logo_img"])){
        $profile_id=$_POST['profileid'];
        $business_name = $mysqli->real_escape_string(trim($_POST['business_name']));
        $mobile1 = trim($_POST['mobile1']);
        $mobile2 = trim($_POST['mobile2']);
        $b_email = trim($_POST['b_email']);
        $gst_no=trim($_POST['gst_no']);
        $doorno =trim($_POST['doorno']);
        $street = trim($_POST['street']);
        $area = trim($_POST['area']);
        $city = trim($_POST['city']);
        $state = trim($_POST['state']);
        $country = trim($_POST['country']);
        $pincode= trim($_POST['pincode']);
        $address = $doorno . ", " . $street . "," . $area . "," . $city . "," . $state ."," . $country."-" . $pincode;
        if(empty($_FILES["logo_img"]["name"])){
            try{
                $profile_update="UPDATE `e_data_profile` SET  `business_name`='$business_name', `mobile_1`='$mobile1',`mobile_2`='$mobile2',`email_id`='$b_email',`gst_no`='$gst_no',`address`='$address',`updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$profile_id'";
                $update_query=$mysqli->query($profile_update);
                $_SESSION['success'] = "Profile Details Updated Successfully!";
                header("Location: profile.php");
                exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $update_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                // echo $err_msg;
                header("Location: addProfile.php?profileid=$profile_id");
                exit();
            } 
        }
        else{
            $targetDir = "../../logo/";
            $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $business_name);
            $imageFileType = strtolower(pathinfo($_FILES["logo_img"]["name"], PATHINFO_EXTENSION));
    
            $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
            $targetFile = $targetDir . $newFileName;
            move_uploaded_file($_FILES["logo_img"]["tmp_name"], $targetFile);
    
            $targetFile=substr($targetFile,6);
            if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
       
                try{
                    $profile_update="UPDATE `e_data_profile` SET `logo_img`='$targetFile', `business_name`='$business_name', `mobile_1`='$mobile1',`mobile_2`='$mobile2',`email_id`='$b_email',`gst_no`='$gst_no',`address`='$address',`updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$profile_id'";
                    $update_query=$mysqli->query($profile_update);
                    $_SESSION['success'] = "Profile Details Updated Successfully!";
                    header("Location: profile.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $update_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    // echo $err_msg;
                    header("Location: addProfile.php?profileid=$profile_id");
                    exit();
                } 
            }
            else{
                $_SESSION['error_message'] = 'Image File Format is Invalid';
                header("Location: addProfile.php?profileid=$profile_id");
                exit();
            }

        }
    }
    else{
        $_SESSION['error_message'] = 'Please Choose tht Image';
        header("Location: addProfile.php?profileid=$profile_id");
        exit();
    }
    }

    //employee add
    elseif (isset($_POST["employee_add"])) {
            $emp_name = $mysqli->real_escape_string(trim($_POST['emp_name']));
            $emp_phone = trim($_POST['emp_phone']);
            $emp_email = trim($_POST['emp_email']);
            $emp_role=trim($_POST['emp_role']);
            if (isset($_POST['role']) && is_array($_POST['role'])) {
                $roles = implode(',', $_POST['role']);
            } else {
                $roles = '';
            }
            $emp_join_date = trim($_POST['emp_join_date']);
            $emp_password = trim($_POST['emp_password']);
            $emp_salary = trim($_POST['emp_salary']);
            $emp_bonus = trim($_POST['emp_bonus'])=='' || NULL ? 0 :trim($_POST['emp_bonus']);
            $emp_doorno = trim($_POST['emp_doorno']);
            $emp_street = trim($_POST['emp_street']);
            $emp_area = trim($_POST['emp_area']);
            $emp_city = trim($_POST['emp_city']);
            $emp_state = trim($_POST['emp_state']);
            $emp_country = trim($_POST['emp_country']);
            $emp_whatsapp = trim($_POST['emp_whatsapp']);
            $emp_pincode = trim($_POST['emp_pincode']);
            $emp_address_parts = array($emp_doorno, $emp_street, $emp_area, $emp_city, $emp_state, $emp_country, $emp_pincode);


            $emp_address_parts = array_filter($emp_address_parts, function($part) {
                return !empty($part);
            });

            $emp_address = implode(", ", $emp_address_parts);

            $mobile_query = $mysqli->query("SELECT count(*) as total FROM `e_salesman_details` 
                WHERE `s_mobile` = '$emp_phone' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            if($mobile_query['total'] > 0){
                $_SESSION['error_message'] = "Employee with same mobile number already exists!";
                $_SESSION['old_delivery'] = $_POST;
                header("Location: addEmployee.php");
                exit();
            } else {
                try{
                    $fields="`cos_id`,`s_name`,`s_mobile`,`whatsapp`,`email`,`s_address`,`password`,`role`,`other_roles`, `joining_date`, `salary`,`bonus`, `created_by`,`platform`";
                    $values="'$cos_id','$emp_name','$emp_phone','$emp_whatsapp','$emp_email','$emp_address','$emp_password','$emp_role','$roles','$emp_join_date','$emp_salary','$emp_bonus','$created_by','$platform'";              
                    $emp_insert="INSERT INTO  `e_salesman_details` ($fields) VALUES ($values)"; 
                    // echo $emp_insert;
                    $insert_query=$mysqli->query($emp_insert);
                    $_SESSION['success'] = "Employee Details Added Successfully!";
                    header("Location: employee.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $insert_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    // echo $err_msg;
                    $_SESSION['old_delivery'] = $_POST;
                    header("Location: addEmployee.php");
                    exit();
                } 
            }
    }
    //employee update
    elseif(isset($_POST['employee_update'])){
        $employee_id=trim($_POST['employeeid']);
        $emp_name = $mysqli->real_escape_string(trim($_POST['emp_name']));
        $emp_phone = trim($_POST['emp_phone']);
        $emp_email = trim($_POST['emp_email']);
        $emp_role = trim($_POST['emp_role']);
        if (isset($_POST['role']) && is_array($_POST['role'])) {
            $roles = implode(',', $_POST['role']);
        } else {
            $roles = '';
        }
        $emp_join_date = trim($_POST['emp_join_date']);
        $emp_password = trim($_POST['emp_password']);
        $emp_salary = trim($_POST['emp_salary']);
        $emp_bonus = trim($_POST['emp_bonus']=='' || NULL ? 0 :$_POST['emp_bonus']);
        $emp_doorno = trim($_POST['emp_doorno']);
        $emp_street = trim($_POST['emp_street']);
        $emp_area = trim($_POST['emp_area']);
        $emp_city = trim($_POST['emp_city']);
        $emp_state = trim($_POST['emp_state']);
        $emp_country = trim($_POST['emp_country']);
        $emp_whatsapp = trim($_POST['emp_whatsapp']);
        $emp_pincode = trim($_POST['emp_pincode']);

        $emp_address_parts = array($emp_doorno, $emp_street, $emp_area, $emp_city, $emp_state, $emp_country, $emp_pincode);


        $emp_address_parts = array_filter($emp_address_parts, function($part) {
            return !empty($part);
        });
        
        $emp_address = implode(", ", $emp_address_parts);        
        try{
            $employee_update="UPDATE `e_salesman_details` SET `s_name`='$emp_name', `s_mobile`='$emp_phone',`whatsapp`='$emp_whatsapp', `email`='$emp_email' ,`s_address`='$emp_address', `password`='$emp_password', `role`='$emp_role' ,`other_roles`='$roles' ,`joining_date`='$emp_join_date',`salary`='$emp_salary',`bonus`='$emp_bonus',`updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$employee_id'";
            $update_query=$mysqli->query($employee_update);
            $_SESSION['success'] = "Employee Details Updated Successfully!";
            header("Location: employee.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            // echo $err_msg;
            header("Location: addEmployee.php?employeeid=$employee_id");
            exit();
        } 
    }
    //delivery add
    elseif (isset($_POST["delivery_add"])) {
        $d_name = $mysqli->real_escape_string(trim($_POST['d_name']));
        $d_phone = trim($_POST['d_phone']);
        $d_email = trim($_POST['d_email']);
        $delivery_person= $mysqli->query("SELECT id FROM `e_salesman_role` WHERE role_title like '%delivery%' AND active != 2 AND cos_id='$cos_id'")->fetch_assoc();
        $d_role = $delivery_person['id'];
        $d_join_date = trim($_POST['d_join_date']);
        $d_password = trim($_POST['d_password']);
        $d_salary = trim($_POST['d_salary']);
        $d_bonus = trim($_POST['d_bonus'])=='' || NULL ? 0 :trim($_POST['d_bonus']);
        $d_doorno = trim($_POST['d_doorno']);
        $d_street = trim($_POST['d_street']);
        $d_area = trim($_POST['d_area']);
        $d_city = trim($_POST['d_city']);
        $d_state = trim($_POST['d_state']);
        $d_country = trim($_POST['d_country']);
        $d_whatsapp = trim($_POST['d_whatsapp']);
        $d_pincode= trim($_POST['d_pincode']);

        $address_parts = array($d_doorno, $d_street, $d_area, $d_city, $d_state, $d_country, $d_pincode);


        $address_parts = array_filter($address_parts, function($part) {
        return !empty($part);
            });

        $d_address = implode(", ",$address_parts);
        $mobile_query = $mysqli->query("SELECT count(*) as total FROM `e_salesman_details` 
            WHERE `s_mobile` = '$d_phone' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
        if($mobile_query['total'] > 0){
            $_SESSION['error_message'] = "Delivery Person with same mobile number already exists!";
            $_SESSION['old_delivery'] = $_POST;
            header("Location: addDeliveryPerson.php");
            exit();
        } else { 
            try{
                $fields="`cos_id`,`s_name`,`s_mobile`,`email`,`whatsapp`,`s_address`,`password`,`role`, `joining_date`, `salary`,`bonus`, `created_by`,`platform`";
                $values="'$cos_id','$d_name','$d_phone','$d_email','$d_whatsapp','$d_address','$d_password','$d_role','$d_join_date','$d_salary','$d_bonus','$created_by','$platform'";              
                $d_insert="INSERT INTO  `e_salesman_details` ($fields) VALUES ($values)"; 
                $insert_query=$mysqli->query($d_insert);
                $_SESSION['success'] = "Delivery Person Details Added Successfully!";
                header("Location: deliveryPerson.php");
                exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $insert_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                // echo $err_msg;
                $_SESSION['old_delivery'] = $_POST;
                header("Location: addDeliveryPerson.php");
                exit();
            } 
        }
    }
    //delivery update
    elseif(isset($_POST['delivery_update'])){
        $delivery_id=trim($_POST['deliveryid']);
        $d_name = $mysqli->real_escape_string(trim($_POST['d_name']));
        $d_phone = trim($_POST['d_phone']);
        $d_email = trim($_POST['d_email']);
        $delivery_person= $mysqli->query("SELECT id FROM `e_salesman_role` WHERE role_title like '%delivery%' AND active != 2 AND cos_id='$cos_id'")->fetch_assoc();
        $d_role = $delivery_person['id'];
        $d_join_date = trim($_POST['d_join_date']);
        $d_password = trim($_POST['d_password']);
        $d_salary = trim($_POST['d_salary']);
        $d_bonus =  trim($_POST['d_bonus'])=='' || NULL ? 0 :trim($_POST['d_bonus']);
        $d_doorno = trim($_POST['d_doorno']);
        $d_street = trim($_POST['d_street']);
        $d_area = trim($_POST['d_area']);
        $d_city = trim($_POST['d_city']);
        $d_state = trim($_POST['d_state']);
        $d_country = trim($_POST['d_country']);
        $d_whatsapp = trim($_POST['d_whatsapp']);
        $d_pincode= trim($_POST['d_pincode']);

        $address_parts = array($d_doorno, $d_street, $d_area, $d_city, $d_state, $d_country, $d_pincode);


            $address_parts = array_filter($address_parts, function($part) {
            return !empty($part);
                });

            $d_address = implode(", ",$address_parts);
        try{
            $delivery_update="UPDATE `e_salesman_details` SET `s_name`='$d_name', `s_mobile`='$d_phone', `email`='$d_email' ,`whatsapp`='$d_whatsapp',`s_address`='$d_address', `password`='$d_password', `role`='$d_role' ,`joining_date`='$d_join_date',`salary`='$d_salary',`bonus`='$d_bonus',`updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$delivery_id'";
            $update_query=$mysqli->query($delivery_update);
            $_SESSION['success'] = "Delivery Person Details Updated Successfully!";
            header("Location: deliveryPerson.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            // echo $err_msg;
            header("Location: addDeliveryPerson.php?deliveryid=$delivery_id");
            exit();
        } 
    }
    //cust add
    elseif (isset($_POST["cust_add"])) {
            $c_name = $mysqli->real_escape_string(trim($_POST['c_name']));
            $c_phone = trim($_POST['c_phone']);
            $c_email = trim($_POST['c_email']);
            $c_password = trim($_POST['c_password']);
            $c_doorno = trim($_POST['c_doorno']);
            $c_street = trim($_POST['c_street']);
            $c_area = trim($_POST['c_area']);
            $c_city = trim($_POST['c_city'])?? '';
            $c_state = trim($_POST['c_state'])??'';
            $c_country = trim($_POST['c_country'])??'';
            $c_pincode = trim($_POST['c_pincode'])??'';
            $c_whatsapp = trim($_POST['c_whatsapp']);

            $code = rand(100000, 999999);

            $mobile_query = $mysqli->query("SELECT count(*) as total FROM `e_user_details` 
                WHERE `mobile` = '$c_phone' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            if($mobile_query['total'] > 0){
                $_SESSION['error_message'] = "Customer with same mobile number already exists!";
                $_SESSION['old_customer'] = $_POST;
                header("Location: addCustomer.php");
                exit();
            } 
            else {
                try{
               
                    $fields1="`cos_id`,`name`,`mobile`,`email_id`,`whatsapp`,`password`,`code`,`created_by`,`platform`";
                    $values1="'$cos_id','$c_name','$c_phone','$c_email','$c_whatsapp','$c_password','$code','$created_by','$platform'";              
                    $cust_insert="INSERT INTO  `e_user_details` ($fields1) VALUES ($values1)";
                    $insert_query1=$mysqli->query($cust_insert);
                    
                    if (!$insert_query1) {
                        throw new Exception("Error occurred during the insert into e_user_details: " . mysqli_error($mysqli));
                    }
                    $user_id = mysqli_insert_id($mysqli);
                    $fields2="`cos_id`,`user_id`,`landmark`,`area`,`pincode`,`address_line_1`,`name`,`mobile`,`city`,`state`,`country`,`created_by`,`platform`";
                    $values2="'$cos_id', '$user_id','$c_street', '$c_area', '$c_pincode', '$c_doorno', '$c_name', '$c_phone', '$c_city','$c_country', '$c_state', '$created_by', '$platform'";              
                    $address_insert="INSERT INTO  `e_address_details` ($fields2) VALUES ($values2)";
                    $insert_query2=$mysqli->query($address_insert);
                    if (!$insert_query2){
                        throw new Exception("Error occurred during the insert into e_address_details: " . mysqli_error($mysqli));
                    }
                    $_SESSION['success'] = "Customer Details Added Successfully!";
                    header("Location: customers.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $insert_query1 = false;
                    $insert_query2 = false;
                    $err_msg=$exception->getMessage();
                    // echo $err_msg;
                    $_SESSION['error_message'] = $err_msg;
                    $_SESSION['old_customer'] = $_POST;
                    header("Location: addCustomer.php");
                    exit();
                } 
            }
    }
    //cust update
    elseif(isset($_POST['cust_update'])){
        $customer_id=trim($_POST['customerid']);
        $c_name = $mysqli->real_escape_string(trim($_POST['c_name']));
        $c_phone = trim($_POST['c_phone']);
        $c_email = trim($_POST['c_email']);
        $c_password = trim($_POST['c_password']);
        $c_doorno = trim($_POST['c_doorno']);
        $c_street = trim($_POST['c_street']);
        $c_area = trim($_POST['c_area']);
        $c_city = trim($_POST['c_city']);
        $c_state = trim($_POST['c_state']);
        $c_country = trim($_POST['c_country']);
        $c_pincode = trim($_POST['c_pincode']);
        $c_whatsapp = trim($_POST['c_whatsapp']);

        try{
            $customer_update="UPDATE `e_user_details` SET `name`='$c_name', `mobile`='$c_phone',`whatsapp`='$c_whatsapp',`email_id`='$c_email' ,`password`='$c_password',`updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$customer_id'";
            // echo $customer_update;
            $update_query1=$mysqli->query($customer_update);
            $address_update="UPDATE `e_address_details` SET `landmark`='$c_street',`area`='$c_area',`address_line_1`='$c_doorno',`name`='$c_name',`mobile`='$c_phone',`pincode`='$c_pincode',`city`='$c_city',`state`='$c_state',`country`='$c_country',`updated_by`='$updated_by',`up_platform`='$platform' WHERE `cos_id`= '$cos_id' AND `user_id`='$customer_id'";
            // echo $address_update;
            $update_query2=$mysqli->query($address_update);
            $_SESSION['success'] = "Customer Details Updated Successfully!";
            header("Location: customers.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query1 = false;
            $update_query2 = false;
            $err_msg=$exception->getMessage();
            // echo $err_msg;
            $_SESSION['error_message'] = $err_msg;
            header("Location: addCustomer.php?customerid=customer_id");
            exit();
        } 
    }
    //Expense Insert
    elseif (isset($_POST["expense_add"])) {
        if (isset($_FILES['exp_img'])) {
          $exp_date = trim($_POST['exp_date']);
          $exp_amount = trim($_POST['exp_amount']);
          $exp_title = $mysqli->real_escape_string(trim($_POST['exp_title']));
          $exp_desc = $mysqli->real_escape_string(trim($_POST['exp_desc']));
          if(empty($_FILES["exp_img"]["name"])){
            $targetFile = "defaultimgs/nullimg.png";
            }
            else{
                $targetDir = "../../expense/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $exp_title);
                $imageFileType = strtolower(pathinfo($_FILES["exp_img"]["name"], PATHINFO_EXTENSION));
        
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["exp_img"]["tmp_name"], $targetFile);
        
                $targetFile=substr($targetFile,6);
            }
            try{
                $fields="`cos_id`,`exp_img`,`exp_desc`,`exp_date`,`exp_title`,`exp_amount`,`created_by`,`platform`";
                $values="'$cos_id','$targetFile','$exp_desc','$exp_date','$exp_title','$exp_amount','$created_by','$platform'";              
                $exp_insert="INSERT INTO  `e_expense_details` ($fields) VALUES ($values)";
                $insert_query=$mysqli->query($exp_insert);
                $_SESSION['success'] = "Expense Details Added Successfully!";
                header("Location: expense.php");
                exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $insert_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                $_SESSION['old_expense'] = $_POST;
                $_SESSION['old_expense']['exp_img'] = $targetFile;  
                header("Location: addExpense.php");
                exit();
            } 
        }
      }
      //Expense Update
      elseif(isset($_POST['expense_update'])){
        if(isset($_FILES["exp_img"])){
            $expense_id=trim($_POST['expenseid']);
            $exp_date = trim($_POST['exp_date']);
            $exp_amount = trim($_POST['exp_amount']);
            $exp_title = $mysqli->real_escape_string(trim($_POST['exp_title']));
            $exp_desc = $mysqli->real_escape_string(trim($_POST['exp_desc']));
            if(empty($_FILES["exp_img"]["name"])){
            //   $targetFile = "defaultimgs/nullimg.png";
            try{
                $expense_update="UPDATE `e_expense_details` SET  `exp_desc`='$exp_desc', `exp_title`='$exp_title',`exp_date`='$exp_date',`exp_amount`='$exp_amount',`updated_by`='$updated_by',`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `exp_id`='$expense_id'";
                $update_query=$mysqli->query($expense_update);
                $_SESSION['success'] = "Expense Details Added Successfully!";
                header("Location: expense.php");
                exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $update_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                header("Location: addExpense.php?expenseid=$expense_id");
                exit();
            } 
          }
          else{
            $targetDir = "../../expense/";
                $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $exp_title);
                $imageFileType = strtolower(pathinfo($_FILES["exp_img"]["name"], PATHINFO_EXTENSION));
        
                $newFileName = $sanitizedTitle . '_' . time() . '.' . $imageFileType;
                $targetFile = $targetDir . $newFileName;
                move_uploaded_file($_FILES["exp_img"]["tmp_name"], $targetFile);
        
                $targetFile=substr($targetFile,6);
          }
      
          if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                try{
                    $expense_update="UPDATE `e_expense_details` SET `exp_img`='$targetFile', `exp_desc`='$exp_desc', `exp_date`='$exp_date', `exp_title`='$exp_title' ,`exp_amount`='$exp_amount',`updated_by`='$updated_by',`up_platform`='$platform'   WHERE `cos_id`= '$cos_id' AND `exp_id`='$expense_id'";
                    $update_query=$mysqli->query($expense_update);
                    $_SESSION['success'] = "Expense Details Updated Successfully!";
                    header("Location: expense.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $update_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    header("Location: addExpense.php?expenseid=$expense_id");
                    exit();
                } 
            }
        }
    }
    // vendor add
      elseif (isset($_POST["vendor_add"])) {
            $v_name = $mysqli->real_escape_string(trim($_POST['v_name']));
            $business_name = $mysqli->real_escape_string(trim($_POST['business_name']));
            $contact_person = trim($_POST['contact_person']);
            $v_mobile = trim($_POST['v_mobile']);
            $v_whatsapp = trim($_POST['v_whatsapp']);
            $gst_no=trim($_POST['gst_no']);
            $v_doorno = trim($_POST['v_doorno']);
            $v_street = trim($_POST['v_street']);
            $v_area = trim($_POST['v_area']);
            $v_city = trim($_POST['v_city']);
            $v_state = trim($_POST['v_state']);
            $v_country = trim($_POST['v_country']);
            $v_pincode= trim($_POST['v_pincode']);
            
            $vend_address_parts = array($v_doorno, $v_street, $v_area, $v_city, $v_state, $v_country, $v_pincode);
            $vend_address_parts = array_filter($vend_address_parts, function($part) {
                return !empty($part);
            });
            $v_address = implode(", ", $vend_address_parts);        

            $mobile_query = $mysqli->query("SELECT count(*) as total FROM `e_vendor_details` 
                WHERE `v_mobile` = '$v_mobile' AND active!=2 AND cos_id='$cos_id'")->fetch_assoc();
            if($mobile_query['total'] > 0){
                $_SESSION['error_message'] = "Vendor with same mobile number already exists!";
                $_SESSION['old_vendor'] = $_POST;
                header("Location: addVendors.php");
                exit();
            } else {

                try{
                    $fields="`cos_id`,`v_name`,`business_name`,`v_mobile`,`contact_person`,`v_whatsapp`,`gst_no`,`v_address`, `created_by`,`platform`";
                    $values="'$cos_id','$v_name','$business_name','$v_mobile','$contact_person','$v_whatsapp','$gst_no','$v_address','$created_by','$platform'";              
                    $v_insert="INSERT INTO  `e_vendor_details` ($fields) VALUES ($values)"; 
                    $insert_query=$mysqli->query($v_insert);
                    $_SESSION['success'] = "Vendor Details Added Successfully!";
                    header("Location: vendors.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $insert_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    $_SESSION['old_vendor'] = $_POST;
                    header("Location: addVendors.php");
                    exit();
                } 
            }
    }
    // vendor update
    elseif(isset($_POST['vendor_update'])){
        $vendor_id=$_POST['vendorid'];
        $v_name = $mysqli->real_escape_string(trim($_POST['v_name']));
        $business_name = $mysqli->real_escape_string(trim($_POST['business_name']));
        $contact_person = trim($_POST['contact_person']);
        $v_mobile = trim($_POST['v_mobile']);
        $v_whatsapp = trim($_POST['v_whatsapp']);
        $gst_no=trim($_POST['gst_no']);
        $v_doorno = trim($_POST['v_doorno']);
        $v_street = trim($_POST['v_street']);
        $v_area = trim($_POST['v_area']);
        $v_city = trim($_POST['v_city']);
        $v_state = trim($_POST['v_state']);
        $v_country = trim($_POST['v_country']);
        $v_pincode= trim($_POST['v_pincode']);

        $vend_address_parts = array($v_doorno, $v_street, $v_area, $v_city, $v_state, $v_country, $v_pincode);
        $vend_address_parts = array_filter($vend_address_parts, function($part) {
            return !empty($part);
        });
        $v_address = implode(", ", $vend_address_parts);   
        try{
            $vendor_update="UPDATE `e_vendor_details` SET `v_name`='$v_name', `business_name`='$business_name', `v_mobile`='$v_mobile', `contact_person`='$contact_person' ,`v_whatsapp`='$v_whatsapp',`gst_no`='$gst_no',`v_address`='$v_address',`updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `v_id`='$vendor_id'";
            $update_query=$mysqli->query($vendor_update);
            $_SESSION['success'] = "Vendor Details Updated Successfully!";
            header("Location: vendors.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            header("Location: addVendors.php?vendorid=$vendor_id");
            exit();
        } 
    }
    //bank add
    elseif (isset($_POST["bank_add"])) { 
            $bank_name = $mysqli->real_escape_string(trim($_POST['bank_name']));
            $account_holder=$mysqli->real_escape_string(trim($_POST['account_holder']));
            $account_no = trim($_POST['account_no']);
            $upi_id = trim($_POST['upi_id']);
            $ifsc_code=trim($_POST['ifsc_code']);
            $app_status = trim($_POST['app_status'])== '' || NULL ? 1 : trim($_POST['app_status']);

            try{
                $fields="`cos_id`,`account_holder`,`bank_name`,`account_no`,`upi_id`,`ifsc_code`,`app_status`,`created_by`,`platform`";
                $values="'$cos_id','$account_holder','$bank_name','$account_no','$upi_id','$ifsc_code','$app_status','$created_by','$platform'";              
                $bank_insert="INSERT INTO  `e_bank_details` ($fields) VALUES ($values)"; 
                $insert_query=$mysqli->query($bank_insert);
                $_SESSION['success'] = "Bank Details Added Successfully!";
                header("Location: bankDetails.php");
                exit();
            }
            catch (mysqli_sql_exception $exception) {
                mysqli_rollback($mysqli);
                $insert_query = false;
                $err_msg=$exception->getMessage();
                $_SESSION['error_message'] = $err_msg;
                $_SESSION['form_data'] = [
                    'bank_name' => $bank_name,
                    'account_holder' => $account_holder,
                    'account_no' => $account_no,
                    'upi_id' => $upi_id,
                    'ifsc_code' => $ifsc_code,
                    'app_status' => $app_status
                ];        
                header("Location: addBank.php");
                exit();
            } 
    }
    // bank update
    elseif(isset($_POST['bank_update'])){
        $bank_id=trim($_POST['bankid']);
        $bank_name = $mysqli->real_escape_string(trim($_POST['bank_name']));
        $account_holder=$mysqli->real_escape_string(trim($_POST['account_holder']));
        $account_no = trim($_POST['account_no']);
        $upi_id = trim($_POST['upi_id']);
        $ifsc_code=trim($_POST['ifsc_code']);
        $app_status = trim($_POST['app_status'])== '' || NULL ? 1 : trim($_POST['app_status']);

        try{
            $bank_update="UPDATE `e_bank_details` SET `account_holder`='$account_holder', `bank_name`='$bank_name', `account_no`='$account_no', `upi_id`='$upi_id' ,`ifsc_code`='$ifsc_code' ,`app_status`='$app_status',`updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$bank_id'";
            $update_query=$mysqli->query($bank_update);
            header("Location: bankDetails.php");
            $_SESSION['success'] = "Bank Details Updated Successfully!";
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            header("Location: addBank.php?bankid=$bank_id");
            exit();
        } 
    }
    // Role add
    elseif (isset($_POST["role_add"])) { 
        $role_title = $mysqli->real_escape_string(trim($_POST['role_title']));
        $role_desc=$mysqli->real_escape_string(trim($_POST['role_desc']));
        $role_status = trim($_POST['role_status']);

        try{
            $fields="`cos_id`,`role_title`,`role_desc`,`active`,`created_by`,`platform`";
            $values="'$cos_id','$role_title','$role_desc','$role_status','$created_by','$platform'";              
            $role_insert="INSERT INTO  `e_salesman_role` ($fields) VALUES ($values)"; 
            $insert_query=$mysqli->query($role_insert);
            $_SESSION['success'] = "New Role Added Successfully!";
            header("Location: roles.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $insert_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            $_SESSION['old_role'] = $_POST;
            header("Location: addRole.php");
            exit();
        } 
    }
    // role update
    elseif(isset($_POST['role_update'])){
        $role_id = $_POST['roleid'];
        $role_title = $mysqli->real_escape_string(trim($_POST['role_title']));
        $role_desc=$mysqli->real_escape_string(trim($_POST['role_desc']));
        $role_status = trim($_POST['role_status'])== '' || NULL ? 1 : trim($_POST['role_status']);

        try{
            $role_update="UPDATE `e_salesman_role` SET `role_title`='$role_title', `role_desc`='$role_desc',`active`='$role_status',
            `updated_by`='$updated_by', `up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$role_id'";
            $update_query=$mysqli->query($role_update);
            header("Location: roles.php");
            $_SESSION['success'] = "Role Details Updated Successfully!";
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $insert_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            header("Location: addRole.php?roleid=$role_id");
            exit();
        } 
    }
    //Delivery Fee Details Update
    elseif(isset($_POST['city_delivery_update'])){
        if(isset($_FILES["city_img"])){
            $deliveryFee_id=trim($_POST['deliveryFeeid']);
            $city_title= $mysqli->real_escape_string(trim($_POST['city_title']));
            $d_charge= $mysqli->real_escape_string(trim($_POST['d_charge']));
            $min_ord_amount=$mysqli->real_escape_string(trim($_POST['min_ord_amount']));
            $d_message=$mysqli->real_escape_string(trim($_POST['d_message']));

          if(empty($_FILES["city_img"]["name"])){
              // $targetFile = "defaultimgs/nullimg.png";
              try{
                  $deliveryFee_update="UPDATE `e_data_city` SET  `title`='$city_title', `d_charge`='$d_charge' ,`disc_alert_msg`='$d_message',`min_amt`='$min_ord_amount', `updated_by`='$updated_by' ,`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$deliveryFee_id'";
                  $update_query=$mysqli->query($deliveryFee_update);
                  $_SESSION['success'] = "Delivery Fee  Details Updated Successfully!";
                  header("Location: deliveryFee.php");
                  exit();
              }
              catch (mysqli_sql_exception $exception) {
                  mysqli_rollback($mysqli);
                  $update_query = false;
                  $err_msg=$exception->getMessage();
                  $_SESSION['error_message'] = $err_msg;
                  header("Location: addDeliveryFee.php?deliveryFeeid=$deliveryFee_id");
                  exit();
              }
              
          }
          else{
              $targetDir = "../../city/";
       
              $sanitizedTitle = preg_replace('/[^A-Za-z0-9\-]/', '_', $coup_title);
              $imageFileType = strtolower(pathinfo($_FILES["city_img"]["name"], PATHINFO_EXTENSION));
  
              $newFileName = $sanitizedTitle . '_' .'city'. '_' . time() . '.' . $imageFileType;
              $targetFile = $targetDir . $newFileName;
              move_uploaded_file($_FILES["city_img"]["tmp_name"], $targetFile);
  
              $targetFile=substr($targetFile,6);
             
              if(strpos($targetFile, ".png") !== false || strpos($targetFile, ".jpg") !== false || strpos($targetFile, ".jpeg") !== false || strpos($targetFile, ".svg") !== false || strpos($targetFile, ".jfif") !== false || strpos($targetFile, ".avif") !== false || strpos($targetFile, ".webp") !== false){
                try{
                    $deliveryFee_update="UPDATE `e_data_city` SET  `title`='$city_title',`c_img`='$targetFile',`d_charge`='$d_charge',`disc_alert_msg`='$d_message',`min_amt`='$min_ord_amount', `updated_by`='$updated_by' ,`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$deliveryFee_id'";
                    $update_query=$mysqli->query($deliveryFee_update);
                    $_SESSION['success'] = "Delivery Fee Details Updated Successfully!";
                    header("Location: deliveryFee.php");
                    exit();
                }
                catch (mysqli_sql_exception $exception) {
                    mysqli_rollback($mysqli);
                    $update_query = false;
                    $err_msg=$exception->getMessage();
                    $_SESSION['error_message'] = $err_msg;
                    header("Location: addDeliveryFee.php?deliveryFeeid=$deliveryFee_id");
                    exit();
                }
              }
          }
          }
      }
    //reconcilation update
    elseif(isset($_POST['recon_submit'])){
        $order_id=trim($_POST['orderid']);
        $bank_trans_id=trim($_POST['bank_trans_id']);
        $upi_id=trim($_POST['upi_id']);
        $recon_status=trim($_POST['recon_status']) =='' || NULL ? 0 : trim($_POST['recon_status']);
        try{
            $recon_update="UPDATE `e_normal_order_details` SET `bank_trans_id`='$bank_trans_id', `upi_id`='$upi_id', `recon_status`='$recon_status',`updated_by`='$updated_by',`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `id`='$order_id'";
            $update_query=$mysqli->query($recon_update);
            header("Location: revenue.php");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query = false;
            $err_msg=$exception->getMessage();
            // echo $err_msg;
        }
    
    }
    //unit Insert
    elseif (isset($_POST["unit_add"])) {
        $unit_status = trim($_POST['unit_status']) =='' || NULL ? 1 : trim($_POST['unit_status']);
        $unit= $_POST['unit'];
          try{    
            $fields="`cos_id`,`unit`,`active`, `platform`";
            $values="'$cos_id','$unit','$unit_status','$platform'";
            $unit_insert="INSERT INTO  `e_unit_details` ($fields) VALUES ($values)";
            $insert_query=$mysqli->query($unit_insert);
            header("Location: unit.php");
            exit();
          }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $insert_query = false;
            $err_msg=$exception->getMessage();
            echo $err_msg;
            $_SESSION['old_unit'] = $_POST;
            header("Location: addUnit.php");
        }
      }
  
  //Unit Update
  
  elseif(isset($_POST['unit_update'])){
      $unit_id=$_POST['unitid'];
      $unit = $_POST['unit'];
      $unit_status = trim($_POST['unit_status']) =='' || NULL ? 1 : trim($_POST['unit_status']);
     
          try{
              $unit_update="UPDATE `e_unit_details` SET `unit`='$unit', 
              `active`='$unit_status', `up_platform`='$platform' 
              WHERE `cos_id`= '$cos_id' AND `id`='$unit_id'";
              $update_query=$mysqli->query($unit_update);
              header("Location: unit.php");
              exit();
          }
          catch (mysqli_sql_exception $exception) {
              mysqli_rollback($mysqli);
              $update_query = false;
              $err_msg=$exception->getMessage();
              echo $err_msg;
          }
  }
    //Profile active Status Update
    elseif (isset($_POST['profile_id'])) {
        $id = trim($_POST['profile_id']);
        $business_status = isset($_POST['business_status']) ? 1 : 0;

        $deactivate_query = "UPDATE e_data_profile 
                        SET `active` = 0 ,`updated_by`='$updated_by',`up_platform`='$platform' 
                        WHERE `cos_id` = '$cos_id' AND `active` = 1";

        // echo $deactivate_query;
    
        $activate_query = "UPDATE e_data_profile 
                       SET `active` = '$business_status',`updated_by`='$updated_by',`up_platform`='$platform' ,`updated_ts` = NOW() 
                       WHERE `cos_id` = '$cos_id' AND `id` = '$id'";

        // echo $activate_query;

        $deactivate_result = $mysqli->query($deactivate_query);
        $activate_result = $mysqli->query($activate_query);

        if (!$deactivate_result || !$activate_result) {
            error_log("Failed to update active status with id $id: " . $mysqli->error);
        }
        $_SESSION['success'] = "Profile Active Status Updated Successfully!";
        header("Location: profile.php");
        exit();
    }    

    //Feedback Msg 

    elseif (isset($_POST['msg_send'])) {
       $msg=$_POST['msg'];
       $receiver_id=$_POST['receiver_id'];
       $sender_id=$_POST['sender_id'];
        
        try{
            $fields="`cos_id`,`sender_id`,`receiver_id`,`message`,`is_read`,`active`,`created_by`,`platform`";
            $values="'$cos_id','$receiver_id','$sender_id','$msg','1','1','$created_by','$platform'";              
            $feedback_insert="INSERT INTO  `e_feedback` ($fields) VALUES ($values)"; 
            $insert_query=$mysqli->query($feedback_insert);
            header("Location: feedbackDetail.php?user-id=$sender_id&&receiver-id=$receiver_id");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $insert_query = false;
            $err_msg=$exception->getMessage();
            $_SESSION['error_message'] = $err_msg;
            header("Location: feedbackDetail.php?user-id=$sender_id&&receiver-id=$receiver_id");
            exit();
        } 
    }
}

if(isset($_GET['user-id']) && isset($_GET['receiver-id'])){

        $sender_id=$_GET['user-id'];
        $receiver_id =$_GET['receiver-id'];

       
        try{
            $read_update_query ="UPDATE `e_feedback` SET is_read=1 
            WHERE is_read=0 
            AND sender_id='$sender_id'  
            AND active= 1 AND cos_id='$cos_id'";
            // echo $read_update_query;
            $update_query=$mysqli->query($read_update_query);
            header("Location: feedbackDetail.php?user-id=$sender_id&&receiver-id=$receiver_id");
            exit();
        }
        catch (mysqli_sql_exception $exception) {
            mysqli_rollback($mysqli);
            $update_query = false;
            $err_msg=$exception->getMessage();
            echo $err_msg;
        }  
}

// //delete Combo product

// if(isset($_GET['product_cid']) && isset($_GET['combo_id'])){
//     $prod_combo_id=$_GET['product_cid'];
//     $combo_id=$_GET['combo_id'];
//     // echo $prod_combo_id."".$combo_id;
//     $combo_prod_delete = "UPDATE `e_product_collection_map` SET `active`=0 ,`updated_by`='$updated_by',`up_platform`='$platform'   WHERE  `prod_id`='$prod_combo_id' AND `c_id`='$combo_id' AND `cos_id`='$cos_id'";
//     // echo $combo_prod_delete;
//     $delete_query = $mysqli->query($combo_prod_delete);
//     if (!$delete_query) {
//         error_log("Failed to update combo product with id $prod_combo_id: " . $mysqli->error);
//     }
//     header("Location: addCombo.php?comboid=$combo_id");
//     exit();
// }

//delete Queries....

try{   
    if (isset($_GET['b_dids'])) {
        $b_dids = $_GET['b_dids'];
        $b_dids_array = explode(',', $b_dids);
    
        foreach ($b_dids_array as $b_did) {
            $b_id = $mysqli->real_escape_string($b_did);

            $banner_delete = "UPDATE `e_dat_banner` SET `active`=2,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$b_did'";
            $delete_query = $mysqli->query($banner_delete);
            if (!$delete_query) {
                error_log("Failed to update banner with id $b_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Banner Details Deleted Successfully!";
        header("Location: banners.php");
        exit();
    }
    //coupon delete
    elseif(isset($_GET['coup_dids'])){
        $coup_dids = $_GET['coup_dids'];
        $coup_dids_array = explode(',', $coup_dids);

        foreach ($coup_dids_array as $coup_did) {
            $coup_did = $mysqli->real_escape_string($coup_did);

            $coupon_delete="UPDATE `e_data_coupon` SET `active`=2,`updated_by`='$updated_by',`up_platform`='$platform'   WHERE cos_id='$cos_id' AND id='$coup_did'";
            $delete_query=$mysqli->query($coupon_delete);
            if (!$delete_query) {
                error_log("Failed to update Coupon with id $coup_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Coupon Details Deleted Successfully!";
        header("Location: coupons.php");
        exit();
    }
    //expense delete
    elseif(isset($_GET['exp_dids'])){
        $exp_dids = $_GET['exp_dids'];
        $exp_dids_array = explode(',', $exp_dids);

        foreach ($exp_dids_array as $exp_did) {
            $exp_did = $mysqli->real_escape_string($exp_did);

            $expense_delete="UPDATE `e_expense_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND exp_id='$exp_did'";
            $delete_query=$mysqli->query($expense_delete);
            if (!$delete_query) {
                error_log("Failed to update expense with id $exp_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Expense Details Deleted Successfully!";
        header("Location: expense.php");
        exit();
    }

    //Employee delete

    elseif (isset($_GET['emp_dids'])) {
        $emp_dids = $_GET['emp_dids'];
        $emp_ids_array = explode(',', $emp_dids);
    
        foreach ($emp_ids_array as $emp_id) {
            $emp_id = $mysqli->real_escape_string($emp_id);
    
            $employee_delete = "UPDATE `e_salesman_details` SET `active`=2,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$emp_id'";
            $delete_query = $mysqli->query($employee_delete);
            if (!$delete_query) {
                error_log("Failed to update employee with id $emp_id: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Employee Details Deleted Successfully!";
        header("Location: employee.php");
        exit();
    }
    elseif(isset($_GET['delivery_dids'])){
        $delivery_dids = $_GET['delivery_dids'];
        $delivery_dids_array = explode(',', $delivery_dids);
        foreach ($delivery_dids_array as $delivery_did) {
            $delivery_did = $mysqli->real_escape_string($delivery_did);
    
            $delivery_delete="UPDATE `e_salesman_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$delivery_did'";
            $delete_query=$mysqli->query($delivery_delete);
            if (!$delete_query) {
                error_log("Failed to update Delivery Person with id $delivery_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Delivery Person Details Deleted Successfully!";
        header("Location: deliveryPerson.php");
        exit();
    }
    elseif(isset($_GET['cust_dids'])){
        $cust_dids = $_GET['cust_dids'];
        $cust_dids_array = explode(',', $cust_dids);
        foreach ($cust_dids_array as $cust_did) {
            $cust_did = $mysqli->real_escape_string($cust_did);
    
            $customer_delete="UPDATE `e_user_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$cust_did'";
            $delete_query1=$mysqli->query($customer_delete);
            $address_delete="UPDATE `e_address_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE `cos_id`= '$cos_id' AND `user_id`='$cust_did'";
            $delete_query2=$mysqli->query($address_delete);
            if (!$delete_query1 || !$delete_query2) {
                error_log("Failed to update Customer with id $cust_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Customer Details Deleted Successfully!";
        header("Location: customers.php");
        exit();
    }
    elseif(isset($_GET['cat_dids'])){
        $cat_dids = $_GET['cat_dids'];
        $cat_dids_array = explode(',', $cat_dids);
        foreach ($cat_dids_array as $cat_did) {
            $cat_did = $mysqli->real_escape_string($cat_did);
    
            $category_delete="UPDATE `e_category_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$cat_did'";
            $delete_query=$mysqli->query($category_delete);
        
            if (!$delete_query) {
                error_log("Failed to update Customer with id $cat_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Category Details Deleted Successfully!";
        header("Location: category.php");
        exit();
    }
    elseif(isset($_GET['subcat_dids'])){
        $subcat_dids = $_GET['subcat_dids'];
        $subcat_dids_array = explode(',', $subcat_dids);
        foreach ($subcat_dids_array as $subcat_did) {
            $subcat_did = $mysqli->real_escape_string($subcat_did);
    
            $subcategory_delete="UPDATE `e_subcategory_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$subcat_did'";
            $delete_query=$mysqli->query($subcategory_delete);
        
            if (!$delete_query) {
                error_log("Failed to update Customer with id $subcat_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Subcategory Details Deleted Successfully!";
        header("Location: subcategory.php");
        exit();
    }
    elseif(isset($_GET['vendor_dids'])){
        $vendor_dids = $_GET['vendor_dids'];
        $vendor_dids_array = explode(',', $vendor_dids);
        foreach ($vendor_dids_array as $vendor_did) {
            $vendor_did = $mysqli->real_escape_string($vendor_did);
    
            $vendor_delete="UPDATE `e_vendor_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform' WHERE cos_id='$cos_id' AND v_id='$vendor_did'";
            $delete_query1=$mysqli->query($vendor_delete);
            if (!$delete_query1) {
                error_log("Failed to update <?php echo $vendor; ?> with id $vendor_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Vendor Details Deleted Successfully!";
        header("Location: vendors.php");
        exit();
    }
    elseif(isset($_GET['timeslot_dids'])){
        $timeslot_dids = $_GET['timeslot_dids'];
        $timeslot_dids_array = explode(',', $timeslot_dids);
        foreach ($timeslot_dids_array as $timeslot_did) {
            $timeslot_did = $mysqli->real_escape_string($timeslot_did);
    
            $timeslot_delete="UPDATE `e_dat_timeslot` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$timeslot_did'";
            $delete_query1=$mysqli->query($timeslot_delete);
            if (!$delete_query1) {
                error_log("Failed to update timeslot with id $timeslot_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Timeslot Details Deleted Successfully!";
        header("Location: timeslot.php");
        exit();
    }
    elseif(isset($_GET['role_dids'])){
        $role_dids = $_GET['role_dids'];
        $role_dids_array = explode(',', $role_dids);
        foreach ($role_dids_array as $role_did) {
            $role_did = $mysqli->real_escape_string($role_did);
    
            $role_delete="UPDATE `e_salesman_role` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$role_did'";
            $delete_query1=$mysqli->query($role_delete);
            if (!$delete_query1) {
                error_log("Failed to update role with id $role_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Role Details Deleted Successfully!";
        header("Location: roles.php");
        exit();
    }
    elseif(isset($_GET['bank_dids'])){
        $bank_dids = $_GET['bank_dids'];
        $bank_dids_array = explode(',', $bank_dids);
        foreach ($bank_dids_array as $bank_did) {
            $bank_did = $mysqli->real_escape_string($bank_did);
    
            $bank_delete="UPDATE `e_bank_details` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$bank_did'";
            $delete_query1=$mysqli->query($bank_delete);
            if (!$delete_query1) {
                error_log("Failed to update <?php echo $vendor; ?> with id $bank_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Bank  Details Deleted Successfully!";
        header("Location: bankDetails.php");
        exit();
    }

    elseif(isset($_GET['unit_dids'])){
        $unit_dids = $_GET['unit_dids'];
        $unit_dids_array = explode(',', $unit_dids);
        foreach ($unit_dids_array as $unit_did) {
            $unit_did = $mysqli->real_escape_string($unit_did);
    
            $unit_delete="UPDATE `e_unit_details` SET `active`=2 ,`updated_by`='$updated_by',
            `up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$unit_did'";
            $delete_query1=$mysqli->query($unit_delete);
            if (!$delete_query1) {
                error_log("Failed to update unit with id $unit_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Unit  Details Deleted Successfully!";
        header("Location: unit.php");
        exit();
    }

    elseif (isset($_GET['product_dids'])) {
        $product_dids = $_GET['product_dids'];
        $product_ids_array = explode(',', $product_dids);
    
        $batch_ids_array = [];
        if (isset($_GET['prod_batch_dids'])) {
            $prod_batch_dids = $_GET['prod_batch_dids'];
            $batch_ids_array = explode(',', $prod_batch_dids);
        }
    
        foreach ($product_ids_array as $index => $product_id) {
            $product_id = $mysqli->real_escape_string($product_id);
    

            $product_delete = "UPDATE `e_product_details` SET `active`=2 ,`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$product_id'";
            $delete_query1 = $mysqli->query($product_delete);
            if (!$delete_query1) {
                error_log("Failed to update product with id $product_id: " . $mysqli->error);
            }

            if (isset($batch_ids_array[$index])) {
                $batch_id = $mysqli->real_escape_string($batch_ids_array[$index]);
    

                $stock_delete = "UPDATE `e_product_stock` SET `active`=2 ,`up_platform`='$platform' WHERE cos_id='$cos_id' AND s_product_id='$product_id' AND s_batch_no='$batch_id'";
                $delete_query2 = $mysqli->query($stock_delete);
                if (!$delete_query2) {
                    error_log("Failed to update stock for product id $product_id and batch id $batch_id: " . $mysqli->error);
                }

                $price_delete = "UPDATE `e_product_price` SET `active`=2 ,`up_platform`='$platform'  WHERE cos_id='$cos_id' AND product_id='$product_id' AND batch_no='$batch_id'";
                $delete_query3 = $mysqli->query($price_delete);
                if (!$delete_query3) {
                    error_log("Failed to update price for product id $product_id and batch id $batch_id: " . $mysqli->error);
                }
            }
        }
        $_SESSION['success'] = "Product Details Deleted Successfully!";
        header("Location: products.php");
        exit();
    }
    elseif (isset($_GET['stock_dids'])) {
        $stock_dids = $_GET['stock_dids'];
        $stock_ids_array = explode(',', $stock_dids);
    
        $batch_ids_array = [];
        if (isset($_GET['prod_batch_dids'])) {
            $prod_batch_dids = $_GET['prod_batch_dids'];
            $batch_ids_array = explode(',', $prod_batch_dids);
        }
    
        foreach ($stock_ids_array as $index => $stock_id) {
            $stock_id = $mysqli->real_escape_string($stock_id);


            if (isset($batch_ids_array[$index])) {
                $batch_id = $mysqli->real_escape_string($batch_ids_array[$index]);
    
                $stock_delete = "UPDATE `e_product_stock` SET `active`=2 ,`up_platform`='$platform'  WHERE cos_id='$cos_id' AND s_product_id='$stock_id' AND s_batch_no='$batch_id'";
                $delete_query2 = $mysqli->query($stock_delete);
                if (!$delete_query2) {
                    error_log("Failed to update stock for product id $stock_id and batch id $batch_id: " . $mysqli->error);
                }

                $price_delete = "UPDATE `e_product_price` SET `active`=2 ,`up_platform`='$platform'  WHERE cos_id='$cos_id' AND product_id='$stock_id' AND batch_no='$batch_id'";
                $delete_query3 = $mysqli->query($price_delete);
                if (!$delete_query3) {
                    error_log("Failed to update price for product id $stock_id and batch id $batch_id: " . $mysqli->error);
                }
            }
        }
        $_SESSION['success'] = "Stock Deleted Successfully!";
        header("Location: product_report.php");
        exit();
    }
    elseif (isset($_GET['combo_dids'])) {
        $combo_dids = $_GET['combo_dids'];
        $combo_dids_array = explode(',', $combo_dids);
    
        $batch_ids_array = [];
        if (isset($_GET['prod_batch_dids'])) {
            $prod_batch_dids = $_GET['prod_batch_dids'];
            $batch_ids_array = explode(',', $prod_batch_dids);
        }
    
        foreach ($combo_dids_array as $index => $combo_id) {
            $combo_id = $mysqli->real_escape_string($combo_id);
    

            $combo_delete = "UPDATE `e_data_collection` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$combo_id'";
            // echo $combo_delete;
            $delete_query1 = $mysqli->query($combo_delete);
            if (!$delete_query1) {
                error_log("Failed to update combo with id $combo_id: " . $mysqli->error);
            }
            $combo_prod_delete = "UPDATE `e_product_collection_map` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'   WHERE cos_id='$cos_id' AND c_id='$combo_id'";
            // echo $combo_prod_delete;
            $delete_query2 = $mysqli->query($combo_prod_delete);
            if (!$delete_query2) {
                error_log("Failed to update stock for product id $product_id and batch id $batch_id: " . $mysqli->error);
            }
            if (isset($batch_ids_array[$index])) {
                $batch_id = $mysqli->real_escape_string($batch_ids_array[$index]);
                $product_delete = "UPDATE `e_product_details` SET `active`=2,`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$batch_id'";
                // echo $product_delete;
                $delete_query3 = $mysqli->query($product_delete);
                if (!$delete_query3) {
                    error_log("Failed to update  product id $batch_id: " . $mysqli->error);
                }
            }
        }
        $_SESSION['success'] = "Combo Details Deleted Successfully!";
        header("Location: combo.php");
        exit();
    }
    elseif(isset($_GET['profile_dids'])){
        $profile_dids = $_GET['profile_dids'];
        $profile_dids_array = explode(',', $profile_dids);

        foreach ($profile_dids_array as $profile_did) {
            $profile_did = $mysqli->real_escape_string($profile_did);

            $profile_delete="UPDATE `e_data_profile` SET `active`=2 ,`updated_by`='$updated_by',`up_platform`='$platform'  WHERE cos_id='$cos_id' AND id='$profile_did'";
            $delete_query=$mysqli->query($profile_delete);
            if (!$delete_query) {
                error_log("Failed to update profile with id $profile_did: " . $mysqli->error);
            }
        }
        $_SESSION['success'] = "Profile Details Deleted Successfully!";
        header("Location: profile.php");
        exit();
    }
}
catch (mysqli_sql_exception $exception) {
    mysqli_rollback($mysqli);
    $delete_query = false;
    $delete_query1 = false;
    $delete_query2 = false;
    $delete_query3 = false;
    $err_msg=$exception->getMessage();
    // echo $err_msg;
}


//Order Status Update

if (isset($_GET['dsid']) && isset($_GET['status'])) {
    $status = intval($_GET['status']);
    $currentStatus = $_GET['currentStatus'];
    $dsid = intval($_GET['dsid']);

    $salesmanId = isset($_GET['salesman_id']) ? intval($_GET['salesman_id']) : null; 
    $toastMessages = [
        1 => 'Packager Assigned Successfully!!',
        2 => 'Employee Updated Successfully!!',
        3 => 'Delivery Person Assigned Successfully!!',
        4 => 'Employee Updated Successfully!!',
        5 => 'Order Completion Successful!!'
    ];

    $statusUpdates = [
        1 => ['active' => 2, 'status' => 'Processing'],
        2 => ['active' => 3, 'status' => 'Packed'],
        3 => ['active' => 4, 'status' => 'Delivery Person Assigned'],
        4 => ['active' => 5, 'status' => 'Out For Delivery'],
        5 => ['active' => 6, 'status' => 'Completed']
    ];

    if (array_key_exists($status, $statusUpdates)) {
        $update = $statusUpdates[$status];
        
       if ($currentStatus === 'pending') {
            $query = $mysqli->query("
                UPDATE `e_normal_order_details` 
                SET  
                    `salesman_id` = '$salesmanId',
                    `active` = {$update['active']}, 
                    `status` = '{$update['status']}',
                    `updated_by` = '$updated_by', 
                    `up_platform` = '$platform' 
                WHERE 
                    `cos_id` = '$cos_id' 
                    AND `id` = $dsid
            ");
        }
        else if($currentStatus === 'packed'){
            $query = $mysqli->query("
                UPDATE `e_normal_order_details` 
                SET  
                    `salesman_id` = '$salesmanId',
                    `active` = {$update['active']}, 
                    `status` = '{$update['status']}', 
                    `updated_by` = '$updated_by', 
                    `up_platform` = '$platform' 
                WHERE 
                    `cos_id` = '$cos_id' 
                    AND `id` = $dsid
            ");
        }
        else if ($currentStatus === 'db_assigned') {
            $query = $mysqli->query("
                UPDATE `e_normal_order_details` 
                SET  
                    `salesman_id` = '$salesmanId',
                    `updated_by` = '$updated_by', 
                    `up_platform` = '$platform' 
                WHERE 
                    `cos_id` = '$cos_id' 
                    AND `id` = $dsid
            ");
        } 
        else if ($currentStatus === 'processing') {
            $query = $mysqli->query("
                UPDATE `e_normal_order_details` 
                SET  
                    `salesman_id` = '$salesmanId',
                    `updated_by` = '$updated_by', 
                    `up_platform` = '$platform' 
                WHERE 
                    `cos_id` = '$cos_id' 
                    AND `id` = $dsid
            ");
        }
        else {
            $query = $mysqli->query("
                UPDATE `e_normal_order_details` 
                SET 
                    `active` = {$update['active']}, 
                    `status` = '{$update['status']}', 
                    `updated_by` = '$updated_by', 
                    `up_platform` = '$platform' 
                WHERE 
                    `cos_id` = '$cos_id' 
                    AND `id` = $dsid
            ");
        }

         
    }
    
    if (array_key_exists($status, $toastMessages)) {

        // if($currentStatus === 'processing' || $currentStatus === 'db_assigned'){
        //     $_SESSION['toast'] = [
        //         'type' => 'success',
        //         'title' => 'Decision Section!!',
        //         'message' => 'Employee Updated Successfully!!'
        //    ];
        // }else{
        //     $_SESSION['toast'] = [
        //         'type' => 'success',
        //         'title' => 'Decision Section!!',
        //         'message' => $toastMessages[$status]
        //    ];
        // }     
        
        $_SESSION['toast'] = [
            'type' => 'success',
            'title' => 'Decision Section!!',
            'message' => $toastMessages[$status]
        ];
    }

     header("Location: orders.php?currentStatus=$currentStatus");
     exit();
}

  $mysqli -> rollback();
?>
