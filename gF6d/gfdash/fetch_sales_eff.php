<?php
require '../api/header.php';
$monthly_sales_efficiency_query = $mysqli->query("
    SELECT 
        MONTH(op.created_ts) AS month, 
        SUM(ps.in_price * op.p_quantity) AS purchased_amount, 
        SUM(op.p_price) AS sales_total
    FROM 
        `e_normal_order_product_details` AS op 
    JOIN 
    `e_normal_order_details` AS od 
    ON
        op.o_id=od.id
    JOIN 
        `e_product_details` AS pd 
    ON 
        pd.p_title = op.p_title 
        AND pd.cos_id = op.cos_id 
    JOIN 
        `e_product_stock` AS ps 
    ON  
        op.batch_no = ps.s_batch_no 
        AND pd.id = ps.s_product_id  
    WHERE 
        pd.cos_id = '$cos_id' AND 
        od.active=6
        AND YEAR(op.created_ts) = YEAR(CURDATE()) 
    GROUP BY 
        MONTH(op.created_ts)
");
$monthly_sales_data = array_fill(1, 12, [
    'purchased_amount' => 0,
    'sales_total' => 0,
    'sales_efficiency' => 0
]);
// $monthly_sales_data = [];
while ($row = $monthly_sales_efficiency_query->fetch_assoc()) {
    $monthly_sales_data[$row['month']] = [
        'purchased_amount' => $row['purchased_amount'],
        'sales_total' => $row['sales_total'],
        'sales_efficiency' => $row['sales_total'] / $row['purchased_amount']
    ];
}

$chart_data = [];
for ($i = 1; $i <= 12; $i++) {
    if (isset($monthly_sales_data[$i])) {
        $chart_data[] = $monthly_sales_data[$i]['sales_efficiency'];
    } else {
        $chart_data[] = 0; // No data for this month
    }
}
// print_r($monthly_sales_data);
?>
<script>
    var ctx = document.getElementById('salesEfficiencyChart').getContext('2d');
    var salesEfficiencyChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            datasets: [{
                label: 'Sales Efficiency',
                data: <?php echo json_encode($chart_data); ?>,
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true,
                pointBackgroundColor: 'rgba(75, 192, 192, 1)',
                pointBorderColor: '#fff',
                pointRadius: 5,
                pointHoverRadius: 7,
                pointHoverBackgroundColor: 'rgba(220, 20, 60, 1)',
                pointHoverBorderColor: '#fff',
                pointHitRadius: 10,
                pointBorderWidth: 2,
                showLine: true // Show line even if no data points
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toFixed(2); // Format as a percentage or decimal
                        }
                    }
                }
            },
            plugins: {
                datalabels: {
                    align: 'top',
                    anchor: 'end',
                    backgroundColor: 'rgba(75, 192, 192, 0.7)',
                    borderRadius: 4,
                    color: 'white',
                    font: {
                        weight: 'bold'
                    },
                    formatter: function(value) {
                        return value.toFixed(2);
                    }
                }
            }
        }
    });
</script>
