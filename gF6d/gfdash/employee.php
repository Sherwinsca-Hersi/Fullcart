<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employees/Roles</title>
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
    <div class="employee_rightbar container">
        <h2>Employees/Roles</h2>
        <div class="searchbar_sect">
            <div class="search_div">
                <input type="text" name="search_input" placeholder="Search Employee/Role Details..." id="customSearchBox" class="searchInput">  
            </div>
            <div class="addEmployee_sect">
                <a href="addEmployee.php" class="export_btn employee_link">Add Employee/Role</a>
                <button type="button" class="export_btn" onclick="exportData()">Export</button>
            </div>
        </div>
        <div class="employee_action">
            <div class="action_sect">
                <img src="..\assets\images\delete_icon.png" width="35px" height="35px" class="delete_icon" alt="delete-icon-img">     
                <img src="..\assets\images\edit_icon.png" width="30px" height="30px" id="editIcon" alt="edit-icon-img"> 
            </div> 
        </div>
        
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display" id="example" data-disablesortingcolumns="2,5">
                    <thead class="table_head">
                        <th><input type="checkbox" class="check_value checkbox_thead" name="checkbox">&emsp;S.No</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone Number</th>
                        <th>Whatsapp</th>
                        <th>Password</th>
                        <th>Address</th>
                        <th>Position/Role</th>
                        <th>Other Roles</th>
                        <th>Joining Date</th>
                        <th>Salary(₹)</th>
                        <th>Bonus(₹)</th>
                    </thead>
                    <?php
                    $i=1;
                    foreach($employee_details as $employee_detail):
                    ?>
                    <tr class="<?php echo ($i % 2 === 0)? 'teven' : 'todd';?>" onclick="redirect(this, <?php echo $employee_detail['id'];?>)">
                        <td><input type="checkbox" class="check_value checkbox_tdef" name="checkbox"  data-id="<?php echo $employee_detail['id'];?>">&emsp;<?php  echo $i; ?></td>
                        <td><?php  echo $employee_detail['s_name'];?></td>
                        <td><?php  echo $employee_detail['email'];?></td>
                        <td><?php  echo $employee_detail['s_mobile'];?></td>
                        <td><?php  echo $employee_detail['whatsapp'];?></td>
                        <td><?php  echo $employee_detail['password'];?></td>
                        <td><?php  echo $employee_detail['s_address'] ==''|| NULL ? 'N/A' : $employee_detail['s_address'];?></td>
                        <?php
                            $role_id = $employee_detail['role'] ?? 0;
                            $role_query = "SELECT role_title FROM `e_salesman_role` WHERE id = '$role_id' AND active != 2 AND cos_id='$cos_id'";

                            $stmt = $mysqli->query($role_query);

                            if ($stmt && $stmt->num_rows > 0) {
                                $role_data = $stmt->fetch_assoc();
                                $role_title = $role_data['role_title'] ?? 'N/A';
                            } else {
                                $role_title = 'N/A'; 
                            }
                            $role_display = $role_title;
                        ?>
                        <td><?php echo htmlspecialchars($role_display); ?></td>
                        <td>
                        <?php
                            if (!empty($employee_detail['other_roles'])) {
                                 $selected_roles = explode(',', $employee_detail['other_roles']);
                            } else {
                                $selected_roles = [];
                            }

                            $role_names = [];

                            if (!empty($selected_roles)) {
                                $role_ids = implode(',', array_map('intval', $selected_roles));

                                $role_query = "SELECT role_title FROM e_salesman_role WHERE id IN ($role_ids) AND active != 2";
                                $result = $mysqli->query($role_query);

                                while ($row = $result->fetch_assoc()) {
                                    $role_names[] = $row['role_title'];
                                }
                            }

                            if (!empty($role_names)) {
                                echo htmlspecialchars(implode(', ', $role_names));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </td>
                        <td data-sort='<?php echo date_format(date_create($employee_detail['joining_date']), "Ymd"); ?>'>
                            <?php 
                                $date = date_create($employee_detail['joining_date']);
                                echo date_format($date, "d/m/Y");
                            ?>
                        </td>
                        <td><?php  echo $employee_detail['salary'];?></td>
                        <td><?php  echo $employee_detail['bonus'];?></td>
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
    const url = `addEmployee.php?employeeid=${checkedRowIds.join(',')}`;
    window.location.href = url;
    } else {
    alert('Please select one row to edit.');
  }
});

const deleteIcon=document.getElementById('delete_icon');
deleteIcon.addEventListener('click', function(){
    const { checkedRowIds, selectedBatchValues } = displayCheck();
    if (checkedRowIds.length > 0) {
    const url = `com_ins_upd.php?emp_dids=${checkedRowIds.join(',')}`;
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
            $export_query=$mysqli->query("SELECT s_name,s_mobile,email,s_address,salary,joining_date,whatsapp,bonus FROM `e_salesman_details` where cos_id = '$cos_id' and active=1");

     
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
        const headers = ["Employee Name", "Mobile No", "Email", "Address", "Salary", "Joining Date", "Whatsapp","Bonus"];
        
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
            
            XLSX.utils.book_append_sheet(wb, ws, "Employee Data");

           
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
function redirect(row, employeeId) {
    const checkbox = row.querySelector('input[type="checkbox"]');
    checkbox.addEventListener('click', function(event) {
        event.stopPropagation();
    });

    if (!checkbox.checked) {
        window.location.href = 'addEmployee.php?employeeid=' + employeeId;
    }
}
</script>

    <script src="../assets/js/session_check.js"></script>
</body>
</html>