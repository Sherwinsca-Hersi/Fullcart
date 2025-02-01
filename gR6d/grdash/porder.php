<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<meta http-equiv="Cache-Control" content="no-cache, must-revalidate">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Expires" content="0">
<?php require_once 'api/live/include-v2/config.php';?>
<?php require_once 'api/live/include-v2/header.php';?>
<body class="vertical-layout">    
<style>
    .btn-secondary{
        background-color: grey;
        border-color: grey;
    }
    .btn-secondary:visited {
        background-color: grey;
        border-color: grey;
    }
    .btn-secondary:hover{
        background-color:#18d26b;
        border-color: #18d26b;
    }
    .selected {
        background-color: skyblue;
    }
</style>
   <div class="infobar-settings-sidebar-overlay"></div>
    <!-- End Infobar Setting Sidebar -->
    <!-- Start Containerbar -->
    <div id="containerbar">
        <?php require_once 'api/live/include-v2/sidebar.php';?>
        <!-- Start Rightbar -->
        <div class="rightbar">
            <!-- Start Breadcrumbbar -->                    
            <div class="breadcrumbbar">
                <div class="row align-items-center">
                    <div class="col-md-8 col-lg-8">
                        <!--<h4 class="page-title"><?php echo $set['d_title'];?></h4>-->
                        <!--<div class="breadcrumb-list">-->
                        <!--    <ol class="breadcrumb">-->
                        <!--        <li class="breadcrumb-item"><a href="dashboard.php">Home</a></li>-->
                        <!--        <li class="breadcrumb-item"><a href="#">Pending Order</a></li>-->
                        <!--    </ol>-->
                        <!--</div>-->
                        <h2>Orders</h2>
                    </div>
					<!--<div class="col-md-4 col-lg-4">-->
     <!--                   <div class="widgetbar">-->
     <!--                      <button class="btn btn-primary-rgba" id="btn-export" data-title="order"><i class="feather icon-plus mr-2"></i>Export</button>-->
     <!--                  </div>                       -->
     <!--             </div>-->
					
                   
                </div>          
            </div>
            <!-- End Breadcrumbbar -->
            <!-- Start Contentbar -->    
            <div class="contentbar">  
                <div class="row">
			           <div class="col-lg-12">
                            <div class="card m-b-30">
                                <div class="card-body">
                                    <form method="post" action="porder.php">
                                        <div class="row btn-row">
                                            <button class="online_btn" name="online_order" class="row-button col-md-4 col-lg-6 col-xs-12 col-sm-12" id="online_order" data-tab="online_order"><h2>Online Order</h2></button>
                                            <button class="real_btn" name="real_order" class="row-button col-md-4 col-lg-6 col-xs-12 col-sm-12" id="real_order" data-tab="real_order"><h2>Real Order</h2></button>
                                        </div>
                                    </form>
                                <div class="table-responsive">
                                    <div id="default-datatable_wrapper" class="dataTables_wrapper container-flu_id dt-bootstrap4">
                                        <div class="row">
									        <div class="col-sm-12 col-md-12">
									            <div class="row">
									                <div class="col-sm-12">
                                
                            <?php
                            if(isset($_POST['real_order'])){
                            ?>
                                    
						                    <table id="data" class="display table table-bordered dataTable dtr-inline" role="gsalesman_id" aria-describedby="default-datatable_info">
                                       <thead>
                                            <tr>
                                                 <th>#</th>
												 <th>Order Id</th>
                                                 <th class="remove_arrow">Order Date </th>
												 <!--<th>Delivery Person Name</th>-->
                                                 <!--<th>Current Status</th>-->
                                                 <th class="remove_arrow">View Data</th>
												 <!--<th>Order Assign?</th>-->
												  <!--<?php if (!isset($_POST['completed'])): ?>-->
                                                    <!--<th class="remove_arrow">Action</th>-->
                                                    <!--<?php endif; ?>-->
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php 
											$i=0;
											 $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='6' and bill_type='2' order by id desc");

                                            while($row = $stmt->fetch_assoc())
                                            {
                                            	$i = $i + 1;
											?>
                                                <tr>
												
												<td><?php echo $i; ?> </td>
												
												<td> <?php echo $row['id']; ?> </td>
                                                
                                               <td data-sort='DDMMYYYY'> <?php 
											   $date=date_create($row['o_date']);
                                                    echo date_format($date,"d-m-Y");
											   ?></td>
											
												 <td> <button class="preview_d btn btn-primary" data-id="<?php echo $row['id'];?>" data-toggle="modal" data-target="#myModal">View  </button></td>
            											  </tr>
                                            <?php } ?>                                  
                                            </tbody>
                                        
                                    </table>
                                
                                    <?php
                                        }
                                    
                                    else{
                                        

                                    ?>
                                    <form id="statusForm" method="post" action="porder.php">
							            <div class="status_btn"> 
							    
                                            <div>
                                                <input type="hidden" name="selectedBtn" id="selectedBtn1" />
                                                <input type="submit" value="Order Placed" name="pending" class="btn btn-secondary order_btn">
                                            </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn2" />
							                    <input type="submit"  value="Order Processing" name="processing" class="btn btn-secondary order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn3" />
							                    <input type="submit"  value="Order Packed" name="packed" class="btn btn-secondary order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn4" />
							                    <input type="submit"  value="Delivery Person Assigned" name="db_assigned" class="btn btn-secondary order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn5" />
							                    <input type="submit"  value="Out For Delivery" name="out_delivery" class="btn btn-secondary order_btn">
							                </div>
							                <div>
							                    <input type="hidden" name="selectedBtn" id="selectedBtn6" />
							                    <input type="submit"  value="Completed" name="completed" class="btn btn-secondary order_btn">
							                </div>
                                        </div>
							    </form>
							    <script>
							    const selectedBtn1=document.querySelector("#selectedBtn1");
							    const selectedBtn2=document.querySelector("#selectedBtn2");
							    const selectedBtn3=document.querySelector("#selectedBtn3");
							    const selectedBtn4=document.querySelector("#selectedBtn4");
							    const selectedBtn5=document.querySelector("#selectedBtn5");
							    const selectedBtn6=document.querySelector("#selectedBtn6");
							    
            let lastSelectedButton = localStorage.getItem('selectedBtn');
if (lastSelectedButton) {
    let btn = document.querySelector(`.order_btn[name=${lastSelectedButton}]`);
    btn.classList.add('btn-success');
    btn.style.backgroundColor = 'green';
}

// Attach an event listener to each button
document.querySelectorAll('.order_btn').forEach(item => {
    item.addEventListener('click', event => {
        // event.preventDefault();
        
        // Reset all button colors and remove the 'btn-success' class
        document.querySelectorAll('.order_btn').forEach(otherItem => {
            otherItem.classList.remove('btn-success');
            otherItem.style.backgroundColor = '';
        });
        
        // Change the background color of the clicked button to green and add the 'btn-success' class
        event.currentTarget.classList.add('btn-success');
        event.currentTarget.style.backgroundColor = 'green';
        
        selectedBtn1.value = event.currentTarget.name;
        localStorage.setItem('selectedBtn', event.currentTarget.name);
        selectedBtn2.value = event.currentTarget.name;
        localStorage.setItem('selectedBtn', event.currentTarget.name);
        selectedBtn3.value = event.currentTarget.name;
        localStorage.setItem('selectedBtn', event.currentTarget.name);
        selectedBtn4.value = event.currentTarget.name;
        localStorage.setItem('selectedBtn', event.currentTarget.name);
        selectedBtn5.value = event.currentTarget.name;
        localStorage.setItem('selectedBtn', event.currentTarget.name);
        selectedBtn6.value = event.currentTarget.name;
        localStorage.setItem('selectedBtn', event.currentTarget.name);
        document.querySelector('form').submit();
    });
});
// localStorage.setItem('selectedBtn', 'pending');
</script>
	                                   
		<table id="data" class="display table table-bordered dataTable dtr-inline" role="gsalesman_id" aria-describedby="default-datatable_info">
                                       <thead>
                                            <tr>
                                                 <th>#</th>
												 <th>Order Id</th>
                                                 <th class="remove_arrow">Order Date </th>
												 <!--<th>Delivery Person Name</th>-->
                                                 <th>Current Status</th>
                                                 <th class="remove_arrow">View Data</th>
												 <?php if (!isset($_POST['completed'])): ?>
												    <th class="remove_arrow">Action</th>
												  <?php else: ?>
												<?php endif; ?>
                                            </tr>
                                        </thead>
                                        <tbody>
											<?php 
$currentStatus =$_GET['currentStatus']??'pending';

if(isset($currentStatus)){
    ?>
    <script>
        localStorage.setItem('selectedBtn',<?php echo $currentStatus;?>);
        const obtainedBtn=document.getElementsByName('<?php echo $currentStatus;?>');
        obtainedBtn.style.backgroundColor="green";
    </script>
<?php    
}

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
                                                }else{
                                                    $stmt = $mysqli->query("SELECT * FROM `e_normal_order_details` where cos_id = '$cos_id' and active='1' and status='Order Placed'  and bill_type='1' order by id desc");
                                                }
                                                

                                            while($row = $stmt->fetch_assoc())
                                            {
                                            	$i = $i + 1;
											?>
                                                <tr class="clickable-row" data-id="<?php echo $row['id']; ?>">
												
												<td><?php echo $i; ?> </td>
												<td><?php echo $row['id']; ?></td>
                                                
                                               <td data-sort='DDMMYYYY'> <?php 
											   $date=date_create($row['o_date']);
                                                    echo date_format($date,"d-m-Y");
											   ?></td>
											   <td> <?php echo $row['status']; ?></td>
											   
												 <td> <button class="preview_d btn btn-primary" data-id="<?php echo $row['id'];?>" data-toggle="modal" data-target="#myModal">View</button></td>
												        
												    <?php if ($currentStatus=='processing'): ?>
                                                        <td>
                                                            <a href="?dsid=<?php echo $row['id']; ?>&status=2 + &currentStatus=processing"  class="btn btn-secondary order-action">Order Packed</a>
                                                        </td>
                                                    <?php elseif ($currentStatus=='packed'): ?>
                                                        <td>
                                                            <a href="?dsid=<?php echo $row['id']; ?>&status=3 + &currentStatus=packed"  class="btn btn-secondary order-action">Delivery Person Assigning</a>
                                                        </td>
                                                    <?php elseif ($currentStatus=='db_assigned'): ?>
                                                        <td>
  <div class="form-group">
    <a href="#" class="myLink order-action" id="myLink" data-dsid="<?php echo $row['id']; ?>"></a>
    <select class="form-control select2-single salesman-select mySelect" id="db_select" required>
    </select>
  </div>
   <script>
  var selectElement = document.getElementById('db_select');
  var anchorElement = document.getElementById('myLink');


    selectElement.addEventListener('change', function() {
        var selectedValue = selectElement.value;
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
        xhr.open('GET', 'porder.php?dsid=' + dsid +'&salesman_id='+selectedValue);
        xhr.send();
        // console.log(dsid)
        var href = "?dsid=" + dsid + "&salesman_id=" + selectedValue + "&status=4&currentStatus=db_assigned";
        anchorElement.setAttribute('href', href);

        var hrefValue = anchorElement.getAttribute('href');
        window.location.href = hrefValue;
    });
</script>
                                                            <script>
                                                                // Check if the script has already been executed to avoid repetition
                                                                if (!document.querySelector('.salesman-select').hasAttribute('data-loaded')) {
                                                                    // Make an AJAX request to fetch salesman data
                                                                    let xhr = new XMLHttpRequest();
                                                                    xhr.onreadystatechange = function() {
                                                                        if (xhr.readyState === XMLHttpRequest.DONE) {
                                                                            if (xhr.status === 200) {
                                                                                // Handle the response
                                                                                let salesmanData = xhr.responseText;
                                                                                // Iterate over each select element with the class 'salesman-select'
                                                                                document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
                                                                                    // Set the fetched data to each select element
                                                                                    selectElement.innerHTML = salesmanData;
                                                                                });
                                                                                // Mark the script as executed to prevent repetition
                                                                                document.querySelectorAll('.salesman-select').forEach(function(selectElement) {
                                                                                    selectElement.setAttribute('data-loaded', 'true');
                                                                                });
                                                                            } else {
                                                                                // Handle error
                                                                                console.error('Request failed: ' + xhr.status);
                                                                            }
                                                                        }
                                                                    };
                                                                    xhr.open("GET", "fetch_salesman.php", true);
                                                                    xhr.send();
                                                                }
                                                            </script>
                                                        </td>
                                                    <?php elseif ($currentStatus=='out_delivery'): ?>
                                                        <td>
                                                            <a href="?dsid=<?php echo $row['id']; ?>&status=5 + &currentStatus=out_delivery"  class="btn btn-secondary order-action">Complete</a>
                                                        </td>
                                                        <?php elseif ($currentStatus=='pending'): ?>
                                                            <td>
                                                                <a href="?dsid=<?php echo $row['id']; ?>&status=1 + &currentStatus=pending" class="btn btn-secondary order-action">Order Processing</a>
                                                            </td>
                                                            
                                                        <?php else: ?>
                                                        <?php endif; ?>
                                                      </tr>
                                               
                                                 <?php } ?>                                  
                                            </tbody>
                                            <script>
    // Function to parse URL parameters
    function getUrlParameter(name) {
        name = name.replace(/[[]/, '\\[').replace(/[\]]/, '\\]');
        var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
        var results = regex.exec(location.search);
        return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
    };

    // Function to delete row based on dsid parameter
    window.onload = function() {
        var dsidToDelete = getUrlParameter('dsid');
        if (dsidToDelete) {
            var rowToRemove = document.querySelector('tr[data-id="' + dsidToDelete + '"]');
            if (rowToRemove) {
                rowToRemove.parentNode.removeChild(rowToRemove);
                // You can also perform an AJAX request here to delete the row from the database
            }
        }
    };
</script>
                                        
                                    </table>   
                                   
                                     <?php
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
				</div>
            </div>
			</div>
		</div>
		</div>
		</div>
		</div>
            <!-- End Contentbar -->
            <!-- Start Footerbar -->
           
            <!-- End Footerbar -->
        </div>
        <!-- End Rightbar -->
    </div>
    <!-- End Containerbar -->
    
<?php 
	if(isset($_GET['dsid'])){
		$status = $_GET['status'];
		
		if($status == 1){
				// Retrieve the data sent from the client-side
    $order_id = $_GET['dsid'];
    
    $table="e_normal_order_details";
    $field = array('active'=>'2','status'=>'Processing', 'updated_by'=>$_SESSION['name'], 'up_platform'=>'3');
    $where = "where cos_id = '$cos_id' and id=".$order_id."";
    $h = new CommonFunction();
    $check = $h->Ins_update_Api($field,$table,$where);	

            /* $checks = $mysqli->query("select * from e_normal_order_details where cos_id = '$cos_id' and id=".$_GET['order_id']."")->fetch_assoc(); 
        	  $u_id = $checks['u_id'];
        	  $o_id = $_GET['order_id'];
        	  	$sql = "select n.token,u.name from e_notification n, e_user_details u where u.cos_id = '$cos_id' and u.id = ".$u_id." and n.cos_id = '$cos_id' and n.u_id = u.id ORDER BY n.created_ts DESC LIMIT 1";
            	$sel = $mysqli->query($sql)->fetch_assoc();
                $token[] = $sel["token"];
                $name = $sel['name'];
        
                $timestamp = date("Y-m-d H:i:s");
                
                $title_main = "Order Confirmed!!";
                $description = ucfirst($name).', Your Order #'.$o_id.' Has Been Confirmed.';
                
                $table="e_notification_details";
                  $field_values=array("u_id","datetime","title","description");
                  $data_values=array("$u_id","$timestamp","$title_main","$description");
          
              $h = new CommonFunction();
              $h->send_notification($token,$description,$title_main);
        	   $h->Ins_latest_Api($field_values,$data_values,$table);*/


        if($check == 1)
        {
        ?>
        <script src="assets/izitoast/js/iziToast.min.js"></script>
         <script>
         iziToast.success({
            title: 'Decision Section!!',
            message: 'Processing Decision is Successfull!!',
            position: 'topRight'
          });
          </script>
  
<?php 
}

}else if($status == 2) {
		$order_id = $_GET['dsid'];
    
    $table="e_normal_order_details";
    $field = array('active'=>'3','status'=>'Packed', 'updated_by'=>$_SESSION['name'], 'up_platform'=>'3');
    $where = "where cos_id = '$cos_id' and id=".$order_id."";
    $h = new CommonFunction();
    $check = $h->Ins_update_Api($field,$table,$where);	
    
	  
    if($check == 1)
    {
    ?>
    <script src="assets/izitoast/js/iziToast.min.js"></script>
     <script>
     iziToast.success({
        title: 'Decision Section!!',
        message: 'Packed Decision Successfully!!',
        position: 'topRight'
      });
      </script>
      
    <?php 
    }
}else if($status == 3) {
		$order_id = $_GET['dsid'];
    
    $table="e_normal_order_details";
    $field = array('active'=>'4','status'=>'Delivery Person Assigned ', 'updated_by'=>$_SESSION['name'], 'up_platform'=>'3');
    $where = "where cos_id = '$cos_id' and id=".$order_id."";
    $h = new CommonFunction();
    $check = $h->Ins_update_Api($field,$table,$where);	

	  
    if($check == 1)
    {
    ?>
    <script src="assets/izitoast/js/iziToast.min.js"></script>
     <script>
     iziToast.success({
        title: 'Decision Section!!',
        message: 'Delivery Person Assigning Decision Successfully!!',
        position: 'topRight'
      });
      </script>
      
    <?php 
    }
}else if($status == 4) {
		$order_id = $_GET['dsid'];
		$salesman_id = $_GET['salesman_id'];
    $table="e_normal_order_details";
    $field = array('active'=>'5', 'salesman_id'=>$salesman_id, 'status'=>'Out For Delivery', 'updated_by'=>$_SESSION['name'], 'up_platform'=>'3');
    $where = "where cos_id = '$cos_id' and id=".$order_id."";
    $h = new CommonFunction();
    $check = $h->Ins_update_Api($field,$table,$where);	

	  
    if($check == 1)
    {
    ?>
    <script src="assets/izitoast/js/iziToast.min.js"></script>
     <script>
     iziToast.success({
        title: 'Decision Section!!',
        message: 'Out of Delivery Decision Successfully!!',
        position: 'topRight'
      });
      </script>
      
    <?php 
    }
}
else if($status == 5) {
		$order_id = $_GET['dsid'];
    
    $table="e_normal_order_details";
    $field = array('active'=>'6','status'=>'Completed', 'updated_by'=>$_SESSION['name'], 'up_platform'=>'3');
    $where = "where cos_id = '$cos_id' and id=".$order_id."";
    $h = new CommonFunction();
    $check = $h->Ins_update_Api($field,$table,$where);	

	  
if($check == 1)
{
?>
<script src="assets/izitoast/js/iziToast.min.js"></script>
 <script>
 iziToast.success({
    title: 'Decision Section!!',
    message: 'Completed Decision Successfully!!',
    position: 'topRight'
  });
  </script>
  
<?php 
}}?>
	<?php 
}
	?>
	
  
<div id="myModal" class="modal" role="dialog">
  <div class="modal-dialog modal-lg ">

    
    <div class="modal-content gray_bg_popup">
      <div class="modal-header">
        <h4>Pending Order Preivew</h4>
        <button type="button" class="close" data-dismiss="modal" class="preview_close">&times;</button>
      </div>
      <div class="modal-body p_data">
      
      </div>
     
    </div>

  </div>
</div>

<?php require_once 'api/live/include-v2/footer.php'; ?>
    <!-- End js -->
</body>

<script>
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
                    $(".p_data").html(result);
                },
                error: function(result) {
                    alert('error');
                }
            });
        });
    });

const onlineOrderButton = document.querySelector("#online_order");
const realOrderButton = document.querySelector("#real_order");

// Retrieve the last selected tab from local storage, or default to 'online_order'
let lastSelectedTab = localStorage.getItem('salesselectedTab') || "online_order";

// Highlight the default selected tab
document.getElementById(lastSelectedTab).style.borderBottom = '2px solid grey';

// Function to handle tab selection
function handleTabSelection(event) {
    // Remove 'selected' class from all buttons
    document.querySelectorAll('.btn-row button').forEach(item => {
        item.style.borderBottom = 'none';
    });

    // Highlight the clicked tab
    event.currentTarget.style.borderBottom = '2px solid grey';

    // Update the selected tab in local storage
    const selectedTab = event.currentTarget.id;
    localStorage.setItem('salesselectedTab', selectedTab);
}

// Add click event listeners to all buttons
document.querySelectorAll('.btn-row button').forEach(item => {
    item.addEventListener('click', handleTabSelection);
});

// Set default tab in local storage
localStorage.setItem('salesselectedTab', 'online_order');
</script>
<script src="../assets/js/session_check.js"></script>
</html>