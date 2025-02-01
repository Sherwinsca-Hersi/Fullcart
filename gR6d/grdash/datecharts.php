<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
<?php

//yearly Data
$currentYear = date('Y');
$yearly = "SELECT  MONTHNAME(updated_ts) AS month, 
            p_title, 
            p_type, 
            p_img, 
            SUM(p_quantity) AS total 
        FROM 
            e_normal_order_product_details 
        WHERE 
            cos_id = '$cos_id' 
            AND active = 1 
            AND YEAR(updated_ts) =2024
        GROUP BY 
            month, 
            p_title, 
            p_type 
        ORDER BY 
            STR_TO_DATE(CONCAT(2024, '-', MONTH(updated_ts), '-01'), '%Y-%m-%d') DESC, 
            total DESC LIMIT 5";

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
            total DESC LIMIT 5";

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
            total DESC LIMIT 5";

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
$yearlyProducts = $allMonths;
// print_r($monthlyProducts['January']);
// Ensure that all 7 days are included in the final output
$weeklyProducts = $allDaysWeek;
// print_r($weeklyProducts);

$monthlyProducts = $allDaysMonth;
//Today Data

$today = date('Y-m-d'); // Get today's date in 'YYYY-MM-DD' format
// echo $today;
$topSellingTodayQuery = "SELECT 
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
    LIMIT 5";

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
?>

<script>
    let chartInstance;

    // Data from PHP
    const yearlyProducts = <?php echo json_encode($yearlyProducts); ?>;
    const weeklyProducts = <?php echo json_encode($weeklyProducts); ?>;
    const monthlyProducts = <?php echo json_encode($monthlyProducts); ?>;
    const topSellingTodayProducts = <?php echo json_encode($topSellingTodayProducts); ?>;
    const daysInMonth = <?php echo $daysInMonth; ?>;

    // Generate random color
    function getRandomColor() {
        const letters = '0123456789ABCDEF';
        let color = '#';
        for (let i = 0; i < 6; i++) {
            color += letters[Math.floor(Math.random() * 16)];
        }
        return color;
    }

    
    function renderChart(labels, datasets) {
        const ctx = document.getElementById('myChart').getContext('2d');
        
        if (chartInstance) {
            chartInstance.destroy(); 
        }

        chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: datasets
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
                    datalabels: {  // Configure data labels
                    anchor: 'center', // Center the label vertically within the bar
                    align: 'center', // Center the label horizontally within the bar
                    color: '#FFFFFF', // Set color for the text (white to contrast with bar colors)
                    formatter: function(value, context) {
                        return value > 0 ? value : ''; // Only show labels for non-zero values
                    },
                    font: {
                        weight: 'bold' // Make the font bold
                    }
                },
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
            },
            plugins: [ChartDataLabels]
        });
    }

    // Today data processing
    function displayTodayData() {
        const labels = ['Today'];
        const datasets = topSellingTodayProducts.map(product => ({
            label: `${product.name} (${product.variation})`,
            data: [product.total],
            backgroundColor: getRandomColor(),
            borderColor: 'rgba(0, 0, 0, 0.1)',
            borderWidth: 1
        }));

        renderChart(labels, datasets);
    }

    // Weekly data processing
    function displayWeeklyData() {
        const labels = Object.keys(weeklyProducts);
        const datasets = [];

        labels.forEach((day, dayIndex) => {
            weeklyProducts[day].forEach(product => {
                const data = Array(labels.length).fill(NaN);
                data[dayIndex] = product.total;

                datasets.push({
                    label: `${product.name} (${product.variation})`,
                    data: data,
                    backgroundColor: getRandomColor(),
                    borderColor: 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1
                });
            });
        });

        renderChart(labels, datasets);
    }

    // Monthly data processing
    function displayMonthlyData() {
        const labels = Array.from({ length: daysInMonth }, (v, i) => (i + 1));
        const datasets = [];

        labels.forEach(day => {
            if (monthlyProducts[day]) {
                monthlyProducts[day].forEach(product => {
                    datasets.push({
                        label: `${product.name} (${product.variation})`,
                        data: Array.from({ length: daysInMonth }, (v, i) => (i + 1 === day ? product.total : NaN)),
                        backgroundColor: getRandomColor(),
                        borderColor: 'rgba(0, 0, 0, 0.1)',
                        borderWidth: 1
                    });
                });
            }
        });

        renderChart(labels, datasets);
    }

    // Yearly data processing
    function displayYearlyData() {
        const labels = Object.keys(yearlyProducts);
        const datasets = [];

        labels.forEach((month, monthIndex) => {
            yearlyProducts[month].forEach(product => {
                const data = Array(labels.length).fill(NaN);
                data[monthIndex] = product.total;

                datasets.push({
                    label: `${product.name} (${product.variation})`,
                    data: data,
                    backgroundColor: getRandomColor(),
                    borderColor: 'rgba(0, 0, 0, 0.1)',
                    borderWidth: 1
                });
            });
        });

        renderChart(labels, datasets);
    }

    // Event listener for buttons
    document.getElementById('date-selector').addEventListener('click', function(e) {
        if (e.target.tagName === 'BUTTON') {
            const dateValue = e.target.getAttribute('data-value');
            switch (dateValue) {
                case 'today':
                    displayTodayData();
                    break;
                case 'this-week':
                    displayWeeklyData();
                    break;
                case 'this-month':
                    displayMonthlyData();
                    break;
                case 'last-year':
                    displayYearlyData();
                    break;
                default:
                    break;
            }
        }
    });

    displayTodayData();

    document.addEventListener('DOMContentLoaded', function () {
    const buttons = document.querySelectorAll('#date-selector button');
    
    // Function to remove 'active' class from all buttons
    function removeActiveClasses() {
        buttons.forEach(button => button.classList.remove('active'));
    }

    // Function to highlight the selected button
    function highlightSelectedButton(button) {
        removeActiveClasses();
        button.classList.add('active');
    }

    // Add click event listener to each button
    buttons.forEach(button => {
        button.addEventListener('click', function (e) {
            e.preventDefault(); // Prevent default action if button is inside a form
            highlightSelectedButton(button);
            const dateValue = button.getAttribute('data-value');

            // Call the function to update the chart based on the selected date range
            switch (dateValue) {
                case 'today':
                    displayTodayData();
                    break;
                case 'this-week':
                    displayWeeklyData();
                    break;
                case 'this-month':
                    displayMonthlyData();
                    break;
                case 'last-year':
                    displayYearlyData();
                    break;
                default:
                    break;
            }
        });
    });

    const defaultButton = document.querySelector('#date-selector button[data-value="today"]');
    if (defaultButton) {
        highlightSelectedButton(defaultButton);
        displayTodayData(); 
    }
});


</script>
</body>
</html>
