<?php
    require 'config.php';
    error_reporting(E_ALL); 
    ini_set('display_errors', 1);
    require 'fetch_data.php';
    

    // header("Cache-Control: no-cache, no-store, must-revalidate"); // HTTP 1.1
    // header("Pragma: no-cache");
    // header("Expires: 0"); 
?>
<link href="../assets/css/style.css" rel="stylesheet" type="text/css">
<link href="../assets/css/media.css" rel="stylesheet" type="text/css">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="apple-touch-icon" sizes="180x180" href="../assets/images/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="../assets/images/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="../assets/images/favicon-16x16.png">
<link rel="manifest" href="../assets/images/site.webmanifest">

<!-- chart js -->

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

<!-- data tables -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<!--iziToast-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">



<script>
document.addEventListener("DOMContentLoaded", function() {
    let cosId = localStorage.getItem("cos_id");

    if (cosId) {
        fetch("../api/config.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "cos_id=" + encodeURIComponent(cosId)
        })
        .then(response => response.json())
        .then(data => console.log("cos_id stored in PHP session:", data))
        .catch(error => console.error("Error sending cos_id:", error));
    }
});
</script>
