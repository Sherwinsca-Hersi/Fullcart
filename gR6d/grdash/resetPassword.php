<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <?php require '../api/header.php'; ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
</head>
<body>
<div class="login_container">
    <div class="login_img">
        <img src="../assets/images/login_img.png" alt="login-img">
    </div>
    <div class="login_sect">
        <form class="login_form" method="POST" autocomplete="off" id="resetPasswordForm" action="changePassword.php">
                <h1>Welcome to <span> <?php echo $project_name;?> </span></h1>
                <div class="login_input">
                    <div class="form-div">
                        <label for="new_pass" class="form-label">New Password</label>
                        <div class="pass_input" style="width:100%">
                            <input type="password" name="new_pass" id="newPassword" class="input_style" required>
                            <span class="eye_icon" onclick="togglePassword('new')"><i class="fa fa-solid fa-eye-slash"></i></span>
                        </div>
                    </div>
                    <div class="form-div">
                        <label for="confirm_pass" class="form-label">Confirm Password</label>
                        <div class="pass_input" style="width:100%">
                            <input type="password" name="confirm_pass" id="confirmPassword" class="input_style" required>
                            <span class="eye_icon" onclick="togglePassword('confirm')"><i class="fa fa-solid fa-eye-slash"></i></span>
                        </div>
                    </div>
                    <div class="err_msg" id="errorMessage">Mismatch Password</div>
                    <div class="form-div">
                        <input type="submit" class="login_btn" value="Reset Password" name="reset_submit">
                    </div>
                </div>    
                <a href="index.php" class="login_link">Back to Login</a>
        </form>
</div>
       
<script>
    document.getElementById('resetPasswordForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    // Retrieve `id` and `mobile` from localStorage
    const userId = localStorage.getItem('userId');
    const mobile = localStorage.getItem('mobile');

    if (!userId || !mobile) {
        iziToast.error({
            title: 'Error',
            message: 'User data not found. Please try again.',
            position: 'bottomCenter',
            timeout: 5000
        });
        return;
    }

    if (newPassword !== confirmPassword) {
        iziToast.error({
            title: 'Error',
            message: 'Passwords do not match.',
            position: 'bottomCenter',
            timeout: 5000
        });
        return;
    }

    // Send the data to the server for password update
    fetch('changePassword.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `new_pass=${encodeURIComponent(newPassword)}&confirm_pass=${encodeURIComponent(confirmPassword)}&userId=${encodeURIComponent(userId)}&mobile=${encodeURIComponent(mobile)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.ResponseCode === "200") {
            iziToast.success({
                title: 'Success',
                message: 'Password updated successfully.',
                position: 'bottomCenter',
                timeout: 5000
            });

            // Clear localStorage after the password is changed
            localStorage.removeItem('userId');
            localStorage.removeItem('mobile');

            // Redirect to the login page or another page
            window.location.href = "index.php";
        } else {
            iziToast.error({
                title: 'Error',
                message: data.Message || 'Failed to update password.',
                position: 'bottomCenter',
                timeout: 5000
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        iziToast.error({
            title: 'Error',
            message: "An error occurred while updating the password.",
            position: 'bottomCenter',
            timeout: 5000
        });
    });
});


function togglePassword(type) {
    // Determine the password field and the corresponding eye icon
    var passwordField, eyeIcon;

    // Select the appropriate password field and icon based on the type
    if (type === 'new') {
        passwordField = document.getElementById('newPassword');
        eyeIcon = document.querySelector('.eye_icon i'); // Select the first eye icon
    } else if (type === 'confirm') {
        passwordField = document.getElementById('confirmPassword');
        eyeIcon = document.querySelectorAll('.eye_icon i')[1]; // Select the second eye icon
    }

    // Toggle the password field's visibility
    if (passwordField.type === 'password') {
        passwordField.type = 'text';
        eyeIcon.classList.remove('fa-eye-slash');
        eyeIcon.classList.add('fa-eye');
    } else {
        passwordField.type = 'password';
        eyeIcon.classList.remove('fa-eye');
        eyeIcon.classList.add('fa-eye-slash');
    }
}

</script>
</body>
</html>
