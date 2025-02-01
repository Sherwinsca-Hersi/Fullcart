<span class="leftbar_container">
        <input type="checkbox" id="check">
        <label for="check">
            <i class="fa fa-bars"  id="btn"></i>
            <i class="fa fa-times"  id="cancel"></i>
        </label>
        <!-- <img src="..\assets\images\<?php echo $imgname; ?>"  class="mobileview_logo" width="10em" height="10em"> -->
        <a href="dashboard.php"><img src="..\<?php echo $imgname; ?>"  class="mobileview_logo" width="10em" height="10em"></a>
        <div class="leftbar">
            <div class="navigationbar">
            <ul class="vertical-menu">


				<li><a href="dashboard.php"><h3>Dashboard</h3></a></li>
                <li><a href="orders.php"><h3>Orders</h3></a></li>
                <li><a href="revenue.php"><h3>Revenue</h3></a></li>
                <li>
                    <a href="inventory.php" class="mainmenu_toggle"><h3>Inventory</h3><span><i class="fa fa-solid fa-angle-down"></i></span></a>
                    <!-- <a href="inventory.php" class="mainmenu_toggle"><h3>Inventory</h3></a> -->
                    <!-- <a href="#" class="mainmenu_toggle"><h3>Report</h3><span><i class="fa fa-solid fa-angle-down"></i></span> -->
                    <ul class="vertical-submenu">
                        <li><a href="inventory_addStock.php"><h3>Add Stock</h3></a></li>
                        <!-- <li><a href="inventory.php"><h3>Inventory Dashboard</h3></a></li> -->
                        <!-- <li><a href="stock_report.php"><h3>Stock Report</h3></a></li> -->
                        <!-- <li><a href="sales_report.php"><h3>Sales Report</h3></a></li> -->
                        <!-- <li><a href="customer_sales_report.php"><h3>Customer Sales Report</h3></a></li> -->
                    </ul>
                </li>
                <li><a href="customers.php"><h3>Customers</h3></a></li>
                <!-- <li><a href="customer_sales_report.php"><h3>Customers Sales Report</h3></a></li> -->
                <li>
                    <a href="vendors.php" class="mainmenu_toggle"><h3><?php echo $vendor; ?>s</h3><span><i class="fa fa-solid fa-angle-down"></i></span></a>
                    <ul class="vertical-submenu">
                        <li><a href="vendorsStock.php"><h3><?php echo $vendor; ?>s Stock</h3></a></li>
                    </ul>
                </li>
                <li>
                    <a href="products.php" class="mainmenu_toggle"><h3>Products</h3><span><i class="fa fa-solid fa-angle-down"></i></span></a>
                    <ul class="vertical-submenu">
                        <li><a href="addproduct.php">Add Product</a></li>
                        <li><a href="category.php">Product Categories</a></li>
                        <li><a href="subcategory.php">Product Subcategories</a></li>
                    </ul>
                </li>
                <li><a href="deliveryPerson.php"><h3>Delivery Persons</h3></a></li>
                <li><a href="expense.php"><h3>Expenses</h3></a></li>
                <li><a href="banners.php"><h3>Banners</h3></a></li>
                <li><a href="coupons.php"><h3>Coupons</h3></a></li>
                <!--<li><a href="#"><h3>Account/Profile</h3></a></li>
                <li><a href="#"><h3>Help/Support</h3></a></li>
                <li><a href="#"><h3>Settings</h3></a></li> -->
                <li><a href="employee.php"><h3>Employees/Roles</h3></a></li>
                <li><a href="#" id="logout"><h3>Logout</h3></a></li>
            </ul>
            </div>
        </div>
    </span>