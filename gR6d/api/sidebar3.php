<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        require '../api/header.php';  
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $project_name;?></title>
</head>
<body>
<div class="sidemenu">
    <div class="logobar">
        <a href="dashboard.php" class="menu-link" data-toggle="submenu"><img src="..\<?php echo $imgname; ?>"></a>
    </div>
    <div class="menu-item">
        <a href="dashboard.php" class="menu-link" data-url="dashboard.php">Dashboard</a>
    </div>
    <?php
    if($_SESSION['role']==3 || $_SESSION['role']==5 || $_SESSION['role']==6){
        ?>
            <div class="menu-item">
                <a href="orders.php" class="menu-link" data-url="orders.php">Orders</a>
            </div> 
    <?php 
    }
    if($_SESSION['role']==5 || $_SESSION['role']==7 || $_SESSION['role']==9){
    ?>
        <div class="menu-item">
            <a href="revenue.php" class="menu-link" data-url="revenue.php">Revenue</a>
        </div> 
    <?php 
    }
    if($_SESSION['role']==5 || $_SESSION['role']==4){
            ?>    
            <div class="menu-item">
                <a href="inventory.php" class="menu-link" data-url="inventory.php">Inventory <span class="arrow down"></span></a>
                <div class="submenu">
                    <a href="inventory_addStock.php" class="submenu-link" data-url="inventory_addStock.php">Add Stock</a>
                    <a href="multipleStock.php" class="submenu-link" data-url="multipleStock.php">Add Multiple Stock</a>
                </div>
            </div>
            <div class="menu-item">
                <a href="products.php" class="menu-link" data-url="products.php">Products <span class="arrow down"></span></a>
                <div class="submenu">
                    <a href="addproduct.php" class="submenu-link" data-url="addproduct.php">Add Product</a>
                    <a href="category.php" class="submenu-link" data-url="category.php">Product Categories</a>
                    <a href="subcategory.php" class="submenu-link" data-url="subcategory.php">Product Subcategories</a>
                    <a href="level.php" class="submenu-link" data-url="level.php">Reorder/Low Stock Level</a>
                </div>
            </div>
            <div class="menu-item">
                <a href="combo.php" class="menu-link" data-url="combo.php">Combo</a>
            </div>
    <?php 
    }
    if($_SESSION['role']==5){
        ?>    
            <div class="menu-item">
                <a href="customers.php" class="menu-link" data-url="customers.php">Customers <span class="arrow down"></span></a>
                <div class="submenu">
                    <a href="unpurchaseCust.php" class="submenu-link" data-url="unpurchaseCust.php">Unpurchased Customers</a>
                </div>
            </div>
    <?php 
    }
    if($_SESSION['role']==5){
    ?>    
        <div class="menu-item">
            <a href="vendors.php" class="menu-link" data-url="vendors.php">Vendors <span class="arrow down"></span></a>
            <div class="submenu">
                <a href="vendorsStock.php" class="submenu-link" data-url="vendorsStock.php">Vendor's Stock</a> 
            </div>
        </div>
    <?php 
    }
    if($_SESSION['role']==5 || $_SESSION['role']==9){
        ?>   
        <div class="menu-item">
            <a href="employee.php" class="menu-link" data-url="employee.php">Employees</a>
        </div>  
    <?php
    }
    if($_SESSION['role']==5 || $_SESSION['role']==7 || $_SESSION['role']==9){
    ?>     
        <div class="menu-item">
            <a href="bankDetails.php" class="menu-link" data-url="bankDetails.php">Bank Details <span class="arrow down"></span></a>
            <div class="submenu">
                <a href="addBank.php" class="submenu-link" data-url="addBank.php">Add Bank</a> 
            </div>
        </div>
    <?php 
    }
    if($_SESSION['role']==5 || $_SESSION['role']==9){
    ?>    
        <div class="menu-item">
            <a href="wallet.php" class="menu-link" data-url="wallet.php">Wallet</a>
        </div>
    <?php 
    }
    if($_SESSION['role']==5 || $_SESSION['role']==6 ||$_SESSION['role']==9){
    ?>    
        <div class="menu-item">
            <a href="deliveryPerson.php" class="menu-link" data-url="deliveryPerson.php">Delivery Persons</a>
        </div>
    <?php 
    }
    if($_SESSION['role']==5 || $_SESSION['role']==9){
        ?>  
            <div class="menu-item">
                <a href="expense.php" class="menu-link" data-url="expense.php">Expenses</a>
            </div>
    <?php 
    }
    if($_SESSION['role']==5){
    ?>   
        <div class="menu-item">
            <a href="banners.php" class="menu-link" data-url="banners.php">Banners</a>
        </div>
        <div class="menu-item">
            <a href="coupons.php" class="menu-link" data-url="coupons.php">Coupons</a>
        </div>
        <div class="menu-item">
            <a href="#" class="menu-link" data-toggle="submenu">Settings <span class="arrow down"></span></a>
            <div class="submenu">
                <a href="bankDetails.php" class="submenu-link" data-url="bankDetails.php">Bank Details</a>
                <a href="employee.php" class="submenu-link" data-url="employee.php">Employees</a>
                <a href="banners.php" class="submenu-link" data-url="banners.php">Banners</a>
                <a href="coupons.php" class="submenu-link" data-url="coupons.php">Coupons</a>
                <a href="category.php" class="submenu-link" data-url="category.php">Product Categories</a>
                <a href="subcategory.php" class="submenu-link" data-url="subcategory.php">Product Subcategories</a>
                <a href="level.php" class="submenu-link" data-url="level.php">Reorder/Low Stock Level</a>
                <a href="timeslot.php" class="submenu-link" data-url="level.php">Time Slot</a>
                <a href="profile.php" class="menu-link" data-url="profile.php">Business Profile</a>
            </div>
        </div>
    <?php 
    }
    ?>
    <div class="menu-item">
        <a href="#" class="menu-link" data-toggle="submenu" id="logout">Logout</a>
    </div>
    
</div>

<div class="content" id="content-area"></div>

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
    });
});

window.addEventListener('load', function() {
    let { mainMenu, submenu } = getActiveMenu();

    if (!mainMenu || mainMenu === 'Logout') {
        mainMenu = 'Dashboard';
        submenu = null;
    }

    document.querySelectorAll('.menu-link').forEach(link => {
        if (link.textContent.trim() === mainMenu) {
            link.classList.add('active');

            const submenuElement = link.nextElementSibling;
            if (submenuElement) {
                submenuElement.classList.add('open');
                if (submenu) {
                    const activeSubmenuLink = Array.from(submenuElement.querySelectorAll('.submenu-link'))
                        .find(sublink => sublink.textContent.trim() === submenu);

                    if (activeSubmenuLink) {
                        activeSubmenuLink.classList.add('active');
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

document.querySelector('.logout-link').addEventListener('click', function() {
    clearActiveMenuOnLogout();
});
</script>

</body>
</html>
