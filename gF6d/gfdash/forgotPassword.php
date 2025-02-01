<!-- <?php
    // require 'session.php';
?> -->
<!DOCTYPE html>
<html lang="en">
<head>
    <?php
        require '../api/header.php';  
    ?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $project_name;?>- Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/izitoast/dist/css/iziToast.min.css">
    <script src="https://cdn.jsdelivr.net/npm/izitoast/dist/js/iziToast.min.js"></script>
</head>
<body>
    <div class="login_container">
        <div class="login_img">
            <img src="../assets/images/login_img.png" class="sign_img_style" alt="login page-img">
        </div>
        <div class="login_sect">
            <form class="signup_form"  method="POST" id="otpForm">
                <h1>Welcome to <span><?php echo $project_name;?></span></h1>
                <div class="signup_input">
                    <div>
                        <div class="form-div">
                            <!-- <label for="mobile" class="form-label">Mobile</label> -->
                            <div>
                                <input type="number" name="mobile" id="mobile" placeholder="Enter Valid Mobile Number"  onKeyPress="if(this.value.length==10) return false;"  class="input_style" maxlength="10" required>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="signup_btn_div">
                    <input type="submit" class="signup_btn" value="Send OTP">
                </div>
                <a href="index.php" class="login_link">Back to Login</a>
            </form>
            <form id="hiddenOtpForm" action="otpVerifyScreen.php" method="POST" style="display:none;">
                <input type="hidden" name="otp" id="otpField">
                <input type="hidden" name="message" id="messageField">
            </form>
        </div>
    </div>
    <script>
document.getElementById('otpForm').addEventListener('submit', function(event) {
    event.preventDefault(); 

    const mobile = document.getElementById('mobile').value;
    
    fetch('checkMobile.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `mobile=${encodeURIComponent(mobile)}`
    })
    .then(response => response.text())
    .then(rawData => {
        console.log('Raw response:', rawData);

        let data;
        try {
            data = JSON.parse(rawData);
        } catch (error) {
            console.error('Failed to parse JSON:', error);
            iziToast.error({
                title: 'Error',
                message: "Invalid response from the server.",
                position: 'bottomCenter',
                timeout: 5000
            });
            return;
        }

        if (data.ResponseCode === "200" && data.Result === "true") {
            localStorage.setItem('userId', data.id);
            localStorage.setItem('mobile', mobile);
            
            const formData = new FormData(document.getElementById('otpForm'));

            fetch('sendOTP.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(rawData => {
                console.log('Raw OTP response:', rawData);

                let data;
                try {
                    data = JSON.parse(rawData);
                } catch (error) {
                    console.error('Failed to parse OTP JSON:', error);
                    iziToast.error({
                        title: 'Error',
                        message: "Invalid OTP response from the server.",
                        position: 'bottomCenter',
                        timeout: 5000
                    });
                    return;
                }

                if (data.ResponseCode === "200" && data.Result === "true") {
                    
                    document.getElementById('otpField').value = data.otp;
                    document.getElementById('messageField').value = data.ResponseMsg;

                   
                    document.getElementById('hiddenOtpForm').submit();
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: data.ResponseMsg || "Something went wrong.",
                        position: 'bottomCenter',
                        timeout: 5000
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                iziToast.error({
                    title: 'Error',
                    message: "An error occurred while sending the OTP.",
                    position: 'bottomCenter',
                    timeout: 5000
                });
            });

        } else {
            iziToast.error({
                title: 'Invalid Mobile',
                message: data.Message || "Mobile number is not valid.",
                position: 'bottomCenter',
                timeout: 5000
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        iziToast.error({
            title: 'Error',
            message: "An error occurred while checking the mobile number.",
            position: 'bottomCenter',
            timeout: 5000
        });
    });
});

// document.getElementById('otpForm').addEventListener('submit', function(event) {
//     event.preventDefault(); 

//     const mobile = document.getElementById('mobile').value;

//     fetch('checkMobile.php', {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//         },
//         body: `mobile=${encodeURIComponent(mobile)}`
//     })
//     .then(response => response.text())
//     .then(rawData => {
//         console.log('Raw response:', rawData);

//         let data;
//         try {
//             data = JSON.parse(rawData);
//         } catch (error) {
//             console.error('Failed to parse JSON:', error);
//             iziToast.error({
//                 title: 'Error',
//                 message: "Invalid response from the server.",
//                 position: 'bottomCenter',
//                 timeout: 5000
//             });
//             return;
//         }

//         if (data.ResponseCode === "200" && data.Result === "true") {
//             localStorage.setItem('userId', data.id);
//             localStorage.setItem('mobile', mobile);
            

//             const dummyOTP = "123456";
//             console.log('Using dummy OTP:', dummyOTP);

//             document.getElementById('otpField').value = dummyOTP;
//             document.getElementById('messageField').value = "Dummy OTP sent successfully!";

//             document.getElementById('hiddenOtpForm').submit();

//         } else {
//             iziToast.error({
//                 title: 'Invalid Mobile',
//                 message: data.Message || "Mobile number is not valid.",
//                 position: 'bottomCenter',
//                 timeout: 5000
//             });
//         }
//     })
//     .catch(error => {
//         console.error('Error:', error);
//         iziToast.error({
//             title: 'Error',
//             message: "An error occurred while checking the mobile number.",
//             position: 'bottomCenter',
//             timeout: 5000
//         });
//     });
// });

</script>
    <script src="../assets/js/session_check.js"></script>
</body>
</html>