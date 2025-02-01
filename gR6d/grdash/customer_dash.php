<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
require '../api/header.php';

$ByRevenue = "SELECT od.u_id,od.name,COUNT(od.id) AS total_orders,SUM(o_total) AS purchased_price,
MAX(o_date) AS latest_odate FROM  `e_normal_order_details` od 
WHERE od.cos_id = '$cos_id' AND od.name!=''
GROUP BY od.u_id
ORDER BY purchased_price DESC
LIMIT 5";

$cust_revenue = $mysqli->query($ByRevenue);

$custRevenue = [];


if ($cust_revenue->num_rows > 0) {
    while ($row = $cust_revenue->fetch_assoc()) {
        $custRevenue[]=$row;
    }
}

$ByPurchase = "SELECT od.u_id,od.name,COUNT(od.id) AS total_orders,SUM(o_total) AS purchased_price,
MAX(o_date) AS latest_odate FROM  `e_normal_order_details` od 
WHERE od.cos_id = '$cos_id' AND od.name!=''
GROUP BY od.u_id
ORDER BY latest_odate DESC
LIMIT 5";

$cust_purchase = $mysqli->query($ByPurchase);

$custPurchase = [];


if ($cust_purchase->num_rows > 0) {
    while ($row = $cust_purchase->fetch_assoc()) {
        $custPurchase[]=$row;
    }
}

$ByOrders = "SELECT od.u_id,od.name,COUNT(od.id) AS total_orders,SUM(o_total) AS purchased_price,
MAX(o_date) AS latest_odate FROM  `e_normal_order_details` od 
WHERE od.cos_id = '$cos_id' AND od.name!=''
GROUP BY od.u_id
ORDER BY total_orders DESC
LIMIT 5";

$cust_orders = $mysqli->query($ByOrders);

$custOrders = [];


if ($cust_orders->num_rows > 0) {
    while ($row = $cust_orders->fetch_assoc()) {
        $custOrders[]=$row;
    }
}

$custByRevenue = $custRevenue;
$custByPurchase = $custPurchase;
$custByOrders = $custOrders;
?>

<script>
    // Data from PHP
    const revenueCust = <?php echo json_encode($custByRevenue); ?>;
    const purchaseCust = <?php echo json_encode($custByPurchase); ?>;
    const ordersCust = <?php echo json_encode($custByOrders); ?>;
    function formatIndianRupees(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR',
        minimumFractionDigits: 2
    }).format(amount);
}
    function displayCustData(customerData) {
    const table = document.querySelector('#cust_table_dash tbody');
    table.innerHTML = '';

    customerData.forEach((customer, index) => {
        const row = `
            <tr class="${index % 2 === 0 ? 'teven' : 'todd'}">
                <td>${customer.u_id}</td>
                <td>${customer.name}</td>
                <td>${customer.total_orders}</td>
                <td>${formatIndianRupees(parseFloat(customer.purchased_price))}</td>
                <td>${customer.latest_odate}</td>
            </tr>
        `;
        table.innerHTML += row;
    });
}
    

    document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('#button-selector2 button');
    function removeActiveClasses() {
        buttons.forEach(button => button.classList.remove('active'));
    }

    function highlightSelectedButton(button) {
        removeActiveClasses();
        button.classList.add('active');
    }

    buttons.forEach(button => {
    button.addEventListener('click', function () {
        highlightSelectedButton(button);
        const dateValue = button.getAttribute('data-value');

        switch (dateValue) {
            case 'byRevenue':
                displayCustData(revenueCust);
                break;
            case 'byOrders':
                displayCustData(ordersCust);
                break;
            case 'byPurchase':
                displayCustData(purchaseCust);
                break;
            default:
                break;
        }
    });
});


    const defaultButton = document.querySelector('#button-selector2 button[data-value="byRevenue"]');
    console.log(defaultButton)
    if (defaultButton) {
        highlightSelectedButton(defaultButton);
        displayCustData(revenueCust);
    }

 
});

</script>
</body>
</html>


