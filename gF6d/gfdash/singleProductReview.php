<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Reviews</title>
    <?php 
        require_once '../api/header.php';
    ?>
    <!--iziToast-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">

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
    <div class="banner_rightbar container">
        <h2>Product Reviews</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Product Name,Customer.." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addBanner_sect">
                <button type="button" class="export_btn" onclick="exportData()">Export</button>
            </div>
        </div>
        <div class="employee_action">
            <div class="action_sect">
                <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon" alt="delete-icon-img">     
                <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon" alt="edit-icon-img"> 
            </div> 
        </div>
        
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="0,2">
                    <thead class="table_head">
                        <th>S.No</th>
                        <th>Product Name</th>
                        <th>Customer Name</th>
                        <th>Rating</th>
                        <th> Customer Review</th>
                    </thead>
                    <?php
                    $review_query=$mysqli->query("SELECT `id`, `cos_id`, `product_id`, `user_id`,
                        `rating`, `comment`,`added_on`, `updated_on`, `active` 
                        FROM `e_products_rating` WHERE product_id=".$_GET['review_prod_id']." AND active=1 AND cos_id='$cos_id'
                         ORDER BY id DESC");

                    $review_prod_details=[];
                    while($review_details=$review_query->fetch_assoc()){
                        $review_prod_details[]=$review_details;
                    }
                    $i=1;
                    foreach($review_prod_details as $review_prod_detail):
                    ?>
                    <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>">
                        <td><?php echo $i; ?></td>
                        <td><?php 
                            $product_query=$mysqli->query("SELECT id,p_title FROM `e_product_details` WHERE id=".$review_prod_detail['product_id']." AND active=1 AND cos_id='$cos_id'")->fetch_assoc();
                            echo $product_query['p_title'] ?? 'N/A'; 
                        ?></td>
                        <td><?php 
                            $user_query=$mysqli->query("SELECT id,name FROM `e_user_details` WHERE id=".$review_prod_detail['user_id']." AND active=1 AND cos_id='$cos_id'")->fetch_assoc();
                            echo $user_query['name'] ?? 'N/A'; 
                        ?></td>
                        <td>
                            <div class="star-display"  id="star-display-<?php echo $i; ?>"></div>
                            <!-- Star Ratings -->
                            <script>
                                (function() {
                                    const rating = <?php echo $review_prod_detail['rating']; ?>;
                                    const starContainer = document.getElementById('star-display-<?php echo $i; ?>');
                                    const totalStars = 5;

                                    for (let j = 1; j <= totalStars; j++) {
                                        const star = document.createElement('span');
                                        star.classList.add('star_rating');

                                        if (j <= Math.floor(rating)) {
                                            star.classList.add('filled');
                                        } else if (j - rating <= 0.5) {
                                            star.classList.add('half-filled');
                                        }

                                        star.innerHTML = '&#9733;';
                                        starContainer.appendChild(star);
                                    }
                                })();
                            </script>
                        </td>
                        <td><?php echo $review_prod_detail['comment'] ?? 'N/A'; ?></td>
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
    const url = `addRole.php?roleid=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select  one row to edit.');
  }
});

const deleteIcon=document.getElementById('delete_icon');
deleteIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `com_ins_upd.php?role_dids=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select at least one row to delete.');
  }
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
<script>
        
        const fetchedData = [
            <?php
            $export_query=$mysqli->query("SELECT id,role_title,role_desc,active FROM `e_salesman_role`
WHERE active=1 AND cos_id = '$cos_id' ORDER BY id DESC");

     
            $role_details= [];
            while ($role_export = $export_query->fetch_assoc()){
                $role_details[] = $role_export;
            }
            foreach ($role_details as $row) {
                echo "['" . implode("','", array_map('addslashes', $row)) . "'],";
            }
            ?>
        ];

        
        function exportData() {
        const headers = ["Role Name", "Role Description", "Role Status"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Role Data");

           
            let wbout = XLSX.write(wb, { bookType: 'xlsx', type: 'binary' });

            function s2ab(s) {
                let buf = new ArrayBuffer(s.length);
                let view = new Uint8Array(buf);
                for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
                return buf;
            }

            
            let fileName = "role_data.xlsx";
            saveAs(new Blob([s2ab(wbout)], { type: "application/octet-stream" }), fileName);
        }
    </script>
<script>
function redirect(row, roleId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addRole.php?roleid=' + roleId;
    }
}
</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>