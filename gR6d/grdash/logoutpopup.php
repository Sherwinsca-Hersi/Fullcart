<div class="logout_popup">
    <div class="popup logout_style" id="logout_popup">
        <div class="logout_confirm">
            <span>Are you sure you want to log out?</span>
            <form action="logout.php" method="post">
                <div class="logout_btns">
                    <input type="submit" value="OK" class="ok_btn">
                    <button type="button" class="popup_cancel" id="cancel_logout">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const logout = document.getElementById("logout");
    const cancelLogout = document.getElementById("cancel_logout");
    const logoutPopup = document.getElementById("logout_popup");
    const container = document.querySelector(".container");
    console.log(container);

    logout.addEventListener("click", () => {
        logoutPopup.classList.add("open_popup");
        container.classList.toggle("active");
    });

    cancelLogout.addEventListener("click", () => {
        logoutPopup.classList.remove("open_popup");
        container.classList.toggle("active");
    });
});
// document.addEventListener('DOMContentLoaded', function() {
// if ((localStorage.getItem('sessionActive'))==='false') {
//         console.log("session is inactive");
//         window.location.href = 'index.php';
//     }
// });
</script>
<script src="../assets/js/session_check.js"></script>