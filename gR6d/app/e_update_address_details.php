<?php 
include_once '../api/config.php';
include_once '../api/function.php';
$data = json_decode(file_get_contents('php://input'), true);
$platform  = $data['platform'];
ini_set('display_errors', 1); ini_set('display_startup_errors', 1); error_reporting(E_ALL);

if ($data['u_id'] == '') {
    $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Something Went Wrong!");
} else {
    $u_id = $data['u_id'];
    $address = $data['address'];
    $pincode = $data['pincode'];
    $city = $data['city'];
    $state = $data['state'];
    $houseno = $data['houseno'];
    $landmark = $data['landmark'];
    $type = $data['type'];
    $lat_map = $data['lat_map'];
    $long_map = $data['long_map'];
    $aid = $data['aid'];
    $name = $data['name'];
    $mobile = $data['mobile'];
    
    $count = $mysqli->query("SELECT * FROM e_user_details WHERE active = 1 AND id = $u_id AND cos_id = '$cos_id'")->num_rows;

    if ($count != 0) {
        if ($aid != 0) {
            // Check delivery location
            $pincheck = $mysqli->query("SELECT * FROM e_data_city WHERE Replace(coalesce(title, ''), ' ', '') = '" . str_replace(' ', '', strtolower($data['city'])) . "' 
                OR Replace(coalesce(title, ''), ' ', '') = '" . str_replace(' ', '', strtolower($data['state'])) . "' 
                OR title = 'Chennai' AND cos_id = '$cos_id'")->num_rows;

            if ($pincheck != 0) {
                // Update address details
                $table = "e_address_details";
                $field = array(
                    'area' => $address,
                    'pincode' => $pincode,
                    'city' => $city,
                    'address_line_1' => $houseno,
                    'landmark' => $landmark,
                    'type' => $type,
                    'lat' => $lat_map,
                    'lng' => $long_map,
                    'name' => $name,
                    'mobile' => $mobile,
                    'state' => $state,
                    'up_platform' => $platform
                );
                $where = "WHERE id = $aid AND cos_id = '$cos_id'";
                
                $h = new CommonFunction();
                $check = $h->Ins_update_Api($field, $table, $where);
                
                // Fetch updated address data
                $adata = $mysqli->query("SELECT * FROM e_address_details WHERE id = $aid AND cos_id = '$cos_id'")->fetch_assoc();
                $p = array();
                $p['id'] = $adata['id'];
                $p['u_id'] = $adata['user_id'];
                $p['hno'] = $adata['address_line_1'];
                $p['address'] = $adata['area'];
                $p['lat_map'] = $adata['lat'];
                $p['long_map'] = $adata['lng'];
                $p['pincode'] = $adata['pincode'];
                $p['state'] = $adata['state'];
                $p['city'] = $adata['city'];
                $p['landmark'] = $adata['landmark'];
                $p['type'] = $adata['type'];
                $p['name'] = $adata['name'];
                $p['mobile'] = $adata['mobile'];
                
                $returnArr = array("AddressData" => $p, "ResponseCode" => "200", "Result" => "true", "ResponseMsg" => "Address Updated Successfully!!!");
            } else {
                $returnArr = array("ResponseCode" => "200", "Result" => "false", "ResponseMsg" => "Not Deliver On This Location!!!");
            }
        } else {
            $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "Address ID (aid) is missing or invalid for updating.");
        }
    } else {
        $returnArr = array("ResponseCode" => "401", "Result" => "false", "ResponseMsg" => "User Either Not Exists OR Deactivated From Admin!");
    }
}
echo json_encode($returnArr);
?>
