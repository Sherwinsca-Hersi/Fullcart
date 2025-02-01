<?php 
session_start();

if (!isset($_SESSION['cos_id'])) {
    header("Location: index.php");
    exit();
}

?>

<script>
if(localStorage.getItem('sessionActive')==='false'){
    console.log("Session is inactive");
    window.location.href="index.php";
}
</script>

<?php
$login_success = false;

if (isset($_SESSION['login_success']) && $_SESSION['login_success'] === true) { 
    $login_success = true;
    unset($_SESSION['login_success']);
}
?>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        <?php if ($login_success==true): ?>
        var x = document.getElementById("snackbar");
        if (!x.classList.contains("show")) {
            x.classList.add("show");
            setTimeout(function () {
                x.classList.remove("show");
            }, 3000);
            $login_success=false;
        }
        <?php endif; ?>
    });
</script>

<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); 
// error_reporting(E_ALL); 
// ini_set('display_errors', 1);
?>

<script>
    window.addEventListener('popstate', function(event) {
    history.back();
    setTimeout(function() {
        location.reload();
    }, 100);
});
</script>