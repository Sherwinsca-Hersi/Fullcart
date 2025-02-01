<?php 
// opcache_reset();
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
session_start();
// if(!isset($_SESSION['name'])){
//         header('location:index.php');
// }
?>
<div>
    <?php
        require_once '../api/config.php';
        $p_id = $_POST['p_id'];
        $c = $mysqli->query("select * from e_normal_order_details where cos_id = '$cos_id' and id=".$p_id."")->fetch_assoc();
        $udata = $mysqli->query("select * from e_user_details where cos_id = '$cos_id' and id=".$c['u_id']."")->fetch_assoc();
        $pdata = $mysqli->query("select * from e_dat_payment where cos_id = '$cos_id' and id=".$c['p_method_id']."")->fetch_assoc();
    ?>
    <div>
        <!--<i class="fa fa-picture-o btn btn-primary text-right cmd" onclick='downloadimage();' aria-hidden="true"></i>-->
        <i class="fa fa fa-download btn btn-primary" onclick='downloadPDF();' aria-hidden="true"></i>
    </div>
    <div id="divprint">
        <div class="card-body bg-white mb-2">
                <div class="row d-flex">
                    <div class="col-md-3">
                    <!-- Heading -->
                        <h6 class="text-muted mb-1"><?php echo $c['bill_type']==1 ? "Order No:" : 'Invoice No:'; ?></h6>
                    <!-- Text -->
                        <p class="mb-lg-0 font-size-sm font-weight-bold"><?php echo $c['bill_type']==1 ? $p_id : $c['invoice_no']; ?></p>
                    </div>
                    <?php 
                        $date=date_create($c['o_date']);
                        $order_date =  date_format($date,"d-m-Y");
                    ?>
                    <div class="col-md-3">
                    <!-- Heading -->
                        <h6 class="text-muted mb-1">Order date:</h6>
                    <!-- Text -->
                        <p class="mb-lg-0 font-size-sm font-weight-bold">
                        <span><?php echo $order_date;?></span>
                        </p>
                    </div>
                  
                    
                    <!-- Heading -->
                    <?php
                       if($c['bill_type']==1){
                          ?>
                                <div class="col-md-3">
                                    <h6 class="text-muted mb-1">Mobile Number:</h6>
                                <!-- Text -->
                                    <p class="mb-0 font-size-sm font-weight-bold"> <?php echo $c['mobile'];?></p>
                                </div>
                                <div class="col-md-3">
                                    <!-- Heading -->
                                    <h6 class="text-muted mb-1">Customer Name:</h6>
                                    <!-- Text -->
                                    <p class="mb-0 font-size-sm font-weight-bold"><?php echo $udata['name'];?></p>
                                </div>
                    <?php
                       }
                      ?>
                  
                    
                </div>
        </div>
        <div class="card style-2 mb-2">
                <div class="card-header d-flex">
                  <h4 class="mb-0">Order Item </h4>
                </div>
                <div class="card-body">
                  <ul class="order-details item-groups">
                  
                    <!-- Single Items -->
                    <?php 

                        $get_data = $mysqli->query("select * from e_normal_order_product_details where cos_id = '$cos_id' and o_id=".$p_id."");
                        $op = 0;
                        while($row = $get_data->fetch_assoc())
                        {
                            $op = $op + 1;
                            $discount = $row['p_price'] * $row['p_discount']*$row['p_quantity'] /100;
                         ?>
                    <li>
                      <div class="row align-items-center">
                        <div class="col-4 col-md-3 col-xl-2">
                          <a href="#"><img src="<?php echo $row['p_img'];?>" width="100px"/></a>
                        </div>
                        
                        <div class="col">
                          <!-- Title -->
                          <p class="mb-2 font-size-sm font-weight-bold">
                            <a class="text-body" href=""><?php echo $op;?>) <?php echo $row['p_title'].' ( '.$row['p_type'].' )';?></a> <br>
                            <span class="theme-cl"> <?php echo $row['p_discount'].' %';?></span>
                          </p>

                          <!-- Text -->
                          <div class="font-size-sm text-muted">
                           
                            Qty: <?php echo $row['p_quantity'];?> x <?php echo $set['currency'].' '.$row['p_price'];?> <br>
                            <?php
                                if ($c['bill_type'] == 1) {
                                    ?>
                                    Price: <?php echo $set['currency'] . ' ' . ($row['p_price'] * $row['p_quantity']) - $discount; ?>
                                    <?php
                                } else {
                                    ?>
                                    Price: <?php echo $set['currency'] . ' ' . ($row['p_price'] * $row['p_quantity']);?>
                                    <?php
                                }
                                ?>
                          </div>
                        </div>
                      </div>
                    </li>
                    <?php       
} ?>
                  </ul>
                </div>
        </div>
        <div class="card style-2 mb-2">
                <div class="card-header">
                  <h4 class="mb-0">Total Order</h4>
                </div>
                <div class="card-body">
                  <ul class="list-group list-group-sm list-group-flush-y list-group-flush-x">
                    <li class="list-group-item d-flex">
                      <span>Subtotal</span>
                      <span class="ml-auto float-right"><?php echo $set['currency'].' '.$c['subtotal'];?></span>
                    </li>
                  <?php 
  if($c['cou_amt'] != 0)
  {
  ?>
                    <li class="list-group-item d-flex">
                      <span>Coupon Code</span>
                      <span class="ml-auto float-right"><?php echo $set['currency'].' '.$c['cou_amt'];?></span>
                    </li>
                     <?php } ?>
					 
					 <?php 
  if($c['wall_amt'] != 0)
  {
  ?>
                    <li class="list-group-item d-flex">
                      <span>Wallet</span>
                      <span class="ml-auto float-right"><?php echo $set['currency'].' '.$c['wall_amt'];?></span>
                    </li>
                     <?php } ?>
					 
					 
                    <li class="list-group-item d-flex">
                      <span>Delivery Charge</span>
                      <span class="ml-auto float-right"><?php echo $set['currency'].' '.$c['d_charge'];?></span>
                    </li>
                    
                    <li class="list-group-item d-flex font-size-lg font-weight-bold">
                      <span>Net Amount</span>
                      <span class="ml-auto float-right"><?php echo $set['currency'].' '.$c['o_total'];?></span>
                    </li>
                  </ul>
                </div>
        </div>
        <div class="card style-2">
                <div class="card-header">
                  <h4 class="mb-0">Shipping &amp; Billing  Details</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                         <?php
                            if($c['bill_type']==1){
                          ?>
                        <div class="col-12 col-md-6">
                        <!-- Heading -->
                            <p class="mb-2 font-weight-bold">
                                Shipping Address:
                            </p>

                            <p class="mb-7 mb-md-0">
                                 <?php echo $c['address'];?>
                            </p>
                        </div>
                    <?php
                            }
                    ?>
                        <div class="col-12 col-md-6">
                            <?php 
                            if($c['p_method_id'] == 2)
                            {
                            ?>
                            <!-- Heading -->
                            <p class="mb-2 font-weight-bold">
                                Payment Method:
                            </p>

                            <p class="mb-2 text-gray-500">
                                <?php echo 'Cash On Delivery';?>
                            </p>
                                <?php 
                                }
                            else
                                {
                            ?>
                            <!-- Heading -->
                            <p class="mb-2 font-weight-bold">
                                Transaction Id:
                            </p>

                            <p class="mb-2 text-gray-500">
                                <?php echo $c['trans_id'];?>
                            </p>
                                <?php 
                                }
                                ?>
                            <!-- Heading -->
                            <p class="mb-2 font-weight-bold">
                                Order Status:
                            </p>

                            <p class="mb-0">
                                <?php echo $c['status'];?>
                            </p>

                        </div>
                    </div>
                </div>
		</div>		            
			<!--<div class="col-12 col-md-12">-->
                                <!-- Heading -->
     <!--                           <p class="mb-2 font-weight-bold">-->
     <!--                               Additional Notes:-->
     <!--                           </p>-->

     <!--                           <p class="mb-7 mb-md-0">-->
     <!--                               <?php echo $c['a_note'];?>-->
     <!--                           </p>-->
     <!--                           </div>-->
                        
                   
                
    
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script>
        async function downloadPDF() {
            const {jsPDF} = window.jspdf;
            const pdf = new jsPDF('p', 'mm', 'a4');
            const element = document.querySelector('#divprint');
            const canvas = await html2canvas(element, { useCORS: true });

            const imgData = canvas.toDataURL('image/png');
            const imgWidth = 190; // A4 width in mm with padding (210mm - 20mm padding)
            const pageHeight = 287; // A4 height in mm with padding (297mm - 10mm padding)
            const imgHeight = canvas.height * imgWidth / canvas.width;
            const padding = 10; // padding of 10mm
            let heightLeft = imgHeight;
            let position = 10; // initial padding from top

            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;

            while (heightLeft >= 0) {
                position = heightLeft - imgHeight + padding;
                pdf.addPage();
                pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
                heightLeft -= pageHeight;
            }

            pdf.save('order_details.pdf');
        }
    </script>