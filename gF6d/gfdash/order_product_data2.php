<?php
    require 'session.php';
?>
<div>
    
    <?php
        require_once '../api/config.php';
        $p_id = $_POST['p_id'];
        $c = $mysqli->query("select * from e_normal_order_details where cos_id = '$cos_id' and id=".$p_id."")->fetch_assoc();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
        $udata = $mysqli->query("select * from e_user_details where cos_id = '$cos_id' and id=".$c['u_id']."")->fetch_assoc();
        $pdata = $mysqli->query("select * from e_dat_payment where cos_id = '$cos_id' and id=".$c['p_method_id']."")->fetch_assoc();
    ?>
    <div id="divprint">
      <div class="preview_row1">
        <div class="order_main">
          <div class="preview_data">
            <h3><?php echo  "Order No"; ?></h3>
            <h4><?php echo  $p_id ?? '-'; ?></h4>
          </div>
          <div class="preview_data">
            <h3><?php echo 'Invoice No'; ?></h3>
            <h4><?php echo $c['invoice_no']==0 ? '-' : $c['invoice_no']; ?></h4>
          </div>
          <?php 
            $date=date_create($c['o_date']);
            $order_date =  date_format($date,"d-m-Y");
          ?>
          <div class="preview_data">
            <h3>Order date</h3>
            <h4><?php echo $order_date;?></h4>
          </div>
        </div>
        <div class="flex_style order_preview_btns">
            <!--<i class="fa fa-picture-o btn btn-primary text-right cmd" onclick='downloadimage();' aria-hidden="true"></i>-->
            <i class="fa-1x fa fa-download export_btn download_btn" onclick='downloadPDF();' aria-hidden="true"></i>
            <button onclick="printSpecificElement()" class="export_btn print_btn"><i class="fa-1x fa fa-print" aria-hidden="true"></i></button>
        </div>
          <!-- <div class="preview_data">
            <h3>Mobile Number</h3>
            <h4><?php echo $c['mobile'];?></h4>
          </div>
          <div class="preview_data">
            <h3>Customer Name</h3>
            <h4><?php echo $udata['name']??'N/A';?></h4>
          </div> -->
      </div>
      <div class="preview_row2">
        <h2>Order Item </h2>
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display">
                    <thead class="product_preview_head">
                        <th>S.No</th>
                        <th>Product Name</th>
                        <th>Product Image</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </thead>
          <?php 
                   
                      $get_data = $mysqli->query("select * from e_normal_order_product_details where cos_id = '$cos_id' and o_id=".$p_id."");

                      $op = 1;
                      while($row = $get_data->fetch_assoc())
                      {
                        $order_data[]=$row;
                      }
                      ?>
                      
                            <tbody class="preview_body_text">
                                    <?php 
                                        foreach($order_data as $order_data):
                                        $discount = $order_data['p_price'] * $order_data['p_discount']*$order_data['p_quantity'] /100;
                                    ?>
                                    <tr>
                                        <td><?php echo $op;?></td>
                                        <td><?php echo $order_data['p_title'].' ( '.$order_data['p_type'].' )';?></td>
                                        <td><img src="../<?php echo $order_data['p_img'];?>"  width="100px" height="fit-content;"/></td>
                                        <td><?php echo $order_data['p_quantity'];?></td>
                                        <td><?php echo $order_data['p_price'];?></td>
                                        <td><?php
                                            if ($c['bill_type'] == 1) {
                                            ?>
                                            <h5><span>₹ <?php echo ($order_data['p_price'] * $order_data['p_quantity']) - $discount; ?><span></h5>
                                            <?php
                                                } else {
                                            ?>
                                            <h5><span>₹  <?php echo ($order_data['p_price'] * $order_data['p_quantity']);?><span></h5>
                                        <?php
                                        }
                                        ?></td>
                                    </tr>
                                    
                                    <?php
                                        $op = $op + 1;
                                        endforeach;
                                    ?>
                            </tbody>
                       </table>

                      <!-- <div class="order_details">
                        <div>
                            <img src="../<?php echo $row['p_img'];?>"  width="100px" height="fit-content;"/>
                        </div>
                        <div>
                            <div  class="preview_data">
                                <h5><?php echo $row['p_title'].' ( '.$row['p_type'].' )';?></h5>
                            </div>
                            <div class="preview_data">
                                <h5><?php echo $row['p_discount'].' %';?></h5>
                            </div>
                            <div class="preview_data">
                                <h5>  Qty: <span><?php echo $row['p_quantity'];?></span></h5>
                            </div>
                            <div class="preview_data">
                                <h5>  Price:₹ <?php echo $row['p_price'];?></span></h5>
                            </div>
                            <div class="preview_data">
                                <?php
                                    if ($c['bill_type'] == 1) {
                                      ?>
                                        <h5>Total: <span>₹ <?php echo ($row['p_price'] * $row['p_quantity']) - $discount; ?><span></h5>
                                    <?php
                                        } else {
                                        ?>
                                        <h5>Total: <span>₹  <?php echo ($row['p_price'] * $row['p_quantity']);?><span></h5>
                                    <?php
                                      }
                                    ?>
                            </div>
                        </div>
                      </div> -->
                    <?php
                    ?>
      </div>
      <div class="preview_row3">
        <h2>Total Order</h2>
        <div class="preview_data">
          <h5>Sub Total</h4>
          <span>₹<?php echo $c['subtotal'];?></span>
        </div>
        <?php
        if($c['cou_amt'] != 0)
        {
        ?>
        <div class="preview_data">
          <h5>Coupon Code</h4>
          <span>₹ <?php echo $c['cou_amt'];?></span>
        </div>
        <?php } ?>
        <?php 
          if($c['wall_amt'] != 0)
          {
          ?>
        <div class="preview_data">
          <h5>Wallet</h4>
          <span>₹<?php echo $c['wall_amt'];?></span>
        </div>
        <?php } ?>
        <div class="preview_data">
          <h5>Delivery Charge</h4>
          <span>₹<?php echo $c['d_charge'];?></span>
        </div>
        <div class="preview_data">
          <h5>Net Amount</h4>
          <span>₹<?php echo $c['o_total'];?></span>
        </div>
      </div>
      <div class="preview_row4">
        <h2>Shipping and Billing Details</h2>
        <div class="order_details">
          
          <div class="preview_data">
            <h4>Shipping Address</h4>
            <p><?php echo $c['address'];?></p>
          </div>
          <?php 
            if($c['p_method_id'] == 2)
            {
          ?>
          <div class="preview_data">
            <h4>Payment Method:</h4>
            <p><?php echo 'Cash On Delivery';?></p>
          </div>
            <?php } 
            else {
          ?>
          <div class="preview_data">
            <h4>Transaction Id:</h4>
            <p><?php echo $c['trans_id'];?></p>
          </div>
          <?php } ?>
          <div class="preview_data">
            <h4>Order Status:</h4>
            <p><?php echo $c['status'];?></p>
          </div>
        </div>
          
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
            const imgWidth = 190; 
            const pageHeight = 287;
            const imgHeight = canvas.height * imgWidth / canvas.width;
            const padding = 10; 
            let heightLeft = imgHeight;
            let position = 10;

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


    function printSpecificElement() {
        const printContents = document.querySelector('#divprint').innerHTML;
        const originalContents = document.body.innerHTML;

        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }
    </script>
    <script src="../assets/js/session_check.js"></script>