<?php 
session_start();
session_unset();
session_destroy();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Logout</title>
</head>
<body>
<script>
        localStorage.removeItem("username");
        localStorage.removeItem("role");
        localStorage.removeItem("cos_id");
        localStorage.removeItem("username");
        localStorage.removeItem("mobile");
        localStorage.removeItem("password");
        localStorage.removeItem("authId");
        localStorage.removeItem("store_type");
        localStorage.removeItem("activeMainMenu");
        localStorage.setItem('sessionActive', 'false');
        setTimeout(function() {
            window.location.href = '../../gF6d/gfdash/index.php';
        }, 100);
      </script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>
