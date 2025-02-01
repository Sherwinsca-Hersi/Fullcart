<?php
require '../api/header.php';


//yearly Data
$currentYear = date('Y');
$yearly = "SELECT 
            MONTHNAME(updated_ts) AS month, 
            p_title, 
            p_type, 
            p_img, 
            SUM(p_quantity) AS total 
        FROM 
            e_normal_order_product_details 
        WHERE 
            cos_id = '$cos_id' 
            AND active = 1 
            AND YEAR(updated_ts) = $currentYear
        GROUP BY 
            month, 
            p_title, 
            p_type 
        ORDER BY 
            STR_TO_DATE(CONCAT($currentYear, '-', MONTH(updated_ts), '-01'), '%Y-%m-%d') DESC, 
            total DESC";

$year_data = $mysqli->query($yearly);

$yearlyProducts = [];

// Initialize months with empty arrays
$allMonths = [
    'January' => [],
    'February' => [],
    'March' => [],
    'April' => [],
    'May' => [],
    'June' => [],
    'July' => [],
    'August' => [],
    'September' => [],
    'October' => [],
    'November' => [],
    'December' => []
];

if ($year_data->num_rows > 0) {
    while ($row = $year_data->fetch_assoc()) {
        $month = $row['month'];
        if (array_key_exists($month, $allMonths)) {
            if (count($allMonths[$month]) < 3) { // Limit to 3 products per month
                $allMonths[$month][] = [
                    'name' => $row['p_title'],
                    'variation' => $row['p_type'],
                    'total' => $row['total']
                ];
            }
        }
    }
}


//weekly Data
$sunday = date('Y-m-d', strtotime('last Sunday'));
$saturday = date('Y-m-d', strtotime('next Saturday'));
// echo $sunday,$saturday;
$weekly = "SELECT 
            DAYNAME(updated_ts) AS day, 
            p_title, 
            p_type, 
            p_img, 
            SUM(p_quantity) AS total 
        FROM 
            e_normal_order_product_details 
        WHERE 
            cos_id = '$cos_id' 
            AND active = 1 
            AND updated_ts BETWEEN '$sunday' AND '$saturday'
        GROUP BY 
            day, 
            p_title, 
            p_type 
        ORDER BY 
            STR_TO_DATE(CONCAT(YEAR(updated_ts), '-', MONTH(updated_ts), '-', DAY(updated_ts)), '%Y-%m-%d') ASC, 
            total DESC";

$week_data = $mysqli->query($weekly);

$weeklyProducts = [];

// Initialize days with empty arrays
$allDaysWeek = [
    'Sunday' => [],
    'Monday' => [],
    'Tuesday' => [],
    'Wednesday' => [],
    'Thursday' => [],
    'Friday' => [],
    'Saturday' => []
];

if ($week_data->num_rows > 0) {
    while ($row = $week_data->fetch_assoc()) {
        $day = $row['day'];
        if (array_key_exists($day, $allDaysWeek)) {
            if (count($allDaysWeek[$day]) < 3) { // Limit to 3 products per day
                $allDaysWeek[$day][] = [
                    'name' => $row['p_title'],
                    'variation' => $row['p_type'],
                    'total' => $row['total']
                ];
            }
        }
    }
}


//monthly Data
$currentMonth = date('m');
$firstDayOfMonth = "$currentYear-$currentMonth-01";
$lastDayOfMonth = date('Y-m-t'); // Last day of the current month

$daysInMonth = date('t');

$monthly = "SELECT 
            DAY(updated_ts) AS day, 
            p_title, 
            p_type, 
            p_img, 
            SUM(p_quantity) AS total 
        FROM 
            e_normal_order_product_details 
        WHERE 
            cos_id = '$cos_id' 
            AND active = 1 
            AND updated_ts BETWEEN '$firstDayOfMonth' AND '$lastDayOfMonth'
        GROUP BY 
            day, 
            p_title, 
            p_type 
        ORDER BY 
            day ASC, 
            total DESC";

$month_data = $mysqli->query($monthly);

$monthlyProducts = [];

// Initialize days with empty arrays
$allDaysMonth = array_fill(1, date('t'), []); // 1 to 31

if ($month_data->num_rows > 0) {
    while ($row = $month_data->fetch_assoc()) {
        $day = (int)$row['day'];
        if (isset($allDaysMonth[$day])) {
            if (count($allDaysMonth[$day]) < 3) { // Limit to 3 products per day
                $allDaysMonth[$day][] = [
                    'name' => $row['p_title'],
                    'variation' => $row['p_type'],
                    'total' => $row['total']
                ];
            }
        }
    }
}


// Ensure that all 12 months are included in the final output
$yearlyProducts = $allMonths;
// print_r($monthlyProducts['January']);
// Ensure that all 7 days are included in the final output
$weeklyProducts = $allDaysWeek;
// print_r($weeklyProducts);

$monthlyProducts = $allDaysMonth;
// print_r($monthlyProducts);

//Today Data

$today = date('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format
echo $today;
$topSellingTodayQuery = "
    SELECT 
        p_title, 
        p_type, 
        p_img, 
        SUM(p_quantity) AS total 
    FROM 
        e_normal_order_product_details 
    WHERE 
        cos_id = '$cos_id' 
        AND active = 1 
        AND DATE(updated_ts) = '$today' 
    GROUP BY 
        p_title, 
        p_type 
    ORDER BY 
        total DESC 
    LIMIT 3
";

$topSellingTodayResult = $mysqli->query($topSellingTodayQuery);

$topSellingTodayProducts = [];

if ($topSellingTodayResult->num_rows > 0) {
    while ($row = $topSellingTodayResult->fetch_assoc()) {
        $topSellingTodayProducts[] = [
            'name' => $row['p_title'],
            'variation' => $row['p_type'],
            'image' => $row['p_img'],
            'total' => $row['total']
        ];
    }
}

// Output the top-selling products for today
print_r($topSellingTodayProducts); 



?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Sales Bar Chart</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
<style>
    div{
        width:50%;
        height:45%;
    }
</style>

<div>
    <canvas id="myChart"></canvas>
</div>
<div>
    <canvas id="myChart2"></canvas>
</div>
<div>
    <canvas id="myChart3"></canvas>
</div>


<div id="date-selector">
    <button data-value="today">Today</button>
    <button data-value="this-week">This Week</button>
    <button data-value="this-month">This Month</button>
    <button data-value="last-year">Last Year</button>
</div>
<div id="content-area">
    
</div>
<script>
  

    // Data from PHP
    const yearlyProducts = <?php echo json_encode($yearlyProducts); ?>;

    // Generate random color
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Extract month names and datasets
    const months = Object.keys(yearlyProducts);
    const datasets_year = [];

    months.forEach((month, monthIndex) => {
        yearlyProducts[month].forEach((product) => {
            const data = Array(months.length).fill(NaN);
            data[monthIndex] = product.total;

            datasets_year.push({
                label: `${product.name} (${product.variation})`, // Label with product name and variation
                data: data, // Align data with the correct month
                backgroundColor: getRandomColor(), // Assign a unique color
                borderColor: 'rgba(0, 0, 0, 0.1)',
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(255, 99, 132, 0.2)',
                hoverBorderColor: 'rgba(255, 99, 132, 1)',
            });
        });
    });

    // Render the chart
    const ctx_year = document.getElementById('myChart').getContext('2d');
    new Chart(ctx_year, {
        type: 'bar',
        data: {
            labels: months,
            datasets: datasets_year
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: true
                },
                y: {
                    stacked: true,
                    beginAtZero: true
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const dataset = tooltipItem.dataset;
                            const label = dataset.label || '';
                            const value = dataset.data[tooltipItem.dataIndex];
                            return `${label}: ${value} units sold`;
                        }
                    }
                }
            }
        }
    });
</script>
   
<script>
    // Data from PHP
    const weeklyProducts = <?php echo json_encode($weeklyProducts); ?>;
    // console.log(weeklyProducts);
    // Generate random color
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Extract day names and datasets
    const days = Object.keys(weeklyProducts);
    const datasets_weekly = [];

    days.forEach((day, dayIndex) => {
        weeklyProducts[day].forEach((product) => {
            const data = Array(days.length).fill(NaN);
            data[dayIndex] = product.total;

            datasets_weekly.push({
                label: `${product.name} (${product.variation})`, // Label with product name and variation
                data: data, // Align data with the correct day
                backgroundColor: getRandomColor(), // Assign a unique color
                borderColor: 'rgba(0, 0, 0, 0.1)',
                borderWidth: 1,
                hoverBackgroundColor: 'rgba(255, 99, 132, 0.2)',
                hoverBorderColor: 'rgba(255, 99, 132, 1)',
            });
        });
    });

    // Render the chart
    const ctx_week = document.getElementById('myChart2').getContext('2d');
    new Chart(ctx_week, {
        type: 'bar',
        data: {
            labels: days,
            datasets: datasets_weekly
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: false // Products will be displayed in a single line, not stacked
                },
                y: {
                    stacked: false,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Units Sold'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const dataset = tooltipItem.dataset;
                            const label = dataset.label || '';
                            const value = dataset.data[tooltipItem.dataIndex];
                            return `${label}: ${value} units sold`;
                        }
                    }
                }
            }
        }
    });

</script>


<script>
    // Data from PHP
    const monthlyProducts = <?php echo json_encode($monthlyProducts); ?>;
    const daysInMonth = <?php echo $daysInMonth; ?>; // Pass the number of days in the month to JavaScript

    // Generate random color
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    // Prepare datasets
    const daysInMonthArray = Array.from({ length: daysInMonth }, (v, i) => (i + 1)); // Days of the month (1 to 31)
    const datasets_month = [];

    daysInMonthArray.forEach(day => {
        if (monthlyProducts[day]) {
            monthlyProducts[day].forEach(product => {
                datasets_month.push({
                    label: `${product.name} (${product.variation})`, // Label with product name and variation
                    data: Array.from({ length: daysInMonth }, (v, i) => (i + 1 === day ? product.total : NaN)), // Align data with the correct day
                    backgroundColor: getRandomColor(), // Assign a unique color
                    borderColor: 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1,
                    hoverBackgroundColor: 'rgba(255, 99, 132, 0.2)',
                    hoverBorderColor: 'rgba(255, 99, 132, 1)',
                });
            });
        }
    });

    // Render the chart
    const ctx_month = document.getElementById('myChart3').getContext('2d');
    new Chart(ctx_month, {
        type: 'bar',
        data: {
            labels: daysInMonthArray, // Days of the month (1 to 31)
            datasets: datasets_month
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    stacked: false, // Products will be displayed in a single line, not stacked
                    title: {
                        display: true,
                        text: 'Day of Month'
                    }
                },
                y: {
                    stacked: false,
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Units Sold'
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            const dataset = tooltipItem.dataset;
                            const label = dataset.label || '';
                            const value = dataset.data[tooltipItem.dataIndex];
                            return `${label}: ${value} units sold`;
                        }
                    }
                }
            }
        }
    });

    document.getElementById('date-selector').addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            const dateValue = e.target.getAttribute('data-value');
            const contentArea = document.getElementById('content-area');

            contentArea.classList.remove('today-design', 'week-design', 'month-design', 'year-design');

    switch (dateValue) {
       case 'today':
            console.log('today');
        //  contentArea.classList.add('today-design');
         contentArea.innerHTML = '<canvas id="myChart"></canvas>';
         break;
       case 'this-week':
        //  contentArea.classList.add('week-design');
         contentArea.innerHTML = '<canvas id="myChart"></canvas>';
         break;
       case 'this-month':
        //  contentArea.classList.add('month-design');
         contentArea.innerHTML = '<canvas id="myChart2"></canvas>';
         break;
       case 'last-year':
        //  contentArea.classList.add('year-design');
         contentArea.innerHTML = '<canvas id="myChart3"></canvas>';
         break;
       default:
         break;
     }
  }

});
</script>
</body>
</html>
