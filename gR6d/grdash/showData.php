<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php
require '../api/header.php';
//New Customer
$customer_new = "SELECT id,name,mobile FROM e_user_details
WHERE cos_id = '$cos_id' GROUP BY id ORDER BY created_ts DESC  LIMIT 6";

$cust_data = $mysqli->query($customer_new);

$custDetails = [];


if ($cust_data->num_rows > 0) {
    while ($row = $cust_data->fetch_assoc()) {
        $custDetails[]=$row;
    }
}

$products_new = "SELECT id,p_title,p_variation,unit,p_img  FROM e_product_details
WHERE type!=2 AND cos_id = '$cos_id' GROUP BY id ORDER BY created_ts DESC  LIMIT 6";

$prod_data = $mysqli->query($products_new);

$prodDetails = [];


if ($prod_data->num_rows > 0) {
    while ($row = $prod_data->fetch_assoc()) {
        $prodDetails[]=$row;
    }
}

// print_r($prodDetails);

$newCustomers = $custDetails;
// print_r($newCustomer);
$newProducts = $prodDetails;
// print_r($weeklyProducts);

?>

<script>
    // Data from PHP
    const newCustomers = <?php echo json_encode($newCustomers); ?>;

    const newProducts = <?php echo json_encode($newProducts); ?>;
    function displayNewCust() {
    const dataDiv = document.getElementById('data');
    const chartDiv = document.querySelector('.linechart_div');

    dataDiv.innerHTML = '';  // Clear any existing data
    chartDiv.style.display = 'none';  // Hide the chart div
    dataDiv.style.display = 'grid';  // Show the data div

    // Loop through each customer and create a card for them
    newCustomers.forEach(customer => {
        const customerCard = `
                <div class="cust_card_dash">
                    <div><i class="fa fa-3x fa-solid fa-user"></i></div>
                    <h3 style="white-space:nowrap;"> ${customer.name} </h3>
                    <p>Mobile: ${customer.mobile}</p>
                </div>
        `;
        dataDiv.innerHTML += customerCard;
    });
}

    // Function to display Product data
    function displayNewProducts() {
        const dataDiv = document.getElementById('data');
        const chartDiv = document.querySelector('.linechart_div');
        dataDiv.innerHTML = '';
        chartDiv.style.display = 'none';
        dataDiv.style.display = 'grid';

        newProducts.forEach(product => {
            const productCard = `
                <div class="prod_card_dash">
                    <h3>${product.p_title}</h3>
                    <p>${product.p_variation} ${product.unit}</p>
                    <img src="../${product.p_img}"  width="50px" height="50px">
                </div>
             `;
            dataDiv.innerHTML += productCard;
        });
    }
    
    

let chartInstance2 = null;
function displayRevenueChart() {
    const chartDiv = document.querySelector('.linechart_div');
    const dataDiv = document.getElementById('data');
    dataDiv.style.display = 'none';
    chartDiv.style.display = 'block';  // Show chart div

    const lineCtx = document.getElementById('lineChart').getContext('2d');

    // Destroy the existing chart instance before creating a new one
    if (chartInstance2) {
        chartInstance2.destroy();
    }

    // Fetch sales data from PHP
    const sales_amount = <?php echo json_encode($total_sales_per_month); ?>;

    // Labels for months
    const labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];

    // Chart configuration
    const lineConfig = {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                data: sales_amount,
                backgroundColor: 'rgba(120, 121, 241, 0.2)',  // Transparent background for the chart
                borderColor: 'rgba(120, 121, 241, 1)',  // Line color
                borderWidth: 2,
                fill: true  // Filling under the line
            }]
        },
        options: {
            responsive: true,  // Make the chart responsive
            scales: {
                x: {
                    grid: {
                        color: '#f2f2f2'  
                    }
                },
                y: {
                    grid: {
                        color: '#f2f2f2' 
                    },
                    beginAtZero: false, 
                    ticks: {
                        callback: function(value) {
                            return '₹' + value; 
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: false 
                },
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    formatter: (value) => '₹' + value,
                    color: '#00004d', 
                    font: {
                        weight: 'bold'
                    }
                }
            }
        },
        plugins: [ChartDataLabels]
    };

    chartInstance2 = new Chart(lineCtx, lineConfig);
}


    document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('#button-selector1 button');
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
                case 'revenue':
                    updateHeader('Revenue Chart (Monthly)');
                    displayRevenueChart();
                    break;
                case 'customers':
                    updateHeader('New Customers');
                    displayNewCust();
                    break;
                case 'products':
                    updateHeader('New Products');
                    displayNewProducts();
                    break;
                default:
                    break;
            }
        });
    });

    function updateHeader(title) {
        document.getElementById('chart-heading').textContent = title;
    }

    const defaultButton = document.querySelector('#button-selector1 button[data-value="revenue"]');
    console.log(defaultButton)
    if (defaultButton) {
        highlightSelectedButton(defaultButton);
        updateHeader('Revenue Chart (Monthly)');
        displayRevenueChart();
    }
});

</script>
</body>
</html>
