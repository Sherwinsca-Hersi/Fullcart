<?php
    require 'session.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
    <?php 
        require_once '../api/header.php';
        // header('Content-type:text/html; charset=utf-8');
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
    <div class="inventory_rightbar container">
        <h2>Inventory</h2>
        <div class="inventory_cards">
            <a href="product_report.php">
                <div class="inventory_card">
                    <div>
                        <span><img src="../assets/images/allstock.png" alt="allStock-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>All Stock</h3>
                        <h4><?php echo  $product_stock->num_rows;?></h4>
                        <!-- <i class="fa fa-1x fa-shopping-bag mr-3 rounded-circle" class="card_icon"></i> -->
                    </div> 
                </div>
            </a>
            <a href="lowStockAlert.php">
                <div  class="inventory_card">
                    <div>
                        <span><img src="../assets/images/lowstockalert.png" alt="low-stock-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>Low Stock Alert</h3>
                        <h4><?php echo $low_stock_alert->num_rows;?></h4>
                        <!-- <i class="fa fa-1x fa-shopping-bag mr-3 rounded-circle" class="card_icon"></i> -->
                    </div>
                </div>
            </a>
            <a href="reorder_level.php">
                <div class="inventory_card">
                    <div>
                        <span><img src="../assets/images/reorderlevel.png" alt="reorder-level-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>Reorder Level</h3>
                        <h4><?php echo $reorder_level_query->num_rows;?>
                        </h4>
                    </div>
                </div>
            </a>
            <a href="outOfStock.php">
                <div class="inventory_card">
                    <div>
                        <span><img src="../assets/images/outofstock.png" alt="Out Of Stock-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>Out Of Stock</h3>
                        <h4><?php echo $out_stock_query->num_rows;?></h4>
                    </div>
                </div>
            </a>
            <!-- <a href="products.php">
                <div class="inventory_card">
                    <div>
                        <span><img src="../assets/images/lowstockalert.png" alt="low-stock-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>Change Low Stock Alert</h3>
                    </div>
                </div>
            </a>
            <a href="products.php">
                <div class="inventory_card">
                    <div>
                        <span><img src="../assets/images/reorderlevel.png" alt="reorder-level-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>Change Reorder Level</h3>
                    </div>
                </div>
            </a> -->
            <a href="missingStock.php">
                <div class="inventory_card">
                    <div>
                        <span><img src="../assets/images/missingstock.png" alt="missing-stock-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>Missing Stock</h3>
                        <h4><?php echo $missing_stock_query->num_rows;?></h4>
                    </div>
                </div>
            </a>
            <a href="purchase_entry.php">
                <div class="inventory_card">
                    <div>
                        <span><img src="../assets/images/purchaseentry.png" alt="Purchase-Entry-img"></span>
                    </div>
                    <div class="inv_card_text">
                        <h3>Purchase Entry</h3>
                        <h4><?php echo $purchase_entry_query->num_rows;?></h4>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div>
        <?php
            require_once "logoutpopup.php";
        ?>
    </div>

    
<!-- <script src="../assets/js/common_script.js"></script> -->
<script src="../assets/js/session_check.js"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
