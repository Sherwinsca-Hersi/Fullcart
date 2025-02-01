<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Profile</title>
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
    <div class="coupon_rightbar container">
        <h2>Business Profile</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Business Name, Contact,Email.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addBanner_sect">
                <a href="addProfile.php" class="export_btn employee_link">Add Profile</a>
                <!-- <button type="button" class="export_btn"  onclick="exportData()" id="exportButton">Export</button> -->
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
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="1,4,5">
    <thead class="table_head">
        <tr>
            <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
            <th>Action</th>
            <th>Business Name</th>
            <th>Logo</th>
            <th>Contact 1</th>
            <th>Contact 2</th>
            <th>Email-Id</th>
            <th>GSTIN</th>
            <th>Address</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $i=1;
        foreach($profile_details as $profile_detail):
        ?>
            <tr class="<?php echo ($i % 2 === 0) ? 'teven' : 'todd'; ?>" onclick="redirect(this, <?php echo $profile_detail['id'];?>)">
            <!-- <tr class="<?php echo ($i % 2 === 0) ? 'teven' : 'todd'; ?>"> -->
                <!-- <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $profile_detail['id']?>"?>">&emsp;<?php echo $i; ?></td> -->
                <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox" data-id="<?php echo $profile_detail['id']?>">&emsp;<?php echo $i; ?></td>
                <td><form action="com_ins_upd.php" method="post" id="profile_form">
                    <input type="checkbox" name="business_status" id="profile_input" <?php echo $profile_detail['active']==1 ? 'checked':'';?>>
                    <input type="hidden" name="profile_id" value="<?php echo $profile_detail['id'];?>">
                </form></td>
                <td><?php echo $profile_detail['business_name'] ?? 'N/A'; ?></td>
                <td><img src='../<?php echo $profile_detail['logo_img']; ?>'  width="60px" height="60px"></td>
                <td><?php echo $profile_detail['mobile_1']; ?></td>
                <td><?php echo $profile_detail['mobile_2'] ?? 'N/A'; ?></td>
                <td><?php echo $profile_detail['email_id'] ?? 'N/A'; ?></td>
                <td><?php echo $profile_detail['gst_no'] ?? 'N/A'; ?></td>
                <td><?php echo $profile_detail['address'] ?? 'N/A'; ?></td>  
                <td><?php if($profile_detail['active']==1){
                    echo "Active";
                }elseif($profile_detail['active']==0){
                    echo "Inactive";
                }
                ?></td>
                    
            </tr>
        <?php
        $i++;
        endforeach;
        ?>
    </tbody>
</table>
        <!-- <div class="profile_card">
            <div class="logo_img"><img src="..\assets\images\logo.svg" alt="logo-img"></div>
            <div class="profile_details">
                <div class="detail_head">
                    <div><h3>Bussiness Name:</h3></div>
                    <div><h3>Bussiness Address:</h3></div>
                    <div><h3>Bussiness Email-ID:</h3></div>
                    <div><h3>Bussiness Contact1:</h3></div>
                    <div><h3>Bussiness Contact2:</h3></div>
                    <div><h3>GSTIN:</h3></div>
                </div>
                <div>
                    <div><h3>Bussiness Name:fgbjkhfjdhgjfdh</h3></div>
                    <div><h3>Bussiness Name:dshfgdhsgfdhsgfhdsgfj</h3></div>
                    <div><h3>Bussiness Name:dhfghdgfhdgfhjdsgh</h3></div>
                    <div><h3>Bussiness Name:dsfhjdhsjshjhdj</h3></div>
                    <div><h3>Bussiness Name:dsjfjhjhdjhjdhj</h3></div>
                    <div><h3>Bussiness Name:hfdbvhfdhhjdfhfdfhghf</h3></div>
                </div>
            </div>
        </div> -->
    </div>
<div>
    <?php
        require_once "logoutpopup.php";
    ?>
</div>
<!-- <div class="popup" id="popup">
    <h4>All unsaved changes will be lost.</h4>
    <div class="popup_btns">
        <button class="price_btn">Price</button>
        <button class="stock_btn">Stock</button>
        <button class="popup_cancel" id="cancel_btn">Cancel</button>
    </div>
</div> -->
    
<div class="popup" id="delete_popup">
    <h4>Are you really want to delete this?</h4>
    <div class="popup_btns">
        <button class="yes_btn" id="delete_icon">Yes</button>
        <button class="popup_cancel" id="del_cancel_btn">Cancel</button>
    </div>
</div> 
<script>

editIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `addProfile.php?profileid=${checkedRowIds.join(',')}`;
    window.location.href = url;
    console.log(url)
    } else {
    alert('Please select  one row to edit.');
  }
});

const deleteIcon=document.getElementById('delete_icon');
deleteIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `com_ins_upd.php?profile_dids=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select at least one row to delete.');
  }
});
</script>
<script>
document.querySelectorAll('input[name="business_status"]').forEach(function(checkbox) {
  checkbox.addEventListener("change", function() {
    this.value = this.checked ? "1" : "0";
    this.closest("form").submit();
    
    this.closest("form").addEventListener("submit", function() {
      setTimeout(function() {
        window.location.reload();
      }, 1000);
    });
  });
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

     </script>
          <script>
function redirect(row, profileId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addProfile.php?profileid=' + profileId;
    }
}

document.querySelectorAll('input[type="checkbox"]').forEach(function(checkbox) {
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });
});
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>