<div class="sidebar">
        <div class="logobar">
            <a href="dashboard.php"><img src="..\assets\images\<?php echo $imgname; ?>"></a>
        </div>
        <div class="navigationbar">
            <ul class="vertical-menu">
                <li><a href="dashboard.php"><h3>Dashboard</h3></a></li>
                <?php
            if($_SESSION['role']==3 || $_SESSION['role']==5 || $_SESSION['role']==6){
                ?>
                <li><a href="orders.php"><h3>Orders</h3></a></li>
            <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==7 || $_SESSION['role']==9){
            ?>
                <li><a href="revenue.php"><h3>Revenue</h3></a></li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==7 || $_SESSION['role']==9){
            ?>    
                <li>
                    <a href="bankDetails.php"><h3>Bank Details</h3><span><i class="fa fa-solid fa-angle-down"></i></span></a>
                    <ul class="vertical-submenu">
                        <li><a href="addBank.php"><h3>Add Bank</h3></a></li>
                    </ul>
                </li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==9){
            ?>    
                <li><a href="wallet.php"><h3>Wallet</h3></a></li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==4){
            ?>    
                <li>
                    <a href="inventory.php" class="mainmenu_toggle"><h3>Inventory</h3><span><i class="fa fa-solid fa-angle-down"></i></span></a>
                    <ul class="vertical-submenu">
                        <li><a href="inventory_addStock.php"><h3>Add Stock</h3></a></li>
                    </ul>
                </li>
                <?php 
            }
            if($_SESSION['role']==5){
            ?>    
                <li><a href="customers.php"><h3>Customers</h3></a></li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==8){
            ?>    
                <li><a href="https://fullcomm.in/UAT/billing/web/" target="blank"><h3>Billing</h3></a></li>
                <?php 
            }
            if($_SESSION['role']==5){
            ?>    
                <li>
                    <a href="vendors.php" class="mainmenu_toggle"><h3><?php echo $vendor; ?>s</h3><span><i class="fa fa-solid fa-angle-down"></i></span></a>
                    <ul class="vertical-submenu">
                        <li><a href="vendorsStock.php"><h3><?php echo $vendor; ?>s Stock</h3></a></li>
                    </ul>
                </li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==4){
            ?>    
                <li>
                    <a href="products.php" class="mainmenu_toggle"><h3>Products</h3><span><i class="fa fa-solid fa-angle-down"></i></span></a>
                    <ul class="vertical-submenu">
                        <li><a href="addproduct.php">Add Product</a></li>
                        <li><a href="category.php">Product Categories</a></li>
                        <li><a href="subcategory.php">Product Subcategories</a></li>
                    </ul>
                </li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==6 ||$_SESSION['role']==9){
            ?>    
                <li><a href="deliveryPerson.php"><h3>Delivery Persons</h3></a></li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==9){
            ?>   
                <li><a href="expense.php"><h3>Expenses</h3></a></li>
                <?php 
            }
            if($_SESSION['role']==5){
            ?>   
                <li><a href="banners.php"><h3>Banners</h3></a></li>
                <li><a href="coupons.php"><h3>Coupons</h3></a></li>
                <?php 
            }
            if($_SESSION['role']==5 || $_SESSION['role']==9){
            ?>   
                
                <li><a href="employee.php"><h3>Employees/Roles</h3></a></li>
            <?php
            }
            ?>
            <!--<li><a href="#"><h3>Account/Profile</h3></a></li>
                <li><a href="#"><h3>Help/Support</h3></a></li>
                <li><a href="#"><h3>Settings</h3></a></li> -->
            <li><a href="#" id="logout"><h3>Logout</h3></a></li>
            </ul>
        </div>
        <h6>Version.0.0.4</h6>
    </div>
    <script>
    // document.addEventListener('DOMContentLoaded', function () {
    //     const toggles = document.querySelectorAll('.mainmenu_toggle');
    //     const submenuLinks = document.querySelectorAll('.vertical-submenu a');
    //     const mainMenuItems = document.querySelectorAll('.vertical-menu > li');
    //     const subMenuItems = document.querySelectorAll('.vertical-submenu > li');

    //     // Function to remove 'active' class from all menu items
    //     function clearActiveClass(items) {
    //         items.forEach(item => item.classList.remove('active'));
    //     }

    //     // Function to set 'active' class based on localStorage
    //     function setActiveFromLocalStorage() {
    //         const activeMainMenu = localStorage.getItem('activeMainMenu');
    //         const activeSubMenu = localStorage.getItem('activeSubMenu');

    //         if (activeMainMenu) {
    //             const mainMenuItem = document.querySelector(`.vertical-menu > li[data-id="${activeMainMenu}"]`);
    //             if (mainMenuItem) {
    //                 mainMenuItem.classList.add('active');
    //                 mainMenuItem.classList.add('open'); // Ensure the submenu is open
    //                 const submenu = mainMenuItem.querySelector('.vertical-submenu');
    //                 if (submenu) {
    //                     submenu.style.display = 'block'; // Ensure the submenu is displayed
    //                 }
    //             }
    //         }

    //         if (activeSubMenu) {
    //             const subMenuItem = document.querySelector(`.vertical-submenu > li[data-id="${activeSubMenu}"]`);
    //             if (subMenuItem) {
    //                 subMenuItem.classList.add('active');
    //                 const parentMenuItem = subMenuItem.closest('.vertical-menu > li');
    //                 if (parentMenuItem) {
    //                     parentMenuItem.classList.add('active');
    //                     parentMenuItem.classList.add('open'); // Ensure the parent menu item is open
    //                     const submenu = parentMenuItem.querySelector('.vertical-submenu');
    //                     if (submenu) {
    //                         submenu.style.display = 'block'; // Ensure the submenu is displayed
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     // Add click event listener to main menu items
    //     mainMenuItems.forEach((item, index) => {
    //         item.setAttribute('data-id', `main-${index}`); // Assign a unique data-id
    //         item.addEventListener('click', function () {
    //             // Clear 'active' class from all main menu items
    //             clearActiveClass(mainMenuItems);
                
    //             // Add 'active' class to the clicked main menu item
    //             item.classList.add('active');

    //             // Store active main menu item in localStorage
    //             localStorage.setItem('activeMainMenu', item.getAttribute('data-id'));
    //             localStorage.removeItem('activeSubMenu'); // Clear active submenu item
    //         });
    //     });

    //     // Add click event listener to submenu items
    //     subMenuItems.forEach((item, index) => {
    //         item.setAttribute('data-id', `sub-${index}`); // Assign a unique data-id
    //         item.addEventListener('click', function (event) {
    //             // Prevent event propagation to avoid triggering parent menu item click event
    //             event.stopPropagation();

    //             // Clear 'active' class from all submenu items
    //             clearActiveClass(subMenuItems);

    //             // Add 'active' class to the clicked submenu item
    //             item.classList.add('active');

    //             // Also add 'active' class to the parent main menu item
    //             const parentMenuItem = item.closest('.vertical-menu > li');
    //             if (parentMenuItem) {
    //                 parentMenuItem.classList.add('active');
    //             }

    //             // Store active submenu item in localStorage
    //             localStorage.setItem('activeSubMenu', item.getAttribute('data-id'));
    //             if (parentMenuItem) {
    //                 localStorage.setItem('activeMainMenu', parentMenuItem.getAttribute('data-id'));
    //             }
    //         });
    //     });

    //     // Toggle submenu display on main menu click
    //     toggles.forEach(function (toggle) {
    //         toggle.addEventListener('click', function (e) {
    //             var submenu = this.nextElementSibling;
    //             var parent = this.parentNode;
    //             if (submenu.style.display === 'block') {
    //                 submenu.style.display = 'none';
    //                 parent.classList.remove('open');
    //             } else {
    //                 submenu.style.display = 'block';
    //                 parent.classList.add('open');
    //             }
    //         });
    //     });

    //     // Prevent submenu links from closing the submenu
    //     submenuLinks.forEach(function (link) {
    //         link.addEventListener('click', function (e) {
    //             e.stopPropagation();
    //             // Keep the submenu open
    //             const parentMenuItem = this.closest('.vertical-menu > li');
    //             if (parentMenuItem) {
    //                 parentMenuItem.classList.add('open');
    //                 const submenu = parentMenuItem.querySelector('.vertical-submenu');
    //                 if (submenu) {
    //                     submenu.style.display = 'block';
    //                 }
    //             }
    //         });
    //     });

    //     // Set active classes based on localStorage
    //     setActiveFromLocalStorage();
    // });


    document.addEventListener('DOMContentLoaded', function () {
    const toggles = document.querySelectorAll('.mainmenu_toggle');
    const submenuLinks = document.querySelectorAll('.vertical-submenu a');
    const mainMenuItems = document.querySelectorAll('.vertical-menu > li');
    const subMenuItems = document.querySelectorAll('.vertical-submenu > li');
    const logoutItem = document.querySelector('#logout').parentElement;

    // Function to remove 'active' class from all menu items
    function clearActiveClass(items) {
        items.forEach(item => item.classList.remove('active'));
    }

    // Function to set default active classes
    function setDefaultActive() {
        const defaultMainMenu = mainMenuItems[0];
        if (defaultMainMenu) {
            defaultMainMenu.classList.add('active', 'open');
            const defaultSubMenu = defaultMainMenu.querySelector('.vertical-submenu');
            if (defaultSubMenu) {
                defaultSubMenu.style.display = 'block';
            }
            localStorage.setItem('activeMainMenu', defaultMainMenu.getAttribute('data-id'));
        }
    }

    // Function to set 'active' class based on localStorage
    function setActiveFromLocalStorage() {
        const activeMainMenu = localStorage.getItem('activeMainMenu');
        const activeSubMenu = localStorage.getItem('activeSubMenu');

        if (activeMainMenu) {
            const mainMenuItem = document.querySelector(`.vertical-menu > li[data-id="${activeMainMenu}"]`);
            if (mainMenuItem) {
                mainMenuItem.classList.add('active');
                mainMenuItem.classList.add('open'); // Ensure the submenu is open
                const submenu = mainMenuItem.querySelector('.vertical-submenu');
                if (submenu) {
                    submenu.style.display = 'block'; // Ensure the submenu is displayed
                }
            }
        }

        if (activeSubMenu) {
            const subMenuItem = document.querySelector(`.vertical-submenu > li[data-id="${activeSubMenu}"]`);
            if (subMenuItem) {
                subMenuItem.classList.add('active');
                const parentMenuItem = subMenuItem.closest('.vertical-menu > li');
                if (parentMenuItem) {
                    parentMenuItem.classList.add('active');
                    parentMenuItem.classList.add('open'); // Ensure the parent menu item is open
                    const submenu = parentMenuItem.querySelector('.vertical-submenu');
                    if (submenu) {
                        submenu.style.display = 'block'; // Ensure the submenu is displayed
                    }
                }
            }
        }
    }

    // Set the first vertical menu as the default if localStorage is empty
    if (!localStorage.getItem('activeMainMenu')) {
        mainMenuItems.forEach((item, index) => {
            item.setAttribute('data-id', `main-${index}`); // Assign a unique data-id
        });
        setDefaultActive();
    } else {
        mainMenuItems.forEach((item, index) => {
            item.setAttribute('data-id', `main-${index}`); // Assign a unique data-id
        });
    }

    // Add click event listener to main menu items
    mainMenuItems.forEach(item => {
        item.addEventListener('click', function () {
            // If logout item is clicked, reset to default
            if (item === logoutItem) {
                localStorage.removeItem('activeMainMenu');
                localStorage.removeItem('activeSubMenu');
                clearActiveClass(mainMenuItems);
                setDefaultActive();
                return;
            }

            // Clear 'active' class from all main menu items
            clearActiveClass(mainMenuItems);

            // Add 'active' class to the clicked main menu item
            item.classList.add('active');

            // Store active main menu item in localStorage
            localStorage.setItem('activeMainMenu', item.getAttribute('data-id'));
            localStorage.removeItem('activeSubMenu'); // Clear active submenu item
        });
    });

    // Add click event listener to submenu items
    subMenuItems.forEach((item, index) => {
        item.setAttribute('data-id', `sub-${index}`); // Assign a unique data-id
        item.addEventListener('click', function (event) {
            // Prevent event propagation to avoid triggering parent menu item click event
            event.stopPropagation();

            // Clear 'active' class from all submenu items
            clearActiveClass(subMenuItems);

            // Add 'active' class to the clicked submenu item
            item.classList.add('active');

            // Also add 'active' class to the parent main menu item
            const parentMenuItem = item.closest('.vertical-menu > li');
            if (parentMenuItem) {
                parentMenuItem.classList.add('active');
            }

            // Store active submenu item in localStorage
            localStorage.setItem('activeSubMenu', item.getAttribute('data-id'));
            if (parentMenuItem) {
                localStorage.setItem('activeMainMenu', parentMenuItem.getAttribute('data-id'));
            }
        });
    });

    // Toggle submenu display on main menu click
    toggles.forEach(function (toggle) {
        toggle.addEventListener('click', function (e) {
            var submenu = this.nextElementSibling;
            var parent = this.parentNode;
            if (submenu.style.display === 'block') {
                submenu.style.display = 'none';
                parent.classList.remove('open');
            } else {
                submenu.style.display = 'block';
                parent.classList.add('open');
            }
        });
    });

    // Prevent submenu links from closing the submenu
    submenuLinks.forEach(function (link) {
        link.addEventListener('click', function (e) {
            e.stopPropagation();
            // Keep the submenu open
            const parentMenuItem = this.closest('.vertical-menu > li');
            if (parentMenuItem) {
                parentMenuItem.classList.add('open');
                const submenu = parentMenuItem.querySelector('.vertical-submenu');
                if (submenu) {
                    submenu.style.display = 'block';
                }
            }
        });
    });

    // Set active classes based on localStorage
    setActiveFromLocalStorage();
});
</script>