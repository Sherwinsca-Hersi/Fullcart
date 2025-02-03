<?php
    require 'session.php';
?>
<div>
    <div class="flex_style order_preview_btns">
        <!--<i class="fa fa-picture-o btn btn-primary text-right cmd" onclick='downloadimage();' aria-hidden="true"></i>-->
        <i class="fa-1x fa fa-download export_btn download_btn" onclick='downloadPDF();' aria-hidden="true"></i>
        <button onclick="printSpecificElement()" class="export_btn print_btn"><i class="fa-1x fa fa-print" aria-hidden="true"></i></button>
    </div>
    <?php
        require_once '../api/config.php';
        $p_id = $_POST['p_id'];
        $c = $mysqli->query("select * from e_normal_order_details where cos_id = '$cos_id' and id=".$p_id."")->fetch_assoc();                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                           
        $udata = $mysqli->query("select * from e_user_details where cos_id = '$cos_id' and id=".$c['u_id']."")->fetch_assoc();
        $pdata = $mysqli->query("select * from e_dat_payment where cos_id = '$cos_id' and id=".$c['p_method_id']."")->fetch_assoc();
    ?>
    <div id="divprint">
      <div class="preview_row1">
          <div class="preview_data">
            <h4><?php echo  "Order No"; ?></h4>
            <h5><?php echo  $p_id ?? '-'; ?></h5>
          </div>
          <div class="preview_data">
            <h4><?php echo 'Invoice No'; ?></h4>
            <h5><?php echo $c['invoice_no']==0 ? '-' : $c['invoice_no']; ?></h5>
          </div>
          <?php 
            $date=date_create($c['o_date']);
            $order_date =  date_format($date,"d-m-Y");
          ?>
          <div class="preview_data">
            <h4>Order date</h4>
            <h5><?php echo $order_date;?></h5>
          </div>
          <div class="preview_data">
            <h4>Mobile Number</h4>
            <h5><?php echo $c['mobile'];?></h5>
          </div>
          <div class="preview_data">
            <h4>Customer Name</h4>
            <h5><?php echo $udata['name']??'N/A';?></h5>
          </div>
      </div>
      <div class="preview_row2">
        <h2>Order Item </h2>
        <table rules='all' cellpadding='50px' cellspacing='50px' class="table_style display order_view_details_table">
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
                      $order_data = [];
                      $op = 1;
                      while($row = $get_data->fetch_assoc())
                      {
                        $order_data[]=$row;
                      }
                      ?>
                      
                            <tbody class="preview_body_text">
                                    <?php 
                                        foreach($order_data as $order_data):
                                        $discount = $order_data['p_price'] * $order_data['p_discount'] * $order_data['p_quantity'] /100;
                                    ?>
                                    <tr>
                                        <td><?php echo $op;?></td>
                                        <td>
                                          <?php echo $order_data['p_title'].' ( '.$order_data['p_type'].' )';?>
                                          <p class="imprint">
                                            <?php 
                                            if(!$order_data['instruction']== ''|| NULL){
                                                echo '(Engraving Description:'.$order_data['instruction'].' )';
                                            }else{
                                                echo '';
                                            }
                                            ?>
                                          </p>
                                        </td>
                                        <td><img src="../../<?php echo $order_data['p_img'];?>" width="100px" height="fit-content;"/></td>
                                        <td><?php echo $order_data['p_quantity'];?></td>
                                        <td><?php echo $order_data['p_price'];?></td>
                                        <td><?php
                                            if ($c['bill_type'] == 1) {
                                            ?>
                                            <h5><span>₹ <?php echo ($order_data['p_price'] * $order_data['p_quantity']); ?><span></h5>
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
                            <img src="../../<?php echo $row['p_img'];?>"  width="100px" height="fit-content;"/>
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
    const { jsPDF } = window.jspdf;
    const element = document.querySelector('#divprint'); 

    if (!element) {
        console.error('Element #divprint not found.');
        return;
    }

    try {
        const canvas = await html2canvas(element, {
            useCORS: true,
            scale: 3,
            backgroundColor: null, 
        });

        
        document.body.appendChild(canvas);

        
        const imgData = canvas.toDataURL('image/png');

       
        console.log('Image data generated:', imgData.length);

        
        const pdf = new jsPDF('p', 'mm', 'a4');
        const imgWidth = 190; 
        const pageHeight = 287;
        const imgHeight = canvas.height * imgWidth / canvas.width; 
        let position = 10;

        
        pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);

       
        let heightLeft = imgHeight - pageHeight + position;

        while (heightLeft > 0) {
            position = heightLeft - imgHeight + 10; 
            pdf.addPage();
            pdf.addImage(imgData, 'PNG', 10, position, imgWidth, imgHeight);
            heightLeft -= pageHeight;
        }

        pdf.save('order_details.pdf');
    } catch (error) {
        console.error('Error generating PDF:', error);
    }
}


    function printSpecificElement() {
        const element = document.querySelector('#divprint');

       
        if (!element) {
            console.error('Element #divprint not found for printing.');
            return;
        }
        console.log('Print content:', element.innerHTML);

        const printContents = element.innerHTML;
        const originalContents = document.body.innerHTML;

        try {
           
            document.body.innerHTML = printContents;
            window.print();
        } finally {
           
            document.body.innerHTML = originalContents;
        }
    }
</script>
    <script src="../assets/js/session_check.js"></script>