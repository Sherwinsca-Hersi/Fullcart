<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
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
        <form class="login_form" method="POST" autocomplete="off" id="otpForm">
            <h1>Welcome to <span><?php echo $project_name; ?></span></h1>
            <div class="otp-container" id="otpContainer">
                <h2>OTP Verification</h2>
                <p>Please enter the 6-digit OTP sent to your email/phone</p>
                <div class="otp-input">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                    <input type="text" maxlength="1">
                </div>
                <input type="submit" class="login_btn" value="Verify OTP">
                <div class="resend">Resend OTP</div>
            </div>

            <div class="verified-video" id="verifiedVideo" style="display: none;">
                <h2>Successfully Verified!</h2>
                <img src="../assets/images/confetti.gif" alt="Verification Success">
            </div>
        </form>
        <?php
    $otp = isset($_POST['otp']) ? $_POST['otp'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
?>

<script>
    function moveFocus(current, index, inputs) {
        if (current.value.length === current.maxLength) {
            const nextInput = inputs[index + 1];
            if (nextInput) {
                nextInput.focus();
            }
        }
    }

    function handleKeyDown(current, event) {
        const prevInput = current.previousElementSibling;

        if (event.key === "Backspace") {
            if (current.value !== "") {
                current.value = "";
            } else if (prevInput && prevInput.tagName === 'INPUT') {
                prevInput.focus();
                prevInput.value = "";
            }
            event.preventDefault();
        }
    }


    document.querySelectorAll('.otp-input input').forEach((input, index, inputs) => {
        input.addEventListener('input', function() {
            moveFocus(this, index, inputs);
        });

        input.addEventListener('keydown', function(event) {
            handleKeyDown(this, event);
        });
    });

    const otp = "<?php echo $otp; ?>";
    const message = "<?php echo $message; ?>";

    console.log('OTP:', otp);
    console.log('Message:', message);


    function verifyOTP() {
        const inputs = document.querySelectorAll('.otp-input input');
        let otpValue = '';

        inputs.forEach(input => {
            otpValue += input.value;
        });

        console.log(window.otp);

        const correctOTP =  window.otp ?? otp;
        // const correctOTP =  "123456";
        if (otpValue == correctOTP) {
            document.getElementById('otpContainer').style.display = 'none';
            document.getElementById('verifiedVideo').style.display = 'block';

            setTimeout(() => {
                window.location.href = 'resetPassword.php';
            }, 500);

        } else {
            iziToast.error({
                title: 'Error',
                message: "Incorrect OTP. Please try again.",
                position: 'topRight',
                timeout: 5000
            });
        }
    }


    document.getElementById('otpForm').addEventListener('submit', function(event) {
        event.preventDefault();
        verifyOTP();
    });

    // const video = document.querySelector('#verifiedVideo video');
    // video.addEventListener('ended', function() {
    //     window.location.href = 'resetPassword.php';
    // });


    let resendAttempts = 0;
    const maxResendAttempts = 2;
    const cooldownTime = 60;
    let cooldownTimer = null;

    document.querySelector('.resend').addEventListener('click', function() {
        const mobile = localStorage.getItem('mobile');

        if (!mobile) {
            iziToast.error({
                title: 'Error',
                message: 'Mobile number is not found. Please try again.',
                position: 'topRight',
                timeout: 5000
            });
            return;
        }

        if (resendAttempts >= maxResendAttempts) {
            iziToast.warning({
                title: 'Warning',
                message: `Maximum resend attempts reached. Please try again after ${cooldownTime} seconds.`,
                position: 'topRight',
                timeout: 5000
            });
            startCooldown();
        } else {
            resendOTP(mobile);
        }
    });


    function resendOTP(mobile) {
        resendAttempts++;

        fetch('sendOTP.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `mobile=${encodeURIComponent(mobile)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.ResponseCode === "200") {
                console.log('New OTP:', data.otp);

                iziToast.success({
                    title: 'Success',
                    message: 'OTP has been resent.',
                    position: 'bottomCenter',
                    timeout: 5000
                });

                window.otp = data.otp; 

                if (resendAttempts >= maxResendAttempts) {
                    startCooldown();
                }
            } else {
                iziToast.error({
                    title: 'Error',
                    message: 'Failed to resend OTP. Please try again.',
                    position: 'bottomCenter',
                    timeout: 5000
                });
            }
        })
        .catch(error => {
            iziToast.error({
                title: 'Error',
                message: 'Something went wrong. Please try again later.',
                position: 'topRight',
                timeout: 5000
            });
            console.error('Error resending OTP:', error);
        });
    }

    function startCooldown() {
        if (cooldownTimer) return;

        let timeLeft = cooldownTime;
        const resendButton = document.querySelector('.resend');
        resendButton.disabled = true;
        resendButton.textContent = `Try again in ${timeLeft}s`;

        cooldownTimer = setInterval(() => {
            timeLeft--;
            resendButton.textContent = `Try again in ${timeLeft}s`;

            if (timeLeft <= 0) {
                clearInterval(cooldownTimer);
                cooldownTimer = null;
                resendButton.disabled = false;
                resendButton.textContent = 'Resend OTP';
                resendAttempts = 0;
            }
        }, 1000);
    }

    
//     document.querySelector('.resend').addEventListener('click', function() {
//     iziToast.warning({
//         title: 'Info',
//         message: 'OTP resend is disabled for testing.',
//         position: 'topRight',
//         timeout: 5000
//     });
// });

</script>

    </div>
</div>
</body>
</html>
