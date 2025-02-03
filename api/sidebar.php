<?php 

$result = $mysqli->query("SELECT id, role_title FROM `e_salesman_role` WHERE cos_id='$cos_id' GROUP BY id,role_title");

$delivery_person = $packager = $inventory_clerk = $admin = $order_processor = $revenue_manager = $biller = $accountant = $hr = $customer_manager = null;

while ($row = $result->fetch_assoc()) {
    switch ($row['role_title']) {
        case 'Delivery Person':
            $delivery_person = $row['id'];
            break;
        case 'Packager':
            $packager = $row['id'];
            break;
        case 'Inventory Clerk':
            $inventory_clerk = $row['id'];
            break;
        case 'Admin':
            $admin = $row['id'];
            break;
        case 'Order Processor':
            $order_processor = $row['id'];
            break;
        case 'Revenue Manager':
            $revenue_manager = $row['id'];
            break;
        case 'Biller':
            $biller = $row['id'];
            break;
        case 'Accountant/Financier':
            $accountant = $row['id'];
            break;
        case 'HR':
            $hr = $row['id'];
            break;
        case 'Customer Manager':
            $customer_manager = $row['id'];
            break;
    }
}

if(isset($_SESSION['mobile']) || isset($_SESSION['password'])){
    $store_count=$mysqli->query("SELECT id, cos_id,username FROM `e_dat_admin` WHERE mobile=".$_SESSION['mobile']." AND password=".$_SESSION['password']."")->num_rows;
}
?>

<div class="sidemenu">
    <div class="logobar">
        <a href="dashboard.php" class="menu-link" data-toggle="submenu"><img src="..\..\<?php echo $imgname; ?>"></a>
    </div>
    <?php
    if($store_count==2){
        ?>
        <div class="menu-item">
            <a href="chooseStore.php" class="menu-link">Home</a>
        </div>
    <?php
    }
    ?>
    <div class="menu-item">
        <a href="dashboard.php" class="menu-link" data-url="dashboard.php" id="dashboard-menu">Dashboard</a>
    </div>
    <?php
    if($_SESSION['role']==$delivery_person || $_SESSION['role']==$packager || $_SESSION['role']==$admin || 
    $_SESSION['role']==$order_processor || $_SESSION['role']==$customer_manager){
        ?>
            <div class="menu-item">
                <a href="orders.php" class="menu-link" data-url="orders.php">Orders</a>
            </div> 
    <?php 
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$revenue_manager || $_SESSION['role']==$accountant){
    ?>
        <div class="menu-item">
            <a href="revenue.php" class="menu-link" data-url="revenue.php">Revenue</a>
        </div> 
    <?php 
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$inventory_clerk){
            ?>    
            <div class="menu-item">
                <a  class="menu-link" data-toggle="submenu">Stock Overview <span class="arrow down"></span></a>
                <div class="submenu">
                    <a href="inventory.php" class="submenu-link" data-url="inventory.php">Inventory</a>
                    <a href="inventory_addStock.php" class="submenu-link" data-url="inventory_addStock.php">Add Stock</a>
                    <a href="multipleStock.php" class="submenu-link" data-url="multipleStock.php">Add Multiple Stock</a>
                </div>
            </div>
            <div class="menu-item">
                <a  class="menu-link" data-toggle="submenu">Products Management<span class="arrow down"></span></a>
                <div class="submenu">
                    <a href="products.php" class="submenu-link" data-url="products.php">Products</a>
                    <a href="addproduct.php" class="submenu-link" data-url="addproduct.php">Add Product</a>
                    <a href="category.php" class="submenu-link" data-url="category.php">Product Categories</a>
                    <a href="subcategory.php" class="submenu-link" data-url="subcategory.php">Product Subcategories</a>
                    <a href="level.php" class="submenu-link" data-url="level.php">Reorder/Low Stock Level</a>
                    <a href="combo.php" class="submenu-link" data-url="combo.php">Combo</a>
                    <a href="productReviews.php" class="submenu-link" data-url="productReviews.php">Product Reviews</a>
                </div>
            </div>
    <?php 
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$hr || $_SESSION['role']==$customer_manager){
        ?>    
            <div class="menu-item">
                <a  class="menu-link" data-toggle="submenu">Customers <span class="arrow down"></span></a>
                <div class="submenu">
                    <a href="customers.php" class="submenu-link" data-url="customers.php">Customers</a>
                    <a href="unpurchaseCust.php" class="submenu-link" data-url="unpurchaseCust.php">Unpurchased Customers</a>
                </div>
            </div>
    <?php 
    }
    if($_SESSION['role']==$admin){
    ?>    
        <div class="menu-item">
            <a  class="menu-link" data-toggle="submenu">Vendors <span class="arrow down"></span></a>
            <div class="submenu">
                <a href="vendors.php" class="submenu-link" data-url="vendors.php">Vendors</a>
                <a href="vendorsStock.php" class="submenu-link" data-url="vendorsStock.php">Vendor's Stock</a> 
            </div>
        </div>
    <?php 
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$accountant || $_SESSION['role']==$hr){
        ?> 
        <div class="menu-item">
            <a  class="menu-link" data-toggle="submenu">Employees/Roles <span class="arrow down"></span></a>
            <div class="submenu">
                <a href="employee.php" class="submenu-link" data-url="employee.php">Employees</a>
                <a href="roles.php" class="submenu-link" data-url="roles.php">Roles</a>
            </div>
        </div> 
    <?php
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$revenue_manager || $_SESSION['role']==$accountant){
    ?>     
        <div class="menu-item">
            <a class="menu-link" data-toggle="submenu">Bank Details <span class="arrow down"></span></a>
            <div class="submenu">
                <a href="bankDetails.php" class="submenu-link" data-url="bankDetails.php">Bank Details</a>
                <a href="addBank.php" class="submenu-link" data-url="addBank.php">Add Bank</a> 
            </div>
        </div>
    <?php 
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$accountant){
    ?>    
        <div class="menu-item">
            <a href="wallet.php" class="menu-link" data-url="wallet.php">Wallet</a>
        </div>
    <?php 
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$order_processor ||$_SESSION['role']==$accountant){
    ?>    
        <div class="menu-item">
            <a href="deliveryPerson.php" class="menu-link" data-url="deliveryPerson.php">Delivery Persons</a>
        </div>
    <?php 
    }
    if($_SESSION['role']==$admin || $_SESSION['role']==$accountant || $_SESSION['role']==$hr){
        ?>  
            <div class="menu-item">
                <a href="expense.php" class="menu-link" data-url="expense.php">Expenses</a>
            </div>
    <?php 
    }
    if($_SESSION['role']==$admin){
    ?>   
        <div class="menu-item">
            <a href="banners.php" class="menu-link" data-url="banners.php">Banners</a>
        </div>
        <div class="menu-item">
            <a href="coupons.php" class="menu-link" data-url="coupons.php">Coupons</a>
        </div>
        <div class="menu-item">
            <a href="feedbackScreen.php" class="menu-link" data-url="feedbackScreen.php">Customer Feedback</a>
        </div>
        <div class="menu-item">
            <a  class="menu-link" data-toggle="submenu">Settings <span class="arrow down"></span></a>
            <div class="submenu">
                <!-- <a href="bankDetails.php" class="submenu-link" data-url="bankDetails.php">Bank Details</a> -->
                <!-- <a href="employee.php" class="submenu-link" data-url="employee.php">Employees</a> -->
                <a href="banners.php" class="submenu-link" data-url="banners.php">Banners</a>
                <a href="coupons.php" class="submenu-link" data-url="coupons.php">Coupons</a>
                <!-- <a href="category.php" class="submenu-link" data-url="category.php">Product Categories</a> -->
                <!-- <a href="subcategory.php" class="submenu-link" data-url="subcategory.php">Product Subcategories</a> -->
                <!-- <a href="level.php" class="submenu-link" data-url="level.php">Reorder/Low Stock Level</a> -->
                <a href="timeslot.php" class="submenu-link" data-url="level.php">Time Slot</a>
                <a href="profile.php" class="submenu-link" data-url="profile.php">Business Profile</a>
                <a href="deliveryFee.php" class="submenu-link" data-url="profile.php">Delivery Fee Details</a>
            </div>
        </div>
    <?php 
    }
    ?>

    <div class="menu-item">
        <a  class="menu-link logout-link" data-toggle="submenu" id="logout">Logout</a>
    </div>
    <h6 class="version">Version.0.0.5</h6>
</div>

<script>
function setActiveMenu(mainMenuId, submenuId) {
    localStorage.setItem('activeMainMenu', mainMenuId);
    localStorage.setItem('activeSubmenu', submenuId);
}

function getActiveMenu() {
    return {
        mainMenu: localStorage.getItem('activeMainMenu'),
        submenu: localStorage.getItem('activeSubmenu')
    };
}

function toggleSubmenu(menuLink, submenu) {
    const isCurrentlyOpen = submenu.classList.contains('open');
    document.querySelectorAll('.submenu').forEach(sub => {
        sub.classList.remove('open');
    });

    // Close all submenus and set active menu
    if (!isCurrentlyOpen) {
        submenu.classList.add('open');
        setActiveMenu(menuLink.textContent.trim(), submenu.querySelector('.submenu-link.active') ? submenu.querySelector('.submenu-link.active').textContent.trim() : null);
    } else {
        setActiveMenu(menuLink.textContent.trim(), null);
    }
}

document.querySelectorAll('.menu-link').forEach(menuLink => {
    menuLink.addEventListener('click', function() {
        const menuItem = this.parentElement;
        const submenu = menuItem.querySelector('.submenu');

        if (submenu) {
            toggleSubmenu(this, submenu);
        }

        document.querySelectorAll('.menu-link').forEach(link => {
            link.classList.remove('active');
        });
        this.classList.add('active');

        setActiveMenu(this.textContent.trim(), null);

        // Delay scroll for smoother effect
        setTimeout(() => {
            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100);
    });
});

document.querySelectorAll('.submenu-link').forEach(submenuLink => {
    submenuLink.addEventListener('click', function(event) {
        event.stopPropagation();

        document.querySelectorAll('.submenu-link').forEach(link => {
            link.classList.remove('active');
        });

        this.classList.add('active');

        const mainMenuText = this.closest('.menu-item').querySelector('.menu-link').textContent.trim();
        setActiveMenu(mainMenuText, this.textContent.trim());

        // Delay scroll for smoother effect
        setTimeout(() => {
            this.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }, 100); // Adjust the delay as needed (100ms)
    });
});

// Highlight Dashboard only if no other menu is active
window.addEventListener('load', function() {
    let { mainMenu, submenu } = getActiveMenu();

    // If no menu is active in localStorage, highlight Dashboard as default
    if (!mainMenu || mainMenu === 'Logout') {
        mainMenu = 'Dashboard';
        submenu = null;
        setActiveMenu(mainMenu, submenu);  // Save 'Dashboard' as the active menu
    }

    // Highlight the saved active menu and submenu (if applicable)
    document.querySelectorAll('.menu-link').forEach(link => {
        if (link.textContent.trim() === mainMenu) {
            link.classList.add('active'); // Highlight the active main menu

            // Delay scroll for smoother effect
            setTimeout(() => {
                link.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }, 100); // Adjust the delay as needed (100ms)

            const submenuElement = link.nextElementSibling;
            if (submenuElement) {
                submenuElement.classList.add('open'); // Open submenu if it exists

                if (submenu) {
                    const activeSubmenuLink = Array.from(submenuElement.querySelectorAll('.submenu-link'))
                        .find(sublink => sublink.textContent.trim() === submenu);

                    if (activeSubmenuLink) {
                        activeSubmenuLink.classList.add('active'); // Highlight the active submenu
                        // Delay scroll for smoother effect
                        setTimeout(() => {
                            activeSubmenuLink.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        }, 100); // Adjust the delay as needed (100ms)
                    }
                }
            }
        }
    });
});

function clearActiveMenuOnLogout() {
    localStorage.removeItem('activeMainMenu');
    localStorage.removeItem('activeSubmenu');
}

document.querySelector('.logout-link').addEventListener('click', function(event) {
    event.preventDefault();
    clearActiveMenuOnLogout();
});
</script>





